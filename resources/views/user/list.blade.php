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
                        <a href="{!! URL::route('user.create') !!}" class="btn btn-primary btn-xs pull-right">Voeg een gebruiker toe</a>
                        <h5>Aanwezige gebruikers</h5>
                    </div>
                </div>



                <div class="ibox-content">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered table-hover" id="projects-table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Achternaam</th>
                                    <th>Voornaam</th>
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
            ajax: '{!! route('api.user.v1') !!}',
            columns: [
                { data: 'action', name: 'action' },
                { data: 'last_name', name: 'last_name' },
                { data: 'first_name', name: 'first_name' }
            ],
            language: {"url":"\/\/cdn.datatables.net\/plug-ins\/a5734b29083\/i18n\/Dutch.json"},
        });
    });
</script>
@endpush
