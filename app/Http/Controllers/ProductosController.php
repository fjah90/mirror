<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\Material;
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
      $productos = Producto::with('proveedor','material')->get();

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
      $materiales = Material::all();
      return view('catalogos.productos.create', compact('proveedores','materiales'));
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
        'material_id' => 'required',
        'composicion' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      Producto::create($request->all());

      return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
      $producto->load('proveedor', 'material');
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
      $materiales = Material::all();
      $producto->load('proveedor', 'material');
      return view('catalogos.productos.edit', compact('producto','proveedores','materiales'));
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
        'material_id' => 'required',
        'composicion' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $producto->update($request->all());

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
      $producto->delete();
      return response()->json(['success' => true, "error" => false], 200);
    }
}
