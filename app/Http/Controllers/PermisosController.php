<?php

namespace App\Http\Controllers;

use App\Models\User;
use Validator;
use Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $roles = Role::all();

        return view('permisos.create', compact('roles'));
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

      if(!is_null($request->firma)){
        $firma = Storage::putFileAs(
          'public/usuarios/'.$usuario->id, $request->firma,
          'firma.'.$request->firma->guessExtension()
        );
        $firma = str_replace('public/', '', $firma);
        $usuario->update(['firma'=>$firma]);
      }

      return response()->json(["success"=>true,"error"=>false], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $roles = Role::all();
        if($usuario->firma) $usuario->firma = asset('storage/'.$usuario->firma);

        return view('permisos.show', compact('usuario','roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($rol_id)
    {
      $rol =  Role::find($rol_id);
      $permisos = Permission::all();
      $permisosrol = $rol->permissions()->get()->pluck('id');
     
      return view('permisos.edit', compact('permisos','rol','permisosrol'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update($rol_id,Request $request)
    {
      $permissions = $request->permisos_ids;
      $role =  Role::find($rol_id);
      $role->syncPermissions($permissions);
      
      return  redirect()->route('permisos.permisos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
