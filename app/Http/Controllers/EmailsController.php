<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\ContactoEmail;

class EmailsController extends Controller
{

  public function store(Request $request){
    $validator = Validator::make($request->all(), [
      'contacto_id' => 'required',
      'contacto_type' => 'required',
      'email' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    $email = ContactoEmail::create($request->all());

    return response()->json(['success'=>true,'error'=>false, 'email'=> $email], 200);
  }

  public function update(Request $request, ContactoEmail $email){
    $validator = Validator::make($request->all(), [
      'email' => 'required',
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors()->all();
      return response()->json([
        "success" => false, "error" => true, "message" => $errors[0]
      ], 422);
    }

    $email->update($request->all());

    return response()->json(['success'=>true,'error'=>false], 200);
  }

  public function destroy(ContactoEmail $email){
    $email->delete();

    return response()->json(['success'=>true,'error'=>false], 200);
  }

}