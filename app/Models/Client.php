<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function user() {
        return $this->hasMany('App\User');
    }
}
