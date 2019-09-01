<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\CondicionCotizacion;

class CondicionesCotizacionController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CondicionCotizacion  $condicion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CondicionCotizacion $condicion)
    {
      $validator = Validator::make($request->all(), ['nombre' => 'required']);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $condicion->update($request->all());

      return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CondicionCotizacion  $condicion
     * @return \Illuminate\Http\Response
     */
    public function destroy(CondicionCotizacion $condicion)
    {
      $condicion->delete();

      return response()->json(['success' => true, "error" => false],200);
    }
}
