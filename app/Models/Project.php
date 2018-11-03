<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    public function scopeWithAll($query)
    {
        return $query->with(
            'logs',
            'logs.passthroughs',
            'logs.passthroughs.passthrough_type',
            'logs.floor',
            'logs.system',
            'logs.location'
        );
    }

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

    public function mapsWithTrashed()
    {
        return $this->hasMany(\App\Models\Floorplan::class)->withTrashed();
    }

    public function users()
    {
        return $this->belongsToMany(\App\User::class)->withTimestamps();
    }
}
