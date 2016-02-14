@extends('app')

@section('title')
    Projectrapport
@endsection


@section('breadcrumb')

@endsection


{{-- Content --}}
@section('content')
    <div class="row">
        <div class="col-lg-12">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('project.parts.projectgegevens')

        </div>
    </div>

    @include('project.parts.plattegronden')

    <div class="row">

        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Log-items</h5>
                </div>
                <div class="ibox-content table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Code</th>
                                <th>Locatie</th>
                                <th>Bouwlaag</th>
                                <th>Product</th>
                                <th>Doorvoeren</th>
                                <th>Commentaar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->logs as $log)

                            <tr @if($log->reports->count() > 0) style="color: #f00; font-weight: bold" @endif>
                                <td>
                                    @if(isset($log->photo->id))
                                        <a href="/photoL/{{ $log->photo->id }}" data-gallery>
                                        <img class="lazy" width="50" height="50" data-original="/photo/{{ $log->photo->id }}" />
                                        </a>
                                    @endif


                                </td>
                                <td>
                                    <a href="{{ URL::route('log.map-show', ['id' => $log->id]) }}">{{ $log->code }}</a>
                                </td>

                                <td>{{ $log->location->naam }}</td>
                                <td>{{ $log->floor->naam }}</td>
                                <td>
                                    @if($log->product_id != 0)
                                        <abbr title="{{ $log->system->naam }}">{{ $log->system->leverancier.' '.$log->system->productnummer }}</abbr>
                                    @endif
                                <td>
                                    @foreach($log->passthroughs as $passthrough)
                                        @if($passthrough->passthrough_type_id != 0)
                                            <?php

                                                if(!isset($count_passthrough[$passthrough->passthrough_type_id])) {
                                                    $count_passthrough[$passthrough->passthrough_type_id] = $passthrough->count;
                                                } else {
                                                    $count_passthrough[$passthrough->passthrough_type_id] += $passthrough->count;
                                                }

                                             ?>
                                            {{ $passthrough->count }}x {{ $passthrough->passthrough_type->naam }}<br />
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $log->commentaar }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p>Een <span style="color:#f00; font-weight: bold">rood</span> logitem is gemeld als verbroken.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Totalen</h5>
                    <a name="totalen"></a>
                </div>
                <div class="ibox-content table-responsive">

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Aantal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($count_passthrough as $type => $count)

                            <tr><td>{!! App\Models\PassthroughType::findOrFail($type)->naam !!}</td><td>{{ $count }}</td></tr>
                        @empty
                            <tr><td colspan="2">Geen items aanwezig</td></tr>
                        @endforelse
                    </table>


                </div>
            </div>
        </div>
    </div>

    <div id="blueimp-gallery" class="blueimp-gallery">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
@stop

@push('styles')
<link href="/css/plugins/blueimp/css/blueimp-gallery.min.css" rel="stylesheet">
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" />
@endpush


@push('scripts')
<script src="/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
<script src="/js/rastercoords.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>

<script>

    $(function() {
        $("img.lazy").lazyload();
    });

</script>
@endpush
