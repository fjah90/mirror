<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\ObservacionCotizacion;

class ObservacionesCotizacionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'texto' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $create = [
        'texto'=>$request->texto,
        'orden'=>ObservacionCotizacion::all()->count()+1
      ];
      $observacion = ObservacionCotizacion::create($create);

      return response()->json(['success'=>true, "error"=>false, 'observacion'=>$observacion],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ObservacionCotizacion  $observacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(ObservacionCotizacion $observacion)
    {
      $observacion->delete();

      return response()->json(['success' => true, "error" => false],200);
    }
}
