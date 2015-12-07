<div class="ibox float-e-margins">

    <div class="ibox-title">
        <h5>Projectdetails</h5>
    </div>



    <div class="ibox-content">

        <div class="form-group">
            <label class="col-lg-2 control-label">Projectnaam</label>
            <div class="col-lg-4">
                {!! Form::text('naam', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Projectnummer</label>
            <div class="col-lg-4">
                {!! Form::text('projectnummer', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Opdrachtgever</label>
            <div class="col-lg-4">
                {!! Form::select('opdrachtgever_id', $clients, NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Adres</label>
            <div class="col-lg-4">
                {!! Form::textarea('adres', NULL, array('class' => 'form-control', 'style' => 'height: 50px;')) !!}
                <span class="help-block m-b-none small">Als het adres leeg gelaten wordt dan wordt het adres van de opdrachtgever gebruikt.</span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Onderwerp</label>
            <div class="col-lg-4">
                {!! Form::text('onderwerp', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Onze referentie</label>
            <div class="col-lg-4">
                {!! Form::text('referentie', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Datum oplevering</label>
            <div class="col-lg-4">
                <div class="input-group date">
                    {!! Form::text('datum_oplevering', NULL, ['class' => 'form-control']) !!}<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
            </div>
        </div>

        <div class="hr-line-dashed"></div>
        <h4>Locaties</h4>

        <div class="clone-wrapper">
            @if(!$is_new)

                <p>Locaties zijn te bewerken via het submenu "Locaties".</p>
                {{--

                @forelse($project->locations as $location)

                    <div class="form-group toclone">
                        <label class="col-lg-2 control-label">Naam</label>
                        <div class="col-lg-4">
                            {!! Form::text('locations[naam][]', $location->naam, ['class' => 'form-control']) !!}
                        </div>

                        <i class="clone fa fa-plus-circle"></i>
                        <i class="delete fa fa-minus-circle"></i>

                    </div>

                @empty

                    <div class="form-group toclone">
                        <label class="col-lg-2 control-label">Naam</label>
                        <div class="col-lg-4">
                            {!! Form::text('locations[naam][]', NULL, ['class' => 'form-control']) !!}
                        </div>

                        <i class="clone fa fa-plus-circle"></i>
                        <i class="delete fa fa-minus-circle"></i>

                    </div>

                @endforelse

                --}}

            @else

                <div class="form-group toclone">
                    <label class="col-lg-2 control-label">Naam</label>
                    <div class="col-lg-4">
                        {!! Form::text('locations[naam][]', NULL, ['class' => 'form-control']) !!}


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
                    <button class="btn btn-primary" type="submit">Bewaar</button>
                </span>
            </div>
        </div>

    </div>

</div>




