<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passthrough extends Model
{

    public function log()
    {
        return $this->belongsTo(\App\Models\Log::class);
    }

    public function passthrough_type()
    {
        return $this->hasOne(\App\Models\PassthroughType::class, 'id', 'passthrough_type_id');
    }
}
