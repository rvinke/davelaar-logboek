<?php

namespace App\Models;

use LaravelArdent\Ardent\Ardent;

class Log extends Ardent
{

    public static $rules = [
        'locatie_id' => 'required|integer',
    ];


    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class);
    }

    public function location()
    {
        return $this->hasOne(\App\Models\Location::class, 'id', 'locatie_id');
    }

    public function floor()
    {
        return $this->hasOne(\App\Models\Floor::class, 'id', 'bouwlaag_id');
    }

    public function system()
    {
        return $this->hasOne(\App\Models\System::class, 'id', 'product_id');
    }



    public function passthroughs()
    {
        return $this->hasMany(\App\Models\Passthrough::class);
    }

    public function photo()
    {
        return $this->hasOne(\App\Models\File::class);
    }

    public function reports()
    {
        return $this->hasMany(\App\Models\Report::class)->where('completed', 0);
    }
}
