@extends('layouts.admin')
@section('content')
    <div class="grid-100 grid-parent">
        @if(!count($tasks))
            <div class="grid-100">
                <div class="ui message info">
                    There are no tasks to show!
                </div>
            </div>
        @else
            @foreach($tasks as $task)
                <div class="ui segment">
                    <div class="grid-90">
                        {{$task->title}}
                        @if(Auth::check() && $task->public)
                            (Public)
                        @endif
                        <small>Assigned
                            @if($task->assignee_id)
                                to <span class="name">{{ $task->assignee->displayname }}</span>
                            @endif
                            by <span class="name">{{ $task->creator->displayname }}</span>
                        </small>
                        {!! Markdown::convertToHTML(strip_tags($task->content)) !!}
                    </div>
                    <div class="grid-10">
                        @if(Auth::check())
                            <div class="text-right">
                                @if(UserSettings::hasNode(auth()->user(), UserSettings::DELETE_TASK))
                                    {!! Form::open(['url'=>URL::to('/tasks/delete/' . $task->id, [], env('HTTPS_ENABLED', true))]) !!}
                                    <button class="circular red small ui icon button"><i class="icon trash"></i></button>
                                    {!! Form::close() !!}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@stop