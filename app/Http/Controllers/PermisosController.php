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
    public function show(Role $id)
    {
        //$roles=Role::all($id);
        //dd($roles);
        $roles=Role::find($id);//find es para buscar en la tabla roles el que tenga este id
        //dd($roles);
        return view('permisos.show', compact('id','roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $roles=Role::find($id);
      $permisos = Permission::all();
     
      return view('permisos.edit', compact('permisos','id','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Role $Roles,$id)
    {
      
          Role::find($id)->update($request->all());

          $roles= Role::find($id);
          $roles->name=$request->name;
          $roles->updated_at=date("Y-m-d H:i:s");
          $roles->save();
      return redirect()->action('UsuariosController@permisos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $Roles,$id)
    {
        $roles->delete();
        return response()->json(['success' => true, "error" => false],200);

        //return redirect()->action('UsuariosController@permisos');    
    }
}
