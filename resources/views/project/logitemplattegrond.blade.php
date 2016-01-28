@extends('app')

@section('title')
    Projectdetails
@endsection


@section('breadcrumb')

@endsection


{{-- Content --}}
@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif





    <div class="row">

        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content float-e-margins">
                    <a href="{!! \URL::route('rapport.show', ['id' => $project->id]) !!}" class="btn btn-primary btn-xs pull-right">Terug naar het rapport</a>
                    <h2>{{ $project->naam }}</h2>
                    <table>
                        <tr>
                            <th style="width:100px">Logcode</th>
                            <td>{{$log->code}}</td>
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
                            <th>Opmerkingen</th>
                            <td>{{ $log->commentaar }}</td>
                        </tr>
                    </table>


                </div>
            </div>
        </div>
    </div>


    @if($log->reports->count() > 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Verbroken</h5>
                </div>
                <div class="ibox-content">
                    Deze brandscheiding is op {{ date("d-m-Y", strtotime($log->reports->last()->created_at)) }} om {{ date("H:i", strtotime($log->reports->last()->created_at)) }}  verbroken
                    gemeld door {{ $log->reports->last()->naam }} namens de organisatie {{ $log->reports->last()->organisatie }}.
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($log->lat != 0.00)
    <div class="row">
        <div class="col-lg-12">
            <div id="map" style="width: 100%; height: 500px;"></div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Foto</h5>
                </div>
                <div class="ibox-content">
                    @if(isset($log->photo->id))
                        <img src="/photoL/{{ $log->photo->id }}" />
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

@stop

@push('styles')
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" />
@endpush


@push('scripts')
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script src="/js/rastercoords.js"></script>
@if($log->lat != 0.00)
<script src="/plattegrond-js/{{ $project->id }}/{{ $log->locatie_id }}/{{ $log->bouwlaag_id }}/0/{{ $log->id }}"></script>
@endif
@endpush
