<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendedor;
use Validator;

class VendedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores  = Vendedor::get();

        return view('catalogos.vendedores.index', compact('vendedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogos.vendedores.create');
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
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $vendedor = Vendedor::create($request->all());
        return response()->json(['success' => true, "error" => false, "vendedor" => $vendedor], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendedor  $tipo
     * @return \Illuminate\Http\Response
     */
    public function show(Vendedor $vendedor)
    {
      return view('catalogos.vendedores.show', compact('vendedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendedor $vendedor)
    {
        return view('catalogos.vendedores.edit', compact(['vendedor']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Vendedor $vendedor)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $vendedor->update($request->all());
        
        return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendedor  $vendedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendedor $vendedor)
    {
        $vendedor->delete();
        return response()->json(['success' => true, "error" => false], 200);
    }
}
