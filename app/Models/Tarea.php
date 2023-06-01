<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    
    protected $table = 'tareas';

    protected $fillable = ['tarea','status','user_id', 'vendedor_id'];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}
