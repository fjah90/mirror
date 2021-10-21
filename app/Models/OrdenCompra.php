<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compra';

    const STATUS_PENDIENTE = "Pendiente";
    const STATUS_POR_AUTORIZAR = "Por Autorizar";
    const STATUS_APROBADA = "Aprobada";
    const STATUS_CONFIRMADA = "Confirmada";
    const STATUS_RECHAZADA = "Rechazada";
    const STATUS_CANCELADA = "Cancelada";

    protected $fillable = ['cliente_id', 'proyecto_id', 'proveedor_id', 'orden_proceso_id',
        'cliente_nombre', 'proyecto_nombre', 'proveedor_empresa', 'status', 'moneda', 'subtotal',
        'iva', 'total', 'motivo_rechazo', 'archivo', 'numero', 'tiempo_entrega', 'numero_proyecto',
        'aduana_id', 'aduana_compañia', 'proveedor_contacto_id', 'confirmacion_fabrica', 'delivery',
        'punto_entrega', 'carga', 'fecha_compra'
    ];

    /*protected $dates = [
        'fecha_compra' => 'datetime:d/m/Y',
    ];*/

    protected $appends = ['fecha_compra_formated'];

    /**
     * Convercion a formato de mysql. year-month-day
     * @param  string  $fecha en formato dia/mes/año
     * @return void
     */
    public function setFechaCompraAttribute($value)
    {
        if ($value != null && $value != "") {
            if (strpos($value, "-")) {
                $value = Carbon::parse($value)->format('d/m/Y');
            }
            list($dia, $mes, $ano) = explode('/', $value);
            $this->attributes['fecha_compra'] = "$ano-$mes-$dia";
        }
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Appends
     * ---------------------------------------------------------------------------
     */

    public function getFechaCompraFormatedAttribute()
    {
        return Carbon::parse($this->fecha_compra)->format('d/m/Y');
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    protected $casts = [
        'subtotal' => 'float',
        'iva' => 'float',
        'total' => 'float',
    ];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente()
    {
        return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function proyecto()
    {
        return $this->belongsTo('App\Models\ProyectoAprobado', 'proyecto_id', 'id')->withTrashed();
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Models\Proveedor', 'proveedor_id', 'id')
            ->withDefault(['id' => 0, 'empresa' => 'Por Definir']);
    }

    public function contacto()
    {
        return $this->belongsTo('App\Models\ProveedorContacto', 'proveedor_contacto_id', 'id')
            ->withDefault(['id' => 0, 'nombre' => 'Por Definir']);
    }

    public function entradas()
    {
        return $this->hasMany('App\Models\OrdenCompraEntrada', 'orden_id', 'id');
    }

    public function ordenProceso()
    {
        return $this->hasOne('App\Models\OrdenProceso', 'orden_compra_id', 'id');
    }

    public function aduana()
    {
        return $this->belongsTo('App\Models\AgenteAduanal', 'aduana_id', 'id');
    }

}
