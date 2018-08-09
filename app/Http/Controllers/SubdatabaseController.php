<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

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
        $model = $this->modelName($subdatabase);

        $objects = $model::all();

        if ($subdatabase == 'location') {
            return Datatables::of($objects)
                ->addColumn('naam', function ($object) {
                    if (isset($object->project->naam)) {
                        return $object->naam.' ('.$object->project->naam.')';
                    } else {
                        return $object->naam.' (Onbekend)';
                    }
                })
                ->addColumn('action', function ($object) use ($subdatabase) {
                    return '<a href="'.\URL::route('subdatabase.edit', ['subdatabase' => $subdatabase, 'id' => $object->id]).'"><i class="fa fa-search"></i></a>';
                })
                ->make(true);
        }

        return Datatables::of($objects)
            ->addColumn('action', function ($object) use ($subdatabase) {
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $subdatabase)
    {
        $model = $this->modelName($subdatabase);

        $object = new $model();

        $object->naam = $request->input('naam');

        if (strtolower($subdatabase) == 'location') {
            $object->project_id = $request->input('project_id');
        }

        if (strtolower($subdatabase) == 'client') {
            $object->adres = $request->input('adres');
            $object->postcode = $request->input('postcode');
            $object->woonplaats = $request->input('woonplaats');
            $object->telefoonnummer = $request->input('telefoonnummer');
        }

        if (strtolower($subdatabase) == 'system') {
            $object->leverancier = $request->input('leverancier');
            $object->productnummer = $request->input('productnummer');

            //Documentatie opslaan
            if (!empty($request->file('documentatie'))) {
                foreach ($request->file('documentatie') as $file) {
                    $file = $request->file('documentatie');
                    $file->move(public_path('documenten/documentatie/'.$object->id.'/'), $file->getClientOriginalName());
                    $object->documentatie = $file->getClientOriginalName();
                }
            }
        }

        $object->save();

        return redirect()->route('subdatabase.index', ['subdatabase' => $subdatabase])->with('status', 'Item opgeslagen.');
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
    }

    /**
     * @param $systemId
     * @param $filename
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadDocumentatie($systemId, $filename)
    {
        $filename = 'documenten/documentatie/'.$systemId.'/'.$filename;

        return \Response::download($filename);
    }

    /**
     * @param $systemId
     * @param $filename
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeDocumentatie($systemId, $filename)
    {
        $system = System::findOrFail($systemId);

        $path = public_path('documenten/documentatie/'.$systemId.'/'.urldecode($filename));

        unlink($path);

        $fileArray = json_decode($system->documentatie);

        if (($key = array_search(urldecode($filename), $fileArray)) !== false) {
            unset($fileArray[$key]);
        }

        $system->documentatie = json_encode($fileArray);

        $system->save();

        return redirect()->route('subdatabase.edit', ['subdatabase' => 'system', 'id' => $systemId])->with('status', 'Item opgeslagen.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($subdatabase, $id)
    {
        $model = $this->modelName($subdatabase);

        $object = $model::findOrFail($id);

        return \View::make('subdatabase.edit')
            ->withSubdatabase($subdatabase)
            ->withModel($object);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $subdatabase, $id)
    {
        $model = $this->modelName($subdatabase);

        $object = $model::findOrFail($id);

        $object->naam = $request->input('naam');

        if (strtolower($subdatabase) == 'location') {
            $object->project_id = $request->input('project_id');
        }

        if (strtolower($subdatabase) == 'client') {
            $object->adres = $request->input('adres');
            $object->postcode = $request->input('postcode');
            $object->woonplaats = $request->input('woonplaats');
            $object->telefoonnummer = $request->input('telefoonnummer');
        }

        if (strtolower($subdatabase) == 'system') {
            $object->leverancier = $request->input('leverancier');
            $object->productnummer = $request->input('productnummer');

            //Documentatie opslaan
            if ($request->hasFile('documentatie')) {
                $storeArray = json_decode($object->documentatie);

                foreach ($request->file('documentatie') as $file) {
                    $file->move(public_path('documenten/documentatie/'.$object->id.'/'), $file->getClientOriginalName());
                    $storeArray[] = $file->getClientOriginalName();
                }

                $object->documentatie = json_encode($storeArray);
            }
        }

        $object->save();

        return redirect()->route('subdatabase.index', ['subdatabase' => $subdatabase])->with('status', 'Item opgeslagen.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($subdatabase, $id)
    {
        $model = 'App\\Models\\'.ucfirst($subdatabase);

        $object = $model::findOrFail($id);

        $object->delete();

        return redirect()->route('subdatabase.index', ['subdatabase' => $subdatabase])->with('status', 'Item is verwijderd, maar nog wel beschikbaar voor weergave in rapporten.');
    }

    /**
     * @param $subdatabase
     *
     * @return \Eloquent
     */
    private function modelName($subdatabase)
    {
        $model = 'App\\Models\\'.ucfirst($subdatabase);
        if ($subdatabase == 'firedamper') {
            $model = 'App\\Models\\FireDamper';
        }

        return $model;
    }
}
