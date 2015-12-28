<?php

namespace App\Http\Controllers;

use App\Models\Floorplan;
use App\Models\Project;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FloorplanController extends Controller
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
        return view('floorplan.create')->withProjectId($project_id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        $file = $request->file('file');
        $floor_id = $request->input('floor_id');

        $stream = fopen($file->getRealPath(), 'r+');

        //dd($stream);

        $project = Project::findOrFail($project_id);
        $year = date("Y", strtotime($project->created_at));

        Flysystem::put($year.'/'.$project_id.'/plattegrond/'.$floor_id.'/'.$file->getClientOriginalName(), $stream);
        $floorplan = new Floorplan();

        $floorplan->filename = $file->getClientOriginalName();
        $floorplan->project_id = $project_id;
        $floorplan->floor_id = $floor_id;

        $floorplan->save();


        //$filesystem->writeStream('documenten/uploads/'.$file->getClientOriginalName(), $stream);
        fclose($stream);

        return redirect()->route('projecten.show', ['id' => $project_id])->with('status', 'Plattegrond toegevoegd.');
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
        //
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
        //
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
