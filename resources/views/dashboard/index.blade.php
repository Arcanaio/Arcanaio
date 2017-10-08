@extends('layouts.app')

@section('title', 'Dashboard')

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="row">
      <div class="ui eight wide column">
        @include('dashboard.menu')
      </div>

      <div class="ui eight wide column">
         <div class="ui grid">
          <div class="ui sixteen wide mobile only column">
            <div class="ui labeled icon dropdown primary fluid button">
              <i class="down caret icon"></i>
              <span class="text">Create</span>
              <div class="menu">
                <a class="item" href="/campaign/create">
                  <i class="large icon" data-icon="&#xe096;"></i>
                  Campaign
                </a>
                <a class="item" href="/monster/create">
                  <i class="large icon" data-icon="&#xe016;"></i>
                  Monster
                </a>
                <a class="item" href="/item/create">
                  <i class="large icon" data-icon="&#xe128;"></i>
                  Item
                </a>
                <a class="item" href="/spell/create">
                  <i class="large icon" data-icon="&#xe0c9;"></i>
                  Spell
                </a>
                <a class="item" href="/npc/create">
                  <i class="large icon" data-icon="&#xe2ee;"></i>
                  NPC
                </a>
                <a class="item" href="/location/create">
                  <i class="large icon" data-icon="&#xe112;"></i>
                  Location
                </a>
              </div>
            </div>
          </div>
          <div class="ui right floated ten wide mobile hidden column">
            <div class="ui labeled icon dropdown primary fluid button">
              <i class="down caret icon"></i>
              <span class="text">Create</span>
              <div class="menu">
                <a class="item" href="/campaign/create">
                  <i class="large icon" data-icon="&#xe096;"></i>
                  Campaign
                </a>
                <a class="item" href="/monster/create">
                  <i class="large icon" data-icon="&#xe016;"></i>
                  Monster
                </a>
                <a class="item" href="/item/create">
                  <i class="large icon" data-icon="&#xe128;"></i>
                  Item
                </a>
                <a class="item" href="/spell/create">
                  <i class="large icon" data-icon="&#xe0c9;"></i>
                  Spell
                </a>
                <a class="item" href="/npc/create">
                  <i class="large icon" data-icon="&#xe2ee;"></i>
                  NPC
                </a>
                <a class="item" href="/location/create">
                  <i class="large icon" data-icon="&#xe112;"></i>
                  Location
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="ui eleven wide column">
  <div class="ui two stackable cards">
    <div class="ui card">
      <div class="content">
        <div class="header">Characters</div>
        <div class="ui small feed">
          @if(!$players->isEmpty())
            @foreach($players as $player)
              <div class="event">
                <div class="content">
                  <div class="summary">
                    <a href="{{ url('character', $player->id) }}">{{ $player->name }}</a>
                    <div class="date">
                     @if($player->classes != NULL)
                       @foreach(json_decode($player->classes, true) as $class)
                         @if(!empty($class['name']))
                           {{ $class['name'] }} {{ $class['level'] }}
                           @if(!$loop->last) / @endif
                         @else
                           @if($loop->last) No Classes @endif
                         @endif
                       @endforeach
                     @else
                       No class selected.
                     @endif
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="event">
              <div class="content">
                <div class="summary">
                  No players created yet.
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
      <div class="extra content">
        <a href="/character">View All</a>
        <a class="ui mini right floated button" href="/character/create">Create Character</a>
      </div>
    </div>

    <div class="ui card">
      <div class="content">
        <div class="header">Campaigns</div>
        <div class="ui small feed">
          @if(!$campaigns->isEmpty())
            @foreach($campaigns->sortByDesc('created_at') as $campaign)
              <div class="event">
                <div class="content">
                  <div class="summary">
                    <a href="{{ url('campaign', $campaign->id) }}">{{ $campaign->name }}</a>
                    <div class="date">
                     {{ $campaign->created_at->diffForHumans() }}
                    </div>
                  </div>

                </div>
              </div>
            @endforeach
          @else
            <div class="event">
              <div class="content">
                <div class="summary">
                  No campaigns created or joined yet.
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
      <div class="extra content">
        <a href="/campaign">View All</a>
        <a class="ui mini right floated button" href="/campaign/create">Create Campaign</a>
      </div>
    </div>
  </div>

  <div class="ui fluid card">
    <div class="content">
      <div class="header">Your Recent Activity</div>
      <div class="ui small feed">
        @forelse($feeds as $feed)
          @include('feed.'.$feed->type)
        @empty
          <p class="item">No activity.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>

<div class="ui five wide column">
  @include('dashboard.right')
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function(){
    $('.pointing.menu .item').tab();
});
</script>
@endsection
