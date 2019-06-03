<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\SubProyecto;

class SubProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $subproyectos = SubProyecto::with('proyecto')->has('proyecto')->get();

      return view('catalogos.subproyectos.index', compact('subproyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $proyectos = Proyecto::all();
      return view('catalogos.subproyectos.create', compact('proyectos'));
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
        'proyecto_id' => 'required',
        'nombre' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      SubProyecto::create($request->all());

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proyecto  $subproyecto
     * @return \Illuminate\Http\Response
     */
    public function show(SubProyecto $subproyecto)
    {
      $subproyecto->load('proyecto');
      return view('catalogos.subproyectos.show', compact('subproyecto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proyecto  $subproyecto
     * @return \Illuminate\Http\Response
     */
    public function edit(SubProyecto $subproyecto)
    {
      $proyectos = Proyecto::all();
      $subproyecto->load('proyecto');
      return view('catalogos.subproyectos.edit', compact(['subproyecto','proyectos']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proyecto  $subproyecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubProyecto $subproyecto)
    {
      $validator = Validator::make($request->all(), [
        'proyecto_id' => 'required',
        'nombre' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $subproyecto->update($request->all());

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proyecto  $subproyecto
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubProyecto $subproyecto)
    {
      $subproyecto->delete();
      return response()->json(['success' => true, "error" => false], 200);
    }
}
