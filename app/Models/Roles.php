<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Roles extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name','guard_name'];
}
