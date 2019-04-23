<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenProceso extends Model
{
    protected $table = 'ordenes_proceso';

    const STATUS_FABRICACION           = "En fabricación";
    const STATUS_EMBARCADO             = "Embarcado de fabrica";
    const STATUS_ADUANA                = "Aduana";
    const STATUS_IMPORTACION           = "Proceso de Importación";
    const STATUS_LIBERADO_ADUANA       = "Liberado de Aduana";
    const STATUS_EMBARCADO_FINAL       = "Embarque al destino Final";
    const STATUS_DESCARGA              = "Descarga";
    const STATUS_ENTREGADO             = "Entregado";

    protected $fillable = ['orden_compra_id','status','factura','packing','bl',
      'certificado','gastos','pago'
    ];

    public function ordenCompra(){
      return $this->belongsTo('App\Models\OrdenCompra', 'orden_compra_id', 'id');
    }

}
