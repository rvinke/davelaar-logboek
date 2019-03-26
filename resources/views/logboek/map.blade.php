@extends('app')

@section('title')
    Geef locatie aan van het logitem (stap 2 van 2)
@stop

{{-- Content --}}
@section('content')
    {!! Form::model($log, array('route' => ['log.update-map', $log->id], 'method' => 'PATCH', 'autocomplete'=>'off', 'class' => 'form-horizontal')) !!}
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <h5>Plattegrond</h5>
                </div>

                <div class="ibox-content">

                    <div id="map" style="width: 100%; height: 300px;"></div>

                    @if(!empty($log->lat))
                        {!! \Form::hidden('position', $log->lat.'|'.$log->lng, ['id' => 'position']) !!}
                    @else
                        {!! \Form::hidden('position', '', ['id' => 'position']) !!}
                    @endif

                </div>

                <div class="ibox-footer">

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <span class="pull-right">
                                <button class="btn btn-primary" type="submit">Bewaar</button>
                            </span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection


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
<script src="/plattegrond-js/{{ $project->id }}/{{ $log->locatie_id }}/{{ $floor }}/1/{{ $log->id }}"></script>
@endpush