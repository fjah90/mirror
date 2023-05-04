<?php

namespace App\Models;

use App\Model;

class DatoFacturacion extends Model
{
    protected $table = 'datos_facturacion';

    protected $fillable = ['rfc', 'razon_social', 'calle',
        'nexterior', 'ninterior', 'colonia', 'cp', 'ciudad', 'estado', 'cliente_id','cat_regimen_id','cat_forma_pago_id','cat_cfdi_id'
    ];

    protected $appends = ['direccion'];

    public function getDireccionAttribute()
    {
        return $this->calle . " #" . $this->nexterior . " " . $this->ciudad . " " . $this->estado;
    }
    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

     public function CatRegimen() 
     {

      return $this->hasOne('App\Models\CatRegimen','id','cat_regimen_id');

     }

    public function CatFormaPago()
     {

      return $this->hasOne('App\Models\CatFormaPago','id','cat_forma_pago_id');

     }

    public function CatCfdi() 
    {

      return $this->hasOne('App\Models\CatCfdi','id','cat_cfdi_id');

    }

}
