<?php

namespace App\Http\Controllers;

use App\Models\CodigoProductor;
use Illuminate\Http\Request;

class CodigoProductorController extends Controller
{
    public function index()
    {
        //
    }
    public function show($id)
    {
        return CodigoProductor::with(['productores', 'codigo_organizador'])->findOrFail($id);
    }

    public function indexFiltrado($compania_id)
    {
        return CodigoProductor::with(['productores', 'codigo_organizador'])->where('compania_id', $compania_id)->get();
    }

    public function store(Request $request)
    {

        try {
            $this->validate($request, []);

            $codigo_productor = CodigoProductor::create([
                'codigo_productor' => $request->input('codigo_productor'),
                'codigo_organizador_id' => $request->input('codigo_organizador_id'),
                'productor_id' => $request->input('productor_id'),
                'compania_id' => $request->input('compania_id'),
                'activo' => $request->input('activo'),
            ]);
            $codigo_productor->load(['productores', 'codigo_organizador']);
            $codigo_productor::with(['productores', 'codigo_organizador']);
            return response($codigo_productor, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $codigo_productor = CodigoProductor::find($id);
            $codigo_productor->update([
                'codigo_productor' => $request->input('codigo_productor'),
                'codigo_organizador_id' => $request->input('codigo_organizador_id'),
                'productor_id' => $request->input('productor_id'),
                'compania_id' => $request->input('compania_id'),
                'activo' => $request->input('activo'),
            ]);
            return response($codigo_productor, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function busquedaCP(Request $request)
    {
        $codigo_organizador_id = $request->input('codigo_organizador_id');
        $codigo_productor = $request->input('codigo_productor');
        $compania_id = $request->input('compania_id');
        return ['usado' => CodigoProductor::where([['compania_id', $compania_id], ['codigo_organizador_id', $codigo_organizador_id], ['codigo_productor', $codigo_productor]])->exists()];
    }

    public function destroy($id)
    {
        $codigo_productores = CodigoProductor::find($id);

        $codigo_productores->delete();

        return ['message' => 'Eliminado'];
    }
}
