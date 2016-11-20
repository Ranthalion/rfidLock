<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Member Portal') }}</title>

    <!-- Styles -->
    {{ Html::style('css/app.css') }}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <link href="/css/chosen.css" rel="stylesheet">
    {{ Html::style('css/chosen.css') }}
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Member Portal') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <!--<li><a href="{{ url('/register') }}">Register</a></li>-->
                        @else
                            <li><a href="{{ url('/members') }}">Members</a></li>
                            <li><a href="{{ url('/resources') }}">Resources</a></li>
                            <li clss="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Reports <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{url('/reports/payments')}}">Payments</a>
                                    </li>
                                    <li>
                                        <a href="{{url('/reports/phpInfo')}}">php Info</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="{{ url('/logs') }}">Logs</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @if(Session::has('message'))
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-success text-center">
                        {{ Session::get('message') }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(Session::has('error'))
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger text-center">
                        {{ Session::get('error') }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        @yield('content')
    </div>

    <!-- Scripts -->
    {{ Html::script('js/app.js') }}
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.0/js/toastr.min.js"></script>
    {{ Html::script('js/chosen.jquery.js') }}
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    {{ Html::script('js/jquery.blockUI.js') }}
    @yield('pagescript')

    <script>
    $(document).ready(function(){
        $.datepicker.setDefaults({dateFormat: "mm/dd/yy"});
        $('select').chosen({disable_search_threshold: 10});
        $('.datepicker').datepicker();
        $('form').submit($.blockUI);
    });
    </script>
</body>
</html>
