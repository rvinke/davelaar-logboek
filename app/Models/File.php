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
}
