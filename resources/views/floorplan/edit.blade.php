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

            {!! Form::model($log, array('route' => ['log.update', $log->id], 'method' => 'PATCH', 'autocomplete'=>'off', 'class' => 'form-horizontal')) !!}
            @include('logboek.form', array('is_new'=>false, 'is_profile'=>false) )
            {!! Form::close() !!}

        </div>
    </div>
@stop

@push('styles')
<link rel="stylesheet" href="/css/plugins/sweetalert/sweetalert.css" />
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" />
@endpush



