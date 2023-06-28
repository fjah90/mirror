<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Venturecraft\Revisionable\RevisionableTrait;

class Tarea extends Model
{
    use RevisionableTrait;
    protected $table = 'tareas';

    protected $fillable = ['tarea','status','user_id', 'vendedor_id','director_id'];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function director()
    {
        return $this->belongsTo(User::class,'director_id');
    }

    public function comentarios(){
        return $this->hasMany('App\Models\TareasComentario', 'tarea_id', 'id');
    }
}
