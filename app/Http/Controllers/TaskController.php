<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Log;
use App\Models\Passthrough;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException as ModelNotFoundException;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{

    public function migrateLogs()
    {
        $logs = Log::all();

        foreach ($logs as $log) {
            $doorvoer_array = json_decode($log->doorvoer_id);

            if (is_array($doorvoer_array)) {
                foreach ($doorvoer_array as $doorvoer) {
                    $dv = new Passthrough();

                    $dv->log_id = $log->id;
                    $dv->passthrough_type_id = $doorvoer;
                    $dv->count = $log->aantal_doorvoer;

                    $dv->save();
                }
            } else {
                $dv = new Passthrough();

                $dv->log_id = $log->id;
                $dv->passthrough_type_id = $doorvoer_array;
                $dv->count = $log->aantal_doorvoer;

                $dv->save();
            }
        }
    }


    public function migratePhotos()
    {
        $files = File::photos()->get();

        foreach ($files as $file) {
            echo $file->project->id.'<br />';

            $code = explode('.', $file->naam)[0];

            if (is_numeric($code)) {
                echo $code.'<br />';
            }

            try {
                $log = Log::where('code', $code)->where('project_id', $file->project->id)->firstOrFail();
                echo 'Log:'.$log->id.'<br />';

                $file->log_id = $log->id;
                $file->save();
            } catch (ModelNotFoundException $e) {
            }
        }
    }
}
