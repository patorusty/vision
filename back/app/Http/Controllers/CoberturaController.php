<?php

namespace App\Http\Controllers;

use App\Coberturas;
use Illuminate\Http\Request;
use App\Http\Resources\Cobertura as CoberturasResource;



class CoberturaController extends Controller
{
    public function index()
    {
        $coberturas = Coberturas::all();

        return CoberturasResource::collection($coberturas);
    }


    public function show($id)
    {
        $coberturas = Coberturas::findOrFail($id);

        return new CoberturasResource($coberturas);
    }

    public function indexFiltrado($compania_id)
    {
        $coberturas = Coberturas::where('compania_id', $compania_id)->get();


        return CoberturasResource::collection($coberturas);
    }


    public function store(Request $request)
    {        
        $this->validate($request, [

        ]);

        $cobertura = Coberturas::create([
            'nombre' => $request->input('nombre'),
            'compania_id' => $request->input('compania_id'),
            'antiguedad' => $request->input('antiguedad'),
            'todo_riesgo' => $request->input('todo_riesgo'),
            'franquicia' => $request->input('franquicia'),
            'activa' => $request->input('activa'),
            'ajuste' => $request->input('ajuste'),
            'detalle' => $request->input('detalle'),
        ]);

        return (['message' => 'guardado']);

    }

    public function update(Request $request, $id)
    {
        $cobertura = Coberturas::find($id);
        $cobertura->update([
            'nombre' => $request->input('nombre'),
            'compania_id' => $request->input('compania_id'),
            'antiguedad' => $request->input('antiguedad'),
            'todo_riesgo' => $request->input('todo_riesgo'),
            'franquicia' => $request->input('franquicia'),
            'activa' => $request->input('activa'),
            'ajuste' => $request->input('ajuste'),
            'detalle' => $request->input('detalle'),
        ]);
    }

    public function destroy($id)
    {
        $cobertura = Coberturas::find($id);
        
        $cobertura->delete();

        return ['message'=> 'Eliminado'];
    }
}
