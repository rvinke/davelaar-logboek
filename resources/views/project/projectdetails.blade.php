@extends('app')

@section('title')
    Projectdetails
@endsection


@section('breadcrumb')

@endsection


{{-- Content --}}
@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif





    @include('project.parts.projectgegevens', ['show_edit_link' => true])


    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Opties</h5>
                </div>
                <div class="ibox-content">
                    <p>
                    <a href="{!! URL::route('log.create', ['project_id' => $project->id]) !!}" class="btn btn-primary">Voeg een logboek-item toe</a>
                    <a href="#totalen" class="btn btn-info">Bekijk totalen</a>

                    <a href="{!! URL::route('file.create', ['project_id' => $project->id]) !!}" class="btn btn-info">Bestanden toevoegen</a>
                    <a href="{!! URL::route('floorplan.create', ['project_id' => $project->id]) !!}" class="btn btn-info">Plattegrond toevoegen</a>
                    <a href="{!! URL::route('project.users', ['project_id' => $project->id]) !!}" class="btn btn-info">Gebruikers koppelen</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('project.parts.plattegronden')


    @include('project.parts.files')

    <div class="row">

        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Log-items</h5>
                </div>
                <div class="ibox-content table-responsive">
                    <table class="table table-striped" id="logs-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Code</th>
                                <th>Locatie</th>
                                <th>Bouwlaag</th>
                                <th>Product</th>
                                <th>Doorvoeren</th>
                                <th>Commentaar</th>
                            </tr>
                        </thead>
                        {{-- datatable --}}
                    </table>

                </div>
            </div>
        </div>
    </div>

    @foreach($project->logs as $log)
        @foreach($log->passthroughs as $passthrough)
            @if($passthrough->passthrough_type_id != 0)
                <?php

                if(!isset($count_passthrough[$passthrough->passthrough_type_id])) {
                    $count_passthrough[$passthrough->passthrough_type_id] = $passthrough->count;
                } else {
                    $count_passthrough[$passthrough->passthrough_type_id] += $passthrough->count;
                }

                ?>
            @endif
        @endforeach
    @endforeach

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
@stop

@push('styles')
<!-- Data Tables -->
<link href="/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<link href="/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
<link href="/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">
@endpush


@push('scripts')
<!-- Data Tables -->
<script src="/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="/js/plugins/dataTables/dataTables.responsive.js"></script>
<script src="/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
<script>
    $(function() {
        $('#logs-table').DataTable({
            dom: 'lfigtp',

            processing: true,
            serverSide: true,


            ajax: '{!! route('api.logs.v1', [$project->id]) !!}',

            columns: [
                { data: 'verbroken', name: 'verbroken', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'code', name: 'code' },
                { data: 'location.naam', name: 'location.naam', searchable: true, orderable: false},
                { data: 'floor.naam', name: 'floor.naam', searchable: true, orderable: false},
                { data: 'system', name: 'system.naam', searchable: false, orderable: false},
                { data: 'passthroughs', name: 'passthroughs', searchable: false, orderable: false},
                { data: 'commentaar', name: 'commentaar'}

            ],


            language: {"url":"\/\/cdn.datatables.net\/plug-ins\/a5734b29083\/i18n\/Dutch.json"},
        });
    });
</script>
@endpush
