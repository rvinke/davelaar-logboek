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

        <div class="hr-line-dashed"></div>
        <h4>Doorvoeren</h4>

        <div class="clone-wrapper">

            <div class="toclone">
                @if(!$is_new)

                    @forelse($log->passthroughs as $passthrough)
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

                    @empty
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
                    @endforelse
                @else

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
                @endif
            </div>


        </div>
    </div>

    <div class="ibox-footer">

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">

                <span class="pull-right">
                    <button class="btn btn-primary" type="submit">Bewaar</button>
                    @if(!$is_new)
                        <button class="btn btn-outline btn-danger delete-button" type="button">Verwijder dit log-item</button>
                    @endif
                </span>
            </div>
        </div>

    </div>

</div>






