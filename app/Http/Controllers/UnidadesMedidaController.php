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
        $unidad_conversion = UnidadMedida::findOrFail($conversion['unidad_id']);

        UnidadMedidaConversion::create([
          'unidad_medida_id' => $unidad->id,
          'unidad_conversion_id' => $unidad_conversion->id,
          'unidad_conversion_simbolo' => $unidad_conversion->simbolo,
          'unidad_conversion_nombre' => $unidad_conversion->nombre,
          'factor_conversion' => $conversion['factor']
        ]);
      }

      return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UnidadMedida  $unidad
     * @return \Illuminate\Http\Response
     */
    public function show(UnidadMedida $unidad)
    {
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
      $unidad->update(['nombre'=>'Loco']);
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
        'conversiones.*.unidad_id' => 'required_with:conversiones',
        'conversiones.*.factor' => 'required_with:conversiones|numeric'
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
          return $nueva['unidad_id'] == $actual->unidad_conversion_id;
        });
        if($index===false){//actual no existe en nuevas, borrarla
          $actual->delete();
        }
        else {
          $nueva = $nuevas->pull($index);
          $actual->update(['valor'=>$nueva['valor']]);
        }

      }

      //ingresar nuevas
      foreach ($nuevas as $nueva) {
        $create = array(
          "producto_id"=>$producto->id,
          "categoria_descripcion_id"=>$nueva['id']
        );
        if(isset($nueva['valor'])) $create['valor'] = $nueva['valor'];
        ProductoDescripcion::create($create);
      }

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
