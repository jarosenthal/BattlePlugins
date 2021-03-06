@extends('layouts.paste')
@section('content')
    <div class="grid-container">
        <h1 class="grid-85">
            {{ $paste->title ? $paste->title : $paste->slug }}
            @if($paste->public)
                (Public)
            @endif
            <small class="author">
                Created by {{ $paste->creator->displayname }} <span title="{{ $paste->created_at }}">{{ $paste->created_at->diffForHumans() }}</span>
                @if($paste->created_at != $paste->updated_at)
                    <span title="Last modified {{ $paste->updated_at->diffForHumans() }} ({{ $paste->updated_at }}).">*</span>
                @endif
            </small>
        </h1>
        @include('paste.partials.data')
    </div>
    @if(Auth::check() && Auth::user()->id == $paste->creator->id)
        @include('paste.partials.editpaste')
    @endif
@stop
@section('extraStyles')
    @if($lang != 'txt')
        <link rel="stylesheet" href="/assets/css/paste/prettify.css"/>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/lang-{{ $lang }}.min.js"/>
    @endif
@stop
