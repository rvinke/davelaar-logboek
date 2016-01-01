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


    <div class="row">
        <div class="col-lg-12">
            <div id="map" style="width: 100%; height: 500px;"></div>
        </div>
    </div>

@stop

@push('styles')
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" />
@endpush


@push('scripts')
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script src="/js/rastercoords.js"></script>
<script src="/plattegrond-js/{{ $project->id }}/{{ $floor }}/0/{{ $log->id }}"></script>
@endpush
