<?php

namespace App\Http\Controllers;

use App\Events\MonsterCreated;
use App\Events\MonsterForkCreated;
use App\Http\Requests\StoreMonster;
use App\Http\Requests\UpdateMonster;
use App\Monster;
use App\MonsterAbility;
use App\MonsterAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MonsterController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index(Request $request) {

		$monster_count = Cache::tags(['monsters'])->remember('monsters_count', 60, function () {
			return Monster::count();
		});

		return view('monster.index', compact('monsters', 'monster_count'));

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$spells = Cache::tags(['spells'])->remember('spells_list', 60, function () {
			return \App\Spell::pluck('name', 'id');
		});

		$skills = [];
		$saving_throws = [];

		return view('monster.create', compact('spells', 'skills', 'saving_throws'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreMonster $request) {

		//
		//  Automatically set some fields that aren't submitted by user.
		//
		$input = $request->except(['_token', 'type', 'languages', 'saving_throws', 'skills', 'damage_vulnerabilities', 'damage_resistances', 'damage_immunities', 'condition_immunities', 'abilities', 'hit_dice_size', 'actions', 'spells_at_will', 'spells_one', 'spells_two', 'spells_three']);
		$input['hit_dice_size'] = ltrim(request('hit_dice_size'), 'd');
		$input['source'] = 'User';
		$input['system'] = '5E';

		//
		//  Start new Monster instance and pass it all input.
		//
		$monster = new Monster($input);

		$monster->user_id = \Auth::id();

		if (request('spell_ability')) {
			$spell_ability = request('spell_ability');
			$monster->spell_save = 8 + request('proficiency')+\Common::mod(request($spell_ability));
		}

		$monster->languages = is_array(request('languages')) ? implode(array_map('ucfirst', request('languages')), ', ') : NULL;

		$monster->damage_vulnerabilities = request('damage_vulnerabilities') ? implode(request('damage_vulnerabilities'), ', ') : NULL;

		$monster->damage_resistances = request('damage_resistances') ? implode(request('damage_resistances'), ', ') : NULL;

		$monster->damage_immunities = request('damage_immunities') ? implode(request('damage_immunities'), ', ') : NULL;

		$monster->condition_immunities = request('condition_immunities') ? implode(request('condition_immunities'), ', ') : NULL;

		//
		//  Process Monster Types
		$monster->type = implode(' / ', request('type'));

		//
		//  Calculate & Set Saving Throws
		if (request('saving_throws')) {
			foreach (request('saving_throws') as $key => $value) {
				$ability = lcfirst(\GeneralHelper::getSavingThrows()[$value]);
				$total = \Common::mod($monster->$ability) + request('proficiency');
				$monster->$value = $total;
			}
		}

		//
		//  Calculate & Set Monster Skills
		if (request('skills')) {
			foreach (request('skills') as $skill) {
				foreach (\CreatureHelper::getSkillMods() as $key => $value) {
					if ($skill == $key) {
						$total = \Common::mod($monster->$value) + request('proficiency');
						$monster->$skill = $total;
					}
				}
			}
		}

		//
		// Set CR to fraction
		$monster->CR_fraction = \Common::decimalToFraction(request('CR'));

		$monster->key = str_random(32);

		//
		//  Save To Monsters Table
		$monster->save();

		if (request('spells_at_will')) {
			foreach (request('spells_at_will') as $spell) {
				$monster->spells()->attach($spell, ['level' => 'at_will']);
			}
		}

		if (request('spells_one')) {
			foreach (request('spells_one') as $spell) {
				$monster->spells()->attach($spell, ['level' => 'one']);
			}
		}

		if (request('spells_two')) {
			foreach (request('spells_two') as $spell) {
				$monster->spells()->attach($spell, ['level' => 'two']);
			}
		}

		if (request('spells_three')) {
			foreach (request('spells_three') as $spell) {
				$monster->spells()->attach($spell, ['level' => 'three']);
			}
		}

		//
		//  Begin Processing Monster Abilities, Attacks and Actions
		if (request('abilities')) {
			foreach (request('abilities') as $ability) {
				if (!empty($ability['name'])) {
					$newAbility = new MonsterAbility;
					$newAbility->monster_id = $monster->id;
					$newAbility->name = $ability['name'];
					$newAbility->description = $ability['description'];
					$newAbility->save();
				}
			}
		}

		if (request('actions')) {
			foreach (request('actions') as $action) {
				if (!empty($action['name'])) {
					$newAction = new MonsterAction;
					$newAction->monster_id = $monster->id;
					$newAction->name = $action['name'];
					$newAction->description = $action['description'];
					$newAction->damage_type = $action['damage_type'];
					$newAction->damage_dice = $action['damage_dice'];
					$newAction->attack_type = $action['attack_type'];
					$newAction->range = $action['range'];
					$newAction->legendary = isset($action['legendary']) ? $action['legendary'] : 0;

					if ($action['attack_type'] == 'ranged') {
						$newAction->attack_bonus = \Common::modUnsigned($monster->dexterity) + $monster->proficiency;
						$newAction->damage_bonus = \Common::modUnsigned($monster->dexterity);
					} elseif ($action['attack_type'] == 'melee') {
						$newAction->attack_bonus = \Common::modUnsigned($monster->strength) + $monster->proficiency;
						$newAction->damage_bonus = \Common::modUnsigned($monster->strength);
					}

					$newAction->save();
				}
			}
		}

		if ($monster->fork_id != NULL) {
			$forked = Monster::find($monster->fork_id);
			event(new MonsterForkCreated($monster, $forked));
		} else {
			event(new MonsterCreated($monster));
		}

		$request->session()->flash('status', 'Your monster has been added!');

		return redirect('monster/' . $monster->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id) {

		$monster = Cache::remember('monsters_' . $id, 60, function () use ($id) {
			return Monster::with('actions', 'abilities', 'comments.user', 'comments.likes', 'likes.user', 'forkedFrom', 'forkedTo', 'private_notes.user', 'user', 'files.user', 'spells')->withTrashed()->findOrFail($id);
		});

		if ($monster->private == 1) {
			if (Auth::id() != $monster->user_id && request()->key != $monster->key) {
				return view('private');
			}
		}

		if ($monster->deleted_at != NULL) {
			return view('deleted');
		}

		$notes = $monster->private_notes->where('user_id', \Auth::id());

		//
		//Log view count
		$key = 'monster_' . $monster->id . '_' . request()->ip();

		if (Cache::add($key, NULL, 30)) {
			\DB::table('monsters')->where('id', $monster->id)->increment('view_count', 1);
			Monster::find($monster->id)->searchable();
		}

		return view('monster.show', compact('monster', 'notes'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$spells = Cache::tags(['spells'])->remember('spells_list', 60, function () {
			return \App\Spell::pluck('name', 'id');
		});

		$monster = Cache::remember('monsters_' . $id, 60, function () use ($id) {
			return Monster::with('actions', 'abilities')->findOrFail($id);
		});

		$this->authorize('update', $monster);

		$monster->type = explode(' / ', $monster->type);

		$saving_throws[] = '';
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			if ($monster->$key != NULL) {
				$saving_throws[] = $key;
			}
		}

		$skills[] = '';
		foreach (\CreatureHelper::getSkills() as $key => $value) {
			if ($monster->$key != NULL) {
				$skills[] = $key;
			}
		}

		$monster->languages = array_map('trim', explode(',', $monster->languages));
		$monster->languages = array_map('lcfirst', $monster->languages);
		$monster->damage_vulnerabilities = array_map('trim', explode(',', $monster->damage_vulnerabilities));
		$monster->damage_resistances = array_map('trim', explode(',', $monster->damage_resistances));
		$monster->damage_immunities = array_map('trim', explode(',', $monster->damage_immunities));
		$monster->condition_immunities = array_map('trim', explode(',', $monster->condition_immunities));

		return view('monster.edit', compact('monster', 'saving_throws', 'skills', 'spells'));
	}

	public function fork($id) {
		$spells = Cache::tags(['spells'])->remember('spells_list', 60, function () {
			return \App\Spell::pluck('name', 'id');
		});

		$monster = Monster::with('actions', 'abilities')->findOrFail($id);

		$monster->type = explode(' / ', $monster->type);

		$saving_throws[] = '';
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			if ($monster->$key != NULL) {
				$saving_throws[] = $key;
			}
		}

		$skills[] = '';
		foreach (\CreatureHelper::getSkills() as $key => $value) {
			if ($monster->$key != NULL) {
				$skills[] = $key;
			}
		}

		$monster->languages = array_map('trim', explode(',', $monster->languages));
		$monster->languages = array_map('lcfirst', $monster->languages);
		$monster->damage_vulnerabilities = array_map('trim', explode(',', $monster->damage_vulnerabilities));
		$monster->damage_resistances = array_map('trim', explode(',', $monster->damage_resistances));
		$monster->damage_immunities = array_map('trim', explode(',', $monster->damage_immunities));
		$monster->condition_immunities = array_map('trim', explode(',', $monster->condition_immunities));

		return view('monster.edit', compact('monster', 'skills', 'saving_throws', 'spells'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateMonster $request, $id) {
		$monster = Monster::findOrFail($id);
		$this->authorize('update', $monster);

		//
		//  Automatically set some fields that aren't submitted by user.
		$input = $request->except(['_token', 'type', 'languages', 'saving_throws', 'skills', 'damage_vulnerabilities', 'damage_resistances', 'damage_immunities', 'condition_immunities', 'abilities', 'attack', 'hit_dice_size', 'actions', 'spells_at_will', 'spells_one', 'spells_two', 'spells_three']);
		$input['hit_dice_size'] = ltrim(request('hit_dice_size'), 'd');
		$input['source'] = 'User';
		$input['system'] = '5E';

		//
		//  Start new Monster instance and pass it all input.
		//
		if (request('spell_ability')) {
			$spell_ability = request('spell_ability');
			$monster->spell_save = 8 + request('proficiency')+\Common::mod(request($spell_ability));
		}

		$monster->languages = is_array(request('languages')) ? implode(array_map('ucfirst', request('languages')), ', ') : NULL;

		$monster->damage_vulnerabilities = request('damage_vulnerabilities') ? implode(request('damage_vulnerabilities'), ', ') : NULL;

		$monster->damage_resistances = request('damage_resistances') ? implode(request('damage_resistances'), ', ') : NULL;

		$monster->damage_immunities = request('damage_immunities') ? implode(request('damage_immunities'), ', ') : NULL;

		$monster->condition_immunities = request('condition_immunities') ? implode(request('condition_immunities'), ', ') : NULL;

		//
		//  Process Monster Types
		//
		$monster->type = implode(' / ', request('type'));

		//
		//  Calculate & Set Saving Throws
		//
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			$monster->$key = NULL;
		}

		if (request('saving_throws')) {
			foreach (request('saving_throws') as $key => $value) {
				$ability = lcfirst(\GeneralHelper::getSavingThrows()[$value]);
				$total = \Common::mod($monster->$ability) + request('proficiency');
				$monster->$value = $total;
			}
		}

		//
		//  Calculate & Set Monster Skills
		foreach (\CreatureHelper::getSkills() as $key => $value) {
			$monster->$key = NULL;
		}
		if (request('skills')) {
			foreach (request('skills') as $skill) {
				foreach (\CreatureHelper::getSkillMods() as $key => $value) {
					if ($skill == $key) {
						$total = \Common::mod($monster->$value) + request('proficiency');
						$monster->$skill = $total;
					}
				}
			}
		}

		//
		// Set CR to fraction
		$monster->CR_fraction = \Common::decimalToFraction(request('CR'));

		if ($monster->key == 0) {
			$monster->key = str_random(32);
		}

		if (request('private') != 1) {
			$monster->private = 0;
		}

		//
		//  Save To Monsters Table
		//
		$monster->update($input);

		$monster->spells()->detach();
		if (request('spells_at_will')) {
			foreach (request('spells_at_will') as $key) {
				$monster->spells()->attach($key, ['level' => 'at_will']);
			}
		}

		if (request('spells_one')) {
			foreach (request('spells_one') as $key) {
				$monster->spells()->attach($key, ['level' => 'one']);
			}
		}

		if (request('spells_two')) {
			foreach (request('spells_two') as $key) {
				$monster->spells()->attach($key, ['level' => 'two']);
			}
		}

		if (request('spells_three')) {
			foreach (request('spells_three') as $key) {
				$monster->spells()->attach($key, ['level' => 'three']);
			}
		}

		//
		//  Begin Processing Monster Abilities, Attacks and Actions
		$monster->abilities()->delete();
		if (request('abilities')) {
			foreach (request('abilities') as $ability) {
				if (!empty($ability['name'])) {
					$newAbility = new MonsterAbility;
					$newAbility->monster_id = $monster->id;
					$newAbility->name = $ability['name'];
					$newAbility->description = $ability['description'];
					$newAbility->save();
				}
			}
		}

		$monster->actions()->delete();
		if (request('actions')) {
			foreach (request('actions') as $action) {
				if (!empty($action['name'])) {
					$newAction = new MonsterAction;
					$newAction->monster_id = $monster->id;
					$newAction->name = $action['name'];
					$newAction->description = $action['description'];
					$newAction->damage_type = $action['damage_type'];
					$newAction->damage_dice = $action['damage_dice'];
					$newAction->attack_type = $action['attack_type'];
					$newAction->range = $action['range'];
					$newAction->legendary = isset($action['legendary']) ? $action['legendary'] : 0;

					if ($action['attack_type'] == 'ranged') {
						$newAction->attack_bonus = \Common::modUnsigned($monster->dexterity) + $monster->proficiency;
						$newAction->damage_bonus = \Common::modUnsigned($monster->dexterity);
					} elseif ($action['attack_type'] == 'melee') {
						$newAction->attack_bonus = \Common::modUnsigned($monster->strength) + $monster->proficiency;
						$newAction->damage_bonus = \Common::modUnsigned($monster->strength);
					}

					$newAction->save();
				}
			}
		}

		$request->session()->flash('status', 'Your monster has been edited!');

		return redirect()->action('MonsterController@show', ['id' => $id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id) {
		$monster = Monster::find($id);
		$this->authorize('delete', $monster);

		$monster->delete();

		// redirect
		$request->session()->flash('status', 'Successfully deleted the monster!');
		return redirect('monster');
	}

}
