<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenProceso extends Model
{
    protected $table = 'ordenes_proceso';

    const STATUS_FABRICACION           = "En fabricación";
    const STATUS_EMBARCADO             = "Embarcado de fabrica";
    const STATUS_FRONTERA              = "En frontera";
    const STATUS_ADUANA                = "Aduana";
    const STATUS_IMPORTACION           = "Proceso de Importación";
    const STATUS_LIBERADO_ADUANA       = "Liberado de Aduana";
    const STATUS_EMBARCADO_FINAL       = "Embarque al destino Final";
    const STATUS_DESCARGA              = "Descarga";
    const STATUS_ENTREGADO             = "Entregado";

    protected $fillable = [
      'orden_compra_id',
      'status','factura','packing','bl',
      'certificado','gastos','pago',
      'deposito_warehouse','carta_entrega',
      'fecha_estimada_fabricacion',    'fecha_real_fabricacion',
      'fecha_estimada_embarque',       'fecha_real_embarque',
      'fecha_estimada_frontera',       'fecha_real_frontera',
      'fecha_estimada_aduana',         'fecha_real_aduana',
      'fecha_estimada_importacion',    'fecha_real_importacion',
      'fecha_estimada_liberado_aduana','fecha_real_liberado_aduana',
      'fecha_estimada_embarque_final', 'fecha_real_embarque_final',
      'fecha_estimada_descarga',       'fecha_real_descarga',
      'fecha_estimada_entrega',        'fecha_real_entrega',
      'fecha_estimada_instalacion',    'fecha_real_instalacion'
    ];

    /**
     * Converciones a formato de mysql. year-month-day
     * @param  string  $fecha en formato dia/mes/año
     * @return void
     */
    public function setFechaEstimadaFabricacionAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_fabricacion'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaEmbarqueAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_embarque'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaFronteraAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_frontera'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaAduanaAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_aduana'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaImportacionAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_importacion'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaLiberadoAduanaAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_liberado_aduana'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaEmbarqueFinalAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_embarque_final'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaDescargaAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_descarga'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaEntregaAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_entrega'] = "$ano-$mes-$dia";
    }
    public function setFechaEstimadaInstalacionAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha_estimada_instalacion'] = "$ano-$mes-$dia";
    }

    public function ordenCompra(){
      return $this->belongsTo('App\Models\OrdenCompra', 'orden_compra_id', 'id');
    }

}
