<h2 class="ui small header">
  {{ ucfirst($npc->name) }}          
  <div class="ui left pointing right floated label">
    {{ $npc->profession or "No Profession" }}
  </div>
  <div class="sub header">
      {{ ucwords($npc->race) }} / {{ ucwords($npc->alignment) }}
  </div>
</h2>
<div class="description-box">

     {!! $npc->description ? preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', clean($npc->description)) :'<blockquote>You rolled a 1 on your Investigation roll. No description for this item.</blockquote>' !!}

  <div class="ui list">
    <div class="item">
      <div class="header inline">
        Ideal: 
      </div>
      {{ $npc->ideal or "None" }}
    </div>
    <div class="item">
      <div class="header inline">
        Bond: 
      </div>
      {{ $npc->bond or "None" }}
    </div>
    <div class="item">
      <div class="header inline">
        Flaw: 
      </div>
      {{ $npc->flaw or "None" }}
    </div>
  </div>

</div>

<div class="ui stats divider"></div>



<div class="ui list">
  <div class="item">
    <div class="header inline">AC</div>
    {{ $npc->AC }}
  </div>
  <div class="item">
    <div class="header inline">Hit Points </div>
    {{ $npc->HP }} ({{ $npc->hit_dice_amount }}d{{ $npc->hit_dice_size }} + {{ \Common::mod($npc->constitution) * $npc->hit_dice_amount }})
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
  @if($npc->languages)
    <div class="item">
      <div class="header inline">Languages</div>
        <div class="ui horizontal divided list">
          <?php $languages = explode(',',$npc->languages); ?>
          @foreach($languages as $language)
            <div class="item">{{ ucwords($language) }}</div>
          @endforeach
      </div>
    </div>
  @endif
  <div class="item">
    <div class="header inline">Skills</div>
    <div class="ui horizontal divided list">
      <?php $total = 0; ?>
      @foreach(\Config::get('constants.skills') as $key => $value)
        <?php $total += $npc->$key; ?>
        @if($npc->$key != 0)
          <div class="item">{{ $value." ".sprintf("%+d",$npc->$key) }}</div>
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
        <?php $total += $npc->$key; ?>
        @if($npc->$key != 0)
          <div class="item">{{ $value." ".sprintf("%+d",$npc->$key) }}</div>
        @endif
      @endforeach
      @if($total == 0)
        <div class="item">None</div>
      @endif
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

@if(!$npc->abilities->isEmpty())
  <div class="ui stats divider"></div>

  <div class="ui list">
    @foreach($npc->abilities as $ability)
      <div class="item margin-b">
        <div class="header inline">{{ $ability->name }}</div>
        {!! nl2br(e($ability->description)) !!}
      </div>
    @endforeach
  </div>
@endif

@if($npc->spell_ability != 'none' && $npc->spell_ability != null)
  <div class="ui stats divider"></div>

  <h4>Spellcasting</h4>

  <h5>Innate Spellcasting</h5>
 The creature’s spellcasting ability is {{ $npc->spell_ability }} (spell save DC {{ $npc->spell_save }}). The creature can innately cast the following spells:

  <div class="ui list">
    @if($npc->spells_at_will)
    <div class="item">
      <div class="header inline">At Will</div>
      <div class="ui horizontal divided list">
        @foreach(\Common::spellLinks($npc->spells_at_will) as $key => $value)
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
    @if($npc->spells_one)
    <div class="item">
      <div class="header inline">1/day each</div>
      <div class="ui horizontal divided list">
        @foreach(\Common::spellLinks($npc->spells_one) as $key => $value)
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
    @if($npc->spells_two)
    <div class="item">
      <div class="header inline">2/day each</div>
      <div class="ui horizontal divided list">
        @foreach(\Common::spellLinks($npc->spells_two) as $key => $value)
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
    @if($npc->spells_three)
    <div class="item">
      <div class="header inline">3/day each</div>
      <div class="ui horizontal divided list">
        @foreach(\Common::spellLinks($npc->spells_three) as $key => $value)
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

@if(!$npc->actions->isEmpty())
<div class="ui stats divider"></div>

<h4>Actions</h4>

<div class="ui list">
  @foreach($npc->actions as $action)
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
