<h2 class="ui small header">
  {{ ucfirst($monster->name) }}          
  <div class="ui left pointing right floated {{ Common::colorCR($monster->CR) }} label">
    CR {{ \Common::decimalToFraction($monster->CR) }}
  </div>
  <div class="sub header">
    {{ ucfirst($monster->type) }}

    @for($i = 1; $i < 6; $i++)
      <?php $type = "subtype".$i ?>
      @if($monster->$type)
        / {{ ucfirst($monster->$type) }}
      @endif            
    @endfor
  </div>
  <div class="sub header">
      {{ ucfirst($monster->size) }}
      {{ ($monster->alignment) ? ' / '.ucwords($monster->alignment):' / Unaligned' }}
  </div>
</h2>



<div class="description-box">
          {!! $monster->description ? preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', clean($monster->description)) :'<blockquote>You rolled a 1 on your Investigation roll. No description for this item.</blockquote>' !!}
</div>

<div class="ui list">
  <div class="item">
    <div class="header inline">Armor Class</div>
    {{ $monster->AC }}
  </div>
  <div class="item">
    <div class="header inline">Hit Points</div>
    {{ $monster->HP }}
    ({{ $monster->hit_dice_amount }}d{{ $monster->hit_dice_size }} + {{ \Common::mod($monster->constitution) * $monster->hit_dice_amount }})
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

<div class="ui list">
  <div class="item">
    <div class="header inline">Skills</div>
    <div class="ui horizontal divided list">
      <?php $total = 0; ?>
      @foreach(\Config::get('constants.skills') as $key => $value)
        <?php $total += $monster->$key; ?>
        @if($monster->$key != 0)
          <div class="item">{{ $value." ".sprintf("%+d",$monster->$key) }}</div>
        @endif
      @endforeach
      @if($total == 0)
        <div class="item">None</div>
      @endif
    </div>
  </div>
  <div class="item">
    <div class="header inline">Saving Throws</div>
    <div class="ui horizontal divided list">
      <?php $total = 0; ?>
      @foreach(\Config::get('constants.saving_throws') as $key => $value)
        <?php $total += $monster->$key; ?>
        @if($monster->$key != 0)
          <div class="item">{{ $value." ".sprintf("%+d",$monster->$key) }}</div>
        @endif
      @endforeach
      @if($total == 0)
        <div class="item">None</div>
      @endif
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
          <?php $languages = explode(',',$monster->languages); ?>
          @foreach($languages as $language)
            <div class="item">{{ ucwords($language) }}</div>
          @endforeach
      </div>
    </div>
  @endif
  <div class="item">
    <div class="header inline">Challenge</div>
    {{ $monster->CR }}
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

@if(!$monster->abilities->isEmpty())
  <div class="ui stats divider"></div>

  <div class="ui list">
    @foreach($monster->abilities as $ability)
      <div class="item margin-b">
        <div class="header inline">{{ $ability->name }}</div>
        {!! nl2br(e($ability->description)) !!}
      </div>
    @endforeach
  </div>
@endif

@if($monster->spell_ability != 'none' && $monster->spell_ability != null)
  <div class="ui stats divider"></div>

  <h4>Spellcasting</h4>

  <h5>Innate Spellcasting</h5>
 The creature’s spellcasting ability is {{ $monster->spell_ability }} (spell save DC {{ $monster->spell_save }}). The creature can innately cast the following spells:

  <div class="ui list">
    @if($monster->spells_at_will)
    <div class="item">
      <div class="header inline">At Will</div>
      <div class="ui horizontal divided list">
        @foreach(\Common::spellLinks($monster->spells_at_will) as $key => $value)
          @if($value != null)
          <a class="item" onclick="$('#{{ str_replace(array('(', ')', ' ', '\''),'',$key) }}').modal('show');">{{ ucwords($key) }}</a>
          <div class="ui modal" id="{{ str_replace(array('(', ')', ' ', '\''),'',$key) }}">
            <i class="close icon"></i>
            <div class="header inline">
              {{ ucwords($key) }}
            </div>
            <div class="content">
                {!! $value !!}
            </div>
            <div class="actions">
              <div class="ui black deny button">
                Close
              </div>
            </div>
          </div>
          @else
            <div class="item">{{ ucwords($key) }}</div>
          @endif
        @endforeach
      </div>
    </div>
    @endif
    @if($monster->spells_one)
    <div class="item">
      <div class="header inline">1/day each</div>
      <div class="ui horizontal divided list">
        @foreach(\Common::spellLinks($monster->spells_one) as $key => $value)
          @if($value != null)
          <a class="item" onclick="$('#{{ str_replace(array('(', ')', ' ', '\''),'',$key) }}').modal('show');">{{ ucwords($key) }}</a>
          <div class="ui modal" id="{{ str_replace(array('(', ')', ' ', '\''),'',$key) }}">
            <i class="close icon"></i>
            <div class="header inline">
              {{ ucwords($key) }}
            </div>
            <div class="content">
                {!! $value !!}
            </div>
            <div class="actions">
              <div class="ui black deny button">
                Close
              </div>
            </div>
          </div>
          @else
            <div class="item">{{ ucwords($key) }}</div>
          @endif
        @endforeach
      </div>
    </div>
    @endif
    @if($monster->spells_two)
    <div class="item">
      <div class="header inline">2/day each</div>
      <div class="ui horizontal divided list">
        @foreach(\Common::spellLinks($monster->spells_two) as $key => $value)
          @if($value != null)
          <a class="item" onclick="$('#{{ str_replace(array('(', ')', ' ', '\''),'',$key) }}').modal('show');">{{ ucwords($key) }}</a>
          <div class="ui modal" id="{{ str_replace(array('(', ')', ' ', '\''),'',$key) }}">
            <i class="close icon"></i>
            <div class="header inline">
              {{ ucwords($key) }}
            </div>
            <div class="content">
                {!! $value !!}
            </div>
            <div class="actions">
              <div class="ui black deny button">
                Close
              </div>
            </div>
          </div>
          @else
            <div class="item">{{ ucwords($key) }}</div>
          @endif
        @endforeach
      </div>
    </div>
    @endif
    @if($monster->spells_three)
    <div class="item">
      <div class="header inline">3/day each</div>
      <div class="ui horizontal divided list">
        @foreach(\Common::spellLinks($monster->spells_three) as $key => $value)
          @if($value != null)
          <a class="item" onclick="$('#{{ str_replace(array('(', ')', ' ', '\''),'',$key) }}').modal('show');">{{ ucwords($key) }}</a>
          <div class="ui modal" id="{{ str_replace(array('(', ')', ' ', '\''),'',$key) }}">
            <i class="close icon"></i>
            <div class="header inline">
              {{ ucwords($key) }}
            </div>
            <div class="content">
                {!! $value !!}
            </div>
            <div class="actions">
              <div class="ui black deny button">
                Close
              </div>
            </div>
          </div>
          @else
            <div class="item">{{ ucwords($key) }}</div>
          @endif
        @endforeach
      </div>
    </div>
    @endif
  </div>
@endif

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

        {!! nl2br(e($action->description)) !!}
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
        {!! nl2br(e($action->description)) !!}
      </div>
    @endif
  @endforeach
</div>
@endif
