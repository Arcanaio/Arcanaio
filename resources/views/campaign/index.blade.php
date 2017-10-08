@extends('layouts.app')

@section('title', 'Campaigns')

@section('header')
<div class="page-header">
  <div class="ui middle aligned stackable grid container">
    <div class="ui eight wide column">
      <h4 class="ui header">
        Campaign Collection
        <div class="sub header">A list of your active campaigns.</div>
      </h4>
    </div>
    <div class="ui eight wide column">
      <div class="ui grid">
        <div class="ui sixteen wide mobile only column">
          <a href="{{ url('campaign/create') }}" class="ui primary labeled icon fluid button" onclick="ga('send', 'event', 'campaign', 'create');"><i class="add icon"></i> Create Campaign</a>
        </div>
        <div class="ui right floated ten wide tablet only computer only column">
          <a href="{{ url('campaign/create') }}" class="ui primary labeled icon fluid button" onclick="ga('send', 'event', 'campaign', 'create');"><i class="add icon"></i> Create Campaign</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="ui sixteen wide column">
  <table class="ui fluid celled unstackable table">
    <thead>
      <tr><th>Campaign</th>
      <th>Created</th>
    </tr></thead>
    <tbody>
      @if(!$campaigns->isEmpty())
        @foreach($campaigns->sortByDesc('created_at') as $campaign)
          <tr>
            <td><a href="{{ url('campaign', $campaign->id) }}">{{ $campaign->name }}</a></td>
            <td>{{ $campaign->created_at->diffForHumans() }}</td>
          </tr>
        @endforeach
      @else
        <tr>
          <td>No campaigns yet.</td>
          <td><a href="/campaign/create">Create a Campaign</a></td>
        </tr>
      @endif
    </tbody>
  </table>
</div>
@endsection
