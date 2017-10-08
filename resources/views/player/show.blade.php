@extends('layouts.app')

@section('title', $player->name)

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="row">
      <div class="ui eight wide column">
        <h2 class="ui header">
          {{ ucfirst($player->name) }}
          <div class="ui left pointing right floated label">
            {{ $player->race or "No Race" }}
          </div>
          <div class="sub header">
              @if($classes)
                @foreach($classes as $class)
                   @if(!empty($class['name']))
                    {{ $class['name'] }} {{ $class['level'] }}
                    @if(!$loop->last) / @endif
                  @endif
                @endforeach
              @endif
          </div>
          <div class="sub header">
             {{ ucwords($player->alignment) }}
          </div>
        </h2>
      </div>
      <div class="ui eight wide column">
        <div class="ui three mini statistics">
          <div class="statistic">
            <div class="value">
              @if($player->hit_dice != NULL)
                {{ $player->hit_dice }}
              @else
                N/A
              @endif
            </div>
            <div class="label">
              Hit Dice
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              {{ $player->AC or 'N/A' }}
            </div>
            <div class="label">
              AC
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              +{{ $player->proficiency }}
            </div>
            <div class="label">
              Proficiency
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('submenu')
<div id="submenu">
  <div class="ui container">
    <div class="ui tabular icon tiny menu">
      <a class="active item" data-tab="stats">
        <i class="large icon" data-icon="&#xe01c;"></i>
      </a>
      <a class="item" data-tab="history">
        <i class="large icon" data-icon="&#xe130;"></i>
      </a>
      <a class="item" data-tab="spellbook">
        <i class="large icon" data-icon="&#xe0c9;"></i>
      </a>
      <a class="item" data-tab="inventory">
        <i class="large icon" data-icon="&#xe139;"></i>
      </a>
      <a class="item" data-tab="notes">
        <i class="large icon" data-icon="&#xe196;"></i>
      </a>
      <div class="right menu">
        <a href="/character" class="item mobile hidden"><i class="angle left icon"></i>Back to Characters</a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="ui eleven wide column">
  <div class="ui active tab" data-tab="stats">
    <div class="ui three column vertically padded grid">
      <div class="row">
        <div class="column">

            <div class="ui center aligned header">
              <i class="icon player-header-icon" data-icon="&#xe28b;"></i>
              <div class="content player-header-content">
                {{ $player->AC or "N/A"}}
                <div class="sub header">AC</div>
              </div>
            </div>

        </div>
        <div class="column">

           <div class="ui center aligned header">
             <i class="icon player-header-icon" data-icon="&#xe24c;"></i>
             <div class="content player-header-content">
               {{ \Common::mod($player->dexterity) }}
               <div class="sub header">Initiative</div>
             </div>
           </div>

        </div>
        <div class="column">

            <div class="ui center aligned header">
              <i class="icon player-header-icon" data-icon="&#xe1c8;"></i>
              <div class="content player-header-content">
                {{ $player->speed or "N/A" }}ft
                <div class="sub header">Speed</div>
              </div>
            </div>

        </div>
      </div>
    </div>

    <div class="ui red indicating large progress" data-value="{{ $player->HP_current }}" data-total="{{ $player->HP_max }}" id="player-health-bar">
        <div class="bar">
          <div class="progress">
            @if($player->HP_current > 0)
              {{ $player->HP_current }}/{{ $player->HP_max }}
            @else
             0
            @endif
          </div>
        </div>
    </div>

    <div class="ui fluid basic buttons">
      <button class="ui button" id="damage-modal-trigger"><i class="icon" data-icon="&#xe02d;"></i>Take Damage</button>
      <button class="ui button" id="heal-modal-trigger"><i class="icon" data-icon="&#xe01e;"></i>Heal</button>
    </div>

    <div class="ui hidden divider"></div>




    <div class="ui stats divider"></div>
    <div class="ui hidden divider"></div>

    <div class="ui six mini statistics">
      <div class="statistic">
        <div class="value">
          {{ $player->strength }}
          @if(\Common::mod($player->strength) > 0)
            <span class="text-green superscript">{{ \Common::mod($player->strength) }}</span>
          @elseif(\Common::mod($player->strength) < 0)
            <span class="text-red superscript">{{ \Common::mod($player->strength) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($player->strength) }}</span>
          @endif
        </div>
        <div class="label">
          Str
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->dexterity }}
          @if(\Common::mod($player->dexterity) > 0)
            <span class="text-green superscript">{{ \Common::mod($player->dexterity) }}</span>
          @elseif(\Common::mod($player->dexterity) < 0)
            <span class="text-red superscript">{{ \Common::mod($player->dexterity) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($player->dexterity) }}</span>
          @endif
        </div>
        <div class="label">
          Dex
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->constitution }}
          @if(\Common::mod($player->constitution) > 0)
            <span class="text-green superscript">{{ \Common::mod($player->constitution) }}</span>
          @elseif(\Common::mod($player->constitution) < 0)
            <span class="text-red superscript">{{ \Common::mod($player->constitution) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($player->constitution) }}</span>
          @endif
        </div>
        <div class="label">
          Con
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->intelligence }}
          @if(\Common::mod($player->intelligence) > 0)
            <span class="text-green superscript">{{ \Common::mod($player->intelligence) }}</span>
          @elseif(\Common::mod($player->intelligence) < 0)
            <span class="text-red superscript">{{ \Common::mod($player->intelligence) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($player->intelligence) }}</span>
          @endif
        </div>
        <div class="label">
          Int
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->wisdom }}
          @if(\Common::mod($player->wisdom) > 0)
            <span class="text-green superscript">{{ \Common::mod($player->wisdom) }}</span>
          @elseif(\Common::mod($player->wisdom) < 0)
            <span class="text-red superscript">{{ \Common::mod($player->wisdom) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($player->wisdom) }}</span>
          @endif
        </div>
        <div class="label">
          Wis
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->charisma }}
          @if(\Common::mod($player->charisma) > 0)
            <span class="text-green superscript">{{ \Common::mod($player->charisma) }}</span>
          @elseif(\Common::mod($player->charisma) < 0)
            <span class="text-red superscript">{{ \Common::mod($player->charisma) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($player->charisma) }}</span>
          @endif
        </div>
        <div class="label">
          Cha
        </div>
      </div>
    </div>

    <div class="ui hidden divider"></div>
    <div class="ui stats divider"></div>

    <div class="ui three column vertically padded grid">
      <div class="row">
        <div class="column">

            <div class="ui center aligned header">
              <i class="icon player-header-icon" data-icon="&#xe163;"></i>
              <div class="content player-header-content">
                +{{ $player->proficiency }}
                <div class="sub header">Proficiency</div>
              </div>
            </div>

        </div>
        <div class="column">
            <div class="ui center aligned header">
              <i class="icon player-header-icon" data-icon="&#xe30a;"></i>
              <div class="content player-header-content">
                {{ 10 + $player->perception }}
                <div class="sub header">Passive Perception</div>
              </div>
            </div>
        </div>

        <div class="column">
            <div class="ui center aligned header">
              <i class="icon player-header-icon" data-icon="&#xe177;"></i>
              <div class="content player-header-content">
                {{ $player->darkvision or '0' }} ft
                <div class="sub header">Darkvision</div>
              </div>
            </div>
        </div>
      </div>
    </div>








      <div class="ui basic segment">
        <table class="ui very basic compact unstackable table">
          <thead>
            <tr><th>Proficient?</th>
            <th>Saving Throw</th>
            <th>Modifier</th>
          </tr></thead>
          <tbody>
            @foreach(\GeneralHelper::getSavingThrows() as $key => $value)
            <tr>
              <td class="collapsing">
                @php $proficiency = $key.'_proficiency'; @endphp
                @if($player->$proficiency)
                  <i class="checkmark icon"></i>
                @endif
              </td>
              <td>{{ $value }}</td>
              <td class="collapsing"><a class="ui {{ \Common::colorSkillMod($player->$key) }} right ribbon label">{{ \Common::signNum($player->$key) }}</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="ui basic segment">
        <table class="ui very basic compact unstackable table">
          <thead>
            <tr><th>Proficient?</th>
            <th>Skill</th>
            <th>Modifier</th>
          </tr></thead>
          <tbody>
            @foreach(\CreatureHelper::getSkills() as $key => $value)
            <tr>
              <td class="collapsing">
                @php $proficiency = $key.'_proficiency'; @endphp
                @if($player->$proficiency)
                  <i class="check icon"></i>
                @endif
                @if($expertise)
                  @if(in_array($key, $expertise))
                    <i class="check icon"></i>
                  @endif
                @endif
              </td>
              <td>{{ $value }} <small class="text-muted">({{ ucfirst(\CreatureHelper::getSkillMods()[$key]) }})</small></td>
              <td class="collapsing"><a class="ui {{ \Common::colorSkillMod($player->$key) }} right ribbon label">{{ \Common::signNum($player->$key) }}</a></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

  </div>

  <div class="ui tab" data-tab="history">
      @if($player->languages)
        <div class="ui dividing small header">Languages</div>
          <div class="ui horizontal divided tiny list">
            <?php $languages = explode(',', $player->languages);?>
            @foreach($languages as $language)
              <div class="item">{{ ucwords($language) }}</div>
            @endforeach
        </div>
      @endif

      <div class="ui dividing small header">Proficiencies</div>
      <p>{!! clean($player->proficiencies) !!}</p>

      <div class="ui dividing small header">Description & Background</div>
      <p>{!! clean($player->description) !!}</p>

      <div class="ui dividing small header">Feats</div>
      <p>{!! clean($player->feats) !!}</p>


  </div>

  <div class="ui tab" data-tab="spellbook">

    <form class="ui form" method="POST" action="{{ url('character/batch/spells') }}">
      {{ csrf_field() }}
      <input type="hidden" name="player" value="{{ $player->id }}">
      <div class="field">
        <div class="ui action fluid input">
          {{ Form::select('spells[]', $spells, null,['class' => 'ui fluid search dropdown', 'multiple'=>'true']) }}
          <button class="ui primary button" type="submit">Add Spells</button>
        </div>
      </div>
    </form>

    <h5 class="ui header">Spellcasting Stats</h5>

    <div class="ui doubling four column grid text-center">
      <div class="column">
        <h4 class="ui sub header">Spell Ability</h4>
        @if($spell_abilities)
          @foreach($spell_abilities as $ability)
            {{ ucwords($ability) }}<br>
          @endforeach
        @endif
      </div>
      <div class="column">
        <h4 class="ui sub header">Spell Attack</h4>
        @if($spell_attack_bonus)
          @foreach($spell_attack_bonus as $bonus)
            {{ $bonus }}<br>
          @endforeach
        @endif
      </div>
      <div class="column">
        <h4 class="ui sub header">Save DC</h4>
        @if($spell_save_dc)
          @foreach($spell_save_dc as $save)
            {{ $save }}<br>
          @endforeach
        @endif

      </div>
      <div class="column">
        <h4 class="ui sub header">Spells Known</h4>
        @if($spells_known)
          @foreach($spells_known as $known)
            {{ $known }}<br>
          @endforeach
        @endif
      </div>
    </div>

    <div class="ui clearing hidden divider"></div>

    <div class="ui hidden divider"></div>

    <h5 class="ui left floated header">
      Cantrips
    </h5>

    @foreach($classes as $class)
      @if(\SpellHelper::checkSpellcaster($class['name']))
        <div class="ui label cantrips-known">
        {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['0-level (Cantrip)'] }} {{ lcfirst($class['name']) }} known
        </div>
      @endif
    @endforeach

    <table class="ui unstackable compact table">
      <thead>
        <th>Name</th>
        <th class="center aligned"></th>
      </thead>
      @forelse($player->spells->where('level', '0-level (Cantrip)') as $spell)
      <tr>
        <td>
          <a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      1st-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="1st-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['1st-level'] }}</span> /
          @if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['1st-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
                @if($class['name'] != 'Warlock')
                  {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['1st-level'] }}
                @else
                 0
                @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="1st-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['1st-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="1st-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['1st-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['1st-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="1st-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">
      <thead>
        <th>Prepared</th>
        <th class="right aligned">Cast Time / Range</th>
      </thead>
      @forelse($player->spells->where('level', '1st-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      2nd-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="2nd-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['2nd-level'] }}</span> /@if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['2nd-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
                @if($class['name'] != 'Warlock')
                  {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['2nd-level'] }}
                @else
                 0
                @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="2nd-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['2nd-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="2nd-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['2nd-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['2nd-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="2nd-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">

      @forelse($player->spells->where('level', '2nd-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      3rd-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="3rd-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['3rd-level'] }}</span> /@if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['3rd-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
              @if($class['name'] != 'Warlock')
                {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['3rd-level'] }}
              @else
               0
              @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="3rd-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['3rd-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="3rd-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['3rd-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['3rd-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="3rd-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">

      @forelse($player->spells->where('level', '3rd-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      4th-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="4th-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['4th-level'] }}</span> /@if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['4th-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
              @if($class['name'] != 'Warlock')
                {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['4th-level'] }}
              @else
               0
              @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="4th-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['4th-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="4th-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['4th-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['4th-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="4th-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">

      @forelse($player->spells->where('level', '4th-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      5th-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="5th-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['5th-level'] }}</span> /@if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['5th-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
              @if($class['name'] != 'Warlock')
                {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['5th-level'] }}
              @else
               0
              @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="5th-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['5th-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="5th-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['5th-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['5th-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="5th-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">

      @forelse($player->spells->where('level', '5th-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      6th-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="6th-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['6th-level'] }}</span> /@if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['6th-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
              @if($class['name'] != 'Warlock')
                {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['6th-level'] }}
              @else
               0
              @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="6th-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['6th-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="6th-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['6th-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['6th-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="6th-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">

      @forelse($player->spells->where('level', '6th-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      7th-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="7th-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['7th-level'] }}</span> /@if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['7th-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
              @if($class['name'] != 'Warlock')
                {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['7th-level'] }}
              @else
               0
              @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="7th-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['7th-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="7th-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['7th-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['7th-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="7th-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">

      @forelse($player->spells->where('level', '7th-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      8th-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="8th-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['8th-level'] }}</span> /@if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['8th-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
              @if($class['name'] != 'Warlock')
                {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['8th-level'] }}
              @else
               0
              @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="8th-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['8th-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="8th-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['8th-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['8th-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="8th-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">

      @forelse($player->spells->where('level', '8th-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui left floated header">
      9th-level Spells
    </h5>

    <div class="ui mini icon right floated buttons spell-slots">
      <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="9th-level">
        <i class="minus icon"></i>
      </button>
      <button class="ui disabled button">
        <span class="slot-quantity">{{ $player->spell_slots['9th-level'] }}</span> /@if(\SpellHelper::checkMultiSpellcaster($classes))
            {{ \SpellHelper::getMultiSpellSlots(\SpellHelper::getMultiSpellLevel($classes))['9th-level'] }}
          @else
            @foreach($classes as $class)
              @if(\SpellHelper::checkSpellcaster($class['name']))
              @if($class['name'] != 'Warlock')
                {{ \SpellHelper::getSpellSlots($class['name'], $class['level'])['9th-level'] }}
              @else
               0
              @endif
              @endif
            @endforeach
          @endif slots
      </button>
      <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="9th-level">
        <i class="plus icon"></i>
      </button>
    </div>

    @if(\SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['9th-level'] != 0)
      @if(in_array('Warlock', array_pluck($classes, 'name')))
        <div class="ui mini icon right floated buttons spell-slots">
          <button class="ui button" onclick="decrementSpellSlot(this)" data-slot="9th-level Pact">
            <i class="minus icon"></i>
          </button>
          <button class="ui disabled button">
            <span class="slot-quantity">{{ $player->spell_slots['9th-level Pact'] }}</span> /
            {{ \SpellHelper::getSpellSlots('Warlock', $classes[array_search('Warlock', $classes)]['level'])['9th-level'] }}
            pacts
          </button>
          <button class="ui button" onclick="incrementSpellSlot(this)" data-slot="9th-level Pact">
            <i class="plus icon"></i>
          </button>
        </div>
      @endif
    @endif

    <table class="ui unstackable compact table">

      @forelse($player->spells->where('level', '9th-level') as $spell)
      <tr>
        <td>
          <div class="ui checkbox">
            <input type="checkbox" name="{{ $spell->id }}" data-spell="{{ $spell->id }}" value="1" @if($spell->pivot->prepared) checked @endif onclick="togglePrepared(this)">
            <label><a href="{{ url('spell', $spell->id) }}" target="_blank">{{ $spell->name }}</a></label>
          </div>
        </td>
        <td class="right aligned">
          <div class="ui horizontal divided tiny list text-right text-muted">
            <div class="item">{{ $spell->casting_time }}</div>  <div class="item">{{ $spell->range }}</div>
            <div class="item"><a href="{{ url('character/forget',[$player->id, $spell->id]) }}" class="ui compact basic icon mini button"><i class="delete icon"></i></a></div>
            </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No spells of this level known.</td>
        </tr>
      @endforelse
    </table>

  </div>

  <div class="ui tab" data-tab="inventory">
    <form class="ui form" method="POST" action="{{ url('character/batch/items') }}">
      {{ csrf_field() }}
      <input type="hidden" name="player" value="{{ $player->id }}">
      <div class="field">
        <div class="ui action fluid input">
          {{ Form::select('items[]', $items, null,['class' => 'ui fluid search dropdown', 'multiple'=>'true']) }}
          <button class="ui primary button" type="submit">Add Items</button>
        </div>
        <label for=""><small class="text-muted">Tip: to remove items, reduce quantity to zero.</small></label>
      </div>
    </form>
    <div class="ui hidden divider"></div>
    <h5 class="ui left floated header">Coins</h5>
    <button class="ui mini icon right floated button" id="coin-modal-trigger"><i class="edit icon"></i></button>
    <div class="ui clearing hidden divider"></div>
    <div class="ui five mini statistics">
      <div class="statistic">
        <div class="value">
          {{ $player->PP or 0 }}
        </div>
        <div class="label">
          PP
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->GP or 0 }}
        </div>
        <div class="label">
          GP
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->EP or 0 }}
        </div>
        <div class="label">
          EP
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->SP or 0 }}
        </div>
        <div class="label">
          SP
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $player->CP or 0 }}
        </div>
        <div class="label">
          CP
        </div>
      </div>
    </div>

    <h5 class="ui header">Weapons</h5>
    <table class="ui unstackable compact table">
      @forelse($player->items->where('type','Weapon') as $item)
      <tr>
        <td class="">
          <a href="{{ url('item', $item->id) }}" target="_blank">{{ $item->name }}</a>

        </td>
        <td class="">
          <span class="text-muted">{{ $item->weapon_damage or "N/A" }}, {{ $item->weapon_properties or "N/A" }}</span>
        </td>
        <td class="collapsing right aligned">
          <div class="ui mini icon basic buttons borderless">
            <button class="ui button" onclick="decrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="minus icon"></i>
            </button>
            <button class="ui disabled button">
              <span class="item-quantity">{{ $item->pivot->quantity }}</span>
            </button>
            <button class="ui button" onclick="incrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="plus icon"></i>
            </button>
          </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No weapons in your inventory.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui header">Armor</h5>
    <table class="ui unstackable compact table">
      @forelse($player->items->where('type','Armor') as $item)
      <tr>
        <td class=""><a href="{{ url('item', $item->id) }}" target="_blank">{{ $item->name }}</a></td>
        <td class="">
          <span class="text-muted">@if($item->ac) AC @endif {{ $item->ac }}</span>
        </td>
        <td class="collapsing right aligned">
          <div class="ui mini icon basic buttons borderless">
            <button class="ui button" onclick="decrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="minus icon"></i>
            </button>
            <button class="ui disabled button">
              <span class="item-quantity">{{ $item->pivot->quantity }}</span>
            </button>
            <button class="ui button" onclick="incrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="plus icon"></i>
            </button>
          </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No armor in your inventory.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui header">Magic</h5>
    <table class="ui unstackable table">
      @forelse($player->items()->magic()->get() as $item)
      <tr>
        <td class=""><a href="{{ url('item', $item->id) }}" target="_blank">{{ $item->name }}</a></td>
        <td class="">
          <span class="text-muted">{{ $item->rarity }}</span>
        </td>
        <td class="collapsing right aligned">
          <div class="ui mini icon basic buttons borderless">
            <button class="ui button" onclick="decrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="minus icon"></i>
            </button>
            <button class="ui disabled button">
              <span class="item-quantity">{{ $item->pivot->quantity }}</span>
            </button>
            <button class="ui button" onclick="incrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="plus icon"></i>
            </button>
          </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No magic items in your inventory.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui header">Potion</h5>
    <table class="ui unstackable table">
      @forelse($player->items->where('type','Potion') as $item)
      <tr>
        <td><a href="{{ url('item', $item->id) }}" target="_blank">{{ $item->name }}</a></td>
        <td class="right aligned">
          <div class="ui mini icon basic buttons borderless">
            <button class="ui button" onclick="decrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="minus icon"></i>
            </button>
            <button class="ui disabled button">
              <span class="item-quantity">{{ $item->pivot->quantity }}</span>
            </button>
            <button class="ui button" onclick="incrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="plus icon"></i>
            </button>
          </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No potions in your inventory.</td>
        </tr>
      @endforelse
    </table>

    <h5 class="ui header">Miscellaneous</h5>
    <table class="ui unstackable table">
      @forelse($player->items()->misc()->get() as $item)
      <tr>
        <td><a href="{{ url('item', $item->id) }}" target="_blank">{{ $item->name }}</a></td>
        <td class="right aligned">
          <div class="ui mini icon basic buttons borderless">
            <button class="ui button" onclick="decrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="minus icon"></i>
            </button>
            <button class="ui disabled button">
              <span class="item-quantity">{{ $item->pivot->quantity }}</span>
            </button>
            <button class="ui button" onclick="incrementItemQuantity(this)" data-item="{{ $item->id }}">
              <i class="plus icon"></i>
            </button>
          </div>
        </td>
      </tr>
      @empty
        <tr>
          <td>No items in your inventory.</td>
        </tr>
      @endforelse
    </table>

  </div>


  <div class="ui tab" data-tab="notes">
    <div class="ui comments">
      @if(!$notes->isEmpty())
        @foreach($notes as $note)
          <div class="comment">
            <a class="avatar">
              <img src="{{ $note->user->avatar }}">
            </a>
            <div class="content">
              <a class="author" href="{{ url('profile', $note->user->id) }}">{{ $note->user->name }}</a>
              <div class="metadata">
                <span class="date">{{ $note->created_at->diffForHumans() }}</span>
              </div>
              <div class="text">
                {!! clean($note->body) !!}
              </div>
              <div class="actions">
                @can('delete', $note)
                  <a class="delete" href="{{ url('note/delete', $note->id) }}">Delete</a>
                @endcan
              </div>
            </div>
          </div>
        @endforeach
      @else
        <div class="ui message">
          <div class="ui small header">Private Notes</div>
          <p class="text-muted">These notes are only visible to you. These notes can contain secrets or DM notes about the character that only you will have access to.</p>
        </div>
      @endif
      @if(Auth::check())
        <form class="ui reply form" method="POST" action="{{ url('note/player', $player->id) }}">
          {{ csrf_field() }}
          <div class="field">
            <textarea name="body" rows="3" class="trumbowyg"></textarea>
          </div>
          <div class="clearfix">
            <button type="submit" class="ui primary labeled submit icon right floated tiny button">
              <i class="icon edit"></i> Add Note
            </button>
          </div>
        </form>
      @else
        <h4 class="ui header text-center">You must be <a href="{{ url('login') }}">logged in</a> to leave a note.</h4>
      @endif
    </div>
  </div>
</div>

<div class="ui five wide column">
  @if(Auth::check())
    <form action="{{ url('/character/longrest', $player->id) }}" method="POST" class="ui form">
      {{ csrf_field() }}
      <input type="hidden" name="player_id" value="{{ $player->id }}">
      <button class="ui fluid labeled icon button" type="submit">
        <i class="bed icon"></i>
        <span>Long Rest</span>
      </button>
    </form>
    <div class="ui hidden divider"></div>
    <div class="ui floating labeled icon dropdown fluid button" id="campaign-button">
      <i class="icon" data-icon="&#xe096;"></i>
      <span class="text">Add to Campaign</span>
      <div class="menu">
        @forelse(\Auth::user()->campaigns as $campaign)
          <form class="ui form" method="POST" action="{{ url('campaign/add/player', $player->id) }}">
            {{ csrf_field() }}
            <input type="hidden" name="campaign" value="{{ $campaign->id }}">
              <button class="item link-button" type="submit">

                 {{ $campaign->name }}

              </button>
          </form>
        @empty
          <a class="item" href="/campaign/create">
            No campaigns. Create one!
          </a>
        @endforelse
      </div>
    </div>
  @endif

  <div class="ui fluid vertical labeled icon basic buttons">
    <a class="ui button" id="share-modal-trigger">
      <i class="linkify icon"></i>
      Share Link
    </a>
    @can('update', $player)
    <a class="ui button" href="{{ url('character/'.$player->id.'/edit') }}">
      <i class="edit icon"></i>
      Edit
    </a>
    @endcan
    @can('delete', $player)
    <a class="ui button" id="delete-modal-trigger">
      <i class="remove icon"></i>
      Delete
    </a>
    @endcan
    @if(Auth::check())
      <a class="ui button" id="report-modal-trigger">
        <i class="warning icon"></i>
        Report Errors & Issues
      </a>
    @endif
  </div>

  @include('partials.files', ['data' => $player, 'type' => 'player'])

  <div class="ui feed">
    <div class="event">
      <div class="label">
        <img src="{{ $player->user->avatar }}">
      </div>
      <div class="content">
        <div class="date">
          {{ $player->created_at->diffForHumans() }}
        </div>
        <div class="summary">
           <a href="{{ url('profile', $player->user->id) }}">{{ $player->user->name }}</a> created this player.
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<div class="ui modal" id="upload-modal">
  <div class="header inline">Choose a file to upload!</div>
  <div class="content">
    <form action="{{ url('upload/player', $player->id) }}" method="POST" enctype="multipart/form-data" class="ui form">
    {{ csrf_field() }}
    <div class="text-center">
        <input type="file" id="file" name="file" placeholder="Choose a File">
        <label for="file" class="ui icon large button" >
            <i class="file icon"></i>
            Choose a File
        </label>

    </div>
    <p class="text-muted text-center">PDF or image files only. Max file size is 5MB.</p>
  </div>
  <div class="actions">
      <div class="ui cancel button">Cancel</div>
      <button type="submit" class="ui green approve button">
          <i class="icon check"></i>Submit
      </button>
    </form>
  </div>
</div>

<div class="ui modal" id="share-modal">
  <div class="header inline">Share Your Character</div>
  <div class="content">
    <p>This link will provide access to your character sheet so others can add it to a campaign. It does not allow them to edit your character.</p>
    <div id="share-markdown">
      <a href="{{ url('character', $player->id) }}/{{ $player->key }}">{{ url('character', $player->id) }}/{{ $player->key }}</a>
    </div>
  </div>
  <div class="actions">
    <div class="ui cancel button">Close</div>
    <div class="ui approve primary button copy-button" data-clipboard-action="copy" data-clipboard-target="#share-markdown">Copy to Clipboard</div>
  </div>
</div>

<div class="ui modal" id="coin-modal">
  <div class="header inline">Edit Coin Amounts</div>
  <div class="content">
    <form action="{{ url('/character/coins', $player->id) }}" method="POST" class="ui form">
    {{ csrf_field() }}
    <input type="hidden" name="player_id" value="{{ $player->id }}">
    <div class="five fields">
      <div class="field">
        <input type="text" name="PP" value="{{ old('PP', $player->PP) }}">
        <label>PP</label>
      </div>
      <div class="field">
        <input type="text" name="GP" value="{{ old('GP', $player->GP) }}">
        <label>GP</label>
      </div>
      <div class="field">
        <input type="text" name="EP" value="{{ old('EP', $player->EP) }}">
        <label>EP</label>
      </div>
      <div class="field">
        <input type="text" name="SP" value="{{ old('SP', $player->SP) }}">
        <label>SP</label>
      </div>
      <div class="field">
        <input type="text" name="CP" value="{{ old('SP', $player->SP) }}">
        <label>CP</label>
      </div>
    </div>
  </div>
  <div class="actions">
      <div class="ui cancel button">Cancel</div>
      <button type="submit" class="ui green approve button">
          <i class="icon check"></i>Submit
      </button>
    </form>
  </div>
</div>

<div class="ui modal" id="damage-modal">
  <div class="header inline">How much damage did you take?</div>
  <div class="content">
    <form action="{{ url('/character/damage', $player->id) }}" method="POST" class="ui form">
    {{ csrf_field() }}
    <input type="hidden" name="player_id" value="{{ $player->id }}">
    <div class="field">
      <input type="text" name="amount" value="{{ old('amount') }}">
      <label>Damage Taken</label>
    </div>
  </div>
  <div class="actions">
      <div class="ui cancel button">Cancel</div>
      <button type="submit" class="ui green approve button">
          <i class="icon check"></i>Submit
      </button>
    </form>
  </div>
</div>

<div class="ui modal" id="heal-modal">
  <div class="header inline">How much healing did you receive?</div>
  <div class="content">
    <form action="{{ url('/character/heal', $player->id) }}" method="POST" class="ui form">
    {{ csrf_field() }}
    <input type="hidden" name="player_id" value="{{ $player->id }}">

    <div class="field">
      <input type="text" name="amount" value="{{ old('amount') }}">
      <label>Healing Taken</label>
    </div>

  </div>
  <div class="actions">
      <div class="ui cancel button">Cancel</div>
      <button type="submit" class="ui green approve button">
          <i class="icon check"></i>Submit
      </button>
    </form>
  </div>
</div>

<div class="ui basic modal" id="delete-modal">
  <div class="header inline">Are you sure?</div>
  <div class="content">
    <p>Deleting the player will remove it permanently.</p>
  </div>
  <div class="actions">
    <form action="{{ url('character/'.$player->id) }}" method="POST">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
      <div class="ui cancel button">Cancel</div>
      <button type="submit" id="delete-player-{{ $player->id }}" class="ui red approve button">
          <i class="icon trash"></i>Delete
      </button>
    </form>
  </div>
</div>

<div class="ui modal" id="report-modal">
  <div class="header inline">What would you like to report?</div>
  <div class="content">
    <form action="{{ url('report/player/'.$player->id) }}" method="POST" class="ui form">
    {{ csrf_field() }}
    <div class="field">
      <textarea name="description"></textarea>
    </div>
  </div>
  <div class="actions">
      <div class="ui cancel button">Cancel</div>
      <button type="submit" class="ui green approve button">
          <i class="icon check"></i>Submit
      </button>
    </form>
  </div>
</div>

<div class="ui modal" id="death-modal">
  <div class="header inline">Uh oh, you are unconcious.</div>
  <div class="content">
  <h4 class="ui header">Death Saving Throws</h4>

  <p>Whenever you start your turn with 0 hit points, you must make a special saving throw, called a death saving throw, to determine whether you creep closer to death or hang onto life. Unlike other saving throws, this one isn’t tied to any ability score. You are in the hands of fate now, aided only by spells and features that improve your chances of succeeding on a saving throw.</p>

  <p>Roll a d20. If the roll is 10 or higher, you succeed. Otherwise, you fail. A success or failure has no effect by itself. On your third success, you become stable (see below). On your third failure, you die. The successes and failures don’t need to be consecutive; keep track of both until you collect three of a kind. The number of both is reset to zero when you regain any hit points or become stable.</p>

  <p>Rolling 1 or 20. When you make a death saving throw and roll a 1 on the d20, it counts as two failures. If you roll a 20 on the d20, you regain 1 hit point.</p>

  <p>Damage at 0 Hit Points. If you take any damage while you have 0 hit points, you suffer a death saving throw failure. If the damage is from a critical hit, you suffer two failures instead. If the damage equals or exceeds your hit point maximum, you suffer instant death.</p>
  </div>
  <div class="actions">
      <div class="ui cancel button">Close</div>
    </form>
  </div>
</div>

<script src="/js/jquery.address.js" type="text/javascript"></script>
<script>
  $('#player-health-bar').progress({
    label: 'ratio',
    text: {
      ratio: '{value} of {total} HP'
    }
  });

  function togglePrepared(elem) {
    //Pull ID & type of item from data-tag
    var spell = $(elem).data('spell');
    var player = {{ $player->id }};
    var baseUrl = document.location.origin;

    $(this).queue(function(){
        //POST an ajax request making sure to send CSRF Token
        $.ajax({
            url: baseUrl+'/character/prepare/'+player+'/'+spell,
            type: 'post',
            data: null,
            context: this,
            headers: {
              'X-CSRF-TOKEN': Laravel.csrfToken
            },
            success: function (response) {
                console.log(response);
            }
        });

        $(this).dequeue();
    });


  }

  function incrementSpellSlot(elem) {
    //Pull ID & type of item from data-tag
    var slot = $(elem).data('slot');
    var player = {{ $player->id }};
    var baseUrl = document.location.origin;
    var quantity = parseInt($(elem).parent().find('.slot-quantity').text());

    $(elem).html("<i class='asterisk loading icon'></i>");

    $(this).queue(function(){
        //POST an ajax request making sure to send CSRF Token
        $.ajax({
            url: baseUrl+'/character/cast/'+player+'/'+slot,
            type: 'post',
            data: null,
            context: this,
            headers: {
              'X-CSRF-TOKEN': Laravel.csrfToken
            },
            success: function (response) {
                if(response['status'] == 'success'){
                  quantity = quantity + 1;
                }

                $(elem).parent().find('.slot-quantity').text(quantity);
                $(elem).html("<i class='plus icon'></i>");

            }
        });

        $(this).dequeue();
    });

    $(this).children('a').toggleClass('red').transition('jiggle');
    $(this).children('div').toggleClass('red').transition('jiggle');
  }

  function decrementSpellSlot(elem) {
    //Pull ID & type of item from data-tag
    var slot = $(elem).data('slot');
    var player = {{ $player->id }};
    var baseUrl = document.location.origin;
    var quantity = parseInt($(elem).parent().find('.slot-quantity').text());

    $(elem).html("<i class='asterisk loading icon'></i>");

    $(this).queue(function(){
        //POST an ajax request making sure to send CSRF Token
        $.ajax({
            url: baseUrl+'/character/uncast/'+player+'/'+slot,
            type: 'post',
            data: null,
            context: this,
            headers: {
              'X-CSRF-TOKEN': Laravel.csrfToken
            },
            success: function (response) {
                if(response['status'] == 'success' && quantity > 0){
                  quantity = quantity - 1;
                }

                $(elem).parent().find('.slot-quantity').text(quantity);
                $(elem).html("<i class='minus icon'></i>");

            }
        });

        $(this).dequeue();
    });

    $(this).children('a').toggleClass('red').transition('jiggle');
    $(this).children('div').toggleClass('red').transition('jiggle');
  }

  function incrementItemQuantity(elem) {
    //Pull ID & type of item from data-tag
    var item = $(elem).data('item');
    var player = {{ $player->id }};
    var baseUrl = document.location.origin;
    var quantity = parseInt($(elem).parent().find('.item-quantity').text());

    $(elem).html("<i class='asterisk loading icon'></i>");

    $(this).queue(function(){
        //POST an ajax request making sure to send CSRF Token
        $.ajax({
            url: baseUrl+'/character/increment/'+player+'/'+item,
            type: 'post',
            data: null,
            context: this,
            headers: {
              'X-CSRF-TOKEN': Laravel.csrfToken
            },
            success: function (response) {
                if(response['status'] == 'success'){
                  quantity = quantity + 1;
                }

                $(elem).parent().find('.item-quantity').text(quantity);
                $(elem).html("<i class='plus icon'></i>");

            }
        });

        $(this).dequeue();
    });

    $(this).children('a').toggleClass('red').transition('jiggle');
    $(this).children('div').toggleClass('red').transition('jiggle');
  }

  function decrementItemQuantity(elem) {
    //Pull ID & type of item from data-tag
    var item = $(elem).data('item');
    var player = {{ $player->id }};
    var baseUrl = document.location.origin;
    var quantity = parseInt($(elem).parent().find('.item-quantity').text());

    $(elem).html("<i class='asterisk loading icon'></i>");

    $(this).queue(function(){
        //POST an ajax request making sure to send CSRF Token
        $.ajax({
            url: baseUrl+'/character/decrement/'+player+'/'+item,
            type: 'post',
            data: null,
            context: this,
            headers: {
              'X-CSRF-TOKEN': Laravel.csrfToken
            },
            success: function (response) {
              if(response['status'] == 'success'){
                quantity = quantity - 1;
                $(elem).parent().find('.item-quantity').text(quantity);
                $(elem).html("<i class='plus icon'></i>");
              }else if(response['status'] == 'deleted'){
                $(elem).parent().parent().parent().hide();
              }
            }
        });

        $(this).dequeue();
    });

    $(this).children('a').toggleClass('red').transition('jiggle');
    $(this).children('div').toggleClass('red').transition('jiggle');
  }

  function incrementHP(elem) {
    //Pull ID & type of item from data-tag

    var player = {{ $player->id }};
    var baseUrl = document.location.origin;
    var quantity = parseInt($(elem).parent().find('#HP-current').text());

    $(elem).html("<i class='asterisk loading icon'></i>");

    $(this).queue(function(){
        //POST an ajax request making sure to send CSRF Token
        $.ajax({
            url: baseUrl+'/character/increment/'+player+'/'+item,
            type: 'post',
            data: null,
            context: this,
            headers: {
              'X-CSRF-TOKEN': Laravel.csrfToken
            },
            success: function (response) {
                if(response['status'] == 'success'){
                  quantity = quantity + amount;
                }

                $(elem).parent().find('.item-quantity').text(quantity);
                $(elem).html("<i class='plus icon'></i>");

            }
        });

        $(this).dequeue();
    });

    $(this).children('a').toggleClass('red').transition('jiggle');
    $(this).children('div').toggleClass('red').transition('jiggle');
  }

  $('#delete-modal-trigger').click(function(){
      $('#delete-modal').modal('show');
  });

  $('#upload-modal-trigger').click(function(){
      $('#upload-modal').modal('show');
  });

  $('#coin-modal-trigger').click(function(){
      $('#coin-modal').modal('show');
  });

  $('#share-modal-trigger').click(function(){
      $('#share-modal').modal('show');
  });

  $('#damage-modal-trigger').click(function(){
      $('#damage-modal').modal('show');
  });

  $('#heal-modal-trigger').click(function(){
      $('#heal-modal').modal('show');
  });

  $('#death-modal-trigger').click(function(){
      $('#death-modal').modal('show');
  });

  $('#report-modal-trigger').click(function(){
      $('#report-modal').modal('show');
  });

  $(".like-button").click(like);

  $('.tabular.menu .item')
    .tab({
    history: true,
    historyType: 'hash'
  });

  $('.popup').popup({movePopup: false,lastResort:'top center'});

  var clipboard = new Clipboard('.copy-button');
  clipboard.on('success', function(e) {
      console.log(e);
  });
  clipboard.on('error', function(e) {
      console.log(e);
  });

</script>
@endsection
