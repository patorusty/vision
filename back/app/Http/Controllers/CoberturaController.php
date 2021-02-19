<?php

namespace App\Http\Controllers;

use App\Models\Cobertura;
use Illuminate\Http\Request;



class CoberturaController extends Controller
{
    public function index()
    {
        return Cobertura::all();
    }


    public function show($id)
    {
        return Cobertura::findOrFail($id);
    }

    public function indexFiltrado($compania_id)
    {
        return Cobertura::where('compania_id', $compania_id)->get();
    }


    public function store(Request $request)
    {

        try {
            $this->validate($request, []);
            $cobertura = Cobertura::create([
                'nombre' => $request->input('nombre'),
                'compania_id' => $request->input('compania_id'),
                'antiguedad' => $request->input('antiguedad'),
                'todo_riesgo' => $request->input('todo_riesgo'),
                'franquicia' => $request->input('franquicia'),
                'activo' => $request->input('activo'),
                'ajuste' => $request->input('ajuste'),
                'detalle' => $request->input('detalle'),
            ]);
            return response($cobertura, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cobertura = Cobertura::find($id);
            $cobertura->update([
                'nombre' => $request->input('nombre'),
                'compania_id' => $request->input('compania_id'),
                'antiguedad' => $request->input('antiguedad'),
                'todo_riesgo' => $request->input('todo_riesgo'),
                'franquicia' => $request->input('franquicia'),
                'activo' => $request->input('activo'),
                'ajuste' => $request->input('ajuste'),
                'detalle' => $request->input('detalle'),
            ]);
            return response($cobertura, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $cobertura = Cobertura::find($id);
            $cobertura->delete();
            return response($cobertura, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
