

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

// set the view centered ...
map_{{ $floor }}.setView(rc.unproject([img[0]/2, img[1]/2]), 2);

// set marker at the image bound edges
var layerBounds = L.layerGroup();
map_{{ $floor }}.addLayer(layerBounds);


var markers = new L.FeatureGroup();
map_{{ $floor }}.addLayer(markers);

// the tile layer containing the image generated with gdal2tiles --leaflet ...
L.tileLayer('/documenten/{{ $year }}/{{ $project->id }}/plattegrond/{{ $floor }}/{z}/{x}/{y}.png', {
noWrap: true,
attribution: 'Plattegrond (c) Davelaarbouw B.V.',
}).addTo(map_{{ $floor }});



@if($log)

    var marker;

    @if(!empty($log->lat))
    var marker = L.marker([{{ $log->lat }}, {{ $log->lng }}], {draggable:true}).addTo(layerBounds);

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
        var marker = new L.Marker(e.latlng, {draggable:true});
        marker.addTo(layerBounds);
        $('#position').val(e.latlng.lat + '|' + e.latlng.lng);

        marker.on('dragend', function(e){
            var marker = e.target;
            var position = marker.getLatLng();
            $('#position').val(position.lat + '|' + position.lng);
            //alert(position);
        });

    }




@endif

@if(!$log)
    @foreach($project->logs as $log)
        @if(!empty($log->lat))
            var marker = L.marker([{{ $log->lat }}, {{ $log->lng }}]).addTo(layerBounds);
        @endif
    @endforeach
@endif