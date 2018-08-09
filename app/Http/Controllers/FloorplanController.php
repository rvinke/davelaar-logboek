<?php

namespace App\Http\Controllers;

use App\Models\Floorplan;
use App\Models\Log;
use App\Models\Project;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

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
        $location_id = $request->input('location_id');

        $stream = fopen($file->getRealPath(), 'r+');

        //dd($stream);

        $project = Project::findOrFail($project_id);
        $year = date("Y", strtotime($project->created_at));

        $adapter = new Local(base_path().'/public/documenten');
        $filesystem = new Filesystem($adapter);


        $file_dir = '/'.$year.'/'.$project_id.'/plattegrond/'.$location_id.'/'.$floor_id.'/';
        $file_location = $file_dir.$file->getClientOriginalName();

        //eerst opruimen
        if ($filesystem->has($file_location)) {
            $filesystem->delete($file_location);
        }

        $stream = fopen($file->getRealPath(), 'r+');
        $filesystem->writeStream($file_location, $stream);
        fclose($stream);

        //controleren of deze combinatie voorkomt
        $existing_floorplan = Floorplan::where('project_id', $project_id)->where('floor_id', $floor_id)->first();

        if (!empty($existing_floorplan)) {
            $existing_floorplan->delete();
        }

        $floorplan = new Floorplan();

        $floorplan->filename = $file->getClientOriginalName();
        $floorplan->project_id = $project_id;
        $floorplan->floor_id = $floor_id;
        $floorplan->location_id = $location_id;

        $floorplan->save();

        //set file permissions
        chmod(public_path().'/documenten/'.$year.'/'.$project_id.'/plattegrond/'.$location_id.'/'.$floor_id, 0777);
        chmod(public_path().'/documenten/'.$year.'/'.$project_id.'/plattegrond/'.$location_id.'/'.$floor_id.'/'.$file->getClientOriginalName(), 0777);

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

    public function download($project_id, $location_id, $floor_id)
    {
        $project = Project::with('logs')
            ->with('logs.passthroughs')
            ->with('logs.passthroughs.passthrough_type')
            ->with('logs.floor')
            ->with('logs.system')
            ->with('logs.location')
            ->findOrFail($project_id);

        $year = date("Y", strtotime($project->created_at));

        $location = public_path().'/documenten/'.$year.'/'.$project_id.'/plattegrond/'.$location_id.'/'.$floor_id.'/plattegrond.png';
        $img = Image::make($location);

        //bepaal de grootte van de marker
        $width = $img->width() / 100;
        $font_size = floor($width * .5 - 10);


        foreach ($project->logs as $log) {
            if (!empty($log->lat) && $log->bouwlaag_id == $floor_id) {
                $img->circle($width, $log->lat, $log->lng, function ($draw) {
                    $draw->background('#0000ff');
                });

                $img->text($log->code, $log->lat, $log->lng, function ($font) use ($font_size) {

                    $font->file(public_path().'/css/Monoid-Regular.ttf');
                    $font->size($font_size);
                    $font->color('#ffffff');
                    $font->align('center');
                    $font->valign('middle');
                });
            }
        }



        $img->save(storage_path().'/app/tempplan.png');

        return response()->download(storage_path().'/app/tempplan.png', 'floorplan-'.$project_id.'-'.$floor_id.'.png');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function javascript($id, $location_id, $floor_id, $editable = false, $log_id = '')
    {

        \Debugbar::disable();

        $project = Project::with('logs')
            ->with('logs.passthroughs')
            ->with('logs.passthroughs.passthrough_type')
            ->with('logs.floor')
            ->with('logs.system')
            ->with('logs.location')
            ->findOrFail($id);

        $floorplan = $project->maps()->where('location_id', $location_id)->where('floor_id', $floor_id)->first();

        if (!$floorplan) {
            return response('');
        }

        $year = date("Y", strtotime($project->created_at));

        //@TODO: $editable echt zo maken dat het over editable gaat en niet over een log-id
        if ($editable) {
            $log = Log::findOrFail($editable);

            return \View::make('project.floorplanJS')->withProject($project)
                ->withFloorplan($floorplan)
                ->withFloor($floor_id)
                ->withYear($year)
                ->withEditable($editable)
                ->withLog($log);
        }

        if (!empty($log_id)) {
            $log = Log::findOrFail($log_id);

            return \View::make('project.floorplanJS')->withProject($project)
                ->withFloorplan($floorplan)
                ->withFloor($floor_id)
                ->withYear($year)
                ->withEditable($editable)
                ->withLat($log->lat)
                ->withLng($log->lng)
                ->withLog($log);
        }

        return \View::make('project.floorplanJS')->withProject($project)
            ->withFloorplan($floorplan)
            ->withFloor($floor_id)
            ->withYear($year)
            ->withEditable($editable)
            ->withLog(false);
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
