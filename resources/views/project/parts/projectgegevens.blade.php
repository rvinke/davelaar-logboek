<div class="row">

    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="m-b-md">
                                <a href="{!! \URL::route('rapport.print', ['id' => $project->id]) !!}" class="btn btn-primary btn-xs pull-right">Print rapport</a>
                            @if(isset($show_edit_link))
                                <a href="{!! \URL::route('projecten.edit', ['id' => $project->id]) !!}" style="margin-right: 10px;" class="btn btn-primary btn-xs pull-right">Bewerk project</a>
                                <a href="{!! \URL::route('rapport.show', ['id' => $project->id]) !!}" style="margin-right: 10px;" class="btn btn-default btn-xs pull-right">Bekijk rapport</a>
                            @endif


                            <h2>{{ $project->naam }}</h2>
                        </div>
                        <dl class="dl-horizontal">
                            <dt>Status:</dt>
                            <dd>
                                @if($project->datum_oplevering < date("Y-m-d"))
                                    <span class="label">Afgerond</span>
                                @else
                                    <span class="label label-primary">Actief</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <dl class="dl-horizontal">

                            <dt>Onderwerp:</dt> <dd>{{ $project->onderwerp }}</dd>
                            <dt>Opleverdatum:</dt> <dd>{!! date("d-m-Y", strtotime($project->datum_oplevering)) !!}</dd>
                            <dt>Projectnummer(s):</dt> <dd>{{ $project->projectnummer }}</dd>

                            <dt>Locatie(s):</dt> <dd>
                                @foreach($project->locations as $locatie)
                                    {{ $locatie->naam }}<br />
                                @endforeach
                            </dd>
                        </dl>
                    </div>
                    <div class="col-lg-7" id="cluster_info">
                        <dl class="dl-horizontal">
                            <dt>Gemaakt op:</dt> <dd>{!! date("d-m-Y H:i", strtotime($project->created_at)) !!}</dd>
                            @if(isset($file) && $file)
                                <dt>Bestanden:</dt> <dd><mark>Er staat een bestand in de wacht!</mark></dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
