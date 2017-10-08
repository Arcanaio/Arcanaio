<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayer;
use App\Http\Requests\UpdatePlayer;
use App\Player;
use App\SpellSlots;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PlayerController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$players = \Auth::user()->players;

		return view('player.index', compact('players'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$skills = [];
		$expertise = [];
		$saving_throws = [];
		$classes = [];

		return view('player.create', compact('saving_throws', 'skills', 'expertise', 'classes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StorePlayer $request) {
		//
		//  Automatically set some fields that aren't submitted by user.
		$input = $request->except(['_token', 'class', 'languages', 'skills', 'expertise', 'saving_throws']);
		$input['source'] = 'User';
		$input['system'] = '5E';

		//
		//  Start new Player instance and pass it all input.
		$player = new Player($input);

		$player->user_id = \Auth::id();
		$player->key = str_random(32);

		if (!request('race')) {
			$player->race = 'None';
		}

		foreach (request('class') as $class) {
			if (!empty($class['name'])) {
				if ($class['level'] != 0 && $class['level'] != null) {
					$classes[] = ['name' => $class['name'], 'level' => $class['level']];
				}
			}
		}

		if (isset($classes)) {
			$player->classes = json_encode($classes);
		} else {
			$request->session()->flash('error', 'At least one class is required!');
			return redirect('character/create')->withInput();
		}

		$player->languages = is_array(request('languages')) ? implode(array_map('ucfirst', request('languages')), ', ') : NULL;

		//
		//  Calculate & Set Saving Throws
		//
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			$ability = lcfirst(\GeneralHelper::getSavingThrows()[$key]);
			$total = \Common::mod(request($ability));
			$player->$key = $total;
			$proficiency = $key . '_proficiency';
			$player->$proficiency = 0;
		}

		if (request('saving_throws')) {
			foreach (request('saving_throws') as $key => $value) {
				$ability = lcfirst(\GeneralHelper::getSavingThrows()[$value]);
				$total = \Common::mod(request($ability)) + request('proficiency');
				$player->$value = $total;
				$proficiency = $value . '_proficiency';
				$player->$proficiency = 1;
			}
		}

		//
		//  Calculate & Set player Skills
		foreach (\CreatureHelper::getSkillMods() as $key => $value) {
			$total = \Common::mod($player->$value);
			$player->$key = $total;
		}

		if (request('skills')) {
			foreach (request('skills') as $skill) {
				foreach (\CreatureHelper::getSkillMods() as $key => $value) {
					if ($skill == $key) {
						$total = \Common::mod($player->$value) + request('proficiency');
						$player->$skill = $total;
						$proficiency = $skill . '_proficiency';
						$player->$proficiency = 1;
					}
				}
			}
		}

		//
		//Set expertise here.
		if (request('expertise')) {
			foreach (request('expertise') as $skill) {
				foreach (\CreatureHelper::getSkillMods() as $key => $value) {
					if ($skill == $key) {
						$total = \Common::mod($player->$value) + (request('proficiency') * 2);
						$player->$skill = $total;
					}
				}
			}

			$player->expertise = json_encode(request('expertise'));
		}

		if (request('HP_max')) {
			$player->HP_current = request('HP_max');
		}

		//
		//  Save To Players Table
		$player->save();

		if (\SpellHelper::checkMultiSpellcaster($classes)) {
			if (in_array('Warlock', array_pluck($classes, 'name'))) {

				$player_slots = new SpellSlots();
				$player_slots->player_id = $player->id;

				$slots = \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes));

				foreach ($slots as $key => $value) {
					$player_slots->$key = $value;
				}

				$pacts = \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level']);

				foreach ($pacts as $key => $value) {
					$key = $key . " Pact";
					$player_slots->$key = $value;
				}

				$player_slots->save();

			} else {
				$player_slots = new SpellSlots();
				$player_slots->player_id = $player->id;

				$slots = \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes));

				foreach ($slots as $key => $value) {
					$player_slots->$key = $value;
				}

				$player_slots->save();
			}
		} else {
			foreach ($classes as $class) {

				if (\SpellHelper::checkSpellcaster($class['name'])) {

					$player_slots = new SpellSlots();
					$player_slots->player_id = $player->id;

					$slots = \SpellHelper::getSpellSlots($class['name'], $class['level']);

					if ($class['name'] == 'Warlock') {
						foreach ($slots as $key => $value) {
							$player_slots->$key = '0';
							$key = $key . " Pact";
							$player_slots->$key = $value;
						}
					} else {
						foreach ($slots as $key => $value) {
							$player_slots->$key = $value;
						}
					}

					$player_slots->save();
				}
			}

		}

		$request->session()->flash('status', 'Your player has been added!');

		return redirect('character/' . $player->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {

		$player = Player::withTrashed()->with('items', 'spells', 'private_notes.user', 'user', 'files.user')->findOrFail($id);

		$this->authorize('view', $player);

		$items = Cache::tags(['items'])->remember('items_list', 60, function () {
			return \App\Item::pluck('name', 'id');
		});

		$spells = Cache::tags(['spells'])->remember('spells_list', 60, function () {
			return \App\Spell::pluck('name', 'id');
		});

		if ($player->deleted_at != NULL) {
			return view('deleted');
		}

		$classes = json_decode($player->classes, true);

		$expertise = json_decode($player->expertise);

		if (count($classes) > 1) {
			foreach ($classes as $class) {
				if (\SpellHelper::checkSpellcaster($class['name']) && \SpellHelper::checkMultiSpellcaster($classes)) {
					$spell_ability = lcfirst(\SpellHelper::getClassSpellAbilities()[$class['name']]);
					$spell_abilities[] = lcfirst(\SpellHelper::getClassSpellAbilities()[$class['name']]) . " (" . $class['name'] . ") ";
					$spell_save_dc[] = (8+\Common::mod($player->$spell_ability) + $player->proficiency) . " (" . $class['name'] . ") ";
					$spell_attack_bonus[] = "+" . (\Common::mod($player->$spell_ability) + $player->proficiency) . " (" . $class['name'] . ") ";
				} elseif (\SpellHelper::checkSpellcaster($class['name'])) {
					$spell_ability = lcfirst(\SpellHelper::getClassSpellAbilities()[$class['name']]);
					$spell_abilities[] = lcfirst(\SpellHelper::getClassSpellAbilities()[$class['name']]);
					$spell_save_dc[] = (8+\Common::mod($player->$spell_ability) + $player->proficiency);
					$spell_attack_bonus[] = "+" . (\Common::mod($player->$spell_ability) + $player->proficiency);
				} else {
					$spell_ability = "";
					$spell_abilities[] = $spell_ability;
					$spell_save_dc[] = "";
					$spell_attack_bonus[] = "";
				}
			}

		} else {
			if (\SpellHelper::checkSpellcaster($classes[0]['name'])) {
				$spell_ability = lcfirst(\SpellHelper::getClassSpellAbilities()[$classes[0]['name']]);
				$spell_abilities[] = $spell_ability;
				$spell_save_dc[] = (8+\Common::mod($player->$spell_ability) + $player->proficiency);
				$spell_attack_bonus[] = "+" . (\Common::mod($player->$spell_ability) + $player->proficiency);
			} else {
				$spell_ability = "None";
				$spell_abilities[] = $spell_ability;
				$spell_save_dc[] = "None";
				$spell_attack_bonus[] = "None";
			}
		}

		$spells_known = [];
		foreach ($classes as $class) {
			if (\SpellHelper::checkSpellcaster($class['name'])) {
				$ability = lcfirst(\SpellHelper::getClassSpellAbilities()[$class['name']]);
				$known = \SpellHelper::getSpellsKnown($class['name'], $class['level'], \Common::modUnsigned($player->$ability));

				if ($class['name'] == 'Cleric' || $class['name'] == 'Druid' || $class['name'] == 'Paladin' || $class['name'] == 'Wizard') {
					$spells_known[] = $known . " to prepare";
				} else {
					$spells_known[] = $known . " known";
				}

			}
		}

		$notes = $player->private_notes->where('user_id', \Auth::id());

		return view('player.show', compact('player', 'notes', 'classes', 'items', 'spells', 'spell_abilities', 'spell_save_dc', 'spell_attack_bonus', 'spells_known', 'expertise'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$player = Player::findOrFail($id);

		$this->authorize('update', $player);

		$saving_throws[] = '';
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			$proficiency = $key . '_proficiency';
			if ($player->$proficiency == 1) {
				$saving_throws[] = $key;
			}
		}

		$skills[] = '';
		foreach (\CreatureHelper::getSkills() as $key => $value) {
			$proficiency = $key . '_proficiency';
			if ($player->$proficiency == 1) {
				$skills[] = $key;
			}
		}

		$expertise[] = '';
		foreach (\CreatureHelper::getSkills() as $key => $value) {
			if (is_array(json_decode($player->expertise)) && in_array($key, json_decode($player->expertise))) {
				$expertise[] = $key;
			}
		}

		$player->languages = array_map('trim', explode(',', $player->languages));
		$player->languages = array_map('lcfirst', $player->languages);

		$classes = json_decode($player->classes, true);

		return view('player.edit', compact('player', 'classes', 'saving_throws', 'skills', 'expertise'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdatePlayer $request, $id) {
		$player = Player::findOrFail($id);
		$this->authorize('update', $player);

		//
		//  Automatically set some fields that aren't submitted by user.
		//

		$input = $request->except(['_token', 'class', 'languages', 'saving_throws', 'skills', 'expertise']);

		if (!request('race')) {
			$player->race = 'None';
		}

		foreach (request('class') as $class) {
			if (!empty($class['name'])) {
				if ($class['level'] != "0" && $class['level'] != null) {
					$classes[] = ['name' => $class['name'], 'level' => $class['level']];
				}
			}
		}

		if (isset($classes)) {
			$player->classes = json_encode($classes);
		}

		if (is_array(request('languages'))) {
			$player->languages = implode(array_map('ucfirst', request('languages')), ', ');
		} else {
			$player->languages = null;
		}

		if ($player->HP_current > request('HP_max') || $player->HP_current == NULL) {
			$player->HP_current = request('HP_max');
		}

		//
		//  Calculate & Set Saving Throws
		//
		foreach (\GeneralHelper::getSavingThrows() as $key => $value) {
			$ability = lcfirst(\GeneralHelper::getSavingThrows()[$key]);
			$total = \Common::mod(request($ability));
			$player->$key = $total;
			$proficiency = $key . '_proficiency';
			$player->$proficiency = 0;
		}

		if (request('saving_throws')) {
			foreach (request('saving_throws') as $key => $value) {
				$ability = lcfirst(\GeneralHelper::getSavingThrows()[$value]);
				$total = \Common::mod(request($ability)) + request('proficiency');
				$player->$value = $total;
				$proficiency = $value . '_proficiency';
				$player->$proficiency = 1;
			}
		}

		//
		//  Calculate & Set player Skills
		//
		foreach (\CreatureHelper::getSkillMods() as $key => $value) {
			$total = \Common::mod(request($value));
			$player->$key = $total;
			$proficiency = $key . '_proficiency';
			$player->$proficiency = 0;
		}

		if (request('skills')) {
			foreach (request('skills') as $skill) {
				foreach (\CreatureHelper::getSkillMods() as $key => $value) {
					if ($skill == $key) {
						$total = \Common::mod(request($value)) + request('proficiency');
						$player->$skill = $total;
						$proficiency = $skill . '_proficiency';
						$player->$proficiency = 1;
					}
				}
			}
		}

		//Set expertise here.
		//
		if (request('expertise')) {
			foreach (request('expertise') as $skill) {
				foreach (\CreatureHelper::getSkillMods() as $key => $value) {
					if ($skill == $key) {
						$total = \Common::mod(request($value)) + (request('proficiency') * 2);
						$player->$skill = $total;
					}
				}
			}

			$player->expertise = json_encode(request('expertise'));
		} else {
			$player->expertise = NULL;
		}

		$player->update($input);

		if (\SpellHelper::checkMultiSpellcaster($classes)) {
			if (in_array('Warlock', array_pluck($classes, 'name'))) {

				$player_slots = SpellSlots::where('player_id', $player->id)->first();
				$player_slots->player_id = $player->id;

				$slots = \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes));

				foreach ($slots as $key => $value) {
					$player_slots->$key = $value;
				}

				$pacts = \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level']);

				foreach ($pacts as $key => $value) {
					$key = $key . " Pact";
					$player_slots->$key = $value;
				}

				$player_slots->save();

			} else {
				$player_slots = SpellSlots::where('player_id', $player->id)->first();
				$player_slots->player_id = $player->id;

				$slots = \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes));

				foreach ($slots as $key => $value) {
					$player_slots->$key = $value;
				}

				$player_slots->save();
			}
		} else {
			foreach ($classes as $class) {
				if (\SpellHelper::checkSpellcaster($class['name'])) {

					$player_slots = SpellSlots::where('player_id', $player->id)->first();
					$player_slots->player_id = $player->id;

					$slots = \SpellHelper::getSpellSlots($class['name'], $class['level']);

					if ($class['name'] == 'Warlock') {
						foreach ($slots as $key => $value) {
							$player_slots->$key = '0';
							$key = $key . " Pact";
							$player_slots->$key = $value;
						}
					} else {
						foreach ($slots as $key => $value) {
							$player_slots->$key = $value;
						}
					}

					$player_slots->save();
				}
			}

		}

		$request->session()->flash('status', 'Your player has been edited!');

		return redirect('/character/' . $player->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, $id) {
		$player = Player::findOrFail($id);
		$this->authorize('delete', $player);

		$player->delete();

		// redirect
		$request->session()->flash('status', 'Successfully deleted the character!');
		return redirect('character');
	}

	public function batchItems(Request $request) {
		$this->validate($request, [
			'player' => 'required|numeric',
			'items' => 'array',
		]);

		$player = Player::findOrFail(request('player'));
		$this->authorize('update', $player);

        $req = request('items');
        
        if(is_array($req)){
	        foreach ($req as $item) {
	            $exists = $player->items->contains($item);
	            if ($exists) {
		            \DB::table('inventories')->where(['player_id' => $player->id, 'item_id' => $item])->increment('quantity');
	            }
	        }

	        $player->items()->syncWithoutDetaching(request('items'));
            $request->session()->flash('status', 'Successfully added to your inventory!');
        }else{
            $request->session()->flash('error', 'Invalid Item');
        }
		

		return redirect('character/' . $player->id . '#/inventory');
	}

	public function batchSpells(Request $request) {
		$this->validate($request, [
			'player' => 'required|numeric',
			'spells' => 'array',
		]);

		$player = Player::findOrFail(request('player'));
		$this->authorize('update', $player);

        $req = request('spells');
        
        if(is_array($req)){
		    $player->spells()->syncWithoutDetaching($req);

		    $request->session()->flash('status', 'Successfully added to your spellbook!');
        }else{
            $request->session()->flash('error', 'Invalid Spell');
        }
		
		return redirect('character/' . $player->id . '#/spellbook');
	}

	public function coins(Request $request) {
		$this->validate($request, [
			'player_id' => 'required|numeric',
			'PP' => 'numeric|digits_between:0,10',
			'GP' => 'numeric|digits_between:0,10',
			'EP' => 'numeric|digits_between:0,10',
			'SP' => 'numeric|digits_between:0,10',
			'CP' => 'numeric|digits_between:0,10',
		]);

		$player = Player::findOrFail(request('player_id'));
		$this->authorize('update', $player);

		if (request('PP')) {
			$player->PP = request('PP');
		} else {
			$player->PP = 0;
		}

		if (request('GP')) {
			$player->GP = request('GP');
		} else {
			$player->GP = 0;
		}

		if (request('EP')) {
			$player->EP = request('EP');
		} else {
			$player->EP = 0;
		}

		if (request('SP')) {
			$player->SP = request('SP');
		} else {
			$player->SP = 0;
		}

		if (request('CP')) {
			$player->CP = request('CP');
		} else {
			$player->CP = 0;
		}

		$player->save();

		$request->session()->flash('status', 'Successfully edited your coins!');

		return redirect('character/' . $player->id . '#/inventory');
	}

	public function prepareSpell(Request $request, $player_id, $spell_id) {
		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		$spell = \DB::table('spellbooks')->where(['player_id' => $player_id, 'spell_id' => $spell_id])->first();

		if ($spell->prepared == 1) {
			\DB::table('spellbooks')->where(['player_id' => $player_id, 'spell_id' => $spell_id])->decrement('prepared');
		} else {
			\DB::table('spellbooks')->where(['player_id' => $player_id, 'spell_id' => $spell_id])->increment('prepared');
		}

		$response = array(
			'status' => 'success',
			'msg' => 'Preparation successful.',
		);

		return response()->json($response);
	}

	public function forgetSpell(Request $request, $player_id, $spell) {
		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		$spell = \DB::table('spellbooks')->where(['player_id' => $player_id, 'spell_id' => $spell])->delete();

		if ($spell) {
			$request->session()->flash('status', 'Successfully removed the spell!');
		} else {
			$request->session()->flash('error', 'Please try again.');
		}

		return back();
	}

	public function incrementSpellCasts(Request $request, $player_id, $slot) {
		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		$player_slots = \DB::table('player_spell_slots')->where('player_id', $player_id)->first();

		\DB::table('player_spell_slots')->where('player_id', $player_id)->increment($slot);

		$response = array(
			'status' => 'success',
			'msg' => 'Increment successful.',
		);

		return response()->json($response);
	}

	public function decrementSpellCasts(Request $request, $player_id, $slot) {
		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		$player_slots = \DB::table('player_spell_slots')->where('player_id', $player_id)->first();

		if ($player_slots->$slot > 0) {
			\DB::table('player_spell_slots')->where('player_id', $player_id)->decrement($slot);
		}

		$response = array(
			'status' => 'success',
			'msg' => 'Increment successful.',
		);

		return response()->json($response);
	}

	public function incrementItemQuantity(Request $request, $player_id, $item_id) {
		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		\DB::table('inventories')->where(['player_id' => $player_id, 'item_id' => $item_id])->increment('quantity');

		$response = array(
			'status' => 'success',
			'msg' => 'Increment successful.',
		);

		return response()->json($response);
	}

	public function decrementItemQuantity(Request $request, $player_id, $item_id) {
		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		\DB::table('inventories')->where(['player_id' => $player_id, 'item_id' => $item_id])->decrement('quantity');

		$pivot = \DB::table('inventories')->where(['player_id' => $player_id, 'item_id' => $item_id])->first();

		$quantity = $pivot->quantity;

		if ($quantity == 0) {
			$player->items()->detach($item_id);

			$response = array(
				'status' => 'deleted',
				'msg' => 'Delete successful.',
			);
		} else {
			$response = array(
				'status' => 'success',
				'msg' => 'Decrement successful.',
			);
		}

		return response()->json($response);
	}

	public function damage(Request $request, $player_id) {
		$this->validate($request, [
			'amount' => 'required|numeric',
			'player_id' => 'required|numeric',
		]);

		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		$total = $player->HP_current - request('amount');

		if ($total < 0) {
			$player->HP_current = 0;
		} else {
			$player->HP_current = $total;
		}

		$player->save();

		return back();
	}

	public function heal(Request $request, $player_id) {
		$this->validate($request, [
			'amount' => 'required|numeric',
			'player_id' => 'required|numeric',
		]);

		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		$total = $player->HP_current + request('amount');

		if ($total > $player->HP_max) {
			$player->HP_current = $player->HP_max;
		} else {
			$player->HP_current = $total;
		}

		$player->save();

		return back();
	}

	public function longRest(Request $request, $player_id) {
		$this->validate($request, [
			'player_id' => 'required|numeric',
		]);

		$player = Player::findOrFail($player_id);
		$this->authorize('update', $player);

		$player->HP_current = $player->HP_max;

		$player->save();

		$classes = json_decode($player->classes, true);

		if (\SpellHelper::checkMultiSpellcaster($classes)) {
			if (in_array('Warlock', array_pluck($classes, 'name'))) {

				$player_slots = SpellSlots::where('player_id', $player->id)->first();
				$player_slots->player_id = $player->id;

				$slots = \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes));

				foreach ($slots as $key => $value) {
					$player_slots->$key = $value;
				}

				$pacts = \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level']);

				foreach ($pacts as $key => $value) {
					$key = $key . " Pact";
					$player_slots->$key = $value;
				}

				$player_slots->save();

			} else {
				$player_slots = SpellSlots::where('player_id', $player->id)->first();
				$player_slots->player_id = $player->id;

				$slots = \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes));

				foreach ($slots as $key => $value) {
					$player_slots->$key = $value;
				}

				$player_slots->save();
			}
		} else {
			foreach ($classes as $class) {
				if (\SpellHelper::checkSpellcaster($class['name'])) {

					$player_slots = SpellSlots::where('player_id', $player->id)->first();
					$player_slots->player_id = $player->id;

					$slots = \SpellHelper::getSpellSlots($class['name'], $class['level']);

					if ($class['name'] == 'Warlock') {
						foreach ($slots as $key => $value) {
							$player_slots->$key = '0';
							$key = $key . " Pact";
							$player_slots->$key = $value;
						}
					} else {
						foreach ($slots as $key => $value) {
							$player_slots->$key = $value;
						}
					}

					$player_slots->save();
				}
			}

		}

		$request->session()->flash('status', 'You feel well rested! HP and spell slots were recovered.');

		return back();
	}

	public function seedSpellSlots() {
		$players = Player::all();

		foreach ($players as $player) {
			$classes = json_decode($player->classes, true);
			if (\SpellHelper::checkMultiSpellcaster($classes)) {
				if (in_array('Warlock', array_pluck($classes, 'name'))) {

					$player_slots = new SpellSlots();
					$player_slots->player_id = $player->id;

					$slots = \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes));

					foreach ($slots as $key => $value) {
						$player_slots->$key = $value;
					}

					$pacts = \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level']);

					foreach ($pacts as $key => $value) {
						$key = $key . " Pact";
						$player_slots->$key = $value;
					}

					$player_slots->save();

				} else {
					$player_slots = new SpellSlots();
					$player_slots->player_id = $player->id;

					$slots = \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes));

					foreach ($slots as $key => $value) {
						$player_slots->$key = $value;
					}

					$player_slots->save();
				}
			} else {
				foreach ($classes as $class) {
					if (\SpellHelper::checkSpellcaster($class['name'])) {

						$player_slots = new SpellSlots();
						$player_slots->player_id = $player->id;

						$slots = \SpellHelper::getSpellSlots($class['name'], $class['level']);

						foreach ($slots as $key => $value) {
							$player_slots->$key = $value;
						}

						$player_slots->save();
					}
				}

			}
		}

		return redirect('/');
	}
}
