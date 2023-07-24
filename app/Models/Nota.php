<?php

namespace App\Models;

use App\Model;

class Nota extends Model
{
  protected $fillable = ['titulo', 'contenido'];

  public function getFechaCreacionAttribute($value)
  {
    return date('d-m-Y H:i:s', strtotime($value));
  }

  public function getFechaActualizacionAttribute($value)
  {
    return date('d-m-Y H:i:s', strtotime($value));
  }
}