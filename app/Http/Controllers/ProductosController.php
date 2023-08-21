<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\CategoriaDescripcion;
use App\Models\Producto;
use App\Models\ProductoDescripcion;
use App\Models\Proveedor;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use Mail;
use Storage;
use Validator;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $productos = Cache::remember('productos', 60, function () {
        //     return Producto::with('proveedor', 'categoria', 'subcategoria')
        //         ->has('categoria')
        //         ->where('status', 'ACTIVO')
        //         ->get();
        // });

        // $productos = Cache::get('productos');
        $productos = Producto::with('proveedor', 'categoria', 'subcategoria')
            ->has('categoria')
            ->where('status', 'ACTIVO')
            ->get();

        $view = view('catalogos.productos.index', compact('productos'));
        return response($view);
    }


    public function productosinactivos()
    {

        $productos = Producto::with('proveedor', 'categoria', 'subcategoria')
            ->has('categoria')
            ->where('status', 'INACTIVO')
            ->get();

        return view('catalogos.productos.index', compact('productos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create2(Request $request)
    {
        $layout = $request->layout;
        return view('catalogos.productos.create2', compact('layout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $layout = $request->layout;
        $proveedores = Proveedor::orderBy('empresa')->get();
        $categorias = Categoria::with('descripciones')->orderBy('nombre')->get();
        $subcategorias = Subcategoria::orderBy('nombre')->get();
        return view('catalogos.productos.create', compact('proveedores', 'categorias', 'subcategorias', 'layout'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'proveedor_id'        => 'required',
            'subcategoria_id'     => 'required',
            'categoria_id'        => 'required',
            'nombre'              => 'required',
            'precio_unitario'     => 'required',
            'precio_residencial'  => 'required',
            'precio_comercial'    => 'required',
            'precio_distribuidor' => 'required',
            'nombre_material'     => 'required',
            'color'               => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false,
                "error"   => true,
                "message" => $errors[0],
            ], 422);
        }

        $create = $request->only('categoria_id', 'nombre');
        if ($request->proveedor_id != 0) {
            $create['proveedor_id'] = $request->proveedor_id;
        }

        if (!is_null($request->subcategoria_id)) {
            $create['subcategoria_id'] = $request->subcategoria_id;
        }

        //$producto = Producto::create($create);

        $producto = Producto::create([
            'proveedor_id'        => $request->proveedor_id,
            'subcategoria_id'     => $request->subcategoria_id,
            'categoria_id'        => $request->categoria_id,
            'nombre'              => $request->nombre,
            'nombre_material'     => $request->nombre_material,
            'color'               => $request->color,
            'precio_unitario'     => $request->precio_unitario,
            'precio_residencial'  => $request->precio_residencial,
            'precio_comercial'    => $request->precio_comercial,
            'precio_distribuidor' => $request->precio_distribuidor,
        ]);

        if (isset($request->foto)) {
            $foto = Storage::putFile('public/productos', $request->file('foto'));
            $foto = str_replace('public/', '', $foto);
            $producto->update(['foto' => $foto]);
        }
        if (isset($request->ficha_tecnica)) {
            $nombre_temp = "temp" . time() . ".pdf";
            $nombre_bueno = "ficha_tecnica_producto_" . $producto->id . ".pdf";

            Storage::putFileAs('public/productos', $request->file('ficha_tecnica'), $nombre_temp);
            //comando para pasar pdf a version 1.4, para poderlo "mergear" a pdfs de cotizacion
            $comando = "ps2pdf14 storage/productos/$nombre_temp storage/productos/$nombre_bueno";
            exec_in_background($comando);
            $producto->update(['ficha_tecnica' => "productos/$nombre_bueno"]);
        }

        foreach ($request->descripciones as $descripcion) {
            $create = array(
                "producto_id"              => $producto->id,
                "categoria_descripcion_id" => $descripcion['id'],
            );
            if (isset($descripcion['valor'])) {
                $create['valor'] = $descripcion['valor'];
            }

            if (isset($descripcion['valor_ingles_valor'])) {
                $create['valor_ingles'] = $descripcion['valor_ingles_valor'];
            }

            ProductoDescripcion::create($create);
        }

        if (auth()->user()->tipo !== 'Administrador') {
            $mensaje = "El usuario " . auth()->user()->name;
            $mensaje .= " ha dado de alta un nuevo producto con nombre: " . $producto->nombre;
            Mail::send('email', ['mensaje' => $mensaje], function ($message) {
                $message->to('abraham@intercorp.mx')->subject('Nueva Alta de Producto');
            });
        }

        $productorespuesta = Producto::where('id', '=', $producto->id)->with('categoria', 'proveedor', 'descripciones.descripcionNombre', 'proveedor.contactos')->first();
        if ($productorespuesta->foto) {
            $productorespuesta->foto = asset('storage/' . $productorespuesta->foto);
        }
        if ($productorespuesta->ficha_tecnica) {
            $productorespuesta->ficha_tecnica = asset('storage/' . $productorespuesta->ficha_tecnica);
        }
        return response()->json(['success' => true, "error" => false, "producto" => $productorespuesta], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function guardar(Request $request)
    {
        if (isset($request->archivo)) {
            $file = $request->file('archivo');
            $archivo = time() . $file->getClientOriginalName();
            $file->move(public_path() . '/archivos/', $archivo);

            $path = public_path() . '/archivos/' . $archivo;
            $data = array_map('str_getcsv', file($path));
            if (count($data) > 1) {

                array_shift($data);

                $this->storeImports($data);
                return response()->json(['success' => true, "error" => false], 200);
            }
            else {
                return response()->json([
                    "success" => false,
                    "error"   => true,
                    "message" => 'Error con el archivo',
                ], 422);
            }
        }
        else {
            return response()->json([
                "success" => false,
                "error"   => true,
                "message" => 'Falta archivo',
            ], 422);
        }
    }

    private function storeImports($rows)
    {

        foreach ($rows as $key => $row) {
            $proveedor = Proveedor::where('empresa', $row[1])->first();
            $categoria = Categoria::where('nombre', $row[2])->first();

            if ($row[3] == null || $row[3] == "") {
                $subcategoria = null;
            }
            else {
                $sub = Subcategoria::where('nombre', $row[3])->first();
                if ($sub) {
                    $subcategoria = $sub->id;
                }
                else {
                    $subcategoria = null;
                }
            }
            $producto = [
                "proveedor_id"        => $proveedor->id,
                "categoria_id"        => $categoria->id,
                "nombre"              => $row[0],
                "nombre_material"     => $row[8],
                "color"               => $row[9],
                "subcategoria_id"     => $subcategoria,
                "precio_unitario"     => $row[4],
                "precio_residencial"  => $row[5],
                "precio_comercial"    => $row[6],
                "precio_distribuidor" => $row[7],
            ];

            $p = Producto::where('nombre', $row[0])->first();
            if ($p) {
                $p->update($producto);
            }
            else {
                $p = Producto::create($producto);
            }

            //cargar las desripciones

            //descripciones en el orden del archvo
            $descripciones_tapices = [
                "Ancho",
                "Composición",
                "Flamabilidad",
                "Minimos de venta",
                "Multiplos de venta",
                "Tamaño de rollo"
            ];
            $descripciones_telas = [
                "Ancho",
                "Composición",
                "Flamabilidad",
                "Abrasión",
                "Decoloracion de la luz",
                "Traspaso de color",
                "Peeling",
                "Aplicación",
                "Acabado",
                "Procedencia",
                "Código de lavado",
                "Repeat HV",
                "Unidad de venta",
                "Notas/otros",
                "Backing",
            ];

            if (count($row) == 25) {
                $columnas = 25;
                $descripciones = $descripciones_telas;
            }
            else {
                $columnas = 16;
                $descripciones = $descripciones_tapices;
            }
            for ($i = 10; $i < $columnas; $i++) {

                $update = array(
                    "valor" => $row[$i]
                );

                $descripcion = CategoriaDescripcion::where('categoria_id', $categoria->id)->where('nombre', $descripciones[$i - 10])->first();
                if ($descripcion) {
                    $productodescripcion = ProductoDescripcion::where('producto_id', $p->id)->where('categoria_descripcion_id', $descripcion['id'])->first();
                    if ($productodescripcion) {
                        $productodescripcion->update($update);
                    }
                    else {
                        $create = array(
                            "producto_id"              => $p->id,
                            "categoria_descripcion_id" => $descripcion['id'],
                            "valor"                    => $row[$i]
                        );
                        ProductoDescripcion::create($create);
                    }
                }
            }



        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        $producto->load('proveedor', 'categoria', 'subcategoria', 'descripciones.descripcionNombre');
        if ($producto->foto) {
            $producto->foto = asset('storage/' . $producto->foto);
        }

        if ($producto->ficha_tecnica) {
            $producto->ficha_tecnica = asset('storage/' . $producto->ficha_tecnica);
        }

        return view('catalogos.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $proveedores = Proveedor::orderBy('empresa')->get();
        $categorias = Categoria::with('descripciones')->orderBy('nombre')->get();
        $subcategorias = Subcategoria::orderBy('nombre')->get();
        $producto->load('proveedor', 'categoria.descripciones', 'subcategoria', 'descripciones.descripcionNombre');
        //dd($producto);

        //dd(1);

        $producto_descripciones = $producto->descripciones->count();
        $categoria_descripciones = $producto->categoria->descripciones->count();
        // dd($producto_descripciones, $categoria_descripciones);
        if ($producto_descripciones < $categoria_descripciones) {
            //hay descripciones nuevas en categoria
            $nuevas = $producto->categoria->descripciones->sortBy('id')
                ->splice($producto_descripciones);
            foreach ($nuevas as $nueva) {
                ProductoDescripcion::create([
                    'producto_id'              => $producto->id,
                    'categoria_descripcion_id' => $nueva->id,
                ]);
            }

            $producto->load('descripciones.descripcionNombre');
        }

        foreach ($producto->descripciones as $descripcion) {
            $descripcion->nombre = $descripcion->descripcionNombre->nombre;
            $descripcion->name = $descripcion->descripcionNombre->name;
        }

        if ($producto->foto) {
            $producto->foto = asset('storage/' . $producto->foto);
        }

        if ($producto->ficha_tecnica) {
            $producto->ficha_tecnica = asset('storage/' . $producto->ficha_tecnica);
        }

        return view('catalogos.productos.edit', compact('producto', 'proveedores', 'categorias', 'subcategorias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'proveedor_id'        => 'required',
            'categoria_id'        => 'required',
            'nombre'              => 'required',
            'precio_unitario'     => 'required',
            'precio_residencial'  => 'required',
            'precio_comercial'    => 'required',
            'precio_distribuidor' => 'required',
            'nombre_material'     => 'required',
            'color'               => 'required'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false,
                "error"   => true,
                "message" => $errors[0],
            ], 422);
        }

        $update = $request->only(
            'categoria_id',
            'nombre',
            'precio_unitario',
            'precio_residencial',
            'precio_comercial',
            'precio_distribuidor',
            'nombre_material',
            'color'
        );
        $update['proveedor_id'] = ($request->proveedor_id != 0) ? $request->proveedor_id : null;
        if (!is_null($request->foto)) {
            Storage::delete('public/' . $producto->foto);
            $update['foto'] = Storage::putFile('public/productos', $request->file('foto'));
            $update['foto'] = str_replace('public/', '', $update['foto']);
        }
        if (!is_null($request->ficha_tecnica)) {
            Storage::delete('public/' . $producto->ficha_tecnica);
            $nombre_temp = "temp" . time() . ".pdf";
            $nombre_bueno = "ficha_tecnica_producto_" . $producto->id . "t" . time() . ".pdf";

            Storage::putFileAs('public/productos', $request->file('ficha_tecnica'), $nombre_temp);

            //comando para pasar pdf a version 1.4, para poderlo "mergear" a pdfs de cotizacion
            $comando = "ps2pdf14 storage/productos/$nombre_temp storage/productos/$nombre_bueno";
            exec_in_background($comando);

            $update['ficha_tecnica'] = "productos/$nombre_bueno";
        }
        if (is_null($request->subcategoria_id)) {
            $update['subcategoria_id'] = null;
        }
        else {
            $update['subcategoria_id'] = $request->subcategoria_id;
        }

        $producto->update($update);
        $producto->save();

        //actualizar descripciones nuevas que ya existan en descripciones actuales
        $producto->load('descripciones');
        $nuevas = collect($request->descripciones);
        $actuales = $producto->descripciones;
        $n = $actuales->count();
        for ($i = 0; $i < $n; $i++) {
            $actual = $actuales->shift();

            //buscar actual entre nuevas
            $index = $nuevas->search(function ($nueva) use ($actual) {
                if (!isset($nueva['categoria_descripcion_id'])) {
                    return false;
                }

                return $nueva['categoria_descripcion_id'] == $actual->categoria_descripcion_id;
            });
            if ($index === false) { //actual no existe en nuevas, borrarla
                $actual->delete();
            }
            else {
                $nueva = $nuevas->pull($index);
                $actual->update(['valor' => $nueva['valor'], 'valor_ingles' => $nueva['valor_ingles']]);
            }
        }

        //ingresar nuevas
        foreach ($nuevas as $nueva) {
            $create = array(
                "producto_id"              => $producto->id,
                "categoria_descripcion_id" => $nueva['id'],
            );
            if (isset($nueva['valor'])) {
                $create['valor'] = $nueva['valor'];
            }

            ProductoDescripcion::create($create);
        }

        return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        Storage::delete('public/' . $producto->foto);
        $producto->delete();
        return response()->json(['success' => true, "error" => false], 200);
    }


    public function activar($id)
    {
        $producto = Producto::find($id);

        $producto->status = 'ACTIVO';

        $producto->save();

        return redirect()->route('productos.inactivo');
    }

    public function desactivar($id)
    {
        $producto = Producto::find($id);

        $producto->status = 'INACTIVO';

        $producto->save();

        return redirect()->route('productos.index');
    }
}