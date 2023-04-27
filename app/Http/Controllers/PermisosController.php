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
         //$roles = Role::all();

         //$rol = Role::all();
         //dd($roles);
         //$permisos = Permission::all();
         //dd($permisos);

        return view('permisos.create');
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
        'name' => 'required',
      ]);

       Role::create($request->all());
       //dd($request->all());

       $create = [
        'name' => $request->name,
      ];
       
      return redirect()->action('UsuariosController@permisos');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Role $rol)
    {
       
        $roles=Role::all();
        return view('permisos.show', compact('rol','roles'));
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
    public function update(Request $request,  Role $rol_id)
    {
      $permissions = $request->permisos_ids;
      $role =  Role::find($rol_id);
      $role->syncPermissions($permissions);

      //$rol->update($request->all());
       Role::find($rol_id)->update($request->all());
      
      return redirect()->route('permisos.permisos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $rol)
    {
        $rol->delete();
        return response()->json(['success' => true, "error" =>false],200);
    }
}
