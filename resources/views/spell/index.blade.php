@extends('layouts.app')

@section('title', 'Spells')

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="ui eight wide column">
      <h4 class="ui header">
        Spell Collection
        <div class="sub header">Spells in Database <div class="ui left pointing basic label">
            {{ $spell_count }}
          </div></div>
      </h4>
    </div>
    <div class="ui eight wide column">
      <div class="ui grid">
        <div class="ui sixteen wide mobile only column">
          <a href="{{ url('spell/create') }}" class="ui primary labeled icon fluid button" onclick="ga('send', 'event', 'spell', 'create');"><i class="add icon"></i> Create Spell</a>
        </div>
        <div class="ui right floated ten wide mobile hidden column">
          <a href="{{ url('spell/create') }}" class="ui primary labeled icon fluid button" onclick="ga('send', 'event', 'spell', 'create');"><i class="add icon"></i> Create Spell</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="ui eleven wide column">
  <div class="ui fluid input">
    <input type="text" placeholder="Search Spells..." id="search-narrow">
    <div id="sort-by-wrapper"><span id="sort-by"></span></div>
  </div>

  <main>
    <div class="ui clearing horizontal divider" id="search-stats"><small id="stats" class="text-muted"></small></div>
    <div id="hits"></div>
    <div id="pagination"></div>
  </main>
</div>
<div class="ui five wide column">
  <div class="ui fluid card">
    <div class="content">
      <div class="ui small header">Filter Results</div>
    </div>
    <div class="content">
      <div id="level" class="facet"></div>
    </div>
    <div class="content">
      <div id="class" class="facet"></div>
    </div>
    <div class="content">
      <div id="school" class="facet"></div>
    </div>
    <div class="content">
      <div id="source" class="facet"></div>
    </div>
    <div class="extra content text-center">
      <span id="clear-all"></span>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/ion.rangeslider/2.0.6/js/ion.rangeSlider.min.js"></script>
<script src="//cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js"></script>

<!-- TEMPLATES -->
<script type="text/html" id="hit-template">
    <div class="content">
      <div class="right floated meta">
        <div class="ui right ribbon label">
          @{{ level }}
        </div>
      </div>
      <a class="small header" href="/spell/@{{ id }}">
        @{{{ _highlightResult.name.value }}}
      </a>
      <div class="meta">
        <div class="ui horizontal divided list">
        @{{#class}}
          <div class="item">@{{ . }}</div>
        @{{/class}}
        </div>
      </div>
      <div class="description">
        @{{{ description_short }}}
      </div>
    </div>
    <div class="extra content">
      <span class="right floated">
        <div class="ui mini basic label">
          <i class="eye icon"></i>
          @{{ view_count }}
        </div>
        <div class="ui mini basic label">
          <i class="comments icon"></i>
          @{{ comment_count }}
        </div>
        <div class="ui mini red basic label like-button" data-id="@{{ id }}" data-type="spell">
          <i class="heart icon"></i>
          <span class="like-counter">@{{ like_count }}</span>
        </div>
      </span>
      <a class="ui image mini label" href="/profile/@{{ user_id }}">
        <img src="@{{ user_avatar }}">
        @{{ user_name }}
      </a>

    </div>

</script>

<script type="text/html" id="no-results-template">
  <div id="no-results-message">
    <p>We didn't find any results for the search <em>"@{{query}}"</em>.</p>
  </div>
</script>
<!-- /TEMPLATES -->


<script>
$( document ).ready(function() {

  var search = instantsearch({
    // Replace with your own values
    appId: '{{ env('ALGOLIA_APP_ID', 'KAJKVCOSDK') }}',
    apiKey: '{{ \Common::generateAlgoliaKey() }}', // search only API key, no ADMIN key
    indexName: 'spells',
    urlSync: true
  });

  search.addWidget(
    instantsearch.widgets.searchBox({
      container: '#search-narrow',
      placeholder: 'Search for items...'
    })
  );

  search.addWidget(
    instantsearch.widgets.hits({
      container: '#hits',
      hitsPerPage: 10,
      templates: {
        item: getTemplate('hit'),
        empty: getTemplate('no-results')
      },
      cssClasses: {
        root: "ui one cards",
        item: "card"
      }
    })
  );

  search.addWidget(
    instantsearch.widgets.stats({
      container: '#stats'
    })
  );

  search.addWidget(
    instantsearch.widgets.clearAll({
      container: '#clear-all',
      templates: {
        link: 'Clear All'
      },
      autoHideContainer: false
    })
  );

  search.addWidget(
    instantsearch.widgets.pagination({
      container: '#pagination',
      cssClasses: {
        active: 'active'
      }
    })
  );

  search.addWidget(
    instantsearch.widgets.refinementList({
      container: '#level',
      attributeName: 'level',
      autoHideContainer: false,
      sortBy: ['name:asc', 'count:desc'],
      operator: 'or',
      limit: 10,
      templates: {
        header: '<h4 class="ui sub header">Level</h4>'
      },
      cssClasses: {
        item: ''
      }
    })
  );

  search.addWidget(
    instantsearch.widgets.refinementList({
      container: '#class',
      attributeName: 'class',
      autoHideContainer: false,
      operator: 'or',
      limit: 20,
      templates: {
        header: '<h4 class="ui sub header">Class</h4>'
      },
      cssClasses: {
        item: ''
      }
    })
  );

  search.addWidget(
    instantsearch.widgets.refinementList({
      container: '#school',
      attributeName: 'school',
      autoHideContainer: false,
      operator: 'or',
      limit: 20,
      templates: {
        header: '<h4 class="ui sub header">School</h4>'
      },
      cssClasses: {
        item: ''
      }
    })
  );

  search.addWidget(
    instantsearch.widgets.refinementList({
      container: '#source',
      attributeName: 'source',
      autoHideContainer: false,
      operator: 'or',
      limit: 10,
      templates: {
        header: '<h4 class="ui sub header">Source</h4>'
      },
      cssClasses: {
        item: ''
      }
    })
  );

  search.addWidget(
    instantsearch.widgets.sortBySelector({
      container: '#sort-by-wrapper',
      autoHideContainer: false,
      cssClasses: {
        root: "ui dropdown"
      },
      indices: [{
        name: search.indexName, label: 'Most Relevant'
      }, {
        name: search.indexName + '_views', label: 'Most Views'
      }, {
        name: search.indexName + '_likes', label: 'Most Likes'
      }, {
        name: search.indexName + '_level', label: 'Level (Low to High)'
      }]
    })
  );

  search.start();

  function getTemplate(templateName) {
    return document.getElementById(templateName + '-template').innerHTML;
  }

  $(document).on('click', '.like-button', like);
});
</script>
@endsection
