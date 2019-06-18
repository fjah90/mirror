<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\CategoriaDescripcion;
use App\Models\ProductoDescripcion;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $categorias = Categoria::all();

      return view('catalogos.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('catalogos.categorias.create');
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

      if($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $create = $request->except('descripciones');
      if(!isset($create['name'])) $create['name'] = $create['nombre'];
      $categoria = Categoria::create($create);

      if(isset($request->descripciones)){
        foreach($request->descripciones as $index => $descripcion) {
          if(!empty($descripcion['nombre']) || !empty($descripcion['name'])){
            $descripcion['categoria_id'] = $categoria->id;
            $descripcion['ordenamiento'] = $index+1;
            CategoriaDescripcion::create($descripcion);
          }
        }
      }

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
      $categoria->load('descripciones');

      return view('catalogos.categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
      $categoria->load('descripciones');

      return view('catalogos.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
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

      $categoria->update($request->except('descripciones'));
      foreach ($request->descripciones as $descripcion) {
        if(isset($descripcion['borrar'])){
          CategoriaDescripcion::destroy($descripcion['id']);
          ProductoDescripcion::where('categoria_descripcion_id', $descripcion['id'])
          ->delete();
        }
        else if(isset($descripcion['actualizar']) && isset($descripcion['id'])){
          CategoriaDescripcion::find($descripcion['id'])->update($descripcion);
        }
        else if(!isset($descripcion['id'])){
          $descripcion['categoria_id'] = $categoria->id;
          CategoriaDescripcion::create($descripcion);
        }
      }

      return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
      $categoria->delete();

      return response()->json(['success' => true, "error" => false],200);
    }
}
