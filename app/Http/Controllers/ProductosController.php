<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Producto;
use App\Models\ProductoDescripcion;
use Mail;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $productos = Producto::with('proveedor','categoria','subcategoria')
      ->has('categoria')
      ->get();

      return view('catalogos.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $proveedores = Proveedor::orderBy('empresa')->get();
      $categorias = Categoria::with('descripciones')->orderBy('nombre')->get();
      $subcategorias = Subcategoria::orderBy('nombre')->get();
      return view('catalogos.productos.create', compact('proveedores','categorias','subcategorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'proveedor_id' => 'required',
        'categoria_id' => 'required',
        'nombre' => 'required|unique:productos,nombre'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $create = $request->only('categoria_id','nombre');
      if($request->proveedor_id!=0) $create['proveedor_id'] = $request->proveedor_id;
      if(!is_null($request->subcategoria_id)){
        $create['subcategoria_id'] = $request->subcategoria_id;
      }
      $producto = Producto::create($create);

      if(isset($request->foto)){
        $foto = Storage::putFile('public/productos', $request->file('foto'));
        $foto = str_replace('public/', '', $foto);
        $producto->update(['foto'=>$foto]);
      }
      if(isset($request->ficha_tecnica)){
        $nombre_temp = "temp".time().".pdf";
        $nombre_bueno = "ficha_tecnica_producto_".$producto->id.".pdf";

        Storage::putFileAs('public/productos', $request->file('ficha_tecnica'), $nombre_temp);
        //comando para pasar pdf a version 1.4, para poderlo "mergear" a pdfs de cotizacion
        $comando = "ps2pdf14 storage/productos/$nombre_temp storage/productos/$nombre_bueno";
        exec_in_background($comando);
        $producto->update(['ficha_tecnica'=>"productos/$nombre_bueno"]);
      }

      foreach ($request->descripciones as $descripcion) {
        $create = array(
          "producto_id"=>$producto->id,
          "categoria_descripcion_id"=>$descripcion['id']
        );
        if(isset($descripcion['valor'])) $create['valor'] = $descripcion['valor'];
        ProductoDescripcion::create($create);
      }

      if(auth()->user()->tipo!=='Administrador'){
        $mensaje = "El usuario ".auth()->user()->name;
        $mensaje.=" ha dado de alta un nuevo producto con nombre: ".$producto->nombre;
        Mail::send('email', ['mensaje' => $mensaje], function ($message){
          $message->to('abraham@intercorp.mx')->subject('Nueva Alta de Producto');
        });
      }

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
      $producto->load('proveedor','categoria','subcategoria','descripciones.descripcionNombre');
      if($producto->foto) $producto->foto = asset('storage/'.$producto->foto);
      if($producto->ficha_tecnica) $producto->ficha_tecnica = asset('storage/'.$producto->ficha_tecnica);
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
      $producto->load('proveedor','categoria.descripciones','subcategoria','descripciones.descripcionNombre');

      $producto_descripciones = $producto->descripciones->count();
      $categoria_descripciones = $producto->categoria->descripciones->count();
      // dd($producto_descripciones, $categoria_descripciones);
      if($producto_descripciones < $categoria_descripciones){
        //hay descripciones nuevas en categoria
        $nuevas = $producto->categoria->descripciones->sortBy('id')
                  ->splice($producto_descripciones);
        foreach ($nuevas as $nueva) {
          ProductoDescripcion::create([
            'producto_id' => $producto->id,
            'categoria_descripcion_id' => $nueva->id
          ]);
        }

        $producto->load('descripciones.descripcionNombre');
      }

      foreach ($producto->descripciones as $descripcion){
        $descripcion->nombre = $descripcion->descripcionNombre->nombre;
        $descripcion->name = $descripcion->descripcionNombre->name;
        unset($descripcion->descripcionNombre);
      }

      if($producto->foto) $producto->foto = asset('storage/'.$producto->foto);
      if($producto->ficha_tecnica) $producto->ficha_tecnica = asset('storage/'.$producto->ficha_tecnica);
      return view('catalogos.productos.edit', compact('producto','proveedores','categorias','subcategorias'));
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
      $validator = Validator::make($request->all(), [
        'proveedor_id' => 'required',
        'categoria_id' => 'required',
        'nombre' => ['required',Rule::unique('productos')->ignore($producto->id)]
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $update = $request->only('categoria_id','nombre');
      $update['proveedor_id'] = ($request->proveedor_id!=0)?$request->proveedor_id:null;
      if(!is_null($request->foto)) {
        Storage::delete('public/'.$producto->foto);
        $update['foto'] = Storage::putFile('public/productos', $request->file('foto'));
        $update['foto'] = str_replace('public/', '', $update['foto']);
      }
      if(!is_null($request->ficha_tecnica)) {
        Storage::delete('public/'.$producto->ficha_tecnica);
        $nombre_temp = "temp".time().".pdf";
        $nombre_bueno = "ficha_tecnica_producto_".$producto->id."t".time().".pdf";

        Storage::putFileAs('public/productos', $request->file('ficha_tecnica'), $nombre_temp);

        //comando para pasar pdf a version 1.4, para poderlo "mergear" a pdfs de cotizacion
        $comando = "ps2pdf14 storage/productos/$nombre_temp storage/productos/$nombre_bueno";
        exec_in_background($comando);

        $update['ficha_tecnica'] = "productos/$nombre_bueno";
      }
      if(is_null($request->subcategoria_id)) $update['subcategoria_id'] = null;
      else $update['subcategoria_id'] = $request->subcategoria_id;
      $producto->update($update);

      //actualizar descripciones nuevas que ya existan en descripciones actuales
      $producto->load('descripciones');
      $nuevas = collect($request->descripciones);
      $actuales = $producto->descripciones;
      $n = $actuales->count();
      for ($i=0; $i<$n; $i++) {
        $actual = $actuales->shift();

        //buscar actual entre nuevas
        $index = $nuevas->search(function($nueva) use($actual){
          if(!isset($nueva['categoria_descripcion_id'])) return false;
          return $nueva['categoria_descripcion_id'] == $actual->categoria_descripcion_id;
        });
        if($index===false){//actual no existe en nuevas, borrarla
          $actual->delete();
        }
        else {
          $nueva = $nuevas->pull($index);
          $actual->update(['valor'=>$nueva['valor']]);
        }
      }

      //ingresar nuevas
      foreach ($nuevas as $nueva) {
        $create = array(
          "producto_id"=>$producto->id,
          "categoria_descripcion_id"=>$nueva['id']
        );
        if(isset($nueva['valor'])) $create['valor'] = $nueva['valor'];
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
      Storage::delete('public/'.$producto->foto);
      $producto->delete();
      return response()->json(['success' => true, "error" => false], 200);
    }
}
