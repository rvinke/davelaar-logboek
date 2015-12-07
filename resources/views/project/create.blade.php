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

                    {!! Form::open(array('route' => 'projecten.store', 'autocomplete'=>'off', 'class' => 'form-horizontal')) !!}
                    @include('project.form', array('is_new'=>true, 'is_profile'=>false) )
                    {!! Form::close() !!}



        </div>
    </div>
@stop

@push('styles')
<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
@endpush


@push('scripts')
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="/js/plugins/cloneya/jquery-cloneya.min.js"></script>
<script>
    $('.input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate: '0d',
        daysOfWeekDisabled: '0,6'
    });

    $('.clone-wrapper').cloneya({
        cloneButton     : '.clone',
        deleteButton    : '.delete'
    });

</script>
@endpush
