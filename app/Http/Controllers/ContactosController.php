<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\ClienteContacto;
use App\Models\ProveedorContacto;
use App\User;

class ContactosController extends Controller
{

  public function store(Request $request){
    $validator = Validator::make($request->all(), [
      'tipo' => 'required',
      'nombre' => 'required',
      'cargo' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    if($request->tipo=='cliente')
      $contacto = ClienteContacto::create($request->except(['tipo','emails','telefonos']));
    else
      $contacto = ProveedorContacto::create($request->except(['tipo','emails','telefonos']));

    if(count($request->emails)){
      $contacto->emails()->createMany($request->emails);
    }
    if(count($request->telefonos)){
      $contacto->telefonos()->createMany($request->telefonos);
    }

    $contacto->load('emails','telefonos');

    return response()->json(['success'=>true,'error'=>false,'contacto'=>$contacto], 200);
  }

  public function update(Request $request, $contacto_id){
    $validator = Validator::make($request->all(), [
      'tipo' => 'required',
      'nombre' => 'required',
      'cargo' => 'required',
      'fax'   =>'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    if($request->tipo=='cliente')
      $contacto = ClienteContacto::findOrFail($contacto_id);
    else
      $contacto = ProveedorContacto::findOrFail($contacto_id);

    $contacto->update($request->only(['nombre','cargo','fax']));

    return response()->json(['success'=>true,'error'=>false], 200);
  }

  public function destroy(Request $request, $contacto_id){
    $validator = Validator::make($request->all(), [
      'tipo' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    if($request->tipo=='cliente')
      $contacto = ClienteContacto::findOrFail($contacto_id);
    else
      $contacto = ProveedorContacto::findOrFail($contacto_id);

    $contacto->delete();

    return response()->json(['success'=>true,'error'=>false], 200);
  }

}