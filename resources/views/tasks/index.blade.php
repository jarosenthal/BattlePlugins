<!DOCTYPE html>
<html lang="en" ng-app="BattleTasks">
<head>
    @include('tasks.partials.head')
</head>
<body>
<div id="top"></div>
@if(!Auth::check())
    <div class="grid-100 text-right">
        <a id="loginButton" href="{{ action('Auth\AuthController@getLogin') }}" class="ui button">Login</a>
    </div>
@endif
@include('tasks.partials.tasks')
</body>
</html>
