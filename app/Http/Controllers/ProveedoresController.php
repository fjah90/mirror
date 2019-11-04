<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Models\ProveedorContacto;

class ProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $proveedoresNacionales = Proveedor::where('nacional',1)->get();
      $proveedoresExtranjeros = Proveedor::where('nacional',0)->get();
      $tab = 0;

      return view('catalogos.proveedores.index', compact('proveedoresNacionales', 'proveedoresExtranjeros','tab'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexExtra()
    {
      $proveedoresNacionales = Proveedor::where('nacional', 1)->get();
      $proveedoresExtranjeros = Proveedor::where('nacional', 0)->get();
      $tab = 1;

      return view('catalogos.proveedores.index', compact('proveedoresNacionales', 'proveedoresExtranjeros', 'tab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('catalogos.proveedores.create', ["nacional"=>true]);
    }

    public function createInter()
    {
      return view('catalogos.proveedores.create', ["nacional"=>false]);
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
        'empresa' => 'required',
        'nacional' => 'required',
        'dias_credito' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $proveedor = Proveedor::create($request->all());

      return response()->json(['success' => true, "error" => false, "proveedor"=> $proveedor],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
      $proveedor->load('contactos');
      return view('catalogos.proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor, Request $request)
    {
      $proveedor->load('contactos.emails','contactos.telefonos');
      $tab = ($request->has('contactos')) ? 1 : 0;
  
      return view('catalogos.proveedores.edit', compact('proveedor','tab'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedor $proveedor)
    {
      $validator = Validator::make($request->all(), [
        'empresa' => 'required',
        'nacional' => 'required',
        'dias_credito' => 'required'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $proveedor->update($request->except('contactos'));

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedor)
    {
      $proveedor->delete();
      return response()->json(['success' => true, "error" => false], 200);
    }
}
