@extends('layouts.app')

@section('title', ucfirst($item->name))

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="row">
      <div class="ui eight wide column">
        <h2 class="ui header">
          {{ ucfirst($item->name) }}
          <div class="ui left pointing right floated label">
            {{ $item->cost or "No Cost" }}
          </div>
          <div class="sub header">
            {{ $item->type }}
          </div>
        </h2>
      </div>
      <div class="ui eight wide column">
        <div class="ui three statistics" id="statistics-mobile">
          <div class="statistic">
            <div class="value">
              @if($item->type == 'Weapon')
                {{ $item->weapon_damage or "-" }}
              @elseif($item->type == 'Armor')
                {{ $item->ac or "-" }}
              @elseif($item->type == 'Vehicle')
                {{ $item->vehicle_speed or "-" }}
              @else
                {{ $item->weight or "-" }}
              @endif
            </div>
            <div class="label">
              @if($item->type == 'Weapon')
                Damage
              @elseif($item->type == 'Armor')
                AC
              @elseif($item->type == 'Vehicle')
                Speed
              @else
                Weight
              @endif
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              @if($item->type == 'Weapon')
                {{ $item->weapon_range or "-" }}
              @elseif($item->type == 'Armor')
                {{ $item->armor_str_req or "-" }}
              @elseif($item->type == 'Vehicle')
                {{ $item->vehicle_capacity or "-" }}
              @else
                {{ $item->rarity ? ucwords($item->rarity) : "-" }}
              @endif
            </div>
            <div class="label">
              @if($item->type == 'Weapon')
                Range
              @elseif($item->type == 'Armor')
                Str Req
              @elseif($item->type == 'Vehicle')
                Capacity
              @else
                Rarity
              @endif
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              @if($item->type == 'Weapon')
                {{ $item->subtype != null ? $item->subtype : "-" }}
              @elseif($item->type == 'Armor')
                {{ $item->subtype != null ? $item->subtype : "-" }}
              @elseif($item->type == 'Vehicle')
                {{ $item->subtype != null ? $item->subtype : "-" }}
              @else
                {{ $item->attunement != "yes" ? "-" : "Yes" }}
              @endif
            </div>
            <div class="label">
              @if($item->type == 'Weapon' || $item->type == 'Armor' || $item->type == 'Vehicle')
                Type
              @else
                Attunement
              @endif
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
        <i class="large icon" data-icon="&#xe128;"></i>
      </a>
      <a class="item" data-tab="notes">
        <i class="large icon" data-icon="&#xe130;"></i>
      </a>
      <div class="right menu">
        <a href="/item" class="item"><i class="arrow circle outline left large icon"></i><span class="mobile hidden">Back to Items</span></a>
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
        <h5 class="ui header">Description</h5>

          {!! $item->description ? preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', clean($item->description)) :'<blockquote>You rolled a 1 on your Investigation roll. No description for this item.</blockquote>' !!}

      </div>
    </div>

    <h5 class="ui header">Item Stats</h5>
    <table class="ui celled compact unstackable table">
      <thead>
        <tr>
          <th>Cost</th>
          <th>Weight</th>
          <th>Rarity</th>
          <th>Attunement?</th>
        </tr>
      </thead>
      <tr>
        <td>{{ $item->cost or "-"}}</td>
        <td>{{ $item->weight or "-"}}</td>
        <td>{{ $item->rarity ? ucwords($item->rarity) : "-" }}</td>
        <td>{{ $item->attunement != "yes" ? "-" : "Yes" }}</td>
      </tr>
    </table>

    @if($item->type == 'Weapon')
      <h5 class="ui header">Weapon Stats</h5>
      <table class="ui celled compact unstackable table">
        <thead>
          <tr>
            <th>Damage</th>
            <th>Range</th>
            <th>Type</th>
            <th>Properties</th>
          </tr>
        </thead>
        <tr>
          <td>{{ $item->weapon_damage or "-"}}</td>
          <td>{{ $item->weapon_range or "-"}}</td>
          <td>{{ $item->subtype or "-" }}</td>
          <td>{{ $item->weapon_properties or "-" }}</td>
        </tr>
      </table>
    @elseif($item->type == 'Armor')
      <h5 class="ui header">Armor Stats</h5>
      <table class="ui celled compact unstackable table">
        <thead>
          <tr>
            <th>AC</th>
            <th>Type</th>
            <th>Str Req?</th>
            <th>Stealth Disadvantage?</th>
          </tr>
        </thead>
        <tr>
          <td>{{ $item->ac or "-"}}</td>
          <td>{{ $item->subtype or "-" }}</td>
          <td>{{ $item->armor_str_req or "-"}}</td>
          <td>{{ $item->armor_stealth or "-" }}</td>
        </tr>
      </table>
    @endif

    <div class="ui hidden divider"></div>

    @include('partials.comments', ['data' => $item->comments, 'type' => 'item', 'id' => $item->id])
  </div>

  <div class="ui tab" data-tab="notes">
    @include('partials.notes', ['data' => $notes, 'type' => 'item', 'id' => $item->id])
  </div>
</div>

<div class="ui five wide column">
  @if (Auth::check())
    <like-button id="{{ $item->id }}" type="item" :liked="{{ $item->likes->contains('user_id', \Auth::id()) ? 'true':'false' }}" count="{{ $item->like_count }}"></like-button>

     @include('partials.campaignbutton', ['type' => 'item', 'id' => $item->id])
  @else
    <div class="ui labeled fluid disabled button" id="like-button">
      <div class="ui fluid button">
        <i class="heart icon"></i> Like
      </div>
      <a class="ui basic left pointing label">
        {{ $item->like_count }}
      </a>
    </div>
  @endif

  <div class="ui fluid vertical labeled icon basic buttons">
      @can('update', $item)
      <a class="ui button" href="{{ url('item/'.$item->id.'/edit') }}">
        <i class="edit icon"></i>
        Edit
      </a>
      @endcan
      @can('delete', $item)
      <a class="ui button" onclick="$('#delete-modal').modal('show')">
        <i class="remove icon"></i>
        Delete
      </a>
      @include('partials.delete', ['type' => 'item', 'id' => $item->id])
      @endcan
    <a class="ui button" href="{{ url('item/fork/'.$item->id) }}">
      <i class="fork icon"></i>
      Use as Template
    </a>
    <a class="ui button" onclick="$('#reddit-modal').modal('show');">
      <i class="reddit icon"></i>
      Reddit Markdown
    </a>
    @include('partials.reddit', ['markdown' => ItemHelper::getRedditMarkdown($item)])
    <a class="ui button" onclick="$('#homebrewery-modal').modal('show');">
      <i class="icon" data-icon="&#xe255;"></i>
      Homebrewery Markdown
    </a>
    @include('partials.homebrewery', ['markdown' => ItemHelper::getHomebreweryMarkdown($item)])
    @if($item->private == 1)
      <a class="ui button" onclick="$('#share-modal').modal('show');">
        <i class="linkify icon"></i>
        Share Link
      </a>
      @include('partials.share', ['data' => $item, 'type' => 'item'])
    @endif
    @if(Auth::check())
      <a class="ui button" onclick="$('#report-modal').modal('show');">
        <i class="warning icon"></i>
        Report Errors & Issues
      </a>
      @include('partials.report', ['type' => 'item', 'id' => $item->id])
    @endif
  </div>

  @include('partials.files', ['data' => $item, 'type' => 'item'])

  @include('partials.fork', ['data' => $item, 'type' => 'item'])
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

</script>

@endsection
