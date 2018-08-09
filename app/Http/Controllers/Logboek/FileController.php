<?php

namespace App\Http\Controllers\Logboek;

use App\Models\File;
use App\Models\Project;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FileController extends Controller
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

        return \View::make('file.create')
            ->withProject($project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $project_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {

        if ($request->hasFile('file-upload')) {
            $file = $request->file('file-upload');
            $type = $request->input('type');

            $stream = fopen($file->getRealPath(), 'r+');

            //dd($stream);

            $project = Project::findOrFail($project_id);
            $year = date("Y", strtotime($project->created_at));
            $extension = $file->getClientOriginalExtension();


            if ($extension == 'zip') {
                if (file_exists(public_path().'/documenten/uploads/project_'.$project_id.'.'.$extension)) {
                    unlink(public_path().'/documenten/uploads/project_'.$project_id.'.'.$extension);
                }

                Flysystem::put('uploads/project_'.$project_id.'.'.$extension, $stream);
            } else {
                if ($type != 'foto') {
                    Flysystem::put($year.'/'.$project_id.'/'.$type.'en/'.$file->getClientOriginalName(), $stream);
                } else {
                    Flysystem::put($year.'/'.$project_id.'/'.$file->getClientOriginalName(), $stream);
                }

                $filedb = new File();

                $filedb->naam = $file->getClientOriginalName();
                $filedb->project_id = $project_id;
                $filedb->type = $type;

                $filedb->save();
            }

            //$filesystem->writeStream('documenten/uploads/'.$file->getClientOriginalName(), $stream);
            fclose($stream);

            return redirect()->route('projecten.show', ['id' => $project_id])->with('status', 'Bestand toegevoegd.');
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
        $file = File::findOrFail($id);
        $project = Project::find($file->project_id);

        $subdir = '';
        if ($file->type == 'tekening') {
            $subdir = 'tekeningen/';
        }
        if ($file->type == 'rapport') {
            $subdir = 'rapporten/';
        }

        $filename = 'documenten/'.date("Y", strtotime($project->created_at)).'/'.$file->project_id.'/'.$subdir.$file->naam;

        return \Response::download($filename);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $file = File::findOrFail($id);

        return view('file.edit')->withModel($file);
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
        $file = File::findOrFail($id);

        $file->alt_name = $request->input('alt_name');

        $file->save();

        return redirect()->route('projecten.show', ['id' => $file->project_id])->with('status', 'Bestandsnaam aangepast.');
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
