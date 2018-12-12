<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\TipoCliente;

class TiposClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $tipos = TipoCliente::all();

      return view('catalogos.tiposClientes.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('catalogos.tiposClientes.create');
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

      TipoCliente::create($request->all());

      return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoCliente  $tipo
     * @return \Illuminate\Http\Response
     */
    public function show(TipoCliente $tipo)
    {
      return view('catalogos.tiposClientes.show', compact('tipo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoCliente  $tipo
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoCliente $tipo)
    {
      return view('catalogos.tiposClientes.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoCliente  $tipoCliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TipoCliente $tipo)
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

      return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoCliente  $tipoCliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoCliente $tipo)
    {
      $tipo->delete();

      return response()->json(['success' => true, "error" => false],200);
    }
}
