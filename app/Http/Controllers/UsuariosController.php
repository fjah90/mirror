<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $usuarios = User::with('roles')->get();

      return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        return view('usuarios.create', compact('roles'));
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
        'tipo' => 'required',
        'nombre' => 'required',
        'email' => 'required|email|unique:users',
        'contraseña' => 'required|confirmed',
        'firma' => 'nullable|file|mimes:jpeg,jpg,png',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 400);
      }

      $rol = Role::where('name', $request->tipo)->first();
      if(is_null($rol)){
        return response()->json([
          "success" => false, "error" => true, "message" => "No existe el tipo seleccionado"
        ], 400);
      }

      $create = [
        'name' => $request->nombre,
        'email' => $request->email,
        'password' => password_hash($request->contraseña, PASSWORD_BCRYPT),
      ];

      $usuario = User::create($create);
      $usuario->assignRole($rol);

      $firma = Storage::putFileAs(
        'public/usuarios/'.$usuario->id, $request->firma,
        'firma.'.$request->firma->guessExtension()
      );
      $firma = str_replace('public/', '', $firma);
      $usuario->update(['firma'=>$firma]);

      return response()->json(["success"=>true,"error"=>false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
      if($usuario->firma) $usuario->firma = asset('storage/'.$usuario->firma);
      return view('usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
      $roles = Role::all();
      if($usuario->firma) $usuario->firma = asset('storage/'.$usuario->firma);

      return view('usuarios.edit', compact('roles','usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {
      $validator = Validator::make($request->all(), [
        'tipo' => 'required',
        'nombre' => 'required',
        'email' => ['required','email', Rule::unique('users')->ignore($usuario->id)],
        'contraseña' => 'confirmed',
        'firma' => 'nullable|file|mimes:jpeg,jpg,png',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 400);
      }

      $rol = Role::where('name', $request->tipo)->first();
      if(is_null($rol)){
        return response()->json([
          "success" => false, "error" => true, "message" => "No existe el tipo seleccionado"
        ], 400);
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
      $usuario->syncRoles([$rol->name]);

      return response()->json(["success"=>true,"error"=>false], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}