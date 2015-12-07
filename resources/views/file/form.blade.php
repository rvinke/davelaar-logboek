<div class="ibox float-e-margins">

    <div class="ibox-title">
        <h5>Projectdetails</h5>
    </div>



    <div class="ibox-content">

        <div class="form-group">
            <label class="col-lg-2 control-label">Bestandstype</label>
            <div class="col-lg-4">
                {!! Form::select('type', ['foto' => 'Foto\'s', 'tekening' => 'Tekening', 'rapport' => 'Rapport'], NULL, array('class' => 'form-control')) !!}
            </div>
        </div>



        <div class="form-group">
            <label class="col-lg-2 control-label">Bestand</label>
            <div class="col-lg-4">
                {!! Form::file('file', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>





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






