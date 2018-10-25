

var mapMinZoom = 0;
var mapMaxZoom = 6;
var img = [
{{ $floorplan->xmax }},
{{ -$floorplan->ymax }}
];

var map_{{ $floor }} = L.map('map', {
maxZoom: mapMaxZoom,
minZoom: mapMinZoom,
scrollWheelZoom: false
});

// assign map and image dimensions
var rc = new L.RasterCoords(map_{{ $floor }}, img);
// set the bounds on map
rc.setMaxBounds();



// set marker at the image bound edges
var layerBounds = L.layerGroup();
map_{{ $floor }}.addLayer(layerBounds);


var markers = new L.FeatureGroup();
map_{{ $floor }}.addLayer(markers);

// the tile layer containing the image generated with gdal2tiles --leaflet ...
L.tileLayer('/documenten/{{ $year }}/{{ $project->id }}/plattegrond/{{ $floorplan->location_id }}/{{ $floor }}//{z}/{x}/{y}.png', {
noWrap: true,
attribution: 'Plattegrond (c) Davelaarbouw B.V.',
}).addTo(map_{{ $floor }});



@if($log && $editable)

    var marker;

    @if(!empty($log->lat))
    var marker = L.marker(rc.unproject([{{ $log->lat }}, {{ $log->lng }}]), {draggable:true}).addTo(layerBounds);

    marker.on('dragend', function(e){
    var marker = e.target;
    var position = marker.getLatLng();
    $('#position').val(position.lat + '|' + position.lng);
    //alert(position);
    });
    @endif



    map_{{ $floor }}.on('click', onMapClick);

    function onMapClick(e) {

        layerBounds.clearLayers();

        //alert(e.latlng);
        var coords = rc.project(e.latlng);
        var marker = new L.Marker(rc.unproject(coords), {draggable:true});
        marker.addTo(layerBounds);
        $('#position').val(Math.floor(coords.x) + '|' + Math.floor(coords.y));

        marker.on('dragend', function(e){
            var marker = e.target;
            var coords = rc.project(marker.getLatLng());
            //$('#position').val(position.lat + '|' + position.lng);
            $('#position').val(Math.floor(coords.x) + '|' + Math.floor(coords.y));
            //alert(position);
        });

    }




@endif

@if(!$editable)
    @foreach($project->logs as $logitem)
        @if(!empty($logitem->lat) && $logitem->bouwlaag_id == $floorplan->floor_id && ($logitem->floorplan_id == $floorplan->id || $logitem->floorplan_id === 0))
            var marker_{{ $logitem->id }} = L.circleMarker(rc.unproject([{{ $logitem->lat }}, {{ $logitem->lng }}])).bindPopup('<b>Doorvoer {{ $logitem->code }}</b><br/>@if(isset($logitem->photo->id))<img  width="200" height="200" src="/photoM/{{ $logitem->photo->id }}" />@endif').addTo(layerBounds);
        @endif
    @endforeach
@endif

@if(!empty($lat))
    map_{{ $floor }}.setView(rc.unproject([{{ $lat }}, {{ $lng }}]), 6);
    marker_{{ $log->id }}.openPopup();
@else
    // set the view centered ...
    map_{{ $floor }}.setView(rc.unproject([img[0]/2, img[1]/2]), 2);
@endif