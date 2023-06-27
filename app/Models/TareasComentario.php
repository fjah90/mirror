<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TareasComentario extends Model
{
    
    protected $table = 'tareas_comentarios';

    protected $fillable = ['comentario','user_id', 'tarea_id'];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
