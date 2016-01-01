<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Plattegronden</h5>
            </div>
            <div class="ibox-content">
                @foreach($project->maps as $floorplan)
                <a href="{!! URL::route('rapport.floorplan', ['id' => $project->id, 'floor' => $floorplan->floor_id]) !!}" class="btn btn-info">Verdieping {{ \App\Models\Floor::findOrFail($floorplan->floor_id)->naam }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>