<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Logboek | Davelaarbouw B.V.</title>

    <style>
        body {
            font-family: 'Arial', 'Helvetica';
            font-size: 12px;
        }

        .page-break {
            page-break-after: always;
        }
        th {
            text-align: left;
            font-family: 'Arial', 'Helvetica';
        }
        td {
            font-family: 'Arial', 'Helvetica';
        }
    </style>
</head>

<body>

<!-- voorpagina-->
<br /><br /><br /><br />
<h1 style="margin-top: 200px;">Project: <br /><span style="font-size: 40px;">{{ $project->naam }}</span></h1>
<h2>{{ $project->onderwerp }}</h2>
<p>
    <dt>Opleverdatum:</dt> <dd>{!! date("d-m-Y", strtotime($project->datum_oplevering)) !!}</dd>
    <dt>Projectnummer(s):</dt> <dd>{{ $project->projectnummer }}</dd>

<dt>Locatie(s):</dt> <dd>
    @foreach($project->locations as $locatie)
        {{ $locatie->naam }}<br />
    @endforeach
</dd>
</p>
<img src="http://www.davelaar.nl/assets/logo-davelaarbouw.png" style="margin-top: 380px; width: 300px; float: right;" />

<div class="page-break"></div>

@foreach($project->logs as $key => $log)
<table style="margin-top: 50px;">
    <tr>
        <th style="width:100px">Logcode</th>
        <td style="width:300px">{{$log->code}}</td>
        <td rowspan="8" style="vertical-align: top;">
            @if(isset($log->photo->id))
                <img src="{{ $log->photo->location() }}" style="max-width: 350px; max-height: 262px;" />
                <!--<img src="/photoL/{{ $log->photo->id }}" />-->
            @else
                Geen foto
            @endif
        </td>
    </tr>
    <tr>
        <th>Systeem</th>
        <td>
            @if($log->product_id != 0)
                <abbr title="{{ $log->system->naam }}">{{ $log->system->leverancier.' '.$log->system->productnummer }}</abbr><br />
            @endif
        </td>
    </tr>
    <tr>
        <th>Doorvoeren</th>
        <td>
            @foreach($log->passthroughs as $passthrough)
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
            @endforeach
        </td>
    </tr>
    <tr>
        <th>Wand/vloer</th>
        <td>
            {{ ($log->oppervlak_type_id ? 'Vloer' : 'Wand') }}
        </td>
    </tr>
    <tr>
        <th>Eis</th>
        <td>
            {{ ($log->eis > 0 ? $log->eis : 'Onbekend') }}
        </td>
    </tr>
    <tr>
        <th>Opmerkingen</th>
        <td>{{ $log->commentaar }}</td>
    </tr>
    <tr>
        <th>Link</th>
        <td><a href="{{ URL::route('log.show', ['id' => $log->id]) }}">{{ URL::route('log.show', ['id' => $log->id]) }}</a></td>
    </tr>
</table>
@if(($key+1)%3 == 0)
    <div class="page-break"></div>
@endif

@endforeach
</body>
</html>