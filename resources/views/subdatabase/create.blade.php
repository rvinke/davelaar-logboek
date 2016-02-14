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

            {!! Form::open(array('route' => ['subdatabase.store', $subdatabase], 'autocomplete'=>'off', 'class' => 'form-horizontal', 'files' => TRUE)) !!}
            @include('subdatabase.form', array('is_new'=>true))
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
