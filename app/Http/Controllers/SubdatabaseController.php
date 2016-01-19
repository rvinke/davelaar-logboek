<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class SubdatabaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($subdatabase)
    {

        return view('subdatabase.list')->withSubdatabase($subdatabase);

    }

    public function getDatatable($subdatabase)
    {

        $model = 'App\\Models\\'.ucfirst($subdatabase);

        $objects = $model::all();

        if($subdatabase == 'location') {
            return Datatables::of($objects)
                ->addColumn('naam', function($object) {
                    if(isset($object->project->naam)) {
                        return $object->naam . ' (' . $object->project->naam . ')';
                    } else {
                        return $object->naam . ' (Onbekend)';
                    }
                })
                ->addColumn('action', function($object) use ($subdatabase){
                    return '<a href="'.\URL::route('subdatabase.edit', ['subdatabase' => $subdatabase, 'id' => $object->id]).'"><i class="fa fa-search"></i></a>';
                })
                ->make(true);
        }

        return Datatables::of($objects)
            ->addColumn('action', function($object) use ($subdatabase){
                return '<a href="'.\URL::route('subdatabase.edit', ['subdatabase' => $subdatabase, 'id' => $object->id]).'"><i class="fa fa-search"></i></a>';
            })
            ->make(true);


    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($subdatabase)
    {

        return \View::make('subdatabase.create')
            ->withSubdatabase($subdatabase);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $subdatabase)
    {
        $model = 'App\\Models\\'.ucfirst($subdatabase);

        $object = new $model;

        $object->naam = \Input::get('naam');

        if(strtolower($subdatabase) == 'client') {
            $object->adres = \Input::get('adres');
            $object->postcode = \Input::get('postcode');
            $object->woonplaats = \Input::get('woonplaats');
            $object->telefoonnummer = \Input::get('telefoonnummer');
        }

        if(strtolower($subdatabase) == 'system') {
            $object->leverancier = \Input::get('leverancier');
            $object->productnummer = \Input::get('productnummer');
        }

        $object->save();

        return redirect()->route('subdatabase.index', ['subdatabase' => $subdatabase])->with('status', 'Item opgeslagen.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($subdatabase, $id)
    {

        $model = 'App\\Models\\'.ucfirst($subdatabase);

        $object = $model::findOrFail($id);

        return \View::make('subdatabase.edit')
            ->withSubdatabase($subdatabase)
            ->withModel($object);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $subdatabase, $id)
    {
        $model = 'App\\Models\\'.ucfirst($subdatabase);

        $object = $model::findOrFail($id);

        $object->naam = \Input::get('naam');

        if(strtolower($subdatabase) == 'client') {
            $object->adres = \Input::get('adres');
            $object->postcode = \Input::get('postcode');
            $object->woonplaats = \Input::get('woonplaats');
            $object->telefoonnummer = \Input::get('telefoonnummer');
        }

        if(strtolower($subdatabase) == 'system') {
            $object->leverancier = \Input::get('leverancier');
            $object->productnummer = \Input::get('productnummer');
        }

        $object->save();

        return redirect()->route('subdatabase.index', ['subdatabase' => $subdatabase])->with('status', 'Item opgeslagen.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($subdatabase, $id)
    {
        $model = 'App\\Models\\'.ucfirst($subdatabase);

        $object = $model::findOrFail($id);

        $object->delete();

        return redirect()->route('subdatabase.index', ['subdatabase' => $subdatabase])->with('status', 'Item is verwijderd, maar nog wel beschikbaar voor weergave in rapporten.');
    }
}
