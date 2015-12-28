@extends('app')

{{-- Content --}}
@section('content')

    <div class="row">
        <div class="col-lg-12">

            {{--
            @if($errors->has())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger alert-dark">{{ $error }}</div>
                @endforeach
            @endif
            --}}

            {!! Form::open(array('route' => ['floorplan.store', $project_id], 'autocomplete'=>'off', 'class' => 'form-horizontal', 'files' => true)) !!}
            @include('floorplan.form', array('is_new'=>true, 'is_profile'=>false) )
            {!! Form::close() !!}

        </div>
    </div>
@stop

@push('styles')
<link rel="stylesheet" href="/css/plugins/datapicker/datepicker3.css" />
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" />
@endpush


