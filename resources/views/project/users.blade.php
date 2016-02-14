@extends('app')

@section('content')
    {!! Form::open(['route' => ['project.users.store', $project->id], 'class' => 'form-horizontal']) !!}
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <div class="m-b-md">
                        <h5>Koppel gebruikers met rol opdrachtgever</h5>
                    </div>
                </div>

                <div class="ibox-content">

                    @foreach($users as $user)
                        @if($user->hasRole('opdrachtgever'))
                        <div class="form-group">
                            <label class="col-lg-2 control-label">{{ $user->first_name }} {{ $user->last_name }}</label>
                            <div class="col-lg-4">
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" @if($user->projects->contains($project->id))checked @endif name="switch[]" value="{{ $user->id }}" class="onoffswitch-checkbox" id="switch_{{ $user->id }}">
                                        <label class="onoffswitch-label" for="switch_{{ $user->id }}">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endif
                    @endforeach

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
@stop