<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\TipoCliente;
use App\User;
use Illuminate\Http\Request;
use Mail;
use Validator;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientesNacionales  = Cliente::with('tipo')->where('nacional', 1)->get();
        $clientesExtranjeros = Cliente::with('tipo')->where('nacional', 0)->get();
        $usuarios            = User::all();
        $tipos               = TipoCliente::all();

        return view('catalogos.clientes.index', compact('clientesNacionales', 'clientesExtranjeros', 'usuarios', 'tipos'));
    }

    public function listado(Request $request)
    {
        $clientesNacionales  = Cliente::with('tipo')->where('nacional', 1);
        $clientesExtranjeros = Cliente::with('tipo')->where('nacional', 0);
        if ($request->id != 'Todos') {
            $clientesNacionales  = $clientesNacionales->where('usuario_id', $request->id);
            $clientesExtranjeros = $clientesExtranjeros->where('usuario_id', $request->id);
        }
        if ($request->tipo != 'Todos') {
            $clientesNacionales  = $clientesNacionales->where('tipo_id', $request->tipo);
            $clientesExtranjeros = $clientesExtranjeros->where('tipo_id', $request->tipo);
        }
        $clientesNacionales  = $clientesNacionales->get();
        $clientesExtranjeros = $clientesExtranjeros->get();

        return response()->json(['success' => true, "error"                              => false,
            'clientesNacionales'               => $clientesNacionales, 'clientesExtranjeros' => $clientesExtranjeros,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $layout   = $request->layout;
        $tipos    = TipoCliente::all();
        $nacional = true;
        $usuarios = User::all()->pluck('name', 'id');
        return view('catalogos.clientes.create', compact('tipos', 'nacional', 'usuarios', 'layout'));
    }

    public function createExtra(Request $request)
    {
        $layout   = $request->layout;
        $tipos    = TipoCliente::all();
        $nacional = false;
        $usuarios = User::all()->pluck('name', 'id');

        return view('catalogos.clientes.create', compact('tipos', 'nacional', 'usuarios', 'layout'));
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
            'tipo_id'    => 'required',
            'nombre'     => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $cliente = Cliente::create($request->all());
        $cliente->update(['usuario_nombre' => $cliente->usuario->name]);

        if (auth()->user()->tipo !== 'Administrador') {
            $mensaje = "El usuario " . $cliente->usuario_nombre;
            $mensaje .= " ha dado de alta a un nuevo cliente con nombre: " . $cliente->nombre;
            Mail::send('email', ['mensaje' => $mensaje], function ($message) {
                $message->to('abraham@intercorp.mx')->subject('Nueva Alta de Cliente');
            });
        }

        return response()->json(['success' => true, "error" => false, "cliente" => $cliente], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Cliente $cliente)
    {
        $cliente->load(['tipo', 'contactos', 'datos_facturacion']);
        return view('catalogos.clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cliente  $tipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente, Request $request)
    {
        $tipos    = TipoCliente::all();
        $usuarios = User::all()->pluck('name', 'id');
        $cliente->load(['tipo', 'contactos.emails', 'contactos.telefonos', 'datos_facturacion']);
        $tab = ($request->has('contactos')) ? 1 : 0;

        return view('catalogos.clientes.edit', compact(['cliente', 'tipos', 'usuarios', 'tab']));
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
            'nombre'  => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json([
                "success" => false, "error" => true, "message" => $errors[0],
            ], 422);
        }

        $cliente->update($request->except('contactos'));
        $cliente->update(['usuario_nombre' => $cliente->usuario->name]);

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
