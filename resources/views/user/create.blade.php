@extends('app')

{{-- Content --}}
@section('content')

    <div class="row">
        <div class="col-lg-12">

            @if($errors->any())
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger alert-dark">{!! ucfirst($error) !!}</div>
                @endforeach
            @endif


            {!! Form::open(array('route' => ['user.store'], 'autocomplete'=>'off', 'class' => 'form-horizontal')) !!}
            @include('user.form', array('is_new'=>true))
            {!! Form::close() !!}

        </div>
    </div>
@stop

@push('styles')

@endpush


@push('scripts')
<script>

</script>
@endpush
