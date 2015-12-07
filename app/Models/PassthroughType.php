<?php
/**
 * Created by PhpStorm.
 * User: rvinke
 * Date: 20-11-15
 * Time: 13:44
 */

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PassthroughType extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

}