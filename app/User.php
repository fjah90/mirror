<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'firma'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['tipo'];

    public function getTipoAttribute()
    {
        $roles = $this->getRoleNames();
        if (count($roles)) return $roles[0];
        else return "";
    }


    public function clientes()
    {
        return $this->hasMany('App\Models\Cliente', 'usuario_id', 'id');
    }

    public function prospectos()
    {
        return $this->hasManyThrough('App\Models\Prospecto', 'App\Models\Cliente', 'usuario_id', 'cliente_id')
            ->orderBy('id', 'desc');
    }

    public function proyectos_aprobados()
    {
        return $this->hasManyThrough('App\Models\ProyectoAprobado', 'App\Models\ProspectoCotizacion', 'usuario_id', 'cotizacion_id');
    }

    /*public function prospectos()
    {
        return $this->hasMany(Prospecto::class);
    }*/
}
