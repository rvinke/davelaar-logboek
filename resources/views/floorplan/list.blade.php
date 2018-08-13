@extends('app')

{{-- Content --}}
@section('content')

    <div class="row">
        <div class="col-lg-12">

            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <a href="{!! \URL::route('projecten.show', ['id' => $project->id]) !!}" style="margin-right: 10px;" class="btn btn-primary btn-xs pull-right">Terug naar het project</a>
                    <h5>Aanwezige plattegronden</h5>
                </div>



                <div class="ibox-content">
                    @foreach($project->locations as $location)
                        <h2><small>{{ $location->naam }}</small></h2>
                        <table>
                            <thead>
                            <tr>
                                <th style="width: 140px;">Plattegrond</th>
                                <th style="width: 120px">Status</th>
                                <th style="width: 140px">Datum</th>
                                <th>Actie</th>
                            </tr>
                            </thead>

                        @forelse($project->mapsWithTrashed->where('location_id', $location->id) as $floorplan)

                            <tr>
                                <td>
                                    Verdieping {{ \App\Models\Floor::findOrFail($floorplan->floor_id)->naam }}
                                </td>
                                <td>
                                    @if($floorplan->deleted_at)
                                        Uitgeschakeld
                                    @elseif($floorplan->ready)
                                        Actief
                                    @elseif(!$floorplan->ready)
                                        In bewerking
                                    @endif
                                </td>
                                <td>
                                    {{ $floorplan->created_at }}
                                </td>
                                <td>
                                    @if($floorplan->deleted_at)
                                        <a href="{!! \URL::route('floorplan.enable', ['id' => $floorplan->id]) !!}">Inschakelen</a><br />
                                    @else
                                        <a href="{!! \URL::route('floorplan.delete', ['id' => $floorplan->id]) !!}" class="delete-button">Uitschakelen</a><br />
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Geen plattegronden aanwezig voor deze locatie</td>
                            </tr>
                        @endforelse
                        </table>
                    @endforeach

                </div>

            </div>

        </div>
    </div>
@stop


@push('styles')
    <link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
@endpush


@push('scripts')
    <script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        $('.delete-button').click(function (e) {
            e.preventDefault();
            var link = $(this).attr('href');

            swal({
                title: "Bevestiging",
                text: "Weet u zeker dat deze plattegrond uitgeschakeld moet worden?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: 'Nee',
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ja",
                closeOnConfirm: false
            }, function () {
                window.location.href = link;
            });
        });
    </script>
@endpush
