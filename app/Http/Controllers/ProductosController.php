<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductoDescripcion;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $productos = Producto::with('proveedor','categoria')->get();

      return view('catalogos.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $proveedores = Proveedor::all();
      $categorias = Categoria::with('descripciones')->get();
      return view('catalogos.productos.create', compact('proveedores','categorias'));
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
        'nombre' => 'required',
        'descripciones' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $create = $request->except('foto','descripciones');
      if(isset($request->foto)){
        $create['foto'] = Storage::putFile('public/productos', $request->file('foto'));
        $create['foto'] = str_replace('public/', '', $create['foto']);
      }
      $producto = Producto::create($create);

      foreach ($request->descripciones as $descripcion) {
        $create = array(
          "producto_id"=>$producto->id,
          "categoria_descripcion_id"=>$descripcion['id']
        );
        if(isset($descripcion['valor'])) $create['valor'] = $descripcion['valor'];
        ProductoDescripcion::create($create);
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
      $producto->load('proveedor', 'categoria', 'descripciones.descripcionNombre');
      if($producto->foto) $producto->foto = asset('storage/'.$producto->foto);
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
      $proveedores = Proveedor::all();
      $categorias = Categoria::with('descripciones')->get();
      $producto->load('proveedor', 'categoria.descripciones', 'descripciones.descripcionNombre');

      foreach ($producto->descripciones as $descripcion){
        $descripcion->nombre = $descripcion->descripcionNombre->nombre;
        $descripcion->name = $descripcion->descripcionNombre->name;
        unset($descripcion->descripcionNombre);
      }

      $producto_descripciones = $producto->descripciones->count();
      $categoria_descripciones = $producto->categoria->descripciones->count();
      // dd($producto_descripciones, $categoria_descripciones);

      if($producto_descripciones < $categoria_descripciones){
        //hay descripciones nuevas en categoria
        $nuevas = $producto->categoria->descripciones->splice($producto_descripciones);
        foreach ($nuevas as $nueva) {
          $descripcion = ProductoDescripcion::create([
            'producto_id' => $producto->id,
            'categoria_descripcion_id' => $nueva->id
          ]);
          $descripcion->nombre = $nueva->nombre;
          $descripcion->name = $nueva->name;
          $descripcion->valor = null;
          $producto->descripciones->push($descripcion);
        }
      }

      if($producto->foto) $producto->foto = asset('storage/'.$producto->foto);
      return view('catalogos.productos.edit', compact('producto','proveedores','categorias'));
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
        'nombre' => 'required',
        'descripciones' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $update = $request->except('foto_ori','foto','descripciones');
      if(!is_null($request->foto)) {
        Storage::delete('public/'.$producto->foto);
        $update['foto'] = Storage::putFile('public/productos', $request->file('foto'));
        $update['foto'] = str_replace('public/', '', $update['foto']);
      }
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
