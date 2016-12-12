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

            @if (count($errors) > 0)
                <div class="alert alert-danger">

                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach

                </div>
            @endif

            <p>U kunt inloggen met de gegevens die u verstrekt zijn door Davelaarbouw.
                <br /><br />
                Wilt u het voorbeeldproject bekijken? Log dan in met <b>logboek@davelaar.nl</b> en wachtwoord <b>logboek</b>.
            </p>
            <form class="m-t" role="form" method="post" action="{{ URL::route('login.post') }}">
                {!! csrf_field() !!}

                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="e-Mailadres" value="{{ old('email') }}" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Wachtwoord" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                <a href="{!! URL::route('auth.password_reset') !!}"><small>Wachtwoord vergeten?</small></a>

            </form>

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>

</body>

</html>
