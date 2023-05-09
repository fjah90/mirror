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

      return $this->hasOne('App\Models\CatRegimen','cat_regimen_id','id');

     }

    public function CatFormaPago()
     {

      return $this->hasOne('App\Models\CatFormaPago','cat_forma_pago_id','id');

     }

    public function CatCfdi() 
    {

      return $this->hasOne('App\Models\CatCfdi','cat_cfdi_id','id');

    }

}
