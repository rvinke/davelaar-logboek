<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Logboek - Davelaarbouw B.V.</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/login.css" rel="stylesheet">

</head>

<body class="white-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>
                <img src="http://www.davelaar.nl/assets/logo-davelaarbouw.png" class="login-image" />
                <!--<h1 class="logo-name">Log</h1>-->

            </div>
            <h3>Welkom bij het Logboek van Davelaarbouw B.V.</h3>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <p>{{ $message }}
            </p>

            <a href="{!! URL::route('qr-code.enter', $code) !!}" class="btn btn-primary">Maak een log-item aan</a>

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>

</body>

</html>
