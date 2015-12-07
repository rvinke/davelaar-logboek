@extends('app')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">

            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <div class="m-b-md">
                        @if(!$rapporten)
                            <a href="{!! URL::route('projecten.create') !!}" class="btn btn-primary btn-xs pull-right">Voeg een project toe</a>
                        @endif
                        <h5>Aanwezige projecten</h5>
                    </div>
                </div>

                <div class="ibox-content">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered table-hover" id="projects-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Naam</th>
                                    <th>Status</th>
                                    <th>Aantal log-items</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

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
        $('#projects-table').DataTable({
            dom: 'lfigtp',

            processing: true,
            serverSide: true,

            @if($rapporten)
                ajax: '{!! route('api.rapporten.v1') !!}',
            @else
                ajax: '{!! route('api.projecten.v1') !!}',
            @endif
            columns: [
                { data: 'action', name: 'action' },
                { data: 'naam', name: 'naam' },
                { data: 'status', name: 'status'},
                { data: 'count_logs', name: 'count_logs'}
            ],
            language: {"url":"\/\/cdn.datatables.net\/plug-ins\/a5734b29083\/i18n\/Dutch.json"},
        });
    });
</script>
@endpush
