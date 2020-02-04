<?php

namespace App\Models;

use App\Model;

class ProspectoCotizacionEntrada extends Model
{
    protected $table = 'prospectos_cotizaciones_entradas';

    protected $fillable = ['cotizacion_id', 'producto_id', 'cantidad', 'medida',
        'precio', 'importe', 'fotos', 'observaciones', 'orden', 'precio_compra', 'fecha_precio_compra'];

    protected $casts = [
        'cantidad' => 'float',
        'precio'   => 'float',
        'importe'  => 'float',
    ];

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

}
