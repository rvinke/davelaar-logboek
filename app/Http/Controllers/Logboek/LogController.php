<?php

namespace App\Http\Controllers\Logboek;

use App\Models\FireDamper;
use App\Models\Log;
use App\Models\Passthrough;
use App\Models\PassthroughType;
use App\Models\Project;
use App\Models\System;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $locations = $project->locations->lists('naam', 'id');
        $products = System::selectRaw('CONCAT(productnummer, " ", naam) as naam, id')->orderBy('naam')->lists('naam', 'id');
        $brandkleppen = FireDamper::lists('naam', 'id');
        $passthroughs = PassthroughType::orderBy('naam')->lists('naam', 'id');

        return \View::make('logboek.create')
            ->withProject($project)
            ->withLocations($locations)
            ->withProducts($products)
            ->withBrandkleppen($brandkleppen)
            ->withPassthroughs($passthroughs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $log = new Log();

        $log->project_id = \Input::get('project_id');
        $log->locatie_id = \Input::get('locatie_id');
        $log->bouwlaag_id = \Input::get('bouwlaag_id');
        $log->product_id = \Input::get('product_id');
        $log->brandklep_id = \Input::get('brandklep_id');
        $log->commentaar = \Input::get('commentaar');


        $log->save();

        foreach(\Input::get('passthroughs')['passthrough_type_id'] as $key => $pt_id) {

            $passthrough = new Passthrough();

            $passthrough->log_id = $log->id;
            $passthrough->passthrough_type_id = $pt_id;
            $passthrough->count = (\Input::get('passthroughs')['count'][$key] + 1);

            $passthrough->save();

        }

        return redirect()->route('log.map', [$log->id, $log->bouwlaag_id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $log = Log::with('passthroughs')->findOrFail($id);
        $project = Project::findOrFail($log->project_id);
        $locations = $project->locations->lists('naam', 'id');
        $products = System::selectRaw('CONCAT(productnummer, " ", naam) as naam, id')->orderBy('naam')->lists('naam', 'id');
        $brandkleppen = FireDamper::lists('naam', 'id');
        $passthroughs = PassthroughType::orderBy('naam')->lists('naam', 'id');

        return \View::make('logboek.edit')
            ->withLog($log)
            ->withProject($project)
            ->withLocations($locations)
            ->withProducts($products)
            ->withBrandkleppen($brandkleppen)
            ->withPassthroughs($passthroughs);
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
        $log = Log::findOrFail($id);

        $log->project_id = $request->input('project_id');
        $log->locatie_id = $request->input('locatie_id');
        $log->bouwlaag_id = $request->input('bouwlaag_id');
        $log->product_id = $request->input('product_id');
        $log->brandklep_id = $request->input('brandklep_id');
        $log->commentaar = $request->input('commentaar');

        /*list($lat, $lng)  = explode("|", $request->input('position'));

        $log->lat = $lat;
        $log->lng = $lng;*/

        $log->save();

        $passthroughs = $log->passthroughs;


        foreach($passthroughs as $passthrough) {
            $passthrough->delete();
        }

        foreach(\Input::get('passthroughs')['passthrough_type_id'] as $key => $pt_id) {

            $passthrough = new Passthrough();

            $passthrough->log_id = $log->id;
            $passthrough->passthrough_type_id = $pt_id;
            $passthrough->count = (\Input::get('passthroughs')['count'][$key] + 1);

            $passthrough->save();

        }

        return redirect()->route('log.map', [$log->id, $log->bouwlaag_id]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMap(Request $request, $id)
    {
        $log = Log::findOrFail($id);


        list($lat, $lng)  = explode("|", $request->input('position'));

        $log->lat = $lat;
        $log->lng = $lng;

        $log->save();


        return redirect()->route('projecten.show', $log->project_id)->with('status', 'Log opgeslagen.');

    }

    public function map($id, $floor_id) {

        $log = Log::findOrFail($id);

        return \View::make('logboek.map')
            ->withLog($log)
            ->withProject($log->project)
            ->withFloor($floor_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $log = Log::findOrFail($id);
        $project_id = $log->project_id;
        $log->delete();

        return redirect()->route('projecten.show', ['id' => $project_id])->with('status', 'Log-item verwijderd.');

    }
}
