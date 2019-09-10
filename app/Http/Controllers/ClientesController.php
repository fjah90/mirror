<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\TipoCliente;
use App\Models\Cliente;
use App\Models\ClienteContacto;
use App\User;
use Mail;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $clientesNacionales = Cliente::with('tipo')->where('nacional',1)->get();
      $clientesExtranjeros = Cliente::with('tipo')->where('nacional',0)->get();

      return view('catalogos.clientes.index', compact('clientesNacionales','clientesExtranjeros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $tipos = TipoCliente::all();
      $nacional = true;
      $usuarios = User::all()->pluck('name','id');

      return view('catalogos.clientes.create', compact('tipos', 'nacional','usuarios'));
    }

    public function createInter()
    {
      $tipos = TipoCliente::all();
      $nacional = false;
      $usuarios = User::all()->pluck('name','id');

      return view('catalogos.clientes.create', compact('tipos', 'nacional','usuarios'));
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
        'usuario_id' => 'required',
        'tipo_id' => 'required',
        'nombre' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $cliente = Cliente::create($request->except('contactos'));
      $cliente->update(['usuario_nombre'=>$cliente->usuario->name]);

      foreach($request->contactos as $contacto){
        $contacto['cliente_id'] = $cliente->id;
        ClienteContacto::create($contacto);
      }

      if(auth()->user()->tipo!=='Administrador'){
        $mensaje = "El usuario ".$cliente->usuario_nombre;
        $mensaje.=" ha dado de alta a un nuevo cliente con nombre: ".$cliente->nombre;
        Mail::send('email', ['mensaje' => $mensaje], function ($message){
          $message->to('abraham@intercorp.mx')->subject('Nueva Alta de Cliente');
        });
      }

      return response()->json(['success' => true, "error" => false],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
      $cliente->load(['tipo', 'contactos']);
      return view('catalogos.clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $tipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
      $tipos = TipoCliente::all();
      $usuarios = User::all()->pluck('name','id');
      $cliente->load(['tipo', 'contactos']);

      return view('catalogos.clientes.edit', compact(['cliente','tipos','usuarios']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
      $validator = Validator::make($request->all(), [
        'tipo_id' => 'required',
        'nombre' => 'required',
      ]);

      if ($validator->fails()) {
        $errors = $validator->errors()->all();
        return response()->json([
          "success" => false, "error" => true, "message" => $errors[0]
        ], 422);
      }

      $cliente->update($request->except('contactos'));
      $cliente->update(['usuario_nombre'=>$cliente->usuario->name]);
      $contactos_ids = [];

      foreach($request->contactos as $contacto){
        if(isset($contacto['id'])){ //actualizar contacto
          $contac = ClienteContacto::find($contacto['id']);
          $contac->update($contacto);
        }
        else {//ingresar nuevo contacto
          $contacto['cliente_id'] = $cliente->id;
          $contac = ClienteContacto::create($contacto);
        }

        $contactos_ids[] = $contac->id;
      }

      //eliminar contactos borrados
      $borrados = ClienteContacto::where('cliente_id', $cliente->id)
      ->whereNotIn('id', $contactos_ids)
      ->get();
      foreach ($borrados as $borrado) {
        $borrado->delete();
      }

      return response()->json(['success' => true, "error" => false], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
      $cliente->delete();
      return response()->json(['success' => true, "error" => false], 200);
    }
}
