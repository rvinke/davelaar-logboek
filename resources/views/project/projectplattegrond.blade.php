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
                    <a href="{!! \URL::route('rapport.floorplan.download', ['id' => $project->id, 'location_id' => $floorplan->location_id, 'floor_id' => $floorplan->floor_id, 'number' => $floorplan->number]) !!}" style="margin-right: 10px;" class="btn btn-info btn-xs pull-right">Download plattegrond</a>
                    <h2>{{ $project->naam }}</h2>
                    <p>Plattegrond <b> {{ \App\Models\Location::findOrFail($floorplan->location_id)->naam }}, verdieping {{ \App\Models\Floor::findOrFail($floorplan->floor_id)->naam }}</b></p>
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
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
<style>
    .leaflet-container {
        background-color: #ffffff;
    }
</style>
@endpush


@push('scripts')
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script src="/js/rastercoords.js"></script>
<script src="/plattegrond-js/{{ $project->id }}/{{ $floorplan->location_id }}/{{ $floor }}/{{ $floorplan->number }}"></script>
@endpush
