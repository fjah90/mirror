<?php

namespace App\Models;

use App\Model;

class TiempoEntrega extends Model
{
  protected $table = 'ordenes_compra_tiempos_entrega';

  protected $fillable = ['valor'];

  /**
   * ---------------------------------------------------------------------------
   *                             Relationships
   * ---------------------------------------------------------------------------
   */
}
