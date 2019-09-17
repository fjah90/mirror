<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\AgenteAduanal;

class AgentesAduanalesController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $agentes = AgenteAduanal::all();

    return view('catalogos.agentesAduanales.index', compact('agentes'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('catalogos.agentesAduanales.create');
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
      'compañia' => 'required',
      'direccion' => 'required',
      'telefono' => 'required',
      'email' => 'required',
      'contacto' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    AgenteAduanal::create($request->all());

    return response()->json(['success' => true, "error" => false], 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\AgenteAduanal  $agente
   * @return \Illuminate\Http\Response
   */
  public function show(AgenteAduanal $agente)
  {
    return view('catalogos.agentesAduanales.show', compact('agente'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\AgenteAduanal  $agente
   * @return \Illuminate\Http\Response
   */
  public function edit(AgenteAduanal $agente)
  {
    return view('catalogos.agentesAduanales.edit', compact('agente'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\AgenteAduanal  $agente
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, AgenteAduanal $agente)
  {
    $validator = Validator::make($request->all(), [
      'compañia' => 'required',
      'direccion' => 'required',
      'telefono' => 'required',
      'email' => 'required',
      'contacto' => 'required'
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    $agente->update($request->all());

    return response()->json(['success' => true, "error" => false], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\AgenteAduanal  $agente
   * @return \Illuminate\Http\Response
   */
  public function destroy(AgenteAduanal $agente)
  {
    $agente->delete();

    return response()->json(['success' => true, "error" => false], 200);
  }
}
