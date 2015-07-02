<!DOCTYPE html>
<html lang="en" ng-app="BattleAdmin">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} :: BattlePlugins Administration Panel</title>
    <link rel="icon" href="/assets/img/bp.png"/>

    <!--        Styles -->
    <link rel="stylesheet" href="/assets/css/semantic.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.12.0/components/icon.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/unsemantic/0/unsemantic-grid-responsive.css">
    <link rel="stylesheet" href="/assets/css/styles.css" type="text/css"/>
    <!--        End Styles -->
    <!--        Scripts -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/1.12.2/semantic.min.js"></script>

    @if(count($alerts) > 0)
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.1/angular.min.js"></script>
        <script type="text/javascript" src="/assets/js/admin/alerts.js"></script>
        @endif
                <!--       End Scripts -->
</head>
<body>
<div class="grid-100 grid-parent">
    @include('admin.partials.menu')
    <div class="grid-100 grid-parent pull-right">
        <div class="grid-85 pull-right">
            @if(count($alerts) > 0)
                <div class="grid-100 grid-parent alerts" ng-controller="AlertsCtrl">
                    <table class="updates">
                        <tbody>
                        <tr>
                            <td width="10%" class="text-center"><h3>Alerts | </h3></td>
                            <td width="80%" ng-bind="alert['content']"></td>
                            <td width="10%" class="text-right">
                                <i ng-click="prevAlert()" ng-hide="alerts.length == 1"
                                   class="icon caret left pointer"></i>
                                <a id="removeAlert"><i class="icon remove pointer"></i></a>
                                <i ng-click="nextAlert()" ng-hide="alerts.length == 1"
                                   class="icon caret right pointer"></i>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="grid-60 updates text-center">Hello, {{ auth()->user()->displayname }}. Welcome to
                    BattleAdmin!
                </div>
            @endif
        </div>
        <div class="grid-container">
            @yield('content')
        </div>
        @include('footer')
    </div>
</div>
<script type="text/javascript">
    $('.ui.checkbox').checkbox();
</script>
</body>
</html>