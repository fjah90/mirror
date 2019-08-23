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

    public function getTipoAttribute(){
      $roles = $this->getRoleNames();
      if(count($roles)) return $roles[0];
      else return "";
    }


    public function clientes(){
      return $this->hasMany('App\Models\Cliente', 'usuario_id', 'id');
    }
}
