<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProspectoCotizacionEntrada extends Model
{
    protected $table = 'prospectos_cotizaciones_entradas';

    protected $fillable = ['cotizacion_id', 'producto_id', 'cantidad', 'medida',
        'precio', 'importe', 'fotos', 'observaciones', 'orden', 'precio_compra', 'fecha_precio_compra', 'proveedor_contacto_id'];

    protected $casts = [
        'cantidad' => 'float',
        'precio'   => 'float',
        'importe'  => 'float',
    ];

    public function setFechaPrecioCompraAttribute($value)
    {
        list($dia, $mes, $ano)                   = explode('/', $value);
        $this->attributes['fecha_precio_compra'] = "$ano-$mes-$dia";
    }

    public function getFechaPrecioCompraFormatedAttribute()
    {
        return Carbon::parse($this->fecha_precio_compra)->format('d/m/Y');
    }

    public function getFotosAttribute($value)
    {
        if ($value == "") {
            return [];
        }

        $fotos = explode('|', $value);
        foreach ($fotos as &$foto) {
            $foto = asset('storage/' . $foto);
        }

        return $fotos;
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cotizacion()
    {
        return $this->belongsTo('App\Models\ProspectoCotizacion', 'cotizacion_id', 'id');
    }

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'producto_id', 'id');
    }

    public function descripciones()
    {
        return $this->hasMany('App\Models\ProspectoCotizacionEntradaDescripcion', 'entrada_id', 'id');
    }

    public function contacto()
    {
        return $this->belongsTo('App\Models\ProveedorContacto', 'proveedor_contacto_id', 'id');
    }

}
