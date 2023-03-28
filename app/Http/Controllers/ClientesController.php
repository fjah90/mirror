<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteContacto;
use App\Models\ClienteUser;
use App\Models\ContactoEmail;
use App\Models\ProveedorContacto;
use App\Models\DatoFacturacion;
use App\Models\Prospecto;
use App\Models\TipoCliente;
use App\Models\Vendedor;
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

            $clientesNacionales = $clientesNacionales->whereHas("users", function($query) use ($request) {
                $query->where("user_id", $request->id);
            });

            $clientesNacionales  = $clientesNacionales->orWhere('usuario_id', $request->id);

            $clientesExtranjeros = $clientesExtranjeros->whereHas("users", function($query) use ($request) {
                $query->where("user_id", $request->id);
            });

            $clientesExtranjeros = $clientesExtranjeros->orWhere('usuario_id', $request->id);
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
        $vendedores = Vendedor::all()->pluck('nombre', 'id');
        return view('catalogos.clientes.create', compact('tipos', 'nacional', 'usuarios', 'layout','vendedores'));
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

        if (!empty($request->contactos)) {
            foreach ($request->contactos as $contacto_item){
                $contacto_item['cliente_id'] = $cliente->id;
                if ($contacto_item['tipo'] == 'cliente') {
                    $contacto = ClienteContacto::create($contacto_item);
                } else {
                    $contacto = ProveedorContacto::create($contacto_item);
                }

                if (!empty($contacto_item['emails'])) {
                    $contacto->emails()->createMany($contacto_item['emails']);
                }
                if (!empty($contacto_item['telefonos'])) {
                    $contacto->telefonos()->createMany($contacto_item['telefonos']);
                }   
            }
        }

        if(!empty($request->userIds)){
            foreach ($request->userIds as $userId){
                $clienteUser =new ClienteUser();
                $clienteUser->user_id = $userId;
                $clienteUser->cliente_id = $cliente->id;
                $clienteUser->save();
            }
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
        $cliente->load(['tipo', 'contactos', 'datos_facturacion', 'users']);

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

        $rfcs = $this->getDatosFacturacion($cliente->id);

        foreach ($rfcs as $rfc) {
            $rfc_verificacion = DatoFacturacion::where('rfc',$rfc['rfc'])->first();


            if (empty($rfc_verificacion)) {

                $rfc_verificacion2 = DatoFacturacion::where('rfc',$rfc['rfc'])->withTrashed()->first();


                if (!empty($rfc_verificacion2)) {
                    $rfc_verificacion2 = DatoFacturacion::where('rfc',$rfc['rfc'])->withTrashed()->first()->restore();                    
                }
                else{
                    if (!empty($rfc['rfc'])) {

                        $nuevo_rfc = DatoFacturacion::create([
                            'rfc'          => $rfc['rfc'],
                            'razon_social' => $rfc['razon_social'],
                            'calle'        => $rfc['calle'],
                            'nexterior'    => $rfc['nexterior'],
                            'ninterior'    => $rfc['ninterior'],
                            'colonia'      => $rfc['colonia'],
                            'cp'           => $rfc['cp'],
                            'ciudad'       => $rfc['ciudad'],
                            'estado'       => $rfc['estado'],
                            'cliente_id'   => $cliente->id,
                        ]);
                        $nuevo_rfc->save();
                    }

                }     
            }
            
        }    

        $tipos    = TipoCliente::all();
        $usuarios = User::all()->pluck('name', 'id');
        $vendedores = Vendedor::all()->pluck('nombre', 'id');
        $cliente->load(['tipo', 'contactos.emails', 'contactos.telefonos', 'datos_facturacion','users']);
        $tab = ($request->has('contactos')) ? 1 : 0;


        return view('catalogos.clientes.edit', compact(['cliente', 'tipos', 'usuarios', 'tab','vendedores']));
    }



    private function getDatosFacturacion($cliente_id)
    {
        $prospectos = Prospecto::with('cotizaciones')->where('cliente_id', $cliente_id)->get();
        $cliente1   = Cliente::where('id', $cliente_id)->first();
        $datos      = [];

        foreach ($prospectos as $prospecto) {
            $datos2 = $prospecto->cotizaciones->reduce(function ($rfcs, $cotizacion) {
                if ($cotizacion->facturar && !isset($rfcs[$cotizacion->rfc])) {
                    $rfcs[$cotizacion->rfc] = [
                        'rfc'          => $cotizacion->rfc,
                        'razon_social' => $cotizacion->razon_social,
                        'calle'        => $cotizacion->calle,
                        'nexterior'    => $cotizacion->nexterior,
                        'ninterior'    => $cotizacion->ninterior,
                        'colonia'      => $cotizacion->colonia,
                        'cp'           => $cotizacion->cp,
                        'ciudad'       => $cotizacion->ciudad,
                        'estado'       => $cotizacion->estado,
                    ];
                }
                return $rfcs;
            }, $datos);

            $datos = array_merge($datos, $datos2);
            if (isset($cliente1->rfc) && !isset($datos[$cliente1->rfc])) {
                $datos[$cliente1->rfc] = [
                    'rfc'          => $cliente1->rfc,
                    'razon_social' => $cliente1->razon_social,
                    'calle'        => $cliente1->calle,
                    'nexterior'    => $cliente1->nexterior,
                    'ninterior'    => $cliente1->ninterior,
                    'colonia'      => $cliente1->colonia,
                    'cp'           => $cliente1->cp,
                    'ciudad'       => $cliente1->ciudad,
                    'estado'       => $cliente1->estado,
                ];
            }
        }

        return $datos;
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

        $cliente->users()->detach();
        if(!empty($request->userIds)){
            foreach ($request->userIds as $userId){
                $clienteUser =new ClienteUser();
                $clienteUser->user_id = $userId;
                $clienteUser->cliente_id = $cliente->id;
                $clienteUser->save();
            }
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
