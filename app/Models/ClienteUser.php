<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClienteUser
 * 
 * @property int $id
 * @property int $cliente_id
 * @property int|null $user_id
 * 
 * @property Cliente $cliente
 * @property User $user
 *
 * @package App\Models
 */
class ClienteUser extends Model
{
	protected $table = 'cliente_users';
	public $timestamps = false;

	protected $casts = [
		'cliente_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'cliente_id',
		'user_id'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
