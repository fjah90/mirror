<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\TareasComentario;
use App\Models\Vendedor;
use App\Models\Notificacion;
use App\User;
use Carbon\Carbon;
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

    public function gethistorial($tarea_id){
       $tarea = Tarea::findOrFail($tarea_id);
       $historial= [];
       foreach($tarea->revisionHistory as $history)
       {
         $h =[];
         $h['usuario'] = $history->userResponsible()->name;
         $h['fecha'] = Carbon::parse($history->created_at)->format('d/m/Y');
         $h['anterior'] = $history->oldValue();
         $h['nuevo'] = $history->newValue();
         array_push($historial, $h);
       }

       return response()->json(['success' => true, "error" => false, 'historial' => $historial], 200);
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
        dd($usuario_destino);
        //sacamos el usuario remitente
        $usuario_remitente  = auth()->user()->name;
        $mensaje = '<b>'.$usuario_destino->nombre.'</b> tienes la siguiente tarea asignada por <b>'.$usuario_remitente .'</b>:<br><br><br>'.$tarea->tarea .'<br><br><br> Favor de atenderla a la brevedad.';
        
        Mail::send('email', ['mensaje' => $mensaje], function ($message)
        use ($usuario_destino) {
            $message->to($usuario_destino->email)
            //$message->to('eduardo.santana@tigears.com')
                //->cc('abraham@intercorp.mx')
                //->replyTo($user->email, $user->name)
                ->subject('Nueva tarea Robinson');
        });

        $notificacion = Notificacion::create([
            'user_creo' => auth()->user()->id,
            'user_dirigido' => $usuario_destino->id,
            'texto'      => 'Creo una nueva tarea',
        ]);



        return response()->json(['success' => true, "error" => false, 'tarea' => $tarea], 200);
    }

    //Marca una notificacion como leida
    public function marcarleida(Request $request)
    {
        $notificacion = Notificacion::findOrFail($request->id);

        $notificacion->status = 'leida';
        $notificacion->save();
        //Sacamos la notificaciones del usuario logueado
        $notificaciones = Notificacion::with('usercreo','userdirigido')->where('user_dirigido',auth()->user()->id)->where('status','sin leer')->get();
        $cant_notificaciones = count($notificaciones);

        return response()->json(['success' => true, "error" => false, 'notificaciones' => $notificaciones,'cant_notificaciones' => $cant_notificaciones], 200);

    }

    //Guarda un comentario sobre una tarea y regresa el histroial de comentarios de la tarea
    public function guardarcomentario(Request $request)
    {
        $comentario = TareasComentario::create([
            'tarea_id' => $request->id,
            'comentario' => $request->comentario,
            'user_id'  => Auth()->user()->id,
        ]);
        $comentario->save();
        $tarea = Tarea::findOrFail($request->id);
        $tarea->load('vendedor','director','comentarios','comentarios.usuario');
        $comentarios = $tarea->comentarios;

        //Si el usuario que creo el comentario es el mismo que creo la tarea ,la notificacion va dirigida a quien se le asigno la tarea
        if(auth()->user()->id == $tarea->user_id){
            if($tarea->vendedor_id == null){
                $user_dirigido = $tarea->director_id;
            }
            else{
                $user = User::where('email',$tarea->vendedor->email)->first();
                $user_dirigido = $user->id;
            }
        }
        //si el usuario que creo el comentario NO es el mismo que creo la tarea entonces la notificacion va dirigida a quien creo la tarea
        else{
            $user_dirigido = $tarea->user_id;

        }

        

        $notificacion = Notificacion::create([
            'user_creo' => auth()->user()->id,
            'user_dirigido' => $user_dirigido,
            'texto'      => 'Realizó un comentarios en una tarea',
        ]);
        
        return response()->json(['success' => true, "error" => false, 'comentarios' => $comentarios], 200);
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
            $tareaspendiente = Tarea::with('vendedor','director','comentarios','comentarios.usuario')->where('status','Pendiente')->where('vendedor_id',$vendedor->id)->orwhere('user_id',$usuario_vendedor->id)->get();
            $tareasproceso = Tarea::with('vendedor','director','comentarios','comentarios.usuario')->where('status','En proceso')->where('vendedor_id',$vendedor->id)->orwhere('user_id',$usuario_vendedor->id)->get();
            $tareasterminadas = Tarea::with('vendedor','director','comentarios','comentarios.usuario')->where('status','Terminada')->where('vendedor_id',$vendedor->id)->orwhere('user_id',$usuario_vendedor->id)->get();
        }
        else{
            if($request->vendedor_id == null){
                //si no tiene vendedor significa que un vendedor creo la tarea para un director 
                $us = User::where('id',$tarea->user_id)->first();
                $vend = Vendedor::where('email',$us->email)->first();
                $tareaspendiente = Tarea::with('vendedor','director','comentarios','comentarios.usuario')->where('status','Pendiente')->where('vendedor_id',$vend->id)->orwhere('user_id',$us->id)->get();
                $tareasproceso = Tarea::with('vendedor','director','comentarios','comentarios.usuario')->where('status','En proceso')->where('vendedor_id',$vend->id)->orwhere('user_id',$us->id)->get();
                $tareasterminadas = Tarea::with('vendedor','director')->where('status','Terminada')->where('vendedor_id',$vend->id)->orwhere('user_id',$us->id)->get();
            }
            else{
                $vend = Vendedor::where('id',$request->vendedor_id)->first();
                $us = User::where('email',$vend->email)->first();

                $tareaspendiente = Tarea::with('vendedor','director','comentarios','comentarios.usuario')->where('status','Pendiente')->where('user_id',auth()->user()->id)->orwhere('director_id',auth()->user()->id)->get();

                $tareasproceso = Tarea::with('vendedor','director','comentarios','comentarios.usuario')->where('status','En proceso')->where('user_id',auth()->user()->id)->orwhere('director_id',auth()->user()->id)->get();

                $tareasterminadas = Tarea::with('vendedor','director','comentarios','comentarios.usuario')->where('status','Terminada')->where('user_id',auth()->user()->id)->orwhere('director_id',auth()->user()->id)->get();
            }

        }

        if($tarea->vendedor_id == null){
            $user_dirigido = $tarea->director_id;
        }
        else{
            $user = User::where('email',$tarea->vendedor->email)->first();
            $user_dirigido = $user->id;
        }

        $notificacion = Notificacion::create([
            'user_creo' => auth()->user()->id,
            'user_dirigido' => $user_dirigido,
            'texto'      => 'Editó una tarea',
        ]);
        

        return response()->json(['success' => true, "error" => false, 'tareaspendiente' => $tareaspendiente,'tareasproceso'=>$tareasproceso,'tareasterminadas'=> $tareasterminadas,'tarea'=>$tarea], 200);
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
