<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\UnidadMedida;
use App\Models\UnidadMedidaConversion;

class UnidadesMedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $unidades = UnidadMedida::with('conversiones')->get();

      return view('catalogos.unidadesMedida.index', compact('unidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $unidades = UnidadMedida::all();

      return view('catalogos.unidadesMedida.create', compact('unidades'));
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
        'simbolo' => 'required',
        'conversiones.*.unidad_id' => 'required_with:conversiones',
        'conversiones.*.factor' => 'required_with:conversiones|numeric',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $unidad = UnidadMedida::create($request->except('conversiones'));

      foreach ($request->conversiones as $conversion) {
        UnidadMedidaConversion::create([
          'unidad_medida_id' => $unidad->id,
          'unidad_conversion_id' => $conversion['unidad_id'],
          'factor_conversion' => $conversion['factor']
        ]);
      }

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UnidadMedida  $unidad
     * @return \Illuminate\Http\Response
     */
    public function show(UnidadMedida $unidad)
    {
      $unidad->load('conversiones');
      return view('catalogos.unidadesMedida.show', compact('unidad'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UnidadMedida  $tipo
     * @return \Illuminate\Http\Response
     */
    public function edit(UnidadMedida $unidad)
    {
      $unidad->load('conversiones');
      $unidades = UnidadMedida::all();

      return view('catalogos.unidadesMedida.edit', compact('unidades', 'unidad'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UnidadMedida  $unidad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnidadMedida $unidad)
    {
      $validator = Validator::make($request->all(), [
        'simbolo' => 'required',
        'conversiones.*.unidad_conversion_id' => 'required_with:conversiones',
        'conversiones.*.factor_conversion' => 'required_with:conversiones|numeric'
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $unidad->update($request->except('conversiones'));

      //actualizar conversiones nuevas que ya existan en conversiones actuales
      $actuales = $unidad->conversiones;
      $nuevas = collect($request->conversiones);
      while($actual = $actuales->shift()){
        //buscar actual entre nuevas
        $index = $nuevas->search(function($nueva) use($actual){
          if(!isset($nueva['unidad_medida_id'])) return false; //nueva
          return $nueva['unidad_conversion_id'] == $actual->unidad_conversion_id;
        });
        if($index===false){//actual no existe en nuevas, borrarla
          $actual->delete();
        }
        else {
          $nueva = $nuevas->pull($index);
          $actual->update(['factor_conversion'=>$nueva['factor_conversion']]);
        }

      }

      //ingresar nuevas
      foreach ($nuevas as $nueva) {
        $nueva['unidad_medida_id'] = $unidad->id;
        UnidadMedidaConversion::create($nueva);
      }

      return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UnidadMedida  $unidad
     * @return \Illuminate\Http\Response
     */
    public function destroy(UnidadMedida $unidad)
    {
      $unidad->delete();

      return response()->json(['success' => true, "error" => false], 200);
    }
}
