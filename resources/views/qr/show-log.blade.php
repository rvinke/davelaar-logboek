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
                <img src="http://www.davelaar.nl/assets/logo-davelaarbouw.png" class="login-image less-margin" />
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

            <p style="text-align: left;">Deze brandscheiding is uitgevoerd door Davelaarbouw. U kunt via deze pagina
                doorgeven dat de brandscheiding verbroken is. Klikt u hiervoor op de button onderaan deze pagina. <br />
            </p>


            <table class="table table-striped" style="text-align: left;">
                <tr><th>Logcode:</th><td>{{ $log->code }}</td></tr>
                <tr><th>Locatie:</th><td>{{ $log->location->naam }}</td></tr>
                <tr><th>Product:</th><td>{{ $log->system->naam }}</td></tr>
                <tr><th>Doorvoeren:</th><td> @foreach($log->passthroughs as $passthrough)
                            @if($passthrough->passthrough_type_id != 0)
                                <?php

                                if(!isset($count_passthrough[$passthrough->passthrough_type_id])) {
                                    $count_passthrough[$passthrough->passthrough_type_id] = $passthrough->count;
                                } else {
                                    $count_passthrough[$passthrough->passthrough_type_id] += $passthrough->count;
                                }

                                ?>
                                {{ $passthrough->count }}x {{ $passthrough->passthrough_type->naam }}<br />
                            @endif
                        @endforeach</td></tr>
                <tr><th>Commentaar</th><td>{{ $log->commentaar }}</td></tr>
            </table>
            @if($log->reports->count() > 0)
                <div class="alert alert-danger"><b>Er is gemeld dat deze brandscheiding verbroken is.</b></div>
                <a href="{!! URL::route('qr-code.restore', $code) !!}" class="btn btn-primary">Meld herstel</a>
            @else
                <a href="{!! URL::route('qr-code.report', $code) !!}" class="btn btn-primary">Meld verbroken brandscheiding</a>
            @endif


        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>

</body>

</html>
