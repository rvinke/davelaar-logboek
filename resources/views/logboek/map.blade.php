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
                    <h5>Projectdetails</h5>
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
@endpush

@push('scripts')
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script src="/js/rastercoords.js"></script>
<script src="/plattegrond-js/{{ $project->id }}/{{ $log->locatie_id }}/{{ $floor }}/{{ $log->id }}"></script>







<!--<script>
        var mapMinZoom = 0;
        var mapMaxZoom = 4;
        var map = L.map('map', {
            maxZoom: mapMaxZoom,
            minZoom: mapMinZoom,
            crs: L.CRS.Simple
        }).setView([0, 0], mapMaxZoom);

        var mapBounds = new L.LatLngBounds(
                map.unproject([0, 3584], mapMaxZoom),
                map.unproject([2560, 0], mapMaxZoom));

        map.fitBounds(mapBounds);
        L.tileLayer('/documenten/2015/134/plattegrond/{z}/{x}/{y}.png', {
            minZoom: mapMinZoom, maxZoom: mapMaxZoom,
            bounds: mapBounds,
            noWrap: true,
            tms: false
        }).addTo(map);





        map.on('click', function(e){
            //alert(e.latlng);
            $('#position').val(e.latlng.lat + '|' + e.latlng.lng);
            var marker = new L.marker(e.latlng, {draggable: 'true'});
            map.addLayer(marker);

            marker.on('dragend', function(e){
                var marker = e.target;
                var position = marker.getLatLng();
                $('#position').val(position.lat + '|' + position.lng);
                //alert(position);
            });
        });




</script>-->

@endpush