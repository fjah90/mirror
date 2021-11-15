<?php

namespace App\Http\Controllers;

use App\Models\AgenteAduanal;
use App\Models\CuentaPagar;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraEntrada;
use App\Models\OrdenCompraEntradaDescripcion;
use App\Models\OrdenProceso;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\ProspectoCotizacion;
use App\Models\ProveedorContacto;
use App\Models\ProyectoAprobado;
use App\Models\TiempoEntrega;
use App\Models\UnidadMedida;
use App\Models\UnidadMedidaConversion;
use App\User;
use Illuminate\Http\Request;
use Mail;
use PDF;
use Storage;
use Validator;

class OrdenesCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $proyecto
     * @return \Illuminate\Http\Response
     */
    public function index($proyecto)
    {
        $ordenes = OrdenCompra::with('entradas.producto')
            ->where('proyecto_id', $proyecto)
            ->get();

        $proyect = ProyectoAprobado::findOrFail($proyecto);
        $cotizacion = ProspectoCotizacion::findOrFail($proyect->cotizacion_id);

        foreach ($ordenes as $orden) {
            if ($orden->archivo) {
                $orden->archivo = asset('storage/' . $orden->archivo);
            }

            $archivos_autorizacion = Storage::disk('public')->files('ordenes_compra/' . $orden->id . '/archivos_autorizacion');
            $archivos_autorizacion = array_map(function ($archivo) {
                return ['liga' => asset("storage/$archivo"), 'nombre' => basename($archivo)];
            }, $archivos_autorizacion);
            $orden->archivos_autorizacion = $archivos_autorizacion;
        }

        return view('ordenes-compra.index', compact('ordenes','cotizacion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \App\Models\ProyectoAprobado $proyecto
     * @return \Illuminate\Http\Response
     */
    public function create(ProyectoAprobado $proyecto)
    {

        $cotizacion = ProspectoCotizacion::findOrFail($proyecto->cotizacion_id);

        $proveedores = Proveedor::all();
        $contactos = ProveedorContacto::all();
        $unidades_medida = UnidadMedida::orderBy('simbolo')->get();
        $aduanas = AgenteAduanal::all();
        $tiempos_entrega = TiempoEntrega::all();
        $productos = Producto::with('categoria', 'proveedor', 'descripciones.descripcionNombre', 'proveedor.contactos')->has('categoria')->get();

        $now = date("d/m/Y");


        return view('ordenes-compra.create',
            compact('proyecto', 'proveedores', 'contactos', 'productos', 'unidades_medida', 'aduanas', 'tiempos_entrega', 'now','cotizacion')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProyectoAprobado $proyecto
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProyectoAprobado $proyecto)
    {
        $validator = Validator::make($request->all(), [
            'proyecto_id' => 'required',
            'proveedor_id' => 'required',
            'proveedor_contacto_id' => 'required',
            'numero' => 'required',
            'moneda' => 'required',
            'subtotal' => 'required',
            'iva' => 'required',
            'total' => 'required',
            'entradas' => 'required',
        ]);

        $proyecto->load('cotizacion', 'cotizacion.entradas', 'cotizacion.entradas.producto', 'cotizacion.entradas.contacto');


        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $create = $request->except('entradas', 'tiempo');
        $create['cliente_id'] = $proyecto->cliente_id;
        $create['cliente_nombre'] = $proyecto->cliente_nombre;
        $create['proyecto_nombre'] = $proyecto->proyecto;

        if (!is_null($request->tiempo['id'])) {
            if ($request->tiempo['id'] > 0) {
                $create['tiempo_entrega'] = TiempoEntrega::find($request->tiempo['id'])->valor;
            } else if ($request->tiempo['id'] == 0 && $request->tiempo['valor']) {
                TiempoEntrega::create(['valor' => $request->tiempo['valor']]);
                $create['tiempo_entrega'] = $request->tiempo['valor'];
            }
        }

        if ($request->iva == "1") {
            $create['iva'] = bcmul($create['subtotal'], 0.16, 2);
            $create['total'] = bcmul($create['subtotal'], 1.16, 2);
        } else {
            $create['total'] = $create['subtotal'];
        }

        $create['flete'] = $request->flete;
        $orden = OrdenCompra::create($create);

        //guardar entradas
        foreach ($request->entradas as $index => $entrada) {

            foreach ($proyecto->cotizacion->entradas as $key => $e) {
                    
                if ($entrada['producto_id'] == $e->producto_id) {
                    if ($e->medida_compra != null) {
                        $unidad_entrada  = UnidadMedida::where('simbolo',$entrada['medida'])->first();
                        $unidad_convertir = UnidadMedida::where('simbolo',$e->medida_compra)->first();
                        $conversion = UnidadMedidaConversion::where('unidad_medida_id',$unidad_entrada->id)->where('unidad_conversion_id',$unidad_convertir->id)->first();

                        if ($conversion != null) {
                            $entrada['conversion'] = $e->medida_compra;
                            $entrada['cantidad_convertida'] = $e->cantidad * $conversion->factor_conversion;                            
                        }
                    }
                }

            }

            //save fotos
            if ($entrada['fotos']) { //hay fotos
                $fotos     = "";
                $separador = "";
                foreach ($entrada['fotos'] as $foto_index => $foto) {
                    if (!is_string($foto)) {
                        $ruta = Storage::putFileAs(
                            'public/ordenes_compra/' . $orden->id,
                            $foto,
                            'entrada_' . ($index + 1) . '_foto_' . ($foto_index + 1) . '.' . $foto->guessExtension()
                        );
                        $ruta = str_replace('public/', '', $ruta);
                        $fotos .= $separador . $ruta;
                        $separador = "|";
                    } else {
                        $fotos .= $separador . strstr($foto, "ordenes_compra/");
                    }
                }
                $entrada['fotos'] = $fotos;
            }else {
                $entrada['fotos'] = "";
            }
            

            $entrada['orden_id'] = $orden->id;
            $ordenCompraEntrada = OrdenCompraEntrada::create($entrada);

            //save descriptions
            foreach ($entrada["descripciones"] as $descripcion) {
                OrdenCompraEntradaDescripcion::create([
                    'entrada_id' => $ordenCompraEntrada->id,
                    'nombre' => $descripcion["nombre"] ?? '',
                    'name' => $descripcion["name"] ?? '',
                    'valor' => $descripcion["valor"],
                    'valor_ingles' => $descripcion["valor_ingles"],
                ]);
            }
        }

        return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function show(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        
        $cotizacion = ProspectoCotizacion::findOrFail($proyecto->cotizacion_id);
        $orden->load('proveedor', 'contacto', 'entradas.producto');
        $archivos_autorizacion = Storage::disk('public')->files('ordenes_compra/' . $orden->id . '/archivos_autorizacion');
        $archivos_autorizacion = array_map(function ($archivo) {
            return ['liga' => asset("storage/$archivo"), 'nombre' => basename($archivo)];
        }, $archivos_autorizacion);

        return view('ordenes-compra.show', compact('proyecto', 'orden', 'archivos_autorizacion','cotizacion'));
    }

    /**
     * Cambia status a Por Autorizar.
     *
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function comprar(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        if (is_null($orden->proveedor_id)) {
            return response()->json(['success' => false, "error" => true,
                'message' => 'Falta agregar proveedor a la orden',
            ], 400);
        }

        if ($orden->status != OrdenCompra::STATUS_PENDIENTE) {
            return response()->json(['success' => false, "error" => true,
                'message' => 'La orden debe estar en estatus ' .
                    OrdenCompra::STATUS_PENDIENTE .
                    ' para poder ser comprada',
            ], 400);
        }

        //$this->avisarOrdenPorAprobar($orden);

        //generar PDF de orden
        $orden->load('proveedor', 'contacto', 'proyecto.cotizacion',
            'proyecto.cliente', 'entradas.producto.descripciones.descripcionNombre', 'aduana');
        $firmaAbraham = User::select('firma')->where('id', 2)->first()->firma;
        if ($firmaAbraham) {
            $firmaAbraham = storage_path('app/public/' . $firmaAbraham);
        } else {
            $firmaAbraham = public_path('images/firma_vacia.png');
        }

        $orden->firmaAbraham = $firmaAbraham;

        $url = 'ordenes_compra/' . $orden->id . '/orden_' . $orden->numero . '.pdf';
        foreach ($orden->entradas as $entrada) {
            if ($entrada->producto->foto) {
                $entrada->producto->foto = asset('storage/' . $entrada->producto->foto);
            }

        }

        list($ano, $mes, $dia) = explode('-', date('Y-m-d'));
        if ($orden->proveedor->nacional) {
            $meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO',
                'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
            $mes = $meses[+$mes - 1];
            $orden->fechaPDF = "$dia DE $mes DEL $ano";
            $vista = 'ordenes-compra.ordenPDF';
            $nombre = "nombre";
        } else {
            $meses = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY',
                'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'];
            $mes = $meses[+$mes - 1];
            $orden->fechaPDF = "$mes $dia, $ano";
            $vista = 'ordenes-compra.ordenInglesPDF';
            $nombre = "name";
        }

        $ordenPDF = PDF::loadView($vista, compact('orden', 'nombre'));
        Storage::disk('public')->put($url, $ordenPDF->output());
        unset($orden->fechaPDF);
        unset($orden->firmaAbraham);
        $orden->update(['status' => 'Por Autorizar', 'archivo' => $url]);

        return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Guarda archivo enviado para autorizacion.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function agregarArchivo(Request $request, ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        $validator = Validator::make($request->all(), [
            'nuevo_archivo' => 'required|file|mimes:jpeg,jpg,png,pdf',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        if ($request->nombre_archivo) {
            $nombre_archivo = $request->nombre_archivo . "." . $request->nuevo_archivo->guessExtension();
        } else {
            $nombre_archivo = $request->nuevo_archivo->getClientOriginalName();
        }

        $archivo = Storage::putFileAs(
            'public/ordenes_compra/' . $orden->id . '/archivos_autorizacion',
            $request->nuevo_archivo, $nombre_archivo
        );
        $archivo = asset('storage/' . str_replace('public/', '', $archivo));

        return response()->json(['success' => true, "error" => false, "archivo" => [
            "liga" => $archivo, "nombre" => $nombre_archivo,
        ]], 200);
    }

    /**
     * Eliminar archivo de carpeta de autorizacion.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function borrarArchivo(Request $request, ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        $validator = Validator::make($request->all(), [
            'archivo' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        Storage::disk('public')->delete('ordenes_compra/' . $orden->id . '/archivos_autorizacion/' . $request->archivo);

        return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Cambiar a status Confirmada.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function confirmar(Request $request, ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        if ($orden->status != OrdenCompra::STATUS_APROBADA) {
            return response()->json([
                'success' => false, "error" => true,
                'message' => 'La orden debe estar en estatus '
                    . OrdenCompra::STATUS_APROBADA
                    . ' para poder ser confirmada',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'confirmacion_fabrica' => 'required|file|mimes:jpeg,jpg,png,pdf',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $confirmacion = Storage::putFileAs(
            'public/ordenes_compra/' . $orden->id, $request->confirmacion_fabrica,
            "confirmacion_fabrica." . $request->confirmacion_fabrica->guessExtension()
        );
        $confirmacion = str_replace('public/', '', $confirmacion);
        $orden->update(['confirmacion_fabrica' => $confirmacion, 'status' => OrdenCompra::STATUS_CONFIRMADA]);

        return response()->json([
            'success' => true, "error" => false, "confirmacion" => asset('storage/' . $confirmacion),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function edit(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        
        $cotizacion = ProspectoCotizacion::findOrFail($proyecto->cotizacion_id);
        $proveedores = Proveedor::all();
        $contactos = ProveedorContacto::all();
        $aduanas = AgenteAduanal::all();
        $productos = Producto::with('categoria', 'proveedor', 'descripciones.descripcionNombre', 'proveedor.contactos')->has('categoria')->get();
        $unidades_medida = UnidadMedida::with('conversiones')->orderBy('simbolo')->get();
        $tiempos_entrega = TiempoEntrega::all();
        $orden->load('proveedor', 'contacto', 'entradas.producto', 'entradas.descripciones');
        if ($orden->iva > 0) {
            $orden->iva = 1;
        }
        $proyecto->load('cotizacion', 'cotizacion.entradas', 'cotizacion.entradas.producto', 'cotizacion.entradas.contacto');

        $tiempo_entrega = TiempoEntrega::where('valor', $orden->tiempo_entrega)->first();
        if (is_null($tiempo_entrega)) {
            $orden->tiempo = ['id' => '', 'valor' => ''];
        } else {
            $orden->tiempo = ['id' => $tiempo_entrega->id, 'valor' => ''];
        }

        return view('ordenes-compra.edit',
            compact('proyecto', 'orden', 'productos', 'proveedores', 'contactos', 'unidades_medida', 'aduanas', 'tiempos_entrega','cotizacion')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        dd($request->entradas);
        
        $validator = Validator::make($request->all(), [
            'proyecto_id' => 'required',
            'proveedor_id' => 'required',
            'numero' => 'required',
            'moneda' => 'required',
            'subtotal' => 'required',
            'iva' => 'required',
            'total' => 'required',
            'entradas' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $update = $request->only(
            'proveedor_id', 'proveedor_empresa', 'moneda', 'numero', 'subtotal', 'numero_proyecto',
            'aduana_id', 'aduana_compañia', 'proveedor_contacto_id', 'punto_entrega', 'carga', 'fecha_compra','flete'
        );

        if (!is_null($request->tiempo['id'])) {
            if ($request->tiempo['id'] > 0) {
                $update['tiempo_entrega'] = TiempoEntrega::find($request->tiempo['id'])->valor;
            } else if ($request->tiempo['id'] == 0 && $request->tiempo['valor']) {
                TiempoEntrega::create(['valor' => $request->tiempo['valor']]);
                $update['tiempo_entrega'] = $request->tiempo['valor'];
            }
        } else {
            $update['tiempo_entrega'] = '';
        }

        if ($request->iva == "1") {
            $update['iva'] = bcmul($update['subtotal'], 0.16, 2);
            $update['total'] = bcmul($update['subtotal'], 1.16, 2);
        } else {
            $update['iva'] = 0;
            $update['total'] = $update['subtotal'];
        }
        
        if ($orden->status == OrdenCompra::STATUS_RECHAZADA) {
            $update['status'] = OrdenCompra::STATUS_PENDIENTE;
           // $this->avisarOrdenPorAprobar($orden);
        }
        
        $update['delivery'] = $request->delivery;
        $update['fecha_compra'] = $request->fecha_compra_formated;
        $orden->update($update);

        //sincronizar entradas
        foreach ($request->entradas as $entrada) {
            if ($orden->moneda == 'Dolares') {
                $Uni = UnidadMedida::where('simbolo',$entrada['medida'])->first();
                if (!empty($Uni->simbolo_ingles)) {
                    $entrada['medida'] = $Uni->simbolo_ingles;    
                }
            }

            if (isset($entrada['id'])) {
                $ent = OrdenCompraEntrada::find($entrada['id']);

                if (isset($entrada['borrar'])) { //borrar entrada
                    $ent->delete();
                    continue;
                }


                if ($entrada['fotos'] && !is_string($entrada['fotos'][0])) { //hay fotos
                    $fotos     = "";
                    $separador = "";
                    foreach ($entrada['fotos'] as $foto_index => $foto) {
                        //borrar archivo actual, si existe
                        Storage::disk('public')->delete('ordenes_compra/' . $orden->id . '/entrada_' . ($index + 1) . '_foto_' . ($foto_index + 1) . '.' . $foto->guessExtension());
                        $ruta = Storage::putFileAs(
                            'public/ordenes_compra/' . $orden->id,
                            $foto,
                            'entrada_' . ($index + 1) . '_foto_' . ($foto_index + 1) . '.' . $foto->guessExtension()
                        );
                        $ruta = str_replace('public/', '', $ruta);
                        $fotos .= $separador . $ruta;
                        $separador = "|";
                    }
                    $entrada['fotos'] = $fotos;
                } else if ($ent->producto_id == $producto->id) {
                    if ($entrada['fotos']) {
                        
                    }
                    else{
                        $entrada['fotos']='';
                        $ent->fotos = '';
                    }
                    unset($entrada['fotos']); //no se actualzan fotos
                }else {
                    $entrada['fotos'] = "";
                }

                //actualizar entrada ya guardada
                unset($entrada['id']);
                if ($entrada['cantidad_convertida'] == "") {
                    unset($entrada['conversion']);
                    unset($entrada['cantidad_convertida']);
                }
                $ent->update($entrada);
                //$ent->descripciones()->delete();

                foreach ($entrada["descripciones"] as $descripcion) {
                    $orderCopraEntrantDescription = OrdenCompraEntradaDescripcion::find($descripcion['id']);
                    $orderCopraEntrantDescription->update([
                        'nombre' => $descripcion["nombre"] ?? '',
                        'name' => $descripcion["name"] ?? '',
                        'valor' => $descripcion["valor"],
                        'valor_ingles' => $descripcion["valor_ingles"],
                    ]);
                }
                continue;
            }
            else{
                //crear nueva entrada
                $entrada['orden_id'] = $orden->id;

                if ($entrada['fotos'] && !is_string($entrada['fotos'][0])) { //hay fotos
                    $fotos     = "";
                    $separador = "";
                    foreach ($entrada['fotos'] as $foto_index => $foto) {
                        $ruta = Storage::putFileAs(
                            'public/ordenes_compra/' . $orden->id,
                            $foto,
                            'entrada_' . ($index + 1) . '_foto_' . ($foto_index + 1) . '.' . $foto->guessExtension()
                        );
                        $ruta = str_replace('public/', '', $ruta);
                        $fotos .= $separador . $ruta;
                        $separador = "|";
                    }
                    $entrada['fotos'] = $fotos;
                }else {
                    $entrada['fotos'] = "";
                }


                $ordenCompraEntrada = OrdenCompraEntrada::create($entrada);
                //save descriptions
                foreach ($entrada["descripciones"] as $descripcion) {
                    OrdenCompraEntradaDescripcion::create([
                        'entrada_id' => $ordenCompraEntrada->id,
                        'nombre' => $descripcion["nombre"] ?? '',
                        'name' => $descripcion["name"] ?? '',
                        'valor' => $descripcion["valor"],
                        'valor_ingles' => $descripcion["valor_ingles"],
                    ]);
                }

            }

            
        }

        /*if ($orden->status == OrdenCompra::STATUS_RECHAZADA) {
            $this->avisarOrdenPorAprobar($orden);
        }*/

        return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        if ($orden->status == OrdenCompra::STATUS_APROBADA) {
            return response()->json(['success' => false, "error" => true,
                'message' => 'No se puede cancelar la orden porque esta en estatus '
                    . OrdenCompra::STATUS_APROBADA,
            ], 400);
        }
        $orden->update(['status' => OrdenCompra::STATUS_CANCELADA]);

        return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Cambia status a Aprobada.
     *
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function aprobar(ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        if ($orden->status != OrdenCompra::STATUS_POR_AUTORIZAR) {
            return response()->json(['success' => false, "error" => true,
                'message' => 'La orden debe estar en estatus '
                    . OrdenCompra::STATUS_POR_AUTORIZAR
                    . ' para poder ser aprobada',
            ], 400);
        }

        //generar numero de orden (proceso)
        if ($orden->proveedor->nacional) {
            $hoy = date('d/m/Y');
            $hoy2 = date('Y-m-d');
            $orden_proceso = OrdenProceso::create([
                'orden_compra_id' => $orden->id,
                'numero' => $orden->numero,
                'status' => OrdenProceso::STATUS_DESCARGA,
                'fecha_estimada_fabricacion' => $hoy, 'fecha_real_fabricacion' => $hoy2,
                'fecha_estimada_embarque' => $hoy, 'fecha_real_embarque' => $hoy2,
                'fecha_estimada_frontera' => $hoy, 'fecha_real_frontera' => $hoy2,
                'fecha_estimada_aduana' => $hoy, 'fecha_real_aduana' => $hoy2,
                'fecha_estimada_importacion' => $hoy, 'fecha_real_importacion' => $hoy2,
                'fecha_estimada_liberado_aduana' => $hoy, 'fecha_real_liberado_aduana' => $hoy2,
                'fecha_estimada_embarque_final' => $hoy, 'fecha_real_embarque_final' => $hoy2,
            ]);
        } else {
            $orden_proceso = OrdenProceso::create([
                'orden_compra_id' => $orden->id,
                'numero' => $orden->numero,
            ]);
        }

        //generar cuenta por pagar
        $create = [
            'proveedor_id' => $orden->proveedor_id,
            'orden_compra_id' => $orden->id,
            'proveedor_empresa' => $orden->proveedor_empresa,
            'proyecto_nombre' => $orden->proyecto_nombre,
            'moneda' => $orden->moneda,
            'dias_credito' => $orden->proveedor->dias_credito,
            'total' => $orden->total,
            'pendiente' => $orden->total,
        ];
        CuentaPagar::create($create);

        //actualizar orden
        $orden->update([
            'status' => OrdenCompra::STATUS_APROBADA,
            'orden_proceso_id' => $orden_proceso->id,
        ]);

        return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Cambia status a Rechazada.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ProyectoAprobado $proyecto
     * @param \App\Models\OrdenCompra $orden
     * @return \Illuminate\Http\Response
     */
    public function rechazar(Request $request, ProyectoAprobado $proyecto, OrdenCompra $orden)
    {
        $validator = Validator::make($request->all(), ['motivo_rechazo' => 'required']);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        if ($orden->status != OrdenCompra::STATUS_POR_AUTORIZAR) {
            return response()->json(['success' => false, "error" => true,
                'message' => 'La orden debe estar en estatus '
                    . OrdenCompra::STATUS_POR_AUTORIZAR
                    . ' para poder ser rechazada',
            ], 400);
        }
        $orden->update([
            'status' => OrdenCompra::STATUS_RECHAZADA,
            'motivo_rechazo' => $request->motivo_rechazo,
        ]);

        $this->avisarOrdenRechazada($orden);

        return response()->json(['success' => true, "error" => false], 200);
    }

    /*
     * Envia mensaje de aviso de nueva orden por aprobar por correo a
     * abraham@intercorp.mx
     * @param  \App\Models\OrdenCompra  $orden
     */
    public function avisarOrdenPorAprobar($orden)
    {
        $mensaje = "Hay una nueva orden por autorizar de parte del usuario: " . auth()->user()->name ." https://intercorp.789.com.mx/proyectos-aprobados/".$orden->proyecto_id."/ordenes-compra/".$orden->id."editar";
        $mensaje .= ", para el proyecto " . $orden->proyecto_nombre;
        Mail::send('email', ['mensaje' => $mensaje], function ($message) use($orden) {
                $message->to('abraham@intercorp.mx')
                //$message->to('edmar.gomez@tigears.com')
                ->subject('Nueva orden por autorizar');
                $message->attach(public_path().'/'.$orden->archivo);
        });
    }

    /*
     * Envia mensaje de aviso de orden rechazada por correo a usuario dueño del proyecto
     * @param  \App\Models\OrdenCompra  $orden
     */
    public function avisarOrdenRechazada($orden)
    {
        $mensaje = "Abraham ha rechazado su orden para el proyecto " . $orden->proyecto_nombre;
        $mensaje .= "<br />Motivo: " . $orden->motivo_rechazo;
        $email = $orden->proyecto->cotizacion->user->email;
        Mail::send('email', ['mensaje' => $mensaje], function ($message) use ($email) {
            $message->to($email)
                ->subject('Su orden ha sido rechazada');
        });
    }

    /**
     * Para crear pdf de una orden a voluntad.
     *
     */
    public function regeneratePDF(Request $request)
    {
        $orden = OrdenCompra::with('proveedor', 'contacto', 'proyecto.cotizacion',
            'proyecto.cliente', 'entradas.producto.descripciones.descripcionNombre', 'aduana')
            ->where('id', $request->orden_id)->first();
        $firmaAbraham = User::select('firma')->where('id', 2)->first()->firma;
        if ($firmaAbraham) {
            $firmaAbraham = storage_path('app/public/' . $firmaAbraham);
        } else {
            $firmaAbraham = public_path('images/firma_vacia.png');
        }

        $orden->firmaAbraham = $firmaAbraham;

        $url = 'ordenes_compra/' . $orden->id . '/orden_' . $orden->numero . '.pdf';
        foreach ($orden->entradas as $entrada) {
            if ($entrada->producto->foto) {
                $entrada->producto->foto = asset('storage/' . $entrada->producto->foto);
            }

        }

        list($ano, $mes, $dia) = explode('-', date('Y-m-d'));
        if ($orden->proveedor->nacional) {
            $meses = [
                'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO',
                'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE',
            ];
            $mes = $meses[+$mes - 1];
            $orden->fechaPDF = "$dia DE $mes DEL $ano";
            $vista = 'ordenes-compra.ordenPDF';
            $nombre = 'nombre';
        } else {
            $meses = [
                'JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY',
                'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER',
            ];
            $mes = $meses[+$mes - 1];
            $orden->fechaPDF = "$mes $dia, $ano";
            $vista = 'ordenes-compra.ordenInglesPDF';
            $nombre = 'name';
        }

        // return view($vista, compact('orden'));
        $ordenPDF = PDF::loadView($vista, compact('orden', 'nombre'));
        Storage::disk('public')->put($url, $ordenPDF->output());
        unset($orden->fechaPDF);
        unset($orden->firmaAbraham);
        $orden->update(['archivo' => $url]);

        return $ordenPDF->download('orden.pdf');
    }

    public function checarDescripciones()
    {
        $proyectos = ProyectoAprobado::with('ordenes', 'ordenes.entradas', 'ordenes.entradas.descripciones')->get();
        $tieneDescripciones = [];
        foreach ($proyectos as $proyecto) {
            foreach ($proyecto->ordenes as $orden) {
                $tieneDescripciones[$orden->id] = sizeof($orden->entradas[0]->descripciones) > 0;
                // $tieneDescripciones[$orden->id] = $orden->entradas;
            }
        }
        return response()->json(['success' => true, "error" => false, "data" => $tieneDescripciones], 200);

    }

}
