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
        return $this->belongsTo('App\Models\Project');
    }

    public function location()
    {
        return $this->hasOne('App\Models\Location', 'id', 'locatie_id');
    }

    public function floor()
    {
        return $this->hasOne('App\Models\Floor', 'id', 'bouwlaag_id');
    }

    public function system()
    {
        return $this->hasOne('App\Models\System', 'id', 'product_id');
    }



    public function passthroughs()
    {
        return $this->hasMany('App\Models\Passthrough');
    }

    public function photo()
    {
        return $this->hasOne('App\Models\File');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\Report')->where('completed', 0);
    }
}
