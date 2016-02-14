<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Plattegronden</h5>
            </div>
            <div class="ibox-content">
                @foreach($project->locations as $location)
                    <h2><small>{{ $location->naam }}</small></h2>
                    @forelse($project->maps->where('location_id', $location->id) as $floorplan)
                        <a href="{!! URL::route('rapport.floorplan', ['id' => $project->id, 'location' => $floorplan->location_id, 'floor' => $floorplan->floor_id]) !!}" class="btn btn-xs btn-info">Verdieping {{ \App\Models\Floor::findOrFail($floorplan->floor_id)->naam }}</a>
                    @empty
                        Geen plattegronden aanwezig voor deze locatie
                    @endforelse
                @endforeach
            </div>
        </div>
    </div>
</div>