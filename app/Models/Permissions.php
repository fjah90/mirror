<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class Permissions extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
		'name',
		'guard_name'
	];
}
