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
                            <tr>
                                <td><a href="{!! URL::route('log.edit', ['id' => $log->id]) !!}"><i style="font-size: 1.2em;" class="fa fa-edit fa-large"></i></a></td>
                                <td>{{ $log->code }}</td>
                                <td>{{ $log->location->naam }}</td>
                                <td>{{ $log->floor->naam }}</td>
                                <td>
                                    @if($log->product_id != 0)
                                        <abbr title="{{ $log->system->naam }}">{{ $log->system->leverancier.' '.$log->system->productnummer }}</abbr></td>
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
@stop

@push('styles')

@endpush


@push('scripts')

@endpush
