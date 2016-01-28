@extends('app')

@section('content')
<div class="row border-bottom white-bg dashboard-header">

    <div class="col-sm-3">
        <h2>Welkom {!! Auth::user()->first_name !!}</h2>
        <p>Welkom in het logboek van Davelaarbouw B.V.</p>
        <p>Er @if($reports->count() == 1) is @else zijn @endif
            op dit moment <span class="label label-success">{{ $reports->count() }}</span> verbroken
             @if($reports->count() != 1)brandscheidingen. @else brandscheiding. @endif</p>

    </div>
    <div class="col-sm-6">

    </div>
    <div class="col-sm-3">

    </div>

</div>

@endsection
