@extends('app')

{{-- Content --}}
@section('content')

    <div class="row">
        <div class="col-lg-12">

            {!! Form::open(array('route' => ['qr-code.generate'], 'autocomplete'=>'off', 'class' => 'form-horizontal')) !!}
            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <h5>QR-codes genereren</h5>
                </div>



                <div class="ibox-content">
                    <p>Met behulp van dit formulier kan een set QR-codes gegenereerd worden. De codes worden in een .zip-document vervolgens
                    gedownload, welke verzonden kan worden naar de drukker. <br /><br />Het genereren van grote batches kan wat tijd in beslag nemen. Het
                    systeem genereert gemiddeld <b>{{ round($average,1) }}</b> codes per seconde.<br /><br /></p>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Aantal codes:</label>
                        <div class="col-lg-4">
                            {!! Form::text('count', NULL, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                </div>

                <div class="ibox-footer">

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">

                            <span class="pull-right">
                                <button class="btn btn-primary" type="submit">Bewaar</button>
                            </span>
                        </div>
                    </div>

                </div>

            </div>
            {!! Form::close() !!}
        </div>
    </div>

@stop


@push('scripts')
<script>
    $('.btn').one('submit', function() {
        $(this).attr('disabled','disabled');
    });
</script>
@endpush



