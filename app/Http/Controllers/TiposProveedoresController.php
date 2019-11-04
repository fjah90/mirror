<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\TipoProveedor;

class TiposProveedoresController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $tipos = TipoProveedor::all();

    return view('catalogos.tiposProveedores.index', compact('tipos'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('catalogos.tiposProveedores.create');
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
      'nombre' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    TipoProveedor::create($request->all());

    return response()->json(['success' => true, "error" => false], 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\TipoProveedor  $tipo
   * @return \Illuminate\Http\Response
   */
  public function show(TipoProveedor $tipo)
  {
    return view('catalogos.tiposProveedores.show', compact('tipo'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\TipoProveedor  $tipo
   * @return \Illuminate\Http\Response
   */
  public function edit(TipoProveedor $tipo)
  {
    return view('catalogos.tiposProveedores.edit', compact('tipo'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\TipoProveedor  $tipoProveedor
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, TipoProveedor $tipo)
  {
    $validator = Validator::make($request->all(), [
      'nombre' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    $tipo->update($request->all());

    return response()->json(['success' => true, "error" => false], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\TipoProveedor  $tipoProveedor
   * @return \Illuminate\Http\Response
   */
  public function destroy(TipoProveedor $tipo)
  {
    $tipo->delete();

    return response()->json(['success' => true, "error" => false], 200);
  }
}
