<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\ContactoTelefono;

class TelefonosController extends Controller
{

  public function store(Request $request){
    $validator = Validator::make($request->all(), [
      'contacto_id' => 'required',
      'contacto_type' => 'required',
      'telefono' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    $telefono = ContactoTelefono::create($request->all());

    return response()->json(['success'=>true,'error'=>false, 'telefono'=> $telefono], 200);
  }

  public function update(Request $request, ContactoTelefono $telefono){
    $validator = Validator::make($request->all(), [
      'telefono' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    $telefono->update($request->all());

    return response()->json(['success'=>true,'error'=>false], 200);
  }

  public function destroy(ContactoTelefono $telefono){
    $telefono->delete();

    return response()->json(['success'=>true,'error'=>false], 200);
  }

}