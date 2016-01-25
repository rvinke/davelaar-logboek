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

                    {!! Form::model($model, array('route' => ['subdatabase.update', $subdatabase, $model->id], 'method' => 'PATCH', 'autocomplete'=>'off', 'class' => 'form-horizontal', 'files' => TRUE)) !!}
                    @include('subdatabase.form', array('is_new'=>false) )
                    {!! Form::close() !!}



        </div>
    </div>
@stop

@push('styles')
<link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
@endpush


@push('scripts')
<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
<script>
    $('.delete-button').click(function () {
        swal({
            title: "Bevestiging",
            text: "Weet u zeker dat dit item verwijderd moet worden?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: 'Nee',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ja",
            closeOnConfirm: false
        }, function () {
            //swal("Verwijderd", "Het item is verwijderd", "success");
            window.location.replace('{!! URL::route('subdatabase.delete', ['subdatabase' => $subdatabase, 'id' => $model->id]) !!}');
        });
    });
</script>
@endpush
