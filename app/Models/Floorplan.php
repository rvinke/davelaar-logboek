<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Floorplan extends Model
{

    public function floor()
    {
        return $this->belongsTo(\App\Models\Floor::class);
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class);
    }
}
