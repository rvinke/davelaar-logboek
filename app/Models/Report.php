<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /*public static $rules = [
        'naam' => 'required|between:3,80',
        'organisatie' => 'required|between:3,80',
        'log_id' => 'required|integer'
    ];*/


    public function logItem()
    {
        return $this->belongsTo(\App\Models\Log::class);
    }
}
