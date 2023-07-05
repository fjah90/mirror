<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{

    protected $table = 'notificaciones';

    protected $fillable = ['user_creo','user_dirigido','texto','status',
    ];
    
    public function usercreo()
    {
        return $this->belongsTo(User::class,'user_creo');
    }

    public function userdirigido()
    {
        return $this->belongsTo(User::class,'user_dirigido');
    }
}
