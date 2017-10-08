<?php

namespace App\Http\Controllers;

use App\Events\NpcCreated;
use App\Events\NpcForkCreated;
use App\Http\Requests\StoreNpc;
use App\Http\Requests\UpdateNpc;
use App\Npc;
use App\NpcAbility;
use App\NpcAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NpcController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$npc_count = Cache::tags(['npcs'])->remember('npcs_count', 60, function () {
			return Npc::count();
		});

		return view('npc.index', compact('npcs', 'npc_count'));
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

		return view('npc.create', compact('spells', 'skills', 'saving_throws'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreNpc $request) {
		//
		//  Automatically set some fields that aren't submitted by user.
		$input = $request->except(['_token', 'type', 'languages', 'saving_throws', 'skills', 'damage_vulnerabilities', 'damage_resistances', 'damage_immunities', 'condition_immunities', 'abilities', 'hit_dice_size', 'actions', 'spells_at_will', 'spells_one', 'spells_two', 'spells_three']);
		$input['hit_dice_size'] = ltrim(request('hit_dice_size'), 'd');
		$input['source'] = 'User';
		$input['system'] = '5E';

		//
		//  Start new npc instance and pass it all input.
		$npc = new Npc($input);

		$npc->user_id = \Auth::id();

		if (request('spell_ability')) {
			$spell_ability = request('spell_ability');
			$npc->spell_save = 8 + request('proficiency')+\Common::mod(request($spell_ability));
		}

		$npc->languages = is_array(request('languages')) ? implode(array_map('ucfirst', request('languages')), ', ') : NULL;

		$npc->damage_vulnerabilities = request('damage_vulnerabilities') ? implode(request('damage_vulnerabilities'), ', ') : NULL;

		$npc->damage_resistances = request('damage_resistances') ? implode(request('damage_resistances'), ', ') : NULL;

		$npc->damage_immunities = request('damage_immunities') ? implode(request('damage_immunities'), ', ') : NULL;

		$npc->condition_immunities = request('condition_immunities') ? implode(request('condition_immunities'), ', ') : NULL;

		//
		//  Calculate & Set Saving Throws
		if (request('saving_throws')) {
			foreach (request('saving_throws') as $key => $value) {
				$ability = lcfirst(\GeneralHelper::getSavingThrows()[$value]);
				$total = \Common::mod($npc->$ability) + request('proficiency');
				$npc->$value = $total;
			}
		}

		//
		//  Calculate & Set Npc Skills
		if (request('skills')) {
			foreach (request('skills') as $skill) {
				foreach (\CreatureHelper::getSkillMods() as $key => $value) {
					if ($skill == $key) {
						$total = \Common::mod($npc->$value) + request('proficiency');
						$npc->$skill = $total;
					}
				}
			}
		}

		//
		// Set CR to fraction
		$npc->CR_fraction = \Common::decimalToFraction($request->input('CR'));

		$npc->key = str_random(32);

		//
		//  Save To npcs Table
		$npc->save();

		if (request('spells_at_will')) {
			foreach (request('spells_at_will') as $spell) {
				$npc->spells()->attach($spell, ['level' => 'at_will']);
			}
		}

		if (request('spells_one')) {
			foreach (request('spells_one') as $spell) {
				$npc->spells()->attach($spell, ['level' => 'one']);
			}
		}

		if (request('spells_two')) {
			foreach (request('spells_two') as $spell) {
				$npc->spells()->attach($spell, ['level' => 'two']);
			}
		}

		if (request('spells_three')) {
			foreach (request('spells_three') as $spell) {
				$npc->spells()->attach($spell, ['level' => 'three']);
			}
		}

		//
		//  Begin Processing Npc Abilities, Attacks and Actions
		if (request('abilities')) {
			foreach (request('abilities') as $ability) {
				if (!empty($ability['name'])) {
					$newAbility = new NpcAbility;
					$newAbility->npc_id = $npc->id;
					$newAbility->name = $ability['name'];
					$newAbility->description = $ability['description'];
					$newAbility->save();
				}
			}
		}

		if (request('actions')) {
			foreach (request('actions') as $action) {
				if (!empty($action['name'])) {
					$newAction = new NpcAction;
					$newAction->npc_id = $npc->id;
					$newAction->name = $action['name'];
					$newAction->description = $action['description'];
					$newAction->damage_type = $action['damage_type'];
					$newAction->damage_dice = $action['damage_dice'];
					$newAction->attack_type = $action['attack_type'];
					$newAction->range = $action['range'];
					$newAction->legendary = isset($action['legendary']) ? $action['legendary'] : 0;

					if ($action['attack_type'] == 'ranged') {
						$newAction->attack_bonus = \Common::modUnsigned($npc->dexterity) + $npc->proficiency;
						$newAction->damage_bonus = \Common::modUnsigned($npc->dexterity);
					} elseif ($action['attack_type'] == 'melee') {
						$newAction->attack_bonus = \Common::modUnsigned($npc->strength) + $npc->proficiency;
						$newAction->damage_bonus = \Common::modUnsigned($npc->strength);
					}

					$newAction->save();
				}
			}
		}

		if ($npc->fork_id != null) {
			$forked = Npc::find($npc->fork_id);
			event(new NpcForkCreated($npc, $forked));
		} else {
			event(new NpcCreated($npc));
		}

		$request->session()->flash('status', 'Your NPC has been added!');

		return redirect('npc/' . $npc->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$npc = Cache::remember('npcs_' . $id, 60, function () use ($id) {
			return Npc::with('actions', 'abilities', 'comments.user', 'comments.likes', 'likes.user', 'forkedFrom', 'forkedTo', 'private_notes.user', 'user', 'files.user', 'spells')->withTrashed()->findOrFail($id);
		});

		if ($npc->deleted_at != NULL) {
			return view('deleted');
		}

		if ($npc->private == 1) {
			if (Auth::id() != $npc->user_id && request()->key != $npc->key) {
				return view('private');
			}
		}

		$notes = $npc->private_notes->where('user_id', \Auth::id());

		//
		//Log view count
		//

		$key = 'npc_' . $npc->id . '_' . request()->ip();

		if (Cache::add($key, NULL, 30)) {
			\DB::table('npcs')->where('id', $npc->id)->increment('view_count', 1);
			Npc::find($npc->id)->searchable();
		}

		return view('npc.show', compact('npc', 'notes'));
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

		$npc = Cache::remember('npcs_' . $id, 60, function () use ($id) {
			return Npc::with('actions', 'abilities')->findOrFail($id);
		});

		$this->authorize('update', $npc);

		$saving_throws[] = '';
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			if ($npc->$key != NULL) {
				$saving_throws[] = $key;
			}
		}

		$skills[] = '';
		foreach (\CreatureHelper::getSkills() as $key => $value) {
			if ($npc->$key != NULL) {
				$skills[] = $key;
			}
		}

		$npc->languages = array_map('trim', explode(',', $npc->languages));
		$npc->languages = array_map('lcfirst', $npc->languages);
		$npc->damage_vulnerabilities = array_map('trim', explode(',', $npc->damage_vulnerabilities));
		$npc->damage_resistances = array_map('trim', explode(',', $npc->damage_resistances));
		$npc->damage_immunities = array_map('trim', explode(',', $npc->damage_immunities));
		$npc->condition_immunities = array_map('trim', explode(',', $npc->condition_immunities));

		return view('npc.edit', compact('npc', 'saving_throws', 'skills', 'spells'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateNpc $request, $id) {
		$npc = Npc::findOrFail($id);
		$this->authorize('update', $npc);

		//
		//  Automatically set some fields that aren't submitted by user.
		$input = $request->except(['_token', 'type', 'languages', 'saving_throws', 'skills', 'damage_vulnerabilities', 'damage_resistances', 'damage_immunities', 'condition_immunities', 'abilities', 'attack', 'hit_dice_size', 'actions', 'spells_at_will', 'spells_one', 'spells_two', 'spells_three']);
		$input['hit_dice_size'] = ltrim(request('hit_dice_size'), 'd');
		$input['source'] = 'User';
		$input['system'] = '5E';

		//
		//  Start new Npc instance and pass it all input.
		//
		if (request('spell_ability')) {
		    $spell_ability = request('spell_ability');
		    $npc->spell_save = 8 + request('proficiency')+\Common::mod(request($spell_ability));
		}

		$npc->languages = is_array(request('languages')) ? implode(array_map('ucfirst', request('languages')), ', ') : NULL;

		$npc->damage_vulnerabilities = request('damage_vulnerabilities') ? implode(request('damage_vulnerabilities'), ', ') : NULL;

		$npc->damage_resistances = request('damage_resistances') ? implode(request('damage_resistances'), ', ') : NULL;

		$npc->damage_immunities = request('damage_immunities') ? implode(request('damage_immunities'), ', ') : NULL;

		$npc->condition_immunities = request('condition_immunities') ? implode(request('condition_immunities'), ', ') : NULL;

		//
		//  Calculate & Set Saving Throws
		//
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			$npc->$key = NULL;
		}

		if (request('saving_throws')) {
			foreach (request('saving_throws') as $key => $value) {
				$ability = lcfirst(\GeneralHelper::getSavingThrows()[$value]);
				$total = \Common::mod($npc->$ability) + request('proficiency');
				$npc->$value = $total;
			}
		}

		//
		//  Calculate & Set Npc Skills
		foreach (\CreatureHelper::getSkills() as $key => $value) {
			$npc->$key = NULL;
		}
		if (request('skills')) {
			foreach (request('skills') as $skill) {
				foreach (\CreatureHelper::getSkillMods() as $key => $value) {
					if ($skill == $key) {
						$total = \Common::mod($npc->$value) + request('proficiency');
						$npc->$skill = $total;
					}
				}
			}
		}

		//
		// Set CR to fraction
		$npc->CR_fraction = \Common::decimalToFraction(request('CR'));

		if ($npc->key == 0) {
			$npc->key = str_random(32);
		}

		if (request('private') != 1) {
			$npc->private = 0;
		}

		//
		//  Save To Npcs Table
		//
		$npc->update($input);

		$npc->spells()->detach();
		if (request('spells_at_will')) {
			foreach (request('spells_at_will') as $key) {
				$npc->spells()->attach($key, ['level' => 'at_will']);
			}
		}

		if (request('spells_one')) {
			foreach (request('spells_one') as $key) {
				$npc->spells()->attach($key, ['level' => 'one']);
			}
		}

		if (request('spells_two')) {
			foreach (request('spells_two') as $key) {
				$npc->spells()->attach($key, ['level' => 'two']);
			}
		}

		if (request('spells_three')) {
			foreach (request('spells_three') as $key) {
				$npc->spells()->attach($key, ['level' => 'three']);
			}
		}

		//
		//  Begin Processing Npc Abilities, Attacks and Actions
		$npc->abilities()->delete();
		if (request('abilities')) {
			foreach (request('abilities') as $ability) {
				if (!empty($ability['name'])) {
					$newAbility = new NpcAbility;
					$newAbility->npc_id = $npc->id;
					$newAbility->name = $ability['name'];
					$newAbility->description = $ability['description'];
					$newAbility->save();
				}
			}
		}

		$npc->actions()->delete();
		if (request('actions')) {
			foreach (request('actions') as $action) {
				if (!empty($action['name'])) {
					$newAction = new NpcAction;
					$newAction->npc_id = $npc->id;
					$newAction->name = $action['name'];
					$newAction->description = $action['description'];
					$newAction->damage_type = $action['damage_type'];
					$newAction->damage_dice = $action['damage_dice'];
					$newAction->attack_type = $action['attack_type'];
					$newAction->range = $action['range'];
					$newAction->legendary = isset($action['legendary']) ? $action['legendary'] : 0;

					if ($action['attack_type'] == 'ranged') {
						$newAction->attack_bonus = \Common::modUnsigned($npc->dexterity) + $npc->proficiency;
						$newAction->damage_bonus = \Common::modUnsigned($npc->dexterity);
					} elseif ($action['attack_type'] == 'melee') {
						$newAction->attack_bonus = \Common::modUnsigned($npc->strength) + $npc->proficiency;
						$newAction->damage_bonus = \Common::modUnsigned($npc->strength);
					}

					$newAction->save();
				}
			}
		}

		$request->session()->flash('status', 'Your npc has been edited!');

		return redirect()->action('NpcController@show', ['id' => $id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id) {
		$npc = Npc::find($id);
		$this->authorize('delete', $npc);

		$npc->delete();

		// redirect
		$request->session()->flash('status', 'Successfully deleted the NPC!');
		return redirect('npc');
	}

	public function fork($id) {
		$spells = Cache::tags(['spells'])->remember('spells_list', 60, function () {
			return \App\Spell::pluck('name', 'id');
		});

		$npc = Npc::with('actions', 'abilities')->findOrFail($id);

		$npc->type = explode(' / ', $npc->type);

		$saving_throws[] = '';
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			if ($npc->$key != NULL) {
				$saving_throws[] = $key;
			}
		}

		$skills[] = '';
		foreach (\CreatureHelper::getSkills() as $key => $value) {
			if ($npc->$key != NULL) {
				$skills[] = $key;
			}
		}

		$npc->languages = array_map('trim', explode(',', $npc->languages));
		$npc->languages = array_map('lcfirst', $npc->languages);
		$npc->damage_vulnerabilities = array_map('trim', explode(',', $npc->damage_vulnerabilities));
		$npc->damage_resistances = array_map('trim', explode(',', $npc->damage_resistances));
		$npc->damage_immunities = array_map('trim', explode(',', $npc->damage_immunities));
		$npc->condition_immunities = array_map('trim', explode(',', $npc->condition_immunities));

		return view('npc.edit', compact('npc', 'skills', 'saving_throws', 'spells'));
	}
}
