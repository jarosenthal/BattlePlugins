@extends('layouts.admin')
@section('content')
    <div class="grid-100">
        <div id="issueList">
            @if(!$gitIssues)
                <div class="have-tasks">
                    There are no issues to show!
                </div>
            @else
                @foreach($gitIssues as $issue)
                    <div class="ui segment" id="g{{ $issue->id }}" ng-class="{highlighted: 'g{{ $issue->id }}' == highlighted}"
                         ng-hide="{{ $issue->state != 'open' }} && !showCompleted">
                        <div class="content grid-100">
                            <div class="header">
                                <a href="#g{{ $issue->id }}" ng-click="setHighlighted('g{{ $issue->id }}')"><i class="icon linkify"></i></a>
                                <a href="{{ $issue->html_url }}"><i class="icon external"></i></a>
                                {{$issue->title}}
                                <small>Assigned
                                    @if($issue->assignee)
                                        to <span class="name">{{ $issue->assignee->login }}</span>
                                    @endif
                                    by <span
                                            class="name">{{ $issue->user->login }}</span> {{ (new \Carbon\Carbon($issue->created_at))->diffForHumans() }}
                                </small>
                            </div>
                            <div class="description">
                                @if(count($issue->labels) > 0)
                                    <p>
                                        @foreach($issue->labels as $label)
                                            <span class="ui label" style="background-color: #{{ $label->color }}">{{ $label->name }}</span>
                                        @endforeach
                                    </p>
                                @endif
                                {!! Markdown::convertToHTML(strip_tags($issue->body)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@stop