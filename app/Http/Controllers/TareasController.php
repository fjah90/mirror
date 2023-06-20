<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Vendedor;
use App\User;
use Auth;
use Mail;

class TareasController extends Controller
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tarea = Tarea::create([
            'vendedor_id' => $request->vendedor_id,
            'director_id' => $request->director_id,
            'tarea'      => $request->tarea,
            'user_id'  => Auth()->user()->id,
            'status'  => $request->status,
        ]);
        $tarea->save();
        $tarea->load('vendedor','director');
        //sacamos el email del destinatario
        if($request->vendedor_id == null){
            $usuario_destino = User::findOrFail($request->director_id);
        
        }
        else{
            $usuario_destino = Vendedor::findOrFail($request->vendedor_id);
            
        }
        //sacamos el usuario remitente
        $usuario_remitente     = auth()->user();
        $mensaje = 'Usted tiene una nueva tarea asiganada por '. $usuario_remitente->name .' favor de atenderla a la brevedad.';

        Mail::send('email', ['mensaje' => $mensaje], function ($message)
        use ($usuario_destino) {
            $message->to($usuario_destino->email)
                //->cc('abraham@intercorp.mx')
                //->replyTo($user->email, $user->name)
                ->subject('Nueva tarea Robinson');
        });


        return response()->json(['success' => true, "error" => false, 'tarea' => $tarea], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
    }

    public function actualizar(Request $request)
    {
        $tarea = Tarea::findOrfail($request->id);
        $tarea->update([
            'vendedor_id' => $request->vendedor_id,
            'director_id' => $request->director_id,
            'tarea'      => $request->tarea,
            'status'  => $request->status,
        ]);
        $tarea->save();
        $tarea->load('vendedor','director');
        //obetenemos el usuario del vendedor
        if(Auth::user()->roles[0]->name == 'Diseñadores'){
            $vendedor = Vendedor::where('email',auth()->user()->email)->first();
            //obtenemos el usuario del vendedor
            $usuario_vendedor = User::where('email',$vendedor->email)->first();
            //
            $tareas = Tarea::with('vendedor','director')->where('vendedor_id',$vendedor->id)->orwhere('user_id',$usuario_vendedor->id)->get();
        }
        else{
            if($request->vendedor_id == null){
                //si no tiene vendedor significa que un vendedor creo la tarea para un director 
                $us = User::where('id',$tarea->user_id)->first();
                $vend = Vendedor::where('email',$us->email)->first();
                $tareas = Tarea::with('vendedor','director')->where('vendedor_id',$vend->id)->orwhere('user_id',$us->id)->get();
            }
            else{
                $vend = Vendedor::where('id',$request->vendedor_id)->first();
                $us = User::where('email',$vend->email)->first();
                $tareas = Tarea::with('vendedor','director')->where('vendedor_id',$request->vendedor_id)->orwhere('director_id',auth()->user()->id)->get();
            }

        }
        

        return response()->json(['success' => true, "error" => false, 'tareas' => $tareas,'tarea'=>$tarea], 200);
    }

 

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
