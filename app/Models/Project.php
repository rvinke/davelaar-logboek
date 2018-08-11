<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{


    public function locations()
    {
        return $this->hasMany(\App\Models\Location::class);
    }

    public function logs()
    {
        return $this->hasMany(\App\Models\Log::class);
    }

    public function floorplans()
    {
        return $this->hasMany(\App\Models\File::class)->floorplans();
    }

    public function reports()
    {
        return $this->hasManyThrough(\App\Models\Report::class, \App\Models\Log::class)->where('completed', 0);
    }

    public function maps()
    {
        return $this->hasMany(\App\Models\Floorplan::class);
    }

    public function users()
    {
        return $this->belongsToMany(\App\User::class)->withTimestamps();
    }
}
