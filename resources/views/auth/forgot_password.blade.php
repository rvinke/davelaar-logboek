<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Logboek - Davelaarbouw B.V.</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Nieuw wachtwoord aanvragen</h2>

                    <p>
                        Vul het e-mailadres waarmee u geregistreerd bent in en u krijgt een nieuw wachtwoord
                        toegezonden op uw e-mailadres.
                    </p>

                    <div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" method="post" action="{!! URL::route('auth.reset') !!}">
                                {!! csrf_field() !!}

                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="e-Mailadres" required="">
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b">Stuur een nieuw wachtwoord</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Davelaarbouw B.V.
            </div>
            <div class="col-md-6 text-right">
               <small>© 2014-2015</small>
            </div>
        </div>
    </div>

</body>

</html>
