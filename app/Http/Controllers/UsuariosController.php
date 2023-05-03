<?php

namespace App\Http\Controllers;

use Validator;
use Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

    public function permisos()
    {
      $roles = Role::all();
      //Role::findOrfail('writer')->permissions 
      //dd($roles);
      return view('usuarios.permisos', compact('roles'));
      
    }

    public function editpermisos($rol_id)
    {
      $rol =  Role::find($rol_id);
      $permisos = Permission::all();
      $permisosrol = $rol->permissions()->get()->pluck('id');
     
      return view('usuarios.editpermisos', compact('permisos','rol','permisosrol'));
    }

    public function updatepermisos($rol_id,Request $request)
    {
      $permissions = $request->permisos_ids;
      $role =  Role::find($rol_id);
      $role->syncPermissions($permissions);
      
      return  redirect()->route('usuarios.permisos');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      
      /*$rol_id= $request->rol_id;
      //dd($rol_id);*/
      $roles = Role::all();
      /*$rol =  Role::find($rol_id);*/
      $permisos = Permission::all();
      /*$permisosrol = $rol->permissions()->get()->pluck('id');*/
        
        return view('usuarios.create', compact('roles','permisos'));
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


    public function login2(Request $request)
    {

        $input = $request->all();
        $user = User::where('email', $input['email'])->first();

        if (!empty($user)) {

            //ver si esta activo

            if ($user->status == 'ACTIVO') {
                
                if(Hash::check($input['password'], $user->password)){
                    Auth::loginUsingId($user->id);
                    if($user->getRoleNames()[0]== 'Cliente'){
                      return response()->redirectTo('prospectos');
                    }
                   
                    return response()->redirectTo('/');
                    
                } else {
                    return response()->redirectTo('/login')
                        ->withErrors('password', "El usuario no se encuentra actvo");
                }
            }
            return response()->redirectTo('/login')->withErrors('password', "Los datos proporcionados no son correctos.");
        } else {
            return response()->redirectTo('/login')
                ->withErrors('email', "El usuario no fue encontrado.");
        }
    }

     public function activar($id)
    {
        $usuario = User::findOrFail($id);
        
        $usuario->status = 'ACTIVO';

        $usuario->save();
        
        return redirect()->route('usuarios.index');
    }

    public function desactivar($id)
    {
        $usuario = User::findOrFail($id);
        
        $usuario->status = 'NOACTIVO';

        $usuario->save();
        
        return redirect()->route('usuarios.index');
    }
}
