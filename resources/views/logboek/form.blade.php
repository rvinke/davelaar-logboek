<div class="ibox float-e-margins">

    <div class="ibox-title">
        <h5>Projectdetails</h5>
    </div>



    <div class="ibox-content">

        {!! Form::hidden('project_id', $project->id) !!}

        <div class="form-group">
            <label class="col-lg-2 control-label">Locatie</label>
            <div class="col-lg-4">
                {!! Form::select('locatie_id', $locations, NULL, array('class' => 'form-control')) !!}
            </div>
        </div>


        <div class="form-group">
            <label class="col-lg-2 control-label">Bouwlaag</label>
            <div class="col-lg-4">
                {!! Form::select('bouwlaag_id', App\Models\Floor::orderBy('naam')->lists('naam', 'id'), NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Wand/vloer</label>
            <div class="col-lg-4">
                {!! Form::select('oppervlak_type_id', ['Wand', 'Vloer'], NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Eis (minuten)</label>
            <div class="col-lg-4">
                {!! Form::select('eis', [20 => '20', 30 => '30', 60 => '60', 90 => '90'], NULL, array('class' => 'form-control')) !!}
            </div>
        </div>


        <div class="form-group">
            <label class="col-lg-2 control-label">Product</label>
            <div class="col-lg-4">
                {!! Form::select('product_id', $products, NULL, array('class' => 'form-control')) !!}
            </div>
        </div>


        <div class="form-group">
            <label class="col-lg-2 control-label">Brandklep</label>
            <div class="col-lg-4">
                {!! Form::select('brandklep_id', $brandkleppen, NULL, array('class' => 'form-control', 'placeholder' => 'Selecteer een optie...')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Commentaar</label>
            <div class="col-lg-4">
                {!! Form::textarea('commentaar', NULL, array('class' => 'form-control', 'style' => 'height: 50px;')) !!}
            </div>
        </div>


        <div class="form-group">
            <label class="col-lg-2 control-label">Foto</label>
            <div class="col-lg-4">
                {!! Form::file('foto', ['accept' => 'image/*', 'capture' => 'camera']) !!}
            </div>
        </div>

        <div class="hr-line-dashed"></div>
        <h4>Doorvoeren</h4>

        <div class="clone-wrapper">


                @if(!$is_new)

                    @forelse($log->passthroughs as $passthrough)
                    <div class="toclone">
                    <div class="form-group">
                            <label class="col-lg-2 control-label">Doorvoertype</label>
                            <div class="col-lg-4">
                                {!! Form::select('passthroughs[passthrough_type_id][]', $passthroughs, $passthrough->passthrough_type_id, ['class' => 'form-control']) !!}


                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Aantal doorvoer</label>
                            <div class="col-lg-4">
                                {!! Form::select('passthroughs[count][]', [1,2,3,4,5,6,7,8,9], $passthrough->count - 1, ['class' => 'form-control']) !!}


                            </div>



                        </div>

                        <i class="clone fa fa-plus-circle"></i>
                        <i class="delete fa fa-minus-circle"></i>
                    </div>
                    @empty
                    <div class="toclone">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Doorvoertype</label>
                            <div class="col-lg-4">
                                {!! Form::select('passthroughs[passthrough_type_id][]', $passthroughs, NULL, ['class' => 'form-control']) !!}


                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Aantal doorvoer</label>
                            <div class="col-lg-4">
                                {!! Form::select('passthroughs[count][]', [1,2,3,4,5,6,7,8,9], NULL, ['class' => 'form-control']) !!}


                            </div>



                        </div>

                        <i class="clone fa fa-plus-circle"></i>
                        <i class="delete fa fa-minus-circle"></i>
                    </div>
                    @endforelse
                @else
                <div class="toclone">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Doorvoertype</label>
                        <div class="col-lg-4">
                            {!! Form::select('passthroughs[passthrough_type_id][]', $passthroughs, NULL, ['class' => 'form-control']) !!}


                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Aantal doorvoer</label>
                        <div class="col-lg-4">
                            {!! Form::select('passthroughs[count][]', [1,2,3,4,5,6,7,8,9], NULL, ['class' => 'form-control']) !!}


                        </div>



                    </div>

                    <i class="clone fa fa-plus-circle"></i>
                    <i class="delete fa fa-minus-circle"></i>
                </div>
                @endif



        </div>
    </div>





    <div class="ibox-footer">

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">

                <span class="pull-right">
                    <button class="btn btn-primary" type="submit">Bewaar en naar stap 2</button>
                    @if(!$is_new)
                        <button class="btn btn-outline btn-danger delete-button" type="button">Verwijder dit log-item</button>
                    @endif
                </span>
            </div>
        </div>

    </div>

</div>



@push('scripts')
<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="/js/plugins/cloneya/jquery-cloneya.min.js"></script>
<script src="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js"></script>
<script>
    var mapMinZoom = 0;
    var mapMaxZoom = 4;
    var map = L.map('map', {
        maxZoom: mapMaxZoom,
        minZoom: mapMinZoom,
        crs: L.CRS.Simple
    }).setView([0, 0], 2);

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


    @if(!$is_new && !empty($log->lat))
        var marker = L.marker([{{ $log->lat }}, {{ $log->lng }}]).addTo(map);
    @endif


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



    @if(!$is_new)
    $('.delete-button').click(function () {
        swal({
            title: "Bevestiging",
            text: "Weet u zeker dat dit log-item verwijderd moet worden?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: 'Nee',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ja",
            closeOnConfirm: false
        }, function () {
            //swal("Verwijderd", "Het item is verwijderd", "success");
            window.location.replace('{!! URL::route('log.delete', ['id' => $log->id]) !!}');
        });
    });
    @endif

    $('.clone-wrapper').cloneya({
        cloneButton     : '.clone',
        deleteButton    : '.delete'
    });

</script>
@endpush




