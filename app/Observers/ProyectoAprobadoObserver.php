<?php
namespace App\Observers;

use App\Models\OrdenCompra;
use App\Models\UnidadMedida;
use App\Models\UnidadMedidaConversion;
use App\Models\OrdenCompraEntrada;
use App\Models\OrdenCompraEntradaDescripcion;

class ProyectoAprobadoObserver
{

    /*
     * Crear ordenes de compra para proveedores involucrados en el proyecto
     */
    public function created($proyecto)
    {
        $proyecto->load('cotizacion.entradas.producto.proveedor', 'cotizacion.entradas.descripciones');

        //se va a crear una orden por cada proveedor
        $proveedores = $proyecto->cotizacion->entradas->groupBy(function ($entrada) {
            return $entrada->producto->proveedor->empresa;
        });

        foreach ($proveedores as $proveedor => $entradas) {
            $create = [
                'cliente_id'        => $proyecto->cliente_id,
                'proyecto_id'       => $proyecto->id,
                'proveedor_id'      => $entradas->first()->producto->proveedor_id,
                'cliente_nombre'    => $proyecto->cliente_nombre,
                'proyecto_nombre'   => $proyecto->proyecto,
                'proveedor_empresa' => $proveedor,
                'moneda'            => $entradas->first()->producto->proveedor->moneda ?? 'Dolares',
            ];

            $orden = OrdenCompra::create($create);
            $orden->update(['numero' => $orden->id]);

            foreach ($entradas as $entrada) {


                if ($entrada->medida_compra != $entrada->medida) {

                    if($entrada->medida_compra != null){


                        $unidad_medida = UnidadMedida::where('simbolo',$entrada->medida)->first();
                        $unidad_convertir = UnidadMedida::where('simbolo',$entrada->medida_compra)->first();
                        $conversion = UnidadMedidaConversion::where('unidad_medida_id',$unidad_medida->id)->where('unidad_conversion_id',$unidad_convertir->id)->first();
                        $cantidad_convertida = $entrada->cantidad * $conversion->factor_conversion;

                        if ($entrada->precio_compra != null) {
                            $importe = $entrada->precio_compra * $cantidad_convertida;
                        }
                        else{
                            $importe = $entrada->precio * $cantidad_convertida;   
                        }
                    }
                    
                }
                else{
                    $cantidad_convertida = null;
                    $importe = $entrada->importe;
                }


                $ent = OrdenCompraEntrada::create([
                    'orden_id'    => $orden->id,
                    'producto_id' => $entrada->producto_id,
                    'cantidad'    => $entrada->cantidad,
                    'medida'      => $entrada->medida,
                    'precio'      => $entrada->precio_compra ?? $entrada->precio,
                    'importe'     => $importe,
                    'conversion' => $entrada->medida_compra,
                    'cantidad_convertida'=> $cantidad_convertida
                ]);

                $orden->subtotal = bcadd($orden->subtotal, $importe, 2);

                foreach ($entrada->descripciones as $descripcion) {
                    OrdenCompraEntradaDescripcion::create([
                        'entrada_id'   => $ent->id,
                        'nombre'       => $descripcion->nombre ?? '',
                        'name'         => $descripcion->name ?? '',
                        'valor'        => $descripcion->valor,
                        'valor_ingles' => $descripcion->valor_ingles,
                    ]);
                }
            }

            if ($proyecto->cotizacion->iva > 0) {
                $orden->iva = bcmul($orden->subtotal, 0.16, 2);
            } else {
                $orden->iva = 0;
            }

            $orden->total = bcadd($orden->subtotal, $orden->iva, 2);
            $orden->save();
        } //foreach proveedores

    }

}
