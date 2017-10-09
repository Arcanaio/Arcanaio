@extends('layouts.app')

@section('title', ucfirst($spell->name))

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="row">
      <div class="ui eight wide column">
        <h2 class="ui header">
          {{ ucfirst($spell->name) }}
          <div class="ui left pointing right floated label">
            {{ $spell->level }}
          </div>
          <div class="sub header">
            {{ ucfirst($spell->school) }}
          </div>
        </h2>
      </div>
      <div class="ui eight wide column">
        <div class="ui three statistics" id="statistics-mobile">
          <div class="statistic">
            <div class="value">
              {{ $spell->range }}
            </div>
            <div class="label">
              Range
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              {{ $spell->casting_time }}
            </div>
            <div class="label">
              Cast Time
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              {{ $spell->duration }}
            </div>
            <div class="label">
              Duration
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
        <i class="large icon" data-icon="&#xe0c9;"></i>
      </a>
      <a class="item" data-tab="notes">
        <i class="large icon" data-icon="&#xe130;"></i>
      </a>
      <div class="right menu">
        <a href="/spell" class="item"><i class="arrow circle outline left large icon"></i><span class="mobile hidden">Back to Spells</span></a>
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
        <div class="header">Classes</div>
        {{ $spell->class }}
      </div>
      @if($spell->archetype)
      <div class="item">
        <div class="header">Archetypes</div>
        {{ $spell->archetype }}
      </div>
      @endif
      <div class="item">
        <div class="ui horizontal list">
          <div class="item">
            <div class="header">Components</div>
            {{ $spell->components }}
          </div>
          <div class="item">
            <div class="header">Concentration</div>
            {{ $spell->concentration == 1 ? 'yes':'no' }}
          </div>
          <div class="item">
            <div class="header">Ritual</div>
            {{ $spell->ritual == 1 ? 'yes':'no' }}
          </div>
        </div>
      </div>
      @if($spell->material)
      <div class="item">
        <div class="header">Materials</div>
        {{ $spell->material }}
      </div>
      @endif
      <div class="item">
        <div class="header">Description</div>
        {!! $spell->description ? preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', clean($spell->description)) :'<blockquote>You rolled a 1 on your Investigation roll. No description for this item.</blockquote>' !!}

      </div>
      @if($spell->higher_level)
        <div class="item">
          <div class="header">Higher Levels</div>
          {!! clean($spell->higher_level) !!}
        </div>
      @endif
    </div>

    @include('partials.comments', ['data' => $spell->comments, 'type' => 'spell', 'id' => $spell->id])
  </div>

  <div class="ui tab" data-tab="notes">
    @include('partials.notes', ['data' => $notes, 'type' => 'spell', 'id' => $spell->id])
  </div>
</div>
<div class="ui five wide column">
  @if (Auth::check())
    <like-button id="{{ $spell->id }}" type="spell" :liked="{{ $spell->likes->contains('user_id', \Auth::id()) ? 'true':'false' }}" count="{{ $spell->like_count }}"></like-button>
  @else
    <div class="ui labeled fluid disabled button" id="like-button">
      <div class="ui fluid button">
        <i class="heart icon"></i> Like
      </div>
      <a class="ui basic left pointing label">
        {{ $spell->like_count }}
      </a>
    </div>
  @endif

  <div class="ui fluid vertical labeled icon basic buttons">
      @can('update', $spell)
      <a class="ui button" href="{{ url('spell/'.$spell->id.'/edit') }}">
        <i class="edit icon"></i>
        Edit
      </a>
      @endcan
      @can('delete', $spell)
      <a class="ui button" onclick="$('#delete-modal').modal('show')">
        <i class="remove icon"></i>
        Delete
      </a>
      @include('partials.delete', ['type' => 'spell', 'id' => $spell->id])
      @endcan
    <a class="ui button" href="{{ url('spell/fork/'.$spell->id) }}">
      <i class="fork icon"></i>
      Use as Template
    </a>
    <a class="ui button" onclick="$('#reddit-modal').modal('show');">
      <i class="reddit icon"></i>
      Reddit Markdown
    </a>
    @include('partials.reddit', ['markdown' => SpellHelper::getRedditMarkdown($spell)])
    <a class="ui button" onclick="$('#homebrewery-modal').modal('show');">
      <i class="icon" data-icon="&#xe255;"></i>
      Homebrewery Markdown
    </a>
    @include('partials.homebrewery', ['markdown' => SpellHelper::getHomebreweryMarkdown($spell)])
    @if($spell->private == 1)
      <a class="ui button" onclick="$('#share-modal').modal('show');">
        <i class="linkify icon"></i>
        Share Link
      </a>
      @include('partials.share', ['data' => $spell, 'type' => 'spell'])
    @endif
    @if(Auth::check())
      <a class="ui button" onclick="$('#report-modal').modal('show');">
        <i class="warning icon"></i>
        Report Errors & Issues
      </a>
      @include('partials.report', ['type' => 'spell', 'id' => $spell->id])
    @endif
  </div>

  @include('partials.files', ['data' => $spell, 'type' => 'spell'])

  @include('partials.fork', ['data' => $spell, 'type' => 'spell'])
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
