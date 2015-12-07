<div class="ibox float-e-margins">

    <div class="ibox-title">
        <h5>Gebruikersdetails</h5>
    </div>



    <div class="ibox-content">




        <div class="form-group">
            <label class="col-lg-2 control-label">Voornaam</label>
            <div class="col-lg-4">
                {!! Form::text('first_name', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Achternaam</label>
            <div class="col-lg-4">
                {!! Form::text('last_name', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">e-Mailadres</label>
            <div class="col-lg-4">
                {!! Form::email('email', NULL, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Opdrachtgever</label>
            <div class="col-lg-4">
                {!! Form::select('client_id', \App\Models\Client::lists('naam', 'id'), NULL, ['class' => 'form-control', 'placeholder' => 'Maak eventueel een keuze']) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label">Rol</label>
            <div class="col-lg-4">
                @if($is_new)
                    {!! Form::select('role', \App\Models\Role::lists('display_name', 'id'), NULL, array('class' => 'form-control')) !!}
                @else
                    {!! Form::select('role', \App\Models\Role::lists('display_name', 'id'), $user->roles->first()->id, array('class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        @if($is_new OR Auth::user()->id == $user->id)
            <h4>Nieuw wachtwoord</h4>
            <div class="form-group">
                <label class="col-lg-2 control-label">Wachtwoord</label>
                <div class="col-lg-4">
                    {!! Form::password('password', array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label">Wachtwoord (controle)</label>
                <div class="col-lg-4">
                    {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
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
                        <!--<button class="btn btn-outline btn-danger delete-button" type="button">Verwijder dit item</button>-->
                    @endif
                </span>
            </div>
        </div>

    </div>

</div>






