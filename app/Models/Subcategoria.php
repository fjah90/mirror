<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Subcategoria extends Model
{
    protected $table = 'subcategorias';

    protected $fillable = ['nombre','name'];

}
