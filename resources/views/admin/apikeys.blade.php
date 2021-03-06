@extends('layouts.admin')
@section('content')
    <div class="grid-100">
        <div class="ui message warning">
            Please remember that these API keys would allow someone to make actions as a user. Do not give these keys out to anyone.
        </div>
    </div>
    <div class="grid-100">
        @if(count($nodes))
            <table class="ui table">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Key (Hover to view)</th>
                </tr>
                </thead>
                <tbody>
                @foreach($nodes as $node)
                    <tr>
                        <td>{{ $node->user->email }} - {{ $node->user->displayname }}</td>
                        <td>
                            @if(UserSettings::hasNode($node->user_id, UserSettings::HIDE_API_KEY))
                                Redacted
                            @else
                                <span class="spoiler">{{ $node->user->api_key }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="ui message info">No users can use the API.</div>
        @endif
    </div>
@stop