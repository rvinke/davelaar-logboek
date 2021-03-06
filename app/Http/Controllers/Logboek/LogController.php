<?php

namespace app\Http\Controllers\Logboek;

use App\Models\File;
use App\Models\FireDamper;
use App\Models\Floor;
use App\Models\Floorplan;
use App\Models\Log;
use App\Models\Passthrough;
use App\Models\PassthroughType;
use App\Models\Project;
use App\Models\System;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\Datatables\Datatables;

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

    public function getDatatable($project_id)
    {
        $logs = Log::where('project_id', $project_id)
            ->with('passthroughs')
            ->with('passthroughs.passthrough_type')
            ->with('floor')
            ->with('system')
            ->with('location')
            ->orderBy('logs.id', 'desc')
            ->get();

        return Datatables::of($logs)

            ->addColumn('verbroken', function ($log) {
                if ($log->reports->count() > 0) {
                    return '<i class="fa fa-exclamation-triangle" style = "color: #f00" ></i>';
                } else {
                    return '';
                }
            })
            ->addColumn('action', function ($log) {
                return '<a href="'.\URL::route('log.edit', ['id' => $log->id]).'"><i style="font-size: 1.2em;" class="fa fa-edit fa-large"></i></a>';
            })
            ->editColumn('code', function ($log) {
                return '<a href="'.\URL::route('log.map-show', ['id' => $log->id]).'">'.$log->code.'</a>';
            })

            ->editColumn('system', function ($log) {
                if ($log->product_id != 0) {
                    return '<abbr title="'.$log->system->naam.'">'.$log->system->leverancier.' '.$log->system->productnummer.'</abbr>';
                } else {
                    return 'Onbekend';
                }
            })
            ->editColumn('passthroughs', function ($log) {
                $return = '';
                foreach ($log->passthroughs as $passthrough) {
                    if ($passthrough->passthrough_type_id != 0) {
                        $return .= $passthrough->count.'x '.$passthrough->passthrough_type->naam.'<br />';
                    }
                }

                return $return;
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $locations = $project->locations->pluck('naam', 'id');
        $products = System::selectRaw('CONCAT(productnummer, " ", naam) as naam, id')->orderBy('naam')->pluck('naam', 'id');
        $brandkleppen = FireDamper::pluck('naam', 'id');
        $passthroughs = PassthroughType::orderBy('naam')->pluck('naam', 'id');

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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $log = new Log();

        $floorplan = Floorplan::findOrFail($request->input('floorplan_id'));

        $log->project_id = $request->input('project_id');
        $log->locatie_id = $request->input('locatie_id');
        $log->bouwlaag_id = $floorplan->floor_id;
        $log->floorplan_id = $request->input('floorplan_id');
        $log->oppervlak_type_id = $request->input('oppervlak_type_id');
        $log->eis = $request->input('eis');
        $log->product_id = $request->input('product_id');
        $log->brandklep_id = $request->input('brandklep_id');
        $log->commentaar = $request->input('commentaar');
        $log->qrcode = $request->input('qrcode');

        $code = $this->genereer_tekeningnummer($log->project_id);
        $log->code = $code;

        if (!$log->save()) {
            return redirect()->route('log.create', [$log->project_id])->withErrors($log->errors()->all())->withInput();
        }

        //handle the file upload als het bestand aanwezig is
        if ($request->hasFile('foto')) {
            $this->storePhoto($request, $log);
        }

        foreach ($request->input('passthroughs')['passthrough_type_id'] as $key => $pt_id) {
            $passthrough = new Passthrough();

            $passthrough->log_id = $log->id;
            $passthrough->passthrough_type_id = $pt_id;
            $passthrough->count = ($request->input('passthroughs')['count'][$key] + 1);

            $passthrough->save();
        }

        return redirect()->route('log.map', [$log->id, $log->bouwlaag_id, $floorplan->number]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $log = Log::findOrFail($id);

        return \View::make('logboek.log')->withLog($log);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $log = Log::with('passthroughs')->findOrFail($id);
        $floorplan_id = $log->floorplan_id;
        if ($log->floorplan_id == 0) {
            $floorplan = Floorplan::where('floor_id', '=', $log->bouwlaag_id)
                ->where('project_id', $log->project_id)
                ->first();
            $floorplan_id = $floorplan->id;
        }
        $project = Project::findOrFail($log->project_id);
        $locations = $project->locations->pluck('naam', 'id');
        $products = System::selectRaw('CONCAT(productnummer, " ", naam) as naam, id')->orderBy('naam')->pluck('naam', 'id');
        $brandkleppen = FireDamper::pluck('naam', 'id');
        $passthroughs = PassthroughType::orderBy('naam')->pluck('naam', 'id');

        return \View::make('logboek.edit')
            ->withLog($log)
            ->withProject($project)
            ->with('floorplan_id', $floorplan_id)
            ->withLocations($locations)
            ->withProducts($products)
            ->withBrandkleppen($brandkleppen)
            ->withPassthroughs($passthroughs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $log = Log::findOrFail($id);

        $floorplan = Floorplan::findOrFail($request->input('floorplan_id'));

        $log->project_id = $request->input('project_id');
        $log->locatie_id = $request->input('locatie_id');
        $log->bouwlaag_id = $floorplan->floor_id;
        $log->floorplan_id = $request->input('floorplan_id');
        $log->oppervlak_type_id = $request->input('oppervlak_type_id');
        $log->eis = $request->input('eis');
        $log->product_id = $request->input('product_id');
        $log->brandklep_id = $request->input('brandklep_id');
        $log->commentaar = $request->input('commentaar');
        $log->qrcode = $request->input('qrcode');

        $log->save();

        //handle the file upload als het bestand aanwezig is
        if ($request->hasFile('foto')) {
            $this->storePhoto($request, $log);
        }

        $passthroughs = $log->passthroughs;

        foreach ($passthroughs as $passthrough) {
            $passthrough->delete();
        }

        foreach ($request->input('passthroughs')['passthrough_type_id'] as $key => $pt_id) {
            $passthrough = new Passthrough();

            $passthrough->log_id = $log->id;
            $passthrough->passthrough_type_id = $pt_id;
            $passthrough->count = ($request->input('passthroughs')['count'][$key] + 1);

            $passthrough->save();
        }

        return redirect()->route('log.map', [$log->id, $log->bouwlaag_id, $floorplan->number]);
    }

    private function storePhoto(Request $request, $log)
    {
        $file = $request->file('foto');

        $project = Project::findOrFail($log->project_id);
        $year = date('Y', strtotime($project->created_at));
        $extension = $file->getClientOriginalExtension();

        if (file_exists(public_path().'/documenten/'.$year.'/'.$project->id.'/'.$log->code.'.'.$extension)) {
            unlink(public_path().'/documenten/'.$year.'/'.$project->id.'/'.$log->code.'.'.$extension);
        }
        $image = $request->file('foto');
        Image::make($image->getRealPath())->resize(1200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('documenten/'.$year.'/'.$project->id.'/').$log->code.'.'.$extension);

        $filedb = new File();

        $filedb->naam = $log->code.'.'.$extension;
        $filedb->project_id = $project->id;
        $filedb->type = 'foto';
        $filedb->log_id = $log->id;

        $filedb->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMap(Request $request, $id)
    {
        $log = Log::findOrFail($id);

        if (!empty($request->input('position'))) {
            list($lat, $lng) = explode('|', $request->input('position'));

            $log->lat = $lat;
            $log->lng = $lng;

            $log->save();
        }

        return redirect()->route('projecten.show', $log->project_id)->with('status', 'Log opgeslagen.');
    }

    public function map($id, $floor_id, $number = 0)
    {
        /*
         * @Todo: waarom moet het project hier opgehaald worden?
         */
        $log = Log::findOrFail($id);
        $project = Project::findOrFail($log->project_id);

        $floor_element = $floor_id.'/'.$number;
        if ($number == 0) {
            $floor_element = $floor_id.'/0';
        }

        return \View::make('logboek.map')
            ->withLog($log)
            ->withProject($project)
            ->withFloor($floor_element);
    }

    public function mapShow($id)
    {
        $log = Log::findOrFail($id);
        $project = Project::findOrFail($log->project_id);

        if($log->floorplan_id != 0) {
            $floorplan = Floorplan::findOrFail($log->floorplan_id);
            $number = $floorplan->number;
        } else {
            $number = 0;
        }


        return \View::make('project.logitemplattegrond')
            ->withLog($log)
            ->withProject($project)
            ->withNumber($number);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $log = Log::findOrFail($id);
        $project_id = $log->project_id;
        $log->delete();

        return redirect()->route('projecten.show', ['id' => $project_id])->with('status', 'Log-item verwijderd.');
    }

    private function genereer_tekeningnummer($project_id)
    {
        //controleer wat het laaste getal is
        $nummers = Log::where('project_id', $project_id)->select('code')->get();
        $max_nummer = $nummers->max();

        if (!empty($max_nummer->code)) {
            $code = $max_nummer->code;

            $vervolgcijfer = $code + 1;
            if (strlen($vervolgcijfer) === 1) {
                $vervolgcijfer = '0'.$vervolgcijfer;
            }

            /*while(array_key_exists($vervolgcijfer, $nummers)){
                //code bestaat al, andere code maken
                $vervolgcijfer++;
                if(strlen($vervolgcijfer) === 1) $vervolgcijfer = '0'.$vervolgcijfer;
            }*/

            //return $vervolgletter.$vervolgcijfer;

            return $vervolgcijfer;
        }

        return '01';//beginpunt
    }
}
