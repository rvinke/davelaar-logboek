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
                    <a href="{!! \URL::route('rapport.floorplan.download', ['id' => $project->id, 'floor_id' => $floorplan->floor_id]) !!}" style="margin-right: 10px;" class="btn btn-info btn-xs pull-right">Download plattegrond</a>
                    <h2>{{ $project->naam }}</h2>
                    <p>Plattegrond <b>verdieping {{ \App\Models\Floor::findOrFail($floorplan->floor_id)->naam }}</b></p>
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
<script src="/plattegrond-js/{{ $project->id }}/{{ $floor }}"></script>
@endpush
