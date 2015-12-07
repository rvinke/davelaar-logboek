<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Alerts e.g. approaching your limit</title>
    <link href="{!! URL::to('/') !!}/css/email/styles.css" media="all" rel="stylesheet" type="text/css" />
</head>

<body>

<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" width="600">
            <div class="content">
                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="alert alert-good">
                            Nieuw wachtwoord
                        </td>
                    </tr>
                    <tr>
                        <td class="content-wrap">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block">
                                        U heeft een nieuw wachtwoord aangevraagd voor het Logboek van Davelaarbouw B.V.
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        Nieuw wachtwoord: {{ $password }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        <a href="{!! URL::route('login') !!}" class="btn-primary">Log nu in</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-block">
                                        Met vriendelijke groet,<br />
                                        Davelaarbouw B.V.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table width="100%">
                        <tr>
                            <td class="aligncenter content-block"></td>
                        </tr>
                    </table>
                </div></div>
        </td>
        <td></td>
    </tr>
</table>

</body>
</html>
