<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $firma
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Cliente[] $clientes
 * @property Collection|Prospecto[] $prospectos
 * @property Collection|ProspectosCotizacione[] $prospectos_cotizaciones
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'firma',
		'remember_token',
		'status',
		'cliente_id'
	];

	public function clientes()
	{
		return $this->hasMany(Cliente::class, 'usuario_id');
	}

	public function prospectos()
	{
		return $this->hasMany(Prospecto::class);
	}

	public function prospectos_cotizaciones()
	{
		return $this->hasMany(ProspectosCotizacione::class);
	}
}
