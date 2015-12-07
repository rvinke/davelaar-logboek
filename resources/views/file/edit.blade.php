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
            {!! Form::model($model, array('route' => ['file.update', $model->id], 'method' => 'PATCH', 'autocomplete'=>'off', 'class' => 'form-horizontal')) !!}

            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <h5>Projectdetails</h5>
                </div>



                <div class="ibox-content">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Alternatieve naam</label>
                        <div class="col-lg-4">
                            {!! Form::text('alt_name', NULL, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>

                <div class="ibox-footer">

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <span class="pull-right">
                                <button class="btn btn-primary" type="submit">Bewaar</button>
                                <button class="btn btn-outline btn-danger delete-button" type="button">Verwijder dit item</button>
                            </span>
                        </div>
                    </div>

                </div>
            </div>


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
            window.location.replace('{!! URL::route('subdatabase.delete', ['id' => $model->id]) !!}');
        });
    });
</script>
@endpush
