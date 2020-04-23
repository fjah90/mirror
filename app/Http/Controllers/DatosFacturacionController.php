<?php

namespace App\Http\Controllers;

use App\Models\DatoFacturacion;
use Illuminate\Http\Request;
use Validator;

class DatosFacturacionController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfc'          => 'required',
            'razon_social' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $dato = DatoFacturacion::create($request->all());

        return response()->json(['success' => true, 'error' => false, 'dato' => $dato], 200);
    }

    public function update(Request $request, $dato_id)
    {
        $validator = Validator::make($request->all(), [
            'rfc'          => 'required',
            'razon_social' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $dato = DatoFacturacion::findOrFail($dato_id);

        $dato->update($request->all());

        return response()->json(['success' => true, 'error' => false, 'dato' => $dato], 200);
    }

    public function destroy($dato_id)
    {

        $dato = DatoFacturacion::findOrFail($dato_id);

        $dato->delete();

        return response()->json(['success' => true, 'error' => false], 200);
    }

}
