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

                    {!! Form::model($log, array('route' => 'log.update', 'autocomplete'=>'off', 'class' => 'form-horizontal')) !!}
                    @include('logboek.form', array('is_new'=>false, 'is_profile'=>false) )
                    {!! Form::close() !!}



        </div>
    </div>
@stop

@push('styles')
<link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
@endpush


@push('scripts')
<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="/js/plugins/cloneya/jquery-cloneya.min.js"></script>
<script>
    $('.delete-button').click(function () {
        swal({
            title: "Bevestiging",
            text: "Weet u zeker dat dit log-item verwijderd moet worden?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: 'Nee',
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Ja",
            closeOnConfirm: false
        }, function () {
            //swal("Verwijderd", "Het item is verwijderd", "success");
            window.location.replace('{!! URL::route('log.delete', ['id' => $log->id]) !!}');
        });
    });

    $('.clone-wrapper').cloneya({
        cloneButton     : '.clone',
        deleteButton    : '.delete'
    });

</script>
@endpush
