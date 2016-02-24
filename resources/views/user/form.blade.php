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
                <span class="help-block m-b-none">Het e-mailadres is ook de gebruikersnaam</span>
            </div>
        </div>

        @if(!$limited)
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
        @endif



        @if($is_new OR Auth::user()->id == $user->id OR Auth::user()->hasRole('admin'))
            <h4>Nieuw wachtwoord</h4>
            <div class="form-group">
                <label class="col-lg-2 control-label">Wachtwoord</label>
                <div class="col-lg-4">
                    {!! Form::password('password', array('class' => 'form-control')) !!}
                    <span class="help-block m-b-none">Het wachtwoord wordt niet aangepast als dit veld leeg is</span>
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
                    @if(!$is_new)
                        <button class="btn btn-outline btn-danger delete-button" type="button">Schakel deze gebruiker uit</button>
                    @endif
                    <button class="btn btn-primary" type="submit">Bewaar</button>
                </span>
            </div>
        </div>

    </div>

</div>






