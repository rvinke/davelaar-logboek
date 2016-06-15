<?php

namespace App\Models;

use LaravelArdent\Ardent\Ardent;

class Report extends Ardent
{
    public static $rules = [
        'naam' => 'required|between:3,80',
        'organisatie' => 'required|between:3,80',
        'log_id' => 'required|integer'
    ];


    public function logItem() {
        return $this->belongsTo('App\Models\Log');
    }
}
