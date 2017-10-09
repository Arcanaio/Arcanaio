@extends('layouts.app')

@section('title', $npc->name)

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="ui eight wide column">
      <h2 class="ui header">
        {{ ucfirst($npc->name) }}
        <div class="ui left pointing right floated {{ Common::colorCR($npc->CR) }} label">
          CR {{ $npc->CR_fraction }}
        </div>
        <div class="sub header">
          {{ ucwords($npc->profession) }}

        </div>
        <div class="sub header">
            {{ $npc->race ? ucfirst($npc->race):'None' }}
            {{ ($npc->alignment) ? ' / '.ucwords($npc->alignment):' / Unaligned' }}
        </div>
      </h2>
    </div>
    <div class="ui eight wide column">
      <div class="ui three mini statistics">
        <div class="statistic">
          <div class="value">
            {{ $npc->HP }}
          </div>
          <div class="label">
            HP
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            {{ $npc->AC }}
          </div>
          <div class="label">
            AC
          </div>
        </div>
        <div class="statistic">
          <div class="value">
            {{ $npc->speed }} ft
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
        <a href="/npc" class="item"><i class="arrow circle outline left large icon"></i><span class="mobile hidden">Back to Npcs</span></a>
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

     {!! $npc->description ? preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', clean($npc->description)) :'<blockquote>You rolled a 1 on your Investigation roll. No description for this item.</blockquote>' !!}

      </div>
      <div class="ui stats divider"></div>
        <div class="item">
          <div class="header inline">Ideal</div>
          {{ $npc->ideal or "None" }}
        </div>
        <div class="item">
          <div class="header inline">Bond</div>
          {{ $npc->bond or "None" }}
        </div>
        <div class="item">
          <div class="header inline">Flaw</div>
          {{ $npc->flaw or "None" }}
        </div>
      <div class="ui stats divider"></div>
      <div class="item">
        <div class="header inline">Armor Class</div>
        {{ $npc->AC }}
      </div>
      <div class="item">
        <div class="header inline">Hit Points</div>
        {{ $npc->HP }}
        ({{ $npc->hit_dice_amount }}d{{ $npc->hit_dice_size }} {{ \Common::signNum(\Common::modUnsigned($npc->constitution) * $npc->hit_dice_amount) }})
      </div>
      <div class="item">
        <div class="header inline">Speed</div>
        <div class="ui horizontal divided list">
          @if($npc->speed != 0)
            <div class="item">Base {{ $npc->speed }} ft</div>
          @endif
          @if($npc->climb_speed != 0)
            <div class="item">Climb {{ $npc->climb_speed }} ft</div>
          @endif
          @if($npc->fly_speed != 0)
            <div class="item">Fly {{ $npc->fly_speed }} ft</div>
          @endif
          @if($npc->swim_speed != 0)
            <div class="item">Swim {{ $npc->swim_speed }} ft</div>
          @endif
          @if($npc->burrow_speed != 0)
            <div class="item">Burrow {{ $npc->burrow_speed }} ft</div>
          @endif
        </div>
      </div>
    </div>

    <div class="ui stats divider"></div>


    <div class="ui six mini statistics">
      <div class="statistic">
        <div class="value">
          {{ $npc->strength }}
          @if(\Common::mod($npc->strength) > 0)
            <span class="text-green superscript">{{ \Common::mod($npc->strength) }}</span>
          @elseif(\Common::mod($npc->strength) < 0)
            <span class="text-red superscript">{{ \Common::mod($npc->strength) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($npc->strength) }}</span>
          @endif
        </div>
        <div class="label">
          Str
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $npc->dexterity }}
          @if(\Common::mod($npc->dexterity) > 0)
            <span class="text-green superscript">{{ \Common::mod($npc->dexterity) }}</span>
          @elseif(\Common::mod($npc->dexterity) < 0)
            <span class="text-red superscript">{{ \Common::mod($npc->dexterity) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($npc->dexterity) }}</span>
          @endif
        </div>
        <div class="label">
          Dex
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $npc->constitution }}
          @if(\Common::mod($npc->constitution) > 0)
            <span class="text-green superscript">{{ \Common::mod($npc->constitution) }}</span>
          @elseif(\Common::mod($npc->constitution) < 0)
            <span class="text-red superscript">{{ \Common::mod($npc->constitution) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($npc->constitution) }}</span>
          @endif
        </div>
        <div class="label">
          Con
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $npc->intelligence }}
          @if(\Common::mod($npc->intelligence) > 0)
            <span class="text-green superscript">{{ \Common::mod($npc->intelligence) }}</span>
          @elseif(\Common::mod($npc->intelligence) < 0)
            <span class="text-red superscript">{{ \Common::mod($npc->intelligence) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($npc->intelligence) }}</span>
          @endif
        </div>
        <div class="label">
          Int
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $npc->wisdom }}
          @if(\Common::mod($npc->wisdom) > 0)
            <span class="text-green superscript">{{ \Common::mod($npc->wisdom) }}</span>
          @elseif(\Common::mod($npc->wisdom) < 0)
            <span class="text-red superscript">{{ \Common::mod($npc->wisdom) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($npc->wisdom) }}</span>
          @endif
        </div>
        <div class="label">
          Wis
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          {{ $npc->charisma }}
          @if(\Common::mod($npc->charisma) > 0)
            <span class="text-green superscript">{{ \Common::mod($npc->charisma) }}</span>
          @elseif(\Common::mod($npc->charisma) < 0)
            <span class="text-red superscript">{{ \Common::mod($npc->charisma) }}</span>
          @else
            <span class="superscript">{{ \Common::mod($npc->charisma) }}</span>
          @endif
        </div>
        <div class="label">
          Cha
        </div>
      </div>
    </div>

    <div class="ui stats divider"></div>

    <div class="ui list" id="npc-skill-list">
      <div class="item">
        <div class="header inline">Saving Throws</div>
        <div class="ui horizontal divided list">
          @foreach(\GeneralHelper::getSavingThrows() as $key => $value)
            @if($npc->$key != 0)
              <div class="item">{{ $value." ".sprintf("%+d",$npc->$key) }}</div>
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
            @if($npc->$key != 0)
              <div class="item">{{ $value." ".sprintf("%+d",$npc->$key) }}</div>
              @php $skills = 1; @endphp
            @endif
          @endforeach
          @if(!isset($skills)) <div class="item">None</div> @endif
        </div>
      </div>

      @if(($npc->darkvision != 0) && ($npc->truesight != 0) && ($npc->blindsight != 0) && ($npc->tremorsense != 0))
        <div class="item">
          <div class="header inline">Senses</div>
          <div class="ui horizontal divided list">
            @if($npc->darkvision != 0)
              <div class="item">Darkvision {{ $npc->darkvision }} ft</div>
            @endif
            @if($npc->truesight != 0)
              <div class="item">Truesight {{ $npc->truesight }} ft</div>
            @endif
            @if($npc->blindsight != 0)
              <div class="item">Blindsight {{ $npc->blindsight }} ft</div>
            @endif
            @if($npc->tremorsense != 0)
              <div class="item">Tremorsense {{ $npc->tremorsense }} ft</div>
            @endif
          </div>
        </div>
      @endif

      @if($npc->languages)
        <div class="item">
          <div class="header inline">Languages</div>
            <div class="ui horizontal divided list">
              <?php $languages = explode(',', $npc->languages);?>
              @foreach($languages as $language)
                <div class="item">{{ ucwords($language) }}</div>
              @endforeach
          </div>
        </div>
      @endif

      <div class="item">
        <div class="header inline">Challenge</div>
        {{ \Common::decimalToFraction($npc->CR) }}
      </div>

      <div class="item">
        <div class="header inline">Environment</div>
        {{ $npc->environment ? ucfirst($npc->environment):'None' }}
      </div>
    </div>

    @if(!empty($npc->damage_resistances) || !empty($npc->damage_immunities) || !empty($npc->damage_vulnerabilities) || !empty($npc->condition_immunities))
      <div class="ui stats divider"></div>

      <div class="ui list">
        @if($npc->damage_immunities)
          <div class="item">
            <div class="header inline">Damage Immunities</div>
            <div class="ui horizontal list">
              <div class="item">
                {{ ucwords($npc->damage_immunities) }}
              </div>
            </div>
          </div>
        @endif
        @if($npc->damage_vulnerabilities)
          <div class="item">
            <div class="header inline">Damage Vulnerabilities</div>
            <div class="ui horizontal list">
              <div class="item">
                {{ ucwords($npc->damage_vulnerabilities) }}
              </div>
            </div>
          </div>
        @endif
        @if($npc->damage_resistances)
          <div class="item">
            <div class="header inline">Damage Resistances</div>
            <div class="ui horizontal list">
              <div class="item">
                {{ ucwords($npc->damage_resistances) }}
              </div>
            </div>
          </div>
        @endif
        @if($npc->condition_immunities)
          <div class="item">
            <div class="header inline">Condition Immunities</div>
            <div class="ui horizontal list">
              <div class="item">
                {{ ucwords($npc->condition_immunities) }}
              </div>
            </div>
          </div>
        @endif
      </div>


    @endif

    @if(!$npc->abilities->isEmpty())
      <div class="ui stats divider"></div>

      <div class="ui list">
        @foreach($npc->abilities as $ability)
          <div class="item margin-b">
            <div class="header inline">{{ $ability->name }}</div>
            {!! clean($ability->description) !!}
          </div>
        @endforeach
      </div>
    @endif

    @if($npc->spell_ability != 'none' && $npc->spell_ability != null)
      <div class="ui stats divider"></div>

      <h4>Spellcasting</h4>

      <h5>Innate Spellcasting</h5>
     The creature’s spellcasting ability is {{ $npc->spell_ability }} (spell save DC {{ $npc->spell_save }}). The creature can innately cast the following spells:
    @endif

    <div class="ui list">

      @if(!$npc->spells->where('pivot.level', 'at_will')->isEmpty())
      <div class="item">
        <div class="header inline">At Will</div>
        <div class="ui horizontal divided list">
          @foreach($npc->spells->where('pivot.level', 'at_will') as $spell)
            <a href="{{ url('spell', $spell->id) }}" class="item">{{ $spell->name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      @if(!$npc->spells->where('pivot.level', 'one')->isEmpty())
      <div class="item">
        <div class="header inline">1/Day</div>
        <div class="ui horizontal divided list">
          @foreach($npc->spells->where('pivot.level', 'one') as $spell)
            <a href="{{ url('spell', $spell->id) }}" class="item">{{ $spell->name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      @if(!$npc->spells->where('pivot.level', 'two')->isEmpty())
      <div class="item">
        <div class="header inline">2/Day</div>
        <div class="ui horizontal divided list">
          @foreach($npc->spells->where('pivot.level', 'two') as $spell)
            <a href="{{ url('spell', $spell->id) }}" class="item">{{ $spell->name }}</a>
          @endforeach
        </div>
      </div>
      @endif

      @if(!$npc->spells->where('pivot.level', 'three')->isEmpty())
      <div class="item">
        <div class="header inline">3/Day</div>
        <div class="ui horizontal divided list">
          @foreach($npc->spells->where('pivot.level', 'three') as $spell)
            <a href="{{ url('spell', $spell->id) }}" class="item">{{ $spell->name }}</a>
          @endforeach
        </div>
      </div>
      @endif
    </div>


    @if($npc->actions->contains('legendary', null))
    <div class="ui stats divider"></div>

    <h4>Actions</h4>

    <div class="ui list">
      @foreach($npc->actions as $action)
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

    @if($npc->actions->contains('legendary', '1'))
    <div class="ui stats divider"></div>

    <h4>Legendary Actions</h4>
    <i>The {{ $npc->name }} can take 3 legendary actions, choosing from the options below. Only one legendary action option can be used at a time and only at the end of another creature’s turn. The {{ $npc->name }} regains spent legendary actions at the start of its turn.</i>

    <div class="ui list">
      @foreach($npc->actions as $action)
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

    @include('partials.comments', ['data' => $npc->comments, 'type' => 'npc', 'id' => $npc->id])
  </div>


  <div class="ui tab" data-tab="notes">
    @include('partials.notes', ['data' => $notes, 'type' => 'npc', 'id' => $npc->id])
  </div>
</div>
<div class="ui five wide column">
  @if (Auth::check())
    <like-button id="{{ $npc->id }}" type="npc" :liked="{{ $npc->likes->contains('user_id', \Auth::id()) ? 'true':'false' }}" count="{{ $npc->like_count }}"></like-button>

     @include('partials.campaignbutton', ['type' => 'npc', 'id' => $npc->id])
  @else
    <div class="ui labeled fluid disabled button" id="like-button">
      <div class="ui fluid button">
        <i class="heart icon"></i> Like
      </div>
      <a class="ui basic left pointing label">
        {{ $npc->like_count }}
      </a>
    </div>
  @endif

  <div class="ui fluid vertical labeled icon basic buttons">
      @can('update', $npc)
      <a class="ui button" href="{{ url('npc/'.$npc->id.'/edit') }}">
        <i class="edit icon"></i>
        Edit
      </a>
      @endcan
      @can('delete', $npc)
      <a class="ui button" onclick="$('#delete-modal').modal('show')">
        <i class="remove icon"></i>
        Delete
      </a>
      @include('partials.delete', ['type' => 'npc', 'id' => $npc->id])
      @endcan
    <a class="ui button" href="{{ url('npc/fork/'.$npc->id) }}">
      <i class="fork icon"></i>
      Use as Template
    </a>
    <a class="ui button" onclick="$('#reddit-modal').modal('show');">
      <i class="reddit icon"></i>
      Reddit Markdown
    </a>
    @include('partials.reddit', ['markdown' => CreatureHelper::getNpcRedditMarkdown($npc)])
    <a class="ui button" onclick="$('#homebrewery-modal').modal('show');">
      <i class="icon" data-icon="&#xe255;"></i>
      Homebrewery Markdown
    </a>
    @include('partials.homebrewery', ['markdown' => CreatureHelper::getNpcHomebreweryMarkdown($npc)])
    @if($npc->private == 1)
      <a class="ui button" onclick="$('#share-modal').modal('show');">
        <i class="linkify icon"></i>
        Share Link
      </a>
      @include('partials.share', ['data' => $npc, 'type' => 'npc'])
    @endif
    @if(Auth::check())
      <a class="ui button" onclick="$('#report-modal').modal('show');">
        <i class="warning icon"></i>
        Report Errors & Issues
      </a>
      @include('partials.report', ['type' => 'npc', 'id' => $npc->id])
    @endif
  </div>

  @include('partials.files', ['data' => $npc, 'type' => 'npc'])

  @include('partials.fork', ['data' => $npc, 'type' => 'npc'])
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
