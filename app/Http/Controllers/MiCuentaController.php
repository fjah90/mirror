<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\User;

class MiCuentaController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $usuario = auth()->user();
      if($usuario->firma) $usuario->firma = asset('storage/'.$usuario->firma);
      return view('mi_cuenta.index', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $usuario = auth()->user();
      $validator = Validator::make($request->all(), [
        'nombre' => 'required',
        'email' => ['required','email', Rule::unique('users')->ignore($usuario->id)],
        'password' => 'required',
        'contraseña' => 'confirmed',
        'firma' => 'nullable|file|mimes:jpeg,jpg,png',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 400);
      }

      //verificar la contraseña_actual
      if(!password_verify($request->password, $usuario->password)){
        return response()->json([
          "success" => false, "error" => true, "message" => "La contraseña actual no es correcta"
        ], 401);
      }

      $update = [
        'name' => $request->nombre,
        'email' => $request->email,
      ];

      if(trim($request->contraseña)!="")
        $update['password'] = password_hash($request->contraseña, PASSWORD_BCRYPT);

      if(!is_null($request->firma)) {
        Storage::delete('public/'.$usuario->firma);
        $update['firma'] = Storage::putFileAs(
          'public/usuarios/'.$usuario->id, $request->firma,
          'firma.'.$request->firma->guessExtension()
        );
        $update['firma'] = str_replace('public/', '', $update['firma']);
      }

      $usuario->update($update);

      return response()->json(["success"=>true,"error"=>false], 200);
    }

}
