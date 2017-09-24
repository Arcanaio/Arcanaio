    <div class="ui inverted vertical menu sidebar">
      <a class="item text-center" href="/@if (Auth::check())dashboard @endif">
        <img src="/img/logo_light.svg" alt="" class="ui mini centered image"> <h5 id="logo-heading">Arcana.io</h5>
      </a>
      <div class="item">
        <div class="menu">
          @if (Auth::check())
            <a href="/profile" class="item"><i class="user icon"></i> Profile</a>
            <a href="/settings" class="item"><i class="settings icon"></i> Settings</a>
            <a href="/logout" class="item"><i class="sign out icon"></i> Sign Out</a>
          @else
            <a href="/register" class="item" >Create Account</a>
            <a href="/login" class="item">Sign In</a>
          @endif
        </div>
      </div>


      <div class="item">
        GM & Player Tools
        <div class="menu">
          <a href="/combat" class="item"><i class="large icon" data-icon="&#xe088;"></i> Combat Tracker</a>
          <a href="/character" class="item"><i class="large icon" data-icon="&#xe0e3;"></i> Character Sheets</a>
          <a href="/campaign" class="item"><i class="large icon" data-icon="&#xe096;"></i>Campaigns</a>
          <a href="/rule" class="item"><i class="large book icon"></i> Rules Compendium</a>
        </div>
      </div>


      <div class="item">
        Database
        <div class="menu">
          <a href="/monster" class="item"><i class="large icon" data-icon="&#xe016;"></i> Monsters</a>
          <a href="/item" class="item"><i class="large icon" data-icon="&#xe128;"></i> Items</a>
          <a href="/spell" class="item"><i class="large icon" data-icon="&#xe0c9;"></i> Spells</a>
          <a href="/npc" class="item"><i class="large icon" data-icon="&#xe2ee;"></i> NPCs</a>
          <a href="/location" class="item"><i class="large icon" data-icon="&#xe112;"></i> Locations</a>
        </div>
      </div>
      <div class="item">
        Arcana
        <div class="menu">
          <a href="https://arcanegoods.com" class="item"><i class="large cart icon"></i> Arcane Goods</a>
          <a href="https://blog.arcana.io" class="item"><i class="large icon" data-icon="&#xe196;"></i> Blog</a>
          <a href="http://reddit.com/r/arcana_io" class="item"><i class="large reddit icon"></i> /r/arcana_io</a>
          <a href="http://twitter.com/arcana_io" class="item"><i class="large twitter icon"></i> @arcana_io</a>
          <a href="https://www.patreon.com/arcanaio" class="item"><i class="large dollar icon"></i> Support on Patreon</a>
          <a href="https://github.com/Arcanaio/Arcanaio" class="item"><i class="large github icon"></i> Developers</a>
        </div>
      </div>
      {{--
      <div class="item">
        Campaign
        <div class="menu">
          <a href="/loot" class="item"><i class="large icon" data-icon="&#xe1d6;"></i> Journal</a>
          <a href="/loot" class="item"><i class="large icon" data-icon="&#xe130;"></i> To Do</a>
          <a href="/loot" class="item"><i class="large icon" data-icon="&#xe081;"></i> Players</a>
          <a href="/blueprint" class="item"><i class="large icon" data-icon="&#xe112;"></i> Locations</a>
          <a href="/blueprint" class="item"><i class="large icon" data-icon="&#xe096;"></i> Organizations</a>
          <a href="/blueprint" class="item"><i class="large icon" data-icon="&#xe120;"></i> NPCs</a>
          <a href="/blueprint" class="item"><i class="large icon" data-icon="&#xe033;"></i> Encounters</a>
          <a href="/blueprint" class="item"><i class="large icon" data-icon="&#xe0fb;"></i> Key Items</a>
        </div>
      </div>
      <div class="item">
        Tools
        <div class="menu">
          <a href="/loot" class="item"><i class="large icon" data-icon="&#xe211;"></i> Loot Stashes</a>
          <a href="/blueprint" class="item"><i class="large icon" data-icon="&#xe16b;"></i> Blueprints</a>
          <a href="/blueprint" class="item"><i class="large icon" data-icon="&#xe088;"></i> Initiative</a>
        </div>
      </div>
      --}}
    </div>