<?php

namespace App\Http\Controllers\Logboek;

use App\Models\Client;
use App\Models\Location;

use App\Models\Log;
use App\Models\Project;
use Barryvdh\Debugbar\LaravelDebugbar;
use DebugBar\DebugBar;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;


class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('project.projecten')->withRapporten(false);

    }

    public function indexRapporten()
    {
        return view('project.projecten')->withRapporten(true);
    }

    public function getDatatable()
    {

        $projecten = Project::select(['naam', 'id', 'created_at'])->orderBy('naam');


        return Datatables::of($projecten)

            ->addColumn('action', function($project) {
                return '<a href="'.\URL::route('projecten.show', ['id' => $project->id]).'"><i class="fa fa-search"></i></a>';
            })
            ->addColumn('status', function($project){
                if($project->datum_oplevering < date("Y-m-d")) {
                    return '<span class="label label-primary">Afgerond</span>';
                }else{
                    return '<span class="label label-primary">Actief</span>';
                }
            })
            ->addColumn('count_logs', function($project){
                return $project->logs->count();
            })

            ->make(true);
    }

    public function getDatatableRapporten()
    {


        if(\Auth::user()->hasRole(['admin', 'medewerker'])) {
            $projecten = Project::select(['naam', 'id']);
        } else {
            $projecten = Project::select(['naam', 'id'])->where('opdrachtgever_id', \Auth::user()->client_id)->get();
        }



        return Datatables::of($projecten)

            ->addColumn('action', function($project){
                return '<a href="'.\URL::route('rapport.show', ['id' => $project->id]).'"><i class="fa fa-search"></i></a>';
            })
            ->addColumn('status', function($project){
                if($project->datum_oplevering < date("Y-m-d")) {
                    return '<span class="label label-primary">Afgerond</span>';
                }else{
                    return '<span class="label label-primary">Actief</span>';
                }
            })
            ->addColumn('count_logs', function($project){
                return $project->logs->count();
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $clients = Client::lists('naam', 'id');

        return \View::make('project.create')->withClients($clients);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project = new Project();

        $project->naam = Input::get('naam');
        $project->projectnummer = Input::get('projectnummer');
        $project->opdrachtgever_id = Input::get('opdrachtgever_id');
        $project->onderwerp = Input::get('onderwerp');
        $project->referentie = Input::get('referentie');
        $project->adres = Input::get('adres');

        if($project->save()) {
            return redirect()->route('projecten.index')->with('status', 'Project opgeslagen.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::with('logs')
            ->with('logs.passthroughs')
            ->with('logs.passthroughs.passthrough_type')
            ->with('logs.floor')
            ->with('logs.system')
            ->with('logs.location')
            ->findOrFail($id);

        return \View::make('project.projectdetails')->withProject($project);
    }

    /**
 * Display the specified resource.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
    public function floorplan($id, $floor_id)
    {
        $project = Project::with('logs')
            ->with('logs.passthroughs')
            ->with('logs.passthroughs.passthrough_type')
            ->with('logs.floor')
            ->with('logs.system')
            ->with('logs.location')
            ->findOrFail($id);

        $floorplan = $project->maps()->where('floor_id', $floor_id)->first();

        return \View::make('project.projectplattegrond')
            ->withProject($project)
            ->withFloorplan($floorplan)
            ->withFloor($floor_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function floorplanJavascript($id, $floor_id, $editable = false)
    {

        \Debugbar::disable();

        $project = Project::with('logs')
            ->with('logs.passthroughs')
            ->with('logs.passthroughs.passthrough_type')
            ->with('logs.floor')
            ->with('logs.system')
            ->with('logs.location')
            ->findOrFail($id);

        $floorplan = $project->maps()->where('floor_id', $floor_id)->first();

        $year = date("Y", strtotime($project->created_at));

        if($editable) {
            $log = Log::findOrFail($editable);
        }

        return \View::make('project.floorplanJS')->withProject($project)
            ->withFloorplan($floorplan)
            ->withFloor($floor_id)
            ->withYear($year)
            ->withEditable($editable)
            ->withLog($log);

    }


    public function rapport($id)
    {
        $project = Project::with('logs')
            ->with('logs.passthroughs')
            ->with('logs.passthroughs.passthrough_type')
            ->with('logs.floor')
            ->with('logs.system')
            ->with('logs.location')
            ->with('logs.photo')
            ->with('floorplans')
            ->findOrFail($id);

        $year = date("Y", strtotime($project->created_at));

        return \View::make('project.rapport')->withProject($project)->withYear($year);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::with('locations')->findOrFail($id);
        $clients = Client::lists('naam', 'id');

        return \View::make('project.edit')->withProject($project)->withClients($clients);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->naam = \Input::get('naam');
        $project->projectnummer = \Input::get('projectnummer');
        $project->opdrachtgever_id = \Input::get('opdrachtgever_id');
        $project->onderwerp = \Input::get('onderwerp');
        $project->referentie = \Input::get('referentie');
        $project->adres = \Input::get('adres');
        $project->datum_oplevering = date("Y-m-d", strtotime(\Input::get('datum_oplevering')));

        if($project->save()) {

            /*dd(\Input::get('locations')['naam']);


            foreach($project->locations as $location) {
                $location->delete();
            }

            foreach(\Input::get('locations')['naam'] as $location_naam) {


                if(!empty($location_naam)) {
                    $location = new Location();

                    $location->project_id = $project->id;
                    $location->naam = $location_naam;

                    $location->save();
                }


            }*/

        }

        return redirect()->route('projecten.index')->with('status', 'Project opgeslagen.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
