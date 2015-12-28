<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Logboek | Davelaarbouw B.V.</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/app.css" rel="stylesheet">

    @stack('styles')

</head>

<body>

<div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs" style="color: #FFDB00"> <strong class="font-bold">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</strong>
                             </span> <span class="text-muted text-xs block">{!! Auth::user()->roles->first()->display_name !!} <b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu m-t-xs">
                                <li><a href="#">Logout</a></li>
                            </ul>
                    </div>
                    <div class="logo-element">
                        Log
                    </div>
                </li>
                @include('partials.menu.items', ['items'=> $menu_example->roots()])
            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">

        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" method="post" action="#">
                        <div class="form-group">

                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="{!! URL::route('logout') !!}">
                            Uitloggen <i class="fa fa-sign-out"></i>
                        </a>
                    </li>
                </ul>

            </nav>
        </div>


        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-sm-4">
                <h2>@yield('title')</h2>
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </div>
        </div>

        <div class="wrapper wrapper-content">


            @yield('content')


        </div>
        <div class="footer">
            <div class="pull-right">
                <img src="http://www.davelaar.nl/assets/logo-davelaarbouw.png" style="height: 30px;"/>
            </div>
            <div style="margin-top: 5px;">
                Logboek {!! \Config::get('app.app_version') !!}
            </div>
        </div>

    </div>
</div>

<!-- Mainly scripts -->
<script src="/js/jquery-2.1.1.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>
<script src="/js/plugins/pace/pace.min.js"></script>
@stack('scripts')

</body>

</html>
