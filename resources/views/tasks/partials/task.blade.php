<div class="item
					@if($task->status)
						completed
					@endif" id="{{ $task->id }}"
     ng-class="{highlighted: {{ $task->id }} == highlighted}"
     ng-hide="{{ $task->status ? '!showCompleted' : 'false' }}">
    <div class="content @if(Auth::check()) grid-90 @else grid-100 @endif">
        <div class="header">
            <a href="#{{ $task->id }}" ng-click="setHighlighted({{ $task->id }})"><i
                        class="icon linkify"></i></a>
            {{$task->title}}
            @if(Auth::check() && $task->public)
                (Public)
            @endif
            @if(array_key_exists($task->user_id, $displaynames))
                <small>Assigned
                    @if($task->assignee_id != 0)
                        to <span class="name">{{ $displaynames[$task->assignee_id] }}</span>
                    @endif
                    by <span class="name">{{ $displaynames[$task->user_id] }}</span>
                </small>
            @endif
        </div>
        <div class="description">
            {!! Markdown::convertToHTML(strip_tags($task->content)) !!}
        </div>
    </div>
    @if(Auth::check())
        <div class="actions grid-10 text-right">
            {!! Form::open(['url'=>URL::to('/tasks/complete/' . $task->id, [], env('HTTPS_ENABLED', true))]) !!}
            <button class="circular small ui positive icon button" ng-class="{disabled: {{ $task->status }} }"><i class="icon check"></i></button>
            {!! Form::close() !!}
            {!! Form::open(['url'=>URL::to('/tasks/delete/' . $task->id, [], env('HTTPS_ENABLED', true))]) !!}
            <button class="circular red small ui icon button"><i class="icon trash"></i></button>
            {!! Form::close() !!}
        </div>
    @endif
</div>