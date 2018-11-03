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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        return view('floorplan.create')->with('project_id', $project_id);
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

        //controleren of deze combinatie floor/location voorkomt
        $existing_floorplans = Floorplan::where('project_id', $project_id)
            ->where('floor_id', $floor_id)
            ->where('location_id', $location_id)
            ->get();

        $number = $existing_floorplans->count();

        $floor_element = $floor_id.'_'.$number;
        if ($number == 0) {
            $floor_element = $floor_id;
        }

        $project = Project::findOrFail($project_id);
        $year = date("Y", strtotime($project->created_at));

        $adapter = new Local(base_path().'/public/documenten');
        $filesystem = new Filesystem($adapter);

        $file_dir = '/'.$year.'/'.$project_id.'/plattegrond/'.$location_id.'/'.$floor_element.'/';
        $file_location = $file_dir.$file->getClientOriginalName();

        /*
        //eerst opruimen
        if ($filesystem->has($file_location)) {
            $filesystem->delete($file_location);
        }*/

        $stream = fopen($file->getRealPath(), 'r+');
        $filesystem->writeStream($file_location, $stream);
        fclose($stream);

        /*
        //controleren of deze combinatie voorkomt
        $existing_floorplan = Floorplan::where('project_id', $project_id)->where('floor_id', $floor_id)->first();

        if (!empty($existing_floorplan)) {
            $existing_floorplan->delete();
        }*/

        $floorplan = new Floorplan();

        $floorplan->filename = $file->getClientOriginalName();
        $floorplan->project_id = $project_id;
        $floorplan->floor_id = $floor_id;
        $floorplan->location_id = $location_id;
        $floorplan->number = $number;

        $floorplan->save();

        //set file permissions
        chmod(public_path().'/documenten/'.$year.'/'.$project_id.'/plattegrond/'.$location_id.'/'.$floor_element, 0777);
        chmod(public_path().'/documenten/'.$year.'/'.$project_id.'/plattegrond/'.$location_id.'/'.$floor_element.'/'.$file->getClientOriginalName(), 0777);

        return redirect()->route('projecten.show', ['id' => $project_id])->with('status', 'Plattegrond toegevoegd.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $project_id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id)
    {
        $project = Project::findOrFail($project_id);

        return view('floorplan.list')->withProject($project);
    }

    public function download($project_id, $location_id, $floor_id, $number)
    {
        $project = Project::withAll()
            ->findOrFail($project_id);

        $year = date("Y", strtotime($project->created_at));

        $floor_element = $floor_id.'_'.$number;
        if ($number == 0) {
            $floor_element = $floor_id;
        }

        $location = public_path().'/documenten/'.$year.'/'.$project_id.'/plattegrond/'.$location_id.'/'.$floor_element.'/plattegrond.png';
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
    public function javascript($project_id, $location_id, $floor_id, $number, $editable = false, $log_id = '')
    {

        $project = Project::withAll()
            ->findOrFail($project_id);

        $floorplan = $project->maps()
            ->where('location_id', $location_id)
            ->where('floor_id', $floor_id)
            ->where('number', $number)
            ->first();

        if (!$floorplan) {
            return response('');
        }

        $year = date("Y", strtotime($project->created_at));

        $floor_element = $floor_id.'_'.$number;
        if ($number == 0) {
            $floor_element = $floor_id;
        }

        //@TODO: $editable echt zo maken dat het over editable gaat en niet over een log-id
        if ($editable) {
            $log = Log::findOrFail($editable);

            return \View::make('project.floorplanJS')->withProject($project)
                ->withFloorplan($floorplan)
                ->withFloor($floor_element)
                ->withYear($year)
                ->withEditable($editable)
                ->withLog($log);
        }

        if (!empty($log_id)) {
            $log = Log::findOrFail($log_id);

            return \View::make('project.floorplanJS')->withProject($project)
                ->withFloorplan($floorplan)
                ->withFloor($floor_element)
                ->withYear($year)
                ->withEditable($editable)
                ->withLat($log->lat)
                ->withLng($log->lng)
                ->withLog($log);
        }

        return \View::make('project.floorplanJS')
            ->withProject($project)
            ->withFloorplan($floorplan)
            ->withFloor($floor_element)
            ->withYear($year)
            ->withEditable($editable)
            ->withLog(false);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $floorplan = Floorplan::findOrFail($id);

        $floorplan->delete();

        return back();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        $floorplan = Floorplan::withTrashed()->findOrFail($id);

        $floorplan->restore();

        return back();
    }
}
