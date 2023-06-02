<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;

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
            'tarea'      => $request->tarea,
            'user_id'  => Auth()->user()->id,
            'status'  => $request->status,
        ]);
        $tarea->save();
        $tarea->load('vendedor');
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
            'tarea'      => $request->tarea,
            'status'  => $request->status,
        ]);
        $tarea->save();
        $tareas = Tarea::with('vendedor')->where('vendedor_id',$tarea->vendedor_id)->get();

        return response()->json(['success' => true, "error" => false, 'tareas' => $tareas], 200);
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
