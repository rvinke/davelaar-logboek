<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Floorplan extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function floor()
    {
        return $this->belongsTo(\App\Models\Floor::class);
    }

    public function location()
    {
        return $this->belongsTo(\App\Models\Location::class);
    }
}
