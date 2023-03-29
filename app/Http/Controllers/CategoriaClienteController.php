<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\CategoriaCliente;


class CategoriaClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = CategoriaCliente::all();
        //dd($categoria);
        return view('catalogos.categoriaCliente.index',compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogos.categoriaCliente.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['nombre'=>'required',]);//validacion del parametro que recibo por request

        ///valido
        if($validator->fails()){
            $error = $validator->errors()->all();
            return response()->json([
                "success"=> false, "error"=> true, "message"=>$errors[0]
            ], 422);
        }

        CategoriaCliente::create($request->all());
        //dd($request->all());
        return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoriaCliente  $categoriaCliente
     * @return \Illuminate\Http\Response
     */
    public function show(CategoriaCliente $categoria)
    {
        return view('catalogos.categoriaCliente.show',compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CategoriaCliente  $categoriaCliente
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoriaCliente $categoria)
    {
        return view('catalogos.categoriaCliente',compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoriaCliente  $categoriaCliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoriaCliente $categoria)
    {
        $validator = Validator::make($request->all(), [
            'nombre' =>'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0]
            ], 422);
        }

        $categoria->update($request->all());
        return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoriaCliente  $categoriaCliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoriaCliente $categoria)
    {
        $categoria->delete();
        return response()->json(['success' => true, "error" => false],200);
    }
}
