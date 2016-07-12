<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public function scopePhotos($query)
    {
        return $query->where('type', '=', 'foto');
    }

    public function scopeFloorplans($query)
    {
        return $query->where('type', '=', 'tekening');
    }

    public function scopeReports($query)
    {
        return $query->where('type', '=', 'rapport');
    }


    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function log()
    {
        return $this->belongsTo('App\Models\Log');
    }

    public function location()
    {

        if(\Cache::has('project_created_at_'.$this->project_id)){
            $created_at = \Cache::get('project_created_at_'.$this->project_id);
        }else{
            $created_at = $this->project->created_at;
            \Cache::put('project_created_at_'.$this->project_id, $this->project->created_at, 10);
        }

        return public_path().'/documenten/'.date("Y", strtotime($created_at)).'/'.$this->project_id.'/'.$this->naam;
    }
}
