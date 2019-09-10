<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ObservacionCotizacion extends Model
{
    protected $table = 'observaciones_cotizacion';

    protected $fillable = ['texto','general','orden'];

    protected $appends = ['activa'];

    public function getActivaAttribute(){
      return false;
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */



}
