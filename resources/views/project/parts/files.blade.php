

<div class="row">
    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Tekeningen</h5>
            </div>
            <div class="ibox-content">
                <?php $i = 1 ?>
                @foreach($project->floorplans as $floorplan)
                    <a href="{!! URL::route('file.edit', ['id' => $floorplan->id]) !!}">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>

                    <a href="{!! URL::route('file.download', ['id' => $floorplan->id]) !!}">
                    @if(!empty($floorplan->alt_name))
                        {{ $floorplan->alt_name }}<br />
                    @else
                        Plattegrond {{ $i }}<br />
                    @endif
                    </a>
                    <?php $i++ ?>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="ibox">
            <div class="ibox-title">
                <h5>Rapporten</h5>
            </div>
            <div class="ibox-content">
                <?php $i = 1 ?>
                @foreach($project->reports as $report)
                    <a href="{!! URL::route('file.edit', ['id' => $report->id]) !!}">
                        <i class="fa fa-pencil-square-o"></i>
                    </a>

                    <a href="{!! URL::route('file.download', ['id' => $report->id]) !!}">
                    @if(!empty($report->alt_name))
                        {{ $report->alt_name }}<br />
                    @else
                        Rapport {{ $i }}<br />
                    @endif
                    </a>
                    <?php $i++ ?>
                @endforeach
            </div>
        </div>
    </div>
</div>