@extends('layouts.app')

@section('title', ucfirst($location->name))

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="ui sixteen wide column">
      <h2 class="ui header">
        {{ ucfirst($location->name) }}
        <div class="ui left pointing right floated label">
          {{ $location->type }}
        </div>
        <div class="sub header">
          {{ ucfirst($location->subtype) }}
        </div>
        @if($location->parent)
          <div class="sub header">
            Belongs to <a href="/location/{{ $location->parent }}">{{ $location->root()->name }}</a>
          </div>
        @endif
      </h2>
    </div>
  </div>
</div>
@endsection

@section('submenu')
<div id="submenu">
  <div class="ui container">
    <div class="ui tabular icon tiny menu">
      <a class="active item" data-tab="stats">
        <i class="large icon" data-icon="&#xe112;"></i>
      </a>
      <a class="item" data-tab="npcs">
        <i class="large icon" data-icon="&#xe2ee;"></i>
      </a>
      <a class="item" data-tab="monsters">
        <i class="large icon" data-icon="&#xe016;"></i>
      </a>
      <a class="item" data-tab="items">
        <i class="large icon" data-icon="&#xe128;"></i>
      </a>
      <a class="item" data-tab="notes">
        <i class="large icon" data-icon="&#xe130;"></i>
      </a>
      <div class="right menu">
        <a href="/location" class="item"><i class="arrow circle outline left large icon"></i><span class="mobile hidden">Back to Locations</span></a>
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

        {!! $location->description ? preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', clean($location->description)) :'<blockquote>You rolled a 1 on your Investigation roll. No description for this item.</blockquote>' !!}

      </div>

      @if($location->owner)
        <div class="item">
          <div class="header">Owner</div>
          {{ $location->owner }}
        </div>
      @endif

      @if($location->other_items)
        <div class="item">
          <div class="header">Additional Items</div>
          {{ $location->other_items }}
        </div>
      @endif

      @if($location->price)
        <div class="item">
          <div class="header">Price per Night</div>
          {{ $location->price }}
        </div>
      @endif

      @if($location->menu)
        <div class="item">
          <div class="header">Menu</div>
          {{ $location->menu }}
        </div>
      @endif

      @if($location->history)
        <div class="item">
          <div class="header">History</div>
          {{ $location->history }}
        </div>
      @endif

      @if($location->government)
        <div class="item">
          <div class="header">Government</div>
          {{ $location->government }}
        </div>
      @endif

      @if($location->demographics)
        <div class="item">
          <div class="header">Demographics</div>
          {{ $location->demographics }}
        </div>
      @endif

    </div>

    <div class="ui small header">Related Locations</div>
    <div class="ui list">
      @forelse($location->children() as $child)
        @if($child->private == 0 || \Auth::id() == $child->user_id)
          <a href="/location/{{ $child->id }}" class="item">{{ $child->name }}</a>
        @endif
      @empty
        <div class="item">None</div>
      @endforelse
    </div>

    @include('partials.comments', ['data' => $location->comments, 'type' => 'location', 'id' => $location->id])

  </div>

  <div class="ui tab" data-tab="notes">
    @include('partials.notes', ['data' => $notes, 'type' => 'location', 'id' => $location->id])
  </div>

  <div class="ui tab" data-tab="npcs">
    <table class="ui sortable striped unstackable celled compact table" id="npc-table">
      <thead>
        <tr>
          <th class="default-sort">Name</th>
          <th>Profession/Title</th>
          <th class="mobile hidden">Recent Notes</th>
        </tr>
      </thead>
      <tbody>
      @forelse ($location->npcs as $npc)
        <tr>
          <td>
            <a href="{{ url('npc', $npc->id) }}">
              {{ $npc->name }}
            </a>
          </td>
          <td>
            {{ $npc->profession or "None" }}
          </td>
          <td class="mobile hidden">
            {{ $npc->private_notes->first()->body or "No recent notes." }}
          </td>
        </tr>
      @empty
        <tr>
          <td>No npcs added yet.</td>
          <td>N/A</td>
          <td>N/A</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="ui tab" data-tab="monsters">
    <table class="ui sortable striped unstackable celled compact table" id="monster-table">
      <thead>
        <tr>
          <th class="default-sort">Name</th>
          <th>CR</th>
          <th class="mobile hidden">Recent Notes</th>
        </tr>
      </thead>
      <tbody>
      @forelse ($location->monsters as $monster)
        <tr>
          <td>
            <a href="{{ url('monster', $monster->id) }}">
              {{ $monster->name }}
            </a>
          </td>
          <td>
            {{ $monster->CR_fraction or "None" }}
          </td>
          <td class="mobile hidden">
            {{ $monster->private_notes->first()->body or "No recent notes." }}
          </td>
        </tr>
      @empty
        <tr>
          <td>No monsters added yet.</td>
          <td>N/A</td>
          <td>N/A</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>

  <div class="ui tab" data-tab="items">
    <table class="ui sortable striped unstackable celled compact table" id="item-table">
      <thead>
        <tr>
          <th class="default-sort">Name</th>
          <th>Type & Price</th>
          <th class="mobile hidden">Recent Notes</th>
        </tr>
      </thead>
      <tbody>
      @forelse ($location->items as $item)
        <tr>
          <td>
            <a href="{{ url('item', $item->id) }}">
              {{ $item->name }}
            </a>
          </td>
          <td>
            {{ $item->type or "None" }} | {{ $item->cost or "None" }}
          </td>
          <td class="mobile hidden">
            {{ $item->private_notes->first()->body or "No recent notes." }}
          </td>
        </tr>
      @empty
        <tr>
          <td>No items added yet.</td>
          <td>N/A</td>
          <td>N/A</td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>

</div>
<div class="ui five wide column">
  @if (Auth::check())
    <like-button id="{{ $location->id }}" type="location" :liked="{{ $location->likes->contains('user_id', \Auth::id()) ? 'true':'false' }}" count="{{ $location->like_count }}"></like-button>
  @else
    <div class="ui labeled fluid disabled button" id="like-button">
      <div class="ui fluid button">
        <i class="heart icon"></i> Like
      </div>
      <a class="ui basic left pointing label">
        {{ $location->like_count }}
      </a>
    </div>
  @endif

  <div class="ui fluid vertical labeled icon basic buttons">
      @can('update', $location)
      <a class="ui button" href="{{ url('location/'.$location->id.'/edit') }}">
        <i class="edit icon"></i>
        Edit
      </a>
      @endcan
      @can('delete', $location)
      <a class="ui button" onclick="$('#delete-modal').modal('show')">
        <i class="remove icon"></i>
        Delete
      </a>
      @include('partials.delete', ['type' => 'location', 'id' => $location->id])
      @endcan
    <a class="ui button" href="{{ url('location/fork/'.$location->id) }}">
      <i class="fork icon"></i>
      Use as Template
    </a>
    @if($location->private == 1)
      <a class="ui button" onclick="$('#share-modal').modal('show');">
        <i class="linkify icon"></i>
        Share Link
      </a>
      @include('partials.share', ['data' => $location, 'type' => 'location'])
    @endif
    @if(Auth::check())
      <a class="ui button" onclick="$('#report-modal').modal('show');">
        <i class="warning icon"></i>
        Report Errors & Issues
      </a>
      @include('partials.report', ['type' => 'location', 'id' => $location->id])
    @endif
  </div>

  @include('partials.files', ['data' => $location, 'type' => 'location'])

  @include('partials.fork', ['data' => $location, 'type' => 'location'])
</div>

@endsection

@section('scripts')
  <script src="/js/jquery.address.js" type="text/javascript"></script>
  <script>

    $('.tabular.menu .item').tab({
      history: true,
      historyType: 'hash'
    });

    var clipboard = new Clipboard('.copy-button');

    $('#npc-table').tablesort().data('tablesort').sort($("th.default-sort"));
    $('#monster-table').tablesort().data('tablesort').sort($("th.default-sort"));
    $('#item-table').tablesort().data('tablesort').sort($("th.default-sort"));

  </script>
@endsection
