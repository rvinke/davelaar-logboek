<div class="ibox float-e-margins">

    <div class="ibox-title">
        <h5>Projectdetails</h5>
    </div>



    <div class="ibox-content">




        <div class="form-group">
            <label class="col-lg-2 control-label">Naam</label>
            <div class="col-lg-4">
                {!! Form::text('naam', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>


        @if($subdatabase == 'system')

            <div class="form-group">
                <label class="col-lg-2 control-label">Leverancier</label>
                <div class="col-lg-4">
                    {!! Form::text('leverancier', NULL, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label">Productnummer</label>
                <div class="col-lg-4">
                    {!! Form::text('productnummer', NULL, array('class' => 'form-control')) !!}
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg-2 control-label">Documentatie</label>
                <div class="col-lg-4">
                    {!! Form::file('documentatie', ['class' => 'form-control']) !!}
                    <span class="help-block m-b-none">
                        @if(!empty($model->documentatie))
                            <i><a href="{!! \Illuminate\Support\Facades\URL::route('documentatie.download', $model->id) !!}" target="_blank">Document aanwezig</a></i>
                        @endif
                    </span>
                </div>
            </div>

        @endif


        @if($subdatabase == 'client')

            <div class="form-group">
                <label class="col-lg-2 control-label">Adres</label>
                <div class="col-lg-4">
                    {!! Form::text('adres', NULL, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label">Postcode</label>
                <div class="col-lg-4">
                    {!! Form::text('postcode', NULL, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label">Woonplaats</label>
                <div class="col-lg-4">
                    {!! Form::text('woonplaats', NULL, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label">Telefoonnummer</label>
                <div class="col-lg-4">
                    {!! Form::text('telefoonnummer', NULL, array('class' => 'form-control')) !!}
                </div>
            </div>
        @endif


    </div>

    <div class="ibox-footer">

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">

                <span class="pull-right">
                    <button class="btn btn-primary" type="submit">Bewaar</button>
                    @if(!$is_new)
                        <button class="btn btn-outline btn-danger delete-button" type="button">Verwijder dit item</button>
                    @endif
                </span>
            </div>
        </div>

    </div>

</div>






