<?php
namespace App\Observers;

use App\Models\OrdenCompra;
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
                $ent = OrdenCompraEntrada::create([
                    'orden_id'    => $orden->id,
                    'producto_id' => $entrada->producto_id,
                    'cantidad'    => $entrada->cantidad,
                    'medida'      => $entrada->medida,
                    'precio'      => $entrada->precio_compra ?? $entrada->precio,
                    'importe'     => $entrada->importe,
                ]);

                $orden->subtotal = bcadd($orden->subtotal, $entrada->importe, 2);

                foreach ($entrada->descripciones as $descripcion) {
                    OrdenCompraEntradaDescripcion::create([
                        'entrada_id' => $ent->id,
                        'nombre'     => $descripcion->nombre ?? '',
                        'name'       => $descripcion->name ?? '',
                        'valor'      => $descripcion->valor,
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
