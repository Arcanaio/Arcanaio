@extends('layouts.app')

@section('title', $campaign->name)

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="ui sixteen wide column">
      @include('campaign.menu')
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="ui eleven wide column">
  <div class="clearfix">
    <h3 class="ui left floated header">Campaign Journal</h3>
    <div class="ui labeled icon mini right floated primary button" id="journal-create"><i class="write icon"></i>New Entry</div>
  </div>
  <section class="cd-horizontal-timeline">
    <div class="timeline">
      <div class="events-wrapper">
        <div class="events">
          <ol>
            @forelse($campaign->journals->sortBy('created_at') as $journal)
              <li><a href="#0" data-date="{{ $journal->created_at->format('d/m/y\TG:i') }}" @if($loop->last) class="selected" @endif>{{ $journal->created_at->format('M j') }}</a></li>
            @empty
              <li><a href="#0" data-date="{{ date('d/m/y') }}" class="selected">{{ date('M j') }}</a></li>
            @endforelse
          </ol>

          <span class="filling-line" aria-hidden="true"></span>
        </div> <!-- .events -->
      </div> <!-- .events-wrapper -->

      <ul class="cd-timeline-navigation">
        <li><a href="#0" class="prev inactive">Prev</a></li>
        <li><a href="#0" class="next">Next</a></li>
      </ul> <!-- .cd-timeline-navigation -->
    </div> <!-- .timeline -->

    <div class="events-content">
      <ol>
        @forelse($campaign->journals->sortBy('created_at') as $journal)
          <li data-date="{{ $journal->created_at->format('d/m/y\TG:i') }}" @if($loop->last) class="selected" @endif>
            <h2>
              {{ $journal->title }}

              <form action="{{ route('journal.destroy', $journal->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="ui basic icon mini right floated button">
                    <i class="trash icon"></i>
                </button>
              </form>
              <a class="ui basic icon mini right floated button" href="{{ route('journal.edit', $journal->id) }}">
                  <i class="edit icon"></i>
              </a>
            </h2>

            <em>{{ $journal->created_at->format('F j, Y') }} | {{ $journal->elapsed_time }} days elapsed in game</em>
            <span class="journal-body">{!! clean($journal->body) !!}</span>
          </li>
        @empty
          <li data-date="{{ $campaign->created_at->format('d/m/y') }}" class="selected">
            <h2>Welcome!</h2>
            <em>{{ $campaign->created_at->format('F j, Y') }}</em>
            <p>You can use your journal to start tracking what happens each session in your campaign journal.</p>
            <p>Journal entries support a variety of formatting and are a great place to put notes at the end of each session so you never lose track of the finer details.</p>
          </li>
        @endforelse
      </ol>
    </div> <!-- .events-content -->
  </section>
</div>
<div class="ui five wide column">
    @include('partials.files', ['data' => $campaign, 'type' => 'campaign'])


  <div class="ui fluid vertical labeled icon basic buttons">
      @can('update', $campaign)
      <a class="ui button" href="{{ url('campaign/'.$campaign->id.'/edit') }}">
        <i class="edit icon"></i>
        Edit Campaign
      </a>
      @endcan
      @can('delete', $campaign)
      <a class="ui button" id="delete-modal-trigger">
        <i class="remove icon"></i>
        Delete Campaign
      </a>
      @endcan
  </div>

  <div class="ui hidden divider"></div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.0.0/jquery-migrate.min.js"></script>
<script src="/js/jquery.mobile.custom.min.js"></script>
<script src="/js/timeline.js"></script>

<div class="ui basic modal" id="delete-modal">
  <div class="header">Are you sure?</div>
  <div class="content">
    <p>Deleting the campaign will remove it permanently.</p>
  </div>
  <div class="actions">
    <form action="{{ url('campaign/'.$campaign->id) }}" method="POST">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
      <div class="ui cancel button">Cancel</div>
      <button type="submit" class="ui red approve button">
          <i class="icon trash"></i>Delete
      </button>
    </form>
  </div>
</div>

<div class="ui modal" id="journal-modal">
  <div class="header">Create a Journal Entry</div>
  <div class="content">
    <form action="{{ route('journal.store') }}" method="POST" class="ui form">
    {{ csrf_field() }}
    <input type="hidden" value="{{ $campaign->id }}" name="campaign_id">
    <div class="field">
      <label for="">Title</label>
      <input type="text" name="title" value="{{ old('title') }}">
    </div>
    <div class="field">
      <textarea name="body" maxlength="5000" class="trumbowyg">{{ old('body') }}</textarea>
    </div>
    <div class="two fields">
      <div class="field">
        <label for="">Date</label>
        <input type="text" data-toggle="datepicker" name="date" value="{{ old('elapsed_time') }}">
      </div>
      <div class="field">
        <label for="">Days Elapsed During Session</label>
        <input type="text" name="elapsed_time" value="{{ old('elapsed_time') }}">
      </div>
    </div>
  </div>
  <div class="actions">
      <div class="ui cancel button">Cancel</div>
      <button type="submit" class="ui green approve button">
          <i class="icon check"></i>Submit
      </button>
    </form>
  </div>
</div>

<div class="ui modal" id="upload-modal">
  <div class="header inline">Choose a file to upload!</div>
  <div class="content">
    <form action="{{ url('upload/campaign', $campaign->id) }}" method="POST" enctype="multipart/form-data" class="ui form">
    {{ csrf_field() }}
    <div class="text-center">
        <input type="file" id="file" name="file" placeholder="Choose a File">
        <label for="file" class="ui icon large button" >
            <i class="file icon"></i>
            Choose a File
        </label>

    </div>
    <p class="text-muted text-center">PDF or image files only. Max file size is 5MB.</p>
  </div>
  <div class="actions">
      <div class="ui cancel button">Cancel</div>
      <button type="submit" class="ui green approve button">
          <i class="icon check"></i>Submit
      </button>
    </form>
  </div>
</div>

<script>

  $('[data-toggle="datepicker"]').datepicker({autoPick:true});

  $('#delete-modal-trigger').click(function(){
      $('#delete-modal').modal('show');
  });
  $('#journal-create').click(function(){
      $('#journal-modal').modal('show');
  });
  $('#upload-modal-trigger').click(function(){
      $('#upload-modal').modal('show');
  });
</script>

@endsection
