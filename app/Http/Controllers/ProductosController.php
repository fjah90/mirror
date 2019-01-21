<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Categoria;
use App\Models\Producto;

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
      $categorias = Categoria::all();
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
        'composicion' => 'required',
        'diseño' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $create = $request->except('foto');
      if(isset($request->foto)){
        $create['foto'] = Storage::putFile('public/productos', $request->file('foto'));
        $create['foto'] = str_replace('public/', '', $create['foto']);
      }
      Producto::create($create);

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
      $producto->load('proveedor', 'categoria');
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
      $categorias = Categoria::all();
      $producto->load('proveedor', 'categoria');
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
        'composicion' => 'required',
        'diseño' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $update = $request->except('foto_ori','foto');
      if(!is_null($request->foto)) {
        Storage::delete('public/'.$producto->foto);
        $update['foto'] = Storage::putFile('public/productos', $request->file('foto'));
        $update['foto'] = str_replace('public/', '', $update['foto']);
      }
      $producto->update($update);

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
