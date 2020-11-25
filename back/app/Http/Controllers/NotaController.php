<?php

namespace App\Http\Controllers;

use App\Notas;
use Illuminate\Http\Request;
use App\Http\Resources\Nota as NotasResource;

class NotaController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notas = Notas::All();
        $notasordenadas = $notas->sortByDesc('id');

        return NotasResource::collection($notasordenadas);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
     
        // ]);

        $nota = Notas::create([
            'user_id' => $request->input('user_id'),
            'titulo' => $request->input('titulo'),
            'fecha' => $request->input('fecha'),
            'nota' => $request->input('nota'),
            'hecho' => $request->input('hecho'),
        ]);

        return (['message' => 'guardado']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notas = Notas::findOrFail($id);

        return new NotasResource($notas);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $nota = Notas::find($id);
        $nota->update([
            'user_id' => $request->input('user_id'),
            'titulo' => $request->input('titulo'),
            'fecha' => $request->input('fecha'),
            'nota' => $request->input('nota'),            
            'hecho' => $request->input('hecho'),            
        ]);
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notas = Notas::find($id);
        
        $notas->delete();

        return ['message'=> 'Eliminado'];
    }
}
