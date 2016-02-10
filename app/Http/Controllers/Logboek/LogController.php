<?php

namespace App\Http\Controllers\Logboek;

use App\Models\File;
use App\Models\FireDamper;
use App\Models\Log;
use App\Models\Passthrough;
use App\Models\PassthroughType;
use App\Models\Project;
use App\Models\System;
use Illuminate\Http\Request;


use App\Http\Requests;
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

            ->addColumn('verbroken', function($log){
                if($log->reports->count() > 0) {
                    return '<i class="fa fa-exclamation-triangle" style = "color: #f00" ></i>';
                } else {
                    return '';
                }
            })
            ->addColumn('action', function($log){
                return '<a href="'.\URL::route('log.edit', ['id' => $log->id]).'"><i style="font-size: 1.2em;" class="fa fa-edit fa-large"></i></a>';
            })

            ->editColumn('system', function($log){
                if($log->product_id != 0) {
                    if(!empty($log->system->documentatie)) {
                        return '<a href="' . \URL::route('documentatie.download', $log->system->id) . '" title="' . $log->system->naam . '">' . $log->system->leverancier . ' ' . $log->system->productnummer . '</a>';
                    } else {
                        return '<abbr title="'.$log->system->naam.'">'.$log->system->leverancier.' '.$log->system->productnummer.'</abbr>';
                    }
                }else{
                    return 'Onbekend';
                }
            })
            ->editColumn('passthroughs', function($log){
                $return = '';
                foreach($log->passthroughs as $passthrough) {
                    if($passthrough->passthrough_type_id != 0) {
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
        $log->oppervlak_type_id = $request->input('oppervlak_type_id');
        $log->eis = $request->input('eis');
        $log->product_id = \Input::get('product_id');
        $log->brandklep_id = \Input::get('brandklep_id');
        $log->commentaar = \Input::get('commentaar');
        $log->qrcode = $request->input('qrcode');

        $code = $this->genereer_tekeningnummer($log->project_id);
        $log->code = $code;

        $log->save();

        //handle the file upload als het bestand aanwezig is
        if(!empty($request->file('foto'))) {

            $this->storePhoto($request, $log);

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
     * Display the specified resource.
     *
     * @param  int  $id
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
        $log->oppervlak_type_id = $request->input('oppervlak_type_id');
        $log->eis = $request->input('eis');
        $log->product_id = $request->input('product_id');
        $log->brandklep_id = $request->input('brandklep_id');
        $log->commentaar = $request->input('commentaar');
        $log->qrcode = $request->input('qrcode');

        $log->save();

        //handle the file upload als het bestand aanwezig is
        if(!empty($request->file('foto'))) {

            $this->storePhoto($request, $log);

        }

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


    private function storePhoto(Request $request, $log) {
        $file = $request->file('foto');

        $project = Project::findOrFail($log->project_id);
        $year = date("Y", strtotime($project->created_at));
        $extension = $file->getClientOriginalExtension();

        if(file_exists(public_path().'/documenten/'.$year.'/'.$project->id.'/'.$log->code.'.'.$extension)){
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


    public function mapShow($id) {
        $log = Log::findOrFail($id);

        return \View::make('project.logitemplattegrond')
            ->withLog($log)
            ->withProject($log->project);
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

    private function genereer_tekeningnummer($project_id)
    {
        //controleer wat het laaste getal is
        $nummers = Log::where('project_id', $project_id)->select('code')->get();
        $max_nummer = $nummers->max();


        if(!empty($max_nummer->code)){

            $code = $max_nummer->code;

            $vervolgcijfer = $code + 1;
            if(strlen($vervolgcijfer) === 1) $vervolgcijfer = '0'.$vervolgcijfer;

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
