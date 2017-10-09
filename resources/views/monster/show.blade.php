@extends('layouts.app')

@section('title', $monster->name)

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="ui eight wide column">
      <h2 class="ui header">
        {{ ucfirst($monster->name) }}
        <div class="ui left pointing right floated {{ Common::colorCR($monster->CR) }} label">
          CR {{ $monster->CR_fraction }}
        </div>
        <div class="sub header">
          {{ ucwords($monster->type) }}

        </div>
        <div class="sub header">
            {{ ucfirst($monster->size) }}
            {{ ($monster->alignment) ? ' / '.ucwords($monster->alignment):' / Unaligned' }}
        </div>
      </h2>
    </div>
    <div class="ui eight wide column">
      <div class="ui three mini statistics">
        <div class="statistic">
          <div class="value">
            {{ $monster->HP }}
          </div>
          <div class="label">
            HP
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            {{ $monster->AC }}
          </div>
          <div class="label">
            AC
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            {{ $monster->speed }} ft
          </div>
          <div class="label">
            Speed
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
        <i class="large icon" data-icon="&#xe016;"></i>
      </a>
      <a class="item" data-tab="notes">
        <i class="large icon" data-icon="&#xe130;"></i>
      </a>
      <div class="right menu">
        <a href="/monster" class="item"><i class="arrow circle outline left large icon"></i><span class="mobile hidden">Back to Monsters</span></a>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="ui eleven wide column">

  <div class="ui active tab" data-tab="stats">
    <div class="ui list">
      <div class="item">
        <div class="header inline">Description</div>

          {!! $monster->description ? preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', clean($monster->description)) :'<blockquote>You rolled a 1 on your Investigation roll. No description for this item.</blockquote>' !!}

      </div>
      <div class="ui stats divider"></div>
      <div class="item">
        <div class="header inline">Armor Class</div>
        {{ $monster->AC }}
      </div>
      <div class="item">
        <div class="header inline">Hit Points</div>
        {{ $monster->HP }}
        ({{ $monster->hit_dice_amount }}d{{ $monster->hit_dice_size }} {{ \Common::signNum(\Common::modUnsigned($monster->constitution) * $monster->hit_dice_amount) }})
      </div>
      <div class="item">
        <div class="header inline">Speed</div>
        <div class="ui horizontal divided list">
          @if($monster->speed != 0)
            <div class="item">Base {{ $monster->speed }} ft</div>
          @endif
          @if($monster->climb_speed != 0)
            <div class="item">Climb {{ $monster->climb_speed }} ft</div>
          @endif
          @if($monster->fly_speed != 0)
            <div class="item">Fly {{ $monster->fly_speed }} ft</div>
          @endif
          @if($monster->swim_speed != 0)
            <div class="item">Swim {{ $monster->swim_speed }} ft</div>
          @endif
          @if($monster->burrow_speed != 0)
            <div class="item">Burrow {{ $monster->burrow_speed }} ft</div>
          @endif
        </div>
      </div>
    </div>

    <div class="ui stats divider"></div>


    <div class="ui six mini statistics">
      <div class="statistic">
        <div class="value">
          {{ $monster->strength }}
          @if(\Common::mod($monster->strength) > 0)
            <span class="text-green superscript">{{ \Common::mod($monster->strength) }}</span>
          @elseif(\Common::mod($monster->strength) < 0)
            <span class="text-red superscript">{{ \Common::mod($monster->strength) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($monster->strength) }}</span>
          @endif
        </div>
        <div class="label">
          Str
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $monster->dexterity }}
          @if(\Common::mod($monster->dexterity) > 0)
            <span class="text-green superscript">{{ \Common::mod($monster->dexterity) }}</span>
          @elseif(\Common::mod($monster->dexterity) < 0)
            <span class="text-red superscript">{{ \Common::mod($monster->dexterity) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($monster->dexterity) }}</span>
          @endif
        </div>
        <div class="label">
          Dex
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $monster->constitution }}
          @if(\Common::mod($monster->constitution) > 0)
            <span class="text-green superscript">{{ \Common::mod($monster->constitution) }}</span>
          @elseif(\Common::mod($monster->constitution) < 0)
            <span class="text-red superscript">{{ \Common::mod($monster->constitution) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($monster->constitution) }}</span>
          @endif
        </div>
        <div class="label">
          Con
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $monster->intelligence }}
          @if(\Common::mod($monster->intelligence) > 0)
            <span class="text-green superscript">{{ \Common::mod($monster->intelligence) }}</span>
          @elseif(\Common::mod($monster->intelligence) < 0)
            <span class="text-red superscript">{{ \Common::mod($monster->intelligence) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($monster->intelligence) }}</span>
          @endif
        </div>
        <div class="label">
          Int
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $monster->wisdom }}
          @if(\Common::mod($monster->wisdom) > 0)
            <span class="text-green superscript">{{ \Common::mod($monster->wisdom) }}</span>
          @elseif(\Common::mod($monster->wisdom) < 0)
            <span class="text-red superscript">{{ \Common::mod($monster->wisdom) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($monster->wisdom) }}</span>
          @endif
        </div>
        <div class="label">
          Wis
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $monster->charisma }}
          @if(\Common::mod($monster->charisma) > 0)
            <span class="text-green superscript">{{ \Common::mod($monster->charisma) }}</span>
          @elseif(\Common::mod($monster->charisma) < 0)
            <span class="text-red superscript">{{ \Common::mod($monster->charisma) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($monster->charisma) }}</span>
          @endif
        </div>
        <div class="label">
          Cha
        </div>
      </div>
    </div>

    <div class="ui stats divider"></div>

    <div class="ui list" id="monster-skill-list">
      <div class="item">
        <div class="header inline">Saving Throws</div>
        <div class="ui horizontal divided list">
          @foreach(\GeneralHelper::getSavingThrows() as $key => $value)
            @if($monster->$key != 0)
              <div class="item">{{ $value." ".sprintf("%+d",$monster->$key) }}</div>
              @php $saving_throws = 1; @endphp
            @endif
          @endforeach
          @if(!isset($saving_throws)) <div class="item">None</div> @endif
        </div>
      </div>
      <div class="item">
        <div class="header inline">Skills</div>
        <div class="ui horizontal divided list">
          @foreach(\CreatureHelper::getSkills() as $key => $value)
            @if($monster->$key != 0)
              <div class="item">{{ $value." ".sprintf("%+d",$monster->$key) }}</div>
              @php $skills = 1; @endphp
            @endif
          @endforeach
          @if(!isset($skills)) <div class="item">None</div> @endif
        </div>
      </div>

      @if(($monster->darkvision != 0) && ($monster->truesight != 0) && ($monster->blindsight != 0) && ($monster->tremorsense != 0))
        <div class="item">
          <div class="header inline">Senses</div>
          <div class="ui horizontal divided list">
            @if($monster->darkvision != 0)
              <div class="item">Darkvision {{ $monster->darkvision }} ft</div>
            @endif
            @if($monster->truesight != 0)
              <div class="item">Truesight {{ $monster->truesight }} ft</div>
            @endif
            @if($monster->blindsight != 0)
              <div class="item">Blindsight {{ $monster->blindsight }} ft</div>
            @endif
            @if($monster->tremorsense != 0)
              <div class="item">Tremorsense {{ $monster->tremorsense }} ft</div>
            @endif
          </div>
        </div>
      @endif

      @if($monster->languages)
        <div class="item">
          <div class="header inline">Languages</div>
            <div class="ui horizontal divided list">
              <?php $languages = explode(',', $monster->languages);?>
              @foreach($languages as $language)
                <div class="item">{{ ucwords($language) }}</div>
              @endforeach
          </div>
        </div>
      @endif

      <div class="item">
        <div class="header inline">Challenge</div>
        {{ \Common::decimalToFraction($monster->CR) }}
      </div>

      <div class="item">
        <div class="header inline">Environment</div>
        {{ $monster->environment ? ucfirst($monster->environment):'None' }}
      </div>
    </div>

    @if(!empty($monster->damage_resistances) || !empty($monster->damage_immunities) || !empty($monster->damage_vulnerabilities) || !empty($monster->condition_immunities))
      <div class="ui stats divider"></div>

      <div class="ui list">
        @if($monster->damage_immunities)
          <div class="item">
            <div class="header inline">Damage Immunities</div>
            <div class="ui horizontal list">
              <div class="item">
                {{ ucwords($monster->damage_immunities) }}
              </div>
            </div>
          </div>
        @endif
        @if($monster->damage_vulnerabilities)
          <div class="item">
            <div class="header inline">Damage Vulnerabilities</div>
            <div class="ui horizontal list">
              <div class="item">
                {{ ucwords($monster->damage_vulnerabilities) }}
              </div>
            </div>
          </div>
        @endif
        @if($monster->damage_resistances)
          <div class="item">
            <div class="header inline">Damage Resistances</div>
            <div class="ui horizontal list">
              <div class="item">
                {{ ucwords($monster->damage_resistances) }}
              </div>
            </div>
          </div>
        @endif
        @if($monster->condition_immunities)
          <div class="item">
            <div class="header inline">Condition Immunities</div>
            <div class="ui horizontal list">
              <div class="item">
                {{ ucwords($monster->condition_immunities) }}
              </div>
            </div>
          </div>
        @endif
      </div>


    @endif

    @if(!$monster->abilities->isEmpty())
      <div class="ui stats divider"></div>

      <div class="ui list">
        @foreach($monster->abilities as $ability)
          <div class="item margin-b">
            <div class="header inline">{{ $ability->name }}</div>
            {!! clean($ability->description) !!}
          </div>
        @endforeach
      </div>
    @endif

    @if($monster->spell_ability != 'none' && $monster->spell_ability != null)
      <div class="ui stats divider"></div>

      <h4>Spellcasting</h4>

      <h5>Innate Spellcasting</h5>
     The creature’s spellcasting ability is {{ $monster->spell_ability }} (spell save DC {{ $monster->spell_save }}). The creature can innately cast the following spells:
    @endif

    <div class="ui list">

      @if(!$monster->spells->where('pivot.level', 'at_will')->isEmpty())
      <div class="item">
        <div class="header inline">At Will</div>
        <div class="ui horizontal divided list">
          @foreach($monster->spells->where('pivot.level', 'at_will') as $spell)
            <a href="{{ url('spell', $spell->id) }}" class="item">{{ $spell->name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      @if(!$monster->spells->where('pivot.level', 'one')->isEmpty())
      <div class="item">
        <div class="header inline">1/Day</div>
        <div class="ui horizontal divided list">
          @foreach($monster->spells->where('pivot.level', 'one') as $spell)
            <a href="{{ url('spell', $spell->id) }}" class="item">{{ $spell->name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      @if(!$monster->spells->where('pivot.level', 'two')->isEmpty())
      <div class="item">
        <div class="header inline">2/Day</div>
        <div class="ui horizontal divided list">
          @foreach($monster->spells->where('pivot.level', 'two') as $spell)
            <a href="{{ url('spell', $spell->id) }}" class="item">{{ $spell->name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      @if(!$monster->spells->where('pivot.level', 'three')->isEmpty())
      <div class="item">
        <div class="header inline">3/Day</div>
        <div class="ui horizontal divided list">
          @foreach($monster->spells->where('pivot.level', 'three') as $spell)
            <a href="{{ url('spell', $spell->id) }}" class="item">{{ $spell->name }}</a>
          @endforeach
        </div>
      </div>
      @endif
    </div>


    @if($monster->actions->contains('legendary', null))
    <div class="ui stats divider"></div>

    <h4>Actions</h4>

    <div class="ui list">
      @foreach($monster->actions as $action)
        @if($action->legendary == null)
          <div class="item margin-b">
            <div class="header inline">
              {{ $action->name }}

            </div>
            <div class="ui horizontal divided list text-muted">
              @if($action->attack_bonus)
                <div class="item">+{{ ucwords($action->attack_bonus) }} to hit</div>
              @endif
              @if($action->damage_dice)
                <div class="item">{{ ucwords($action->damage_dice) }} @if($action->damage_bonus) + {{ $action->damage_bonus }}@endif @if($action->damage_type){{ $action->damage_type }} @endif damage</div>
              @endif
              @if($action->range)
                <div class="item">{{ ucwords($action->range) }} range</div>
              @endif
            </div>

            {!! clean($action->description) !!}

          </div>
        @endif
      @endforeach
    </div>
    @endif

    @if($monster->actions->contains('legendary', '1'))
    <div class="ui stats divider"></div>

    <h4>Legendary Actions</h4>
    <i>The {{ $monster->name }} can take 3 legendary actions, choosing from the options below. Only one legendary action option can be used at a time and only at the end of another creature’s turn. The {{ $monster->name }} regains spent legendary actions at the start of its turn.</i>

    <div class="ui list">
      @foreach($monster->actions as $action)
        @if($action->legendary == 1)
          <div class="item margin-b">
            <div class="header inline">{{ $action->name }}</div>
            <div class="ui horizontal divided list text-muted">
              @if($action->attack_bonus)
                <div class="item">+{{ ucwords($action->attack_bonus) }} to hit</div>
              @endif
              @if($action->damage_dice)
                <div class="item">{{ ucwords($action->damage_dice) }} @if($action->damage_bonus) + {{ $action->damage_bonus }}@endif @if($action->damage_type){{ $action->damage_type }} @endif damage</div>
              @endif
              @if($action->range)
                <div class="item">{{ ucwords($action->range) }} range</div>
              @endif
            </div>
            {!! clean($action->description) !!}
          </div>
        @endif
      @endforeach
    </div>
    @endif

    @include('partials.comments', ['data' => $monster->comments, 'type' => 'monster', 'id' => $monster->id])
  </div>


  <div class="ui tab" data-tab="notes">
    @include('partials.notes', ['data' => $notes, 'type' => 'monster', 'id' => $monster->id])
  </div>
</div>
<div class="ui five wide column">
  @if (Auth::check())
    <like-button id="{{ $monster->id }}" type="monster" :liked="{{ $monster->likes->contains('user_id', \Auth::id()) ? 'true':'false' }}" count="{{ $monster->like_count }}"></like-button>

     @include('partials.campaignbutton', ['type' => 'monster', 'id' => $monster->id])
  @else
    <div class="ui labeled fluid disabled button" id="like-button">
      <div class="ui fluid button">
        <i class="heart icon"></i> Like
      </div>
      <a class="ui basic left pointing label">
        {{ $monster->like_count }}
      </a>
    </div>
  @endif

  <div class="ui fluid vertical labeled icon basic buttons">
      @can('update', $monster)
      <a class="ui button" href="{{ url('monster/'.$monster->id.'/edit') }}">
        <i class="edit icon"></i>
        Edit
      </a>
      @endcan
      @can('delete', $monster)
      <a class="ui button" onclick="$('#delete-modal').modal('show')">
        <i class="remove icon"></i>
        Delete
      </a>
      @include('partials.delete', ['type' => 'monster', 'id' => $monster->id])
      @endcan
    <a class="ui button" href="{{ url('monster/fork/'.$monster->id) }}">
      <i class="fork icon"></i>
      Use as Template
    </a>
    <a class="ui button" onclick="$('#reddit-modal').modal('show');">
      <i class="reddit icon"></i>
      Reddit Markdown
    </a>
    @include('partials.reddit', ['markdown' => CreatureHelper::getMonsterRedditMarkdown($monster)])
    <a class="ui button" onclick="$('#homebrewery-modal').modal('show');">
      <i class="icon" data-icon="&#xe255;"></i>
      Homebrewery Markdown
    </a>
    @include('partials.homebrewery', ['markdown' => CreatureHelper::getMonsterHomebreweryMarkdown($monster)])
    @if($monster->private == 1)
      <a class="ui button" onclick="$('#share-modal').modal('show');">
        <i class="linkify icon"></i>
        Share Link
      </a>
      @include('partials.share', ['data' => $monster, 'type' => 'monster'])
    @endif
    @if(Auth::check())
      <a class="ui button" onclick="$('#report-modal').modal('show');">
        <i class="warning icon"></i>
        Report Errors & Issues
      </a>
      @include('partials.report', ['type' => 'monster', 'id' => $monster->id])
    @endif
  </div>

  @include('partials.files', ['data' => $monster, 'type' => 'monster'])

  @include('partials.fork', ['data' => $monster, 'type' => 'monster'])
</div>

@endsection

@section('scripts')

<script src="/js/jquery.address.js" type="text/javascript"></script>
<script>

  $('.tabular.menu .item')
    .tab({
    history: true,
    historyType: 'hash'
  });

  var clipboard = new Clipboard('.copy-button');

</script>
@endsection
