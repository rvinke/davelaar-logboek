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
        return $this->hasMany('App\Models\File')->reports();
    }

    public function maps()
    {
        return $this->hasMany('App\Models\Floorplan');
    }
}
