<?php

namespace App\Http\Controllers;

use App\Models\Nota;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;
use Auth;
use DateTime;
use Mail;
use PDF;
use PDFMerger;
use Storage;
use Svg\Tag\Rect;
use Validator;

class NotasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Nota::all());
        $notas = Nota::all();
        // return view('catalogos.notas.index', compact('notas'));

        // $notas = Cache::remember('notas', 60, function () {
        //     return Nota::all();
        // });

        // $notas = Cache::get('notas');
        // dd($notas);
        $view = view('catalogos.notas.index', compact('notas'));

        return response($view);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('catalogos.notas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'    => 'required',
            'contenido' => 'required',
        ], [
            'titulo.required'    => 'El título es obligatorio',
            'contenido.required' => 'El contenido es obligatorio',
        ]);


        $nota = new Nota([
            'titulo'    => $request->input('titulo'),
            'contenido' => $request->input('contenido'),
        ]);
        $nota->save();

        // return response()->json(['nota' => $nota]);
        return response()->json(['success' => true, "error" => false, "nota" => $nota], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $nota = Nota::findOrFail($id);

        return view('catalogos.notas.show', compact('nota'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $nota = Nota::findOrFail($id);

        // Codificar los caracteres especiales en las propiedades del objeto
        $nota->titulo = htmlentities($nota->titulo, ENT_QUOTES);
        $nota->descripcion = htmlentities($nota->descripcion, ENT_QUOTES);

        return view('catalogos.notas.edit', compact('nota'));
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

        $titulo = $request->input('nota.titulo');
        $contenido = $request->input('contenido');

        // dd($request->all());
        $data = $request->validate([
            'titulo'    => 'required|max:255',
            'contenido' => 'required',
        ]);

        $nota = Nota::findOrFail($id);

        // dd($nota);
        $nota->update($data);

        return redirect()->route('notas.index')->with('success', 'Nota actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->delete();

        return redirect()->route('notas.index')->with('success', 'Nota eliminada correctamente.');
    }
}