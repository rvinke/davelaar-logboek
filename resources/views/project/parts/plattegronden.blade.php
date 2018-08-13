<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                @if(isset($show_edit_link))
                    <a href="{!! \URL::route('floorplans', ['id' => $project->id]) !!}" style="margin-right: 10px;" class="btn btn-primary btn-xs pull-right">Bewerk plattegronden</a>
                @endif
                <h5>Plattegronden</h5>
            </div>
            <div class="ibox-content">
                @foreach($project->locations as $location)
                    <h2><small>{{ $location->naam }}</small></h2>
                    @forelse($project->maps->where('location_id', $location->id) as $floorplan)
                        @if($floorplan->ready == 1)
                            <a href="{!! URL::route('rapport.floorplan', ['id' => $project->id, 'location' => $floorplan->location_id, 'floor' => $floorplan->floor_id]) !!}" class="btn btn-xs btn-info">Verdieping {{ \App\Models\Floor::findOrFail($floorplan->floor_id)->naam }}</a>
                        @else
                            <button type="button" disabled class="btn btn-xs btn-outline">Verdieping {{ \App\Models\Floor::findOrFail($floorplan->floor_id)->naam }} <i class="fa fa-cogs"></i></button>
                        @endif

                    @empty
                        Geen plattegronden aanwezig voor deze locatie
                    @endforelse
                @endforeach
            </div>
        </div>
    </div>
</div>