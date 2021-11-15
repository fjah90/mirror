<?php

namespace App\Models;

use App\Model;

class OrdenCompraEntrada extends Model
{
    protected $table = 'ordenes_compra_entradas';

    protected $fillable = ['orden_id', 'producto_id', 'cantidad', 'medida', 'conversion',
        'cantidad_convertida', 'precio', 'importe', 'comentarios','orden','fotos'];

    protected $casts = [
        'cantidad'            => 'float',
        'cantidad_convertida' => 'float',
        'precio'              => 'float',
        'importe'             => 'float',
    ];

    protected $appends = [
        'medida_ingles', 'conversion_ingles','area','foto_cotizacion'
    ];

    //TODO: esto deberia ser una columna en tabla unidades_medida, pero se guardan Strings en lugar de referencias a la tabla y el cliente quiere el cambio YA

    public function getMedidaInglesAttribute()
    {
        $result = $this->medida;
        switch ($result) {
            case 'Pies':
                $result = "Feet";
                break;
            case 'Pies2':
                $result = "Feet2";
                break;
            case 'Caja':
                $result = "Box";
                break;
            case 'Cubeta':
                $result = "Pails";
                break;
            case 'Piezas':
                $result = "Pieces";
                break;
            case 'Pieza':
                $result = "Piece";
                break;
            case 'Unidad':
                $result = "Unit";
                break;
            case 'Yarda2':
                $result = "Yard2";
                break;
            case 'Yarda':
                $result = "Yard";
                break;

            default:
                # code...
                break;
        }

        return $result;
    }
    public function getConversionInglesAttribute()
    {
        $result = $this->conversion;
        switch ($result) {
            case 'Pies':
                $result = "Feet";
                break;
            case 'Pies2':
                $result = "Feet2";
                break;
            case 'Caja':
                $result = "Box";
                break;
            case 'Cubeta':
                $result = "Pails";
                break;
            case 'Piezas':
                $result = "Pieces";
                break;
            case 'Pieza':
                $result = "Piece";
                break;
            case 'Unidad':
                $result = "Unit";
                break;
            case 'Yarda2':
                $result = "Yard2";
                break;
            case 'Yarda':
                $result = "Yard";
                break;

            default:
                # code...
                break;
        }

        return $result;
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

     public function getAreaAttribute()
    {

        $r = '';

    
        $entradascotizacion = $this->descripciones;
        foreach ($entradascotizacion as $key => $entrada) {
            if ($entrada->name == 'Area') {
                    $r = $entrada->valor;
            }
        }                    

        
        /*
        if ($orden->proyecto) {
            if ($orden->proyecto->cotizacion) {
                if ($orden->proyecto->cotizacion->entradas) {
                    $entradascotizacion = $orden->proyecto->cotizacion->entradas;

                    foreach ($entradascotizacion as $key => $entrada) {
                        if ($entrada->producto_id == $this->producto_id) {
                            $r = $entrada->area;
                        }
                    }            
                }
            }
        }
        */
    

        return $r;
    }


    public function getFotoCotizacionAttribute()
    {

        $r = '';
        if($this->orden){
            if ($this->orden->proyecto) {
                if ($this->orden->proyecto->cotizacion) {
                    if ($this->orden->proyecto->cotizacion->entradas) {
                        $entradascotizacion = $this->orden->proyecto->cotizacion->entradas;
                        foreach ($entradascotizacion as $key => $entrada) {
                            if ($entrada->producto_id == $this->producto_id) {
                                    $r = $entrada->fotos;
                            }
                        }                    
                    }
                }
            }
        }
    

        return $r;
    }


    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function orden()
    {
        return $this->belongsTo('App\Models\OrdenCompra', 'orden_id', 'id')->withTrashed();
    }

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'producto_id', 'id');
    }

    public function descripciones()
    {
        return $this->hasMany('App\Models\OrdenCompraEntradaDescripcion', 'entrada_id', 'id');
    }
}
