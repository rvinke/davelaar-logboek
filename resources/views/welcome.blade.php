@extends('app')

@section('content')
<div class="row">

    <div class="col-sm-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">

                <h5>Welkom</h5>
            </div>
            <div class="ibox-content" style="height: 100px;">
                Welkom in het logboek van Davelaarbouw B.V.
            </div>
        </div>


    </div>

    <div class="col-sm-3">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Uw profiel</h5>
            </div>
            <div class="ibox-content" style="height: 100px;">
                Ga naar uw <a href="{!! URL::route('user.show') !!}">profiel</a> om uw gegevens (zoals uw wachtwoord) aan te passen.
            </div>
        </div>

    </div>

    <div class="col-sm-3">

    </div>

</div>

<div class="row">
    <div class="col-sm-3">
        <div class="widget style1 red-bg">
            <div class="row">
                <div class="col-xs-4">
                    <i class="fa fa-exclamation-triangle fa-5x"></i>
                </div>
                <div class="col-xs-8 text-right">
                    <span> Verbroken brandscheidingen </span>
                    <h2 class="font-bold">{{ $reports->count() }}</h2>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
