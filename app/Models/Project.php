<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{


    public function locations()
    {
        return $this->hasMany('App\Models\Location');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\Log');
    }

    public function floorplans()
    {
        return $this->hasMany('App\Models\File')->floorplans();
    }

    public function reports()
    {
        return $this->hasManyThrough('App\Models\Report', 'App\Models\Log')->where('completed', 0);
    }

    public function maps()
    {
        return $this->hasMany('App\Models\Floorplan');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
