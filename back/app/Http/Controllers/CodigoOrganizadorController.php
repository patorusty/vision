<?php

namespace App\Http\Controllers;

use App\Models\CodigoOrganizador;
use App\Models\CodigoProductor;
use Illuminate\Http\Request;

class CodigoOrganizadorController extends Controller
{
    public function index()
    {
        return CodigoOrganizador::with('organizadores')->get();
    }

    public function show($id)
    {
        return CodigoOrganizador::with("organizadores")->findOrFail($id);
    }

    public function indexFiltrado($compania_id)
    {
        return CodigoOrganizador::with('organizadores')->where('compania_id', $compania_id)->get();
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, []);
            $codigo_organizador = CodigoOrganizador::create([
                'codigo_organizador' => $request->input('codigo_organizador'),
                'organizador_id' => $request->input('organizador_id'),
                'compania_id' => $request->input('compania_id'),
                'activo' => $request->input('activo'),
            ]);

            $codigo_organizador->load('organizadores');
            $codigo_organizador::with("organizadores");
            return response($codigo_organizador, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $codigo_organizador = CodigoOrganizador::find($id);
            $codigo_organizador->update([
                'codigo_organizador' => $request->input('codigo_organizador'),
                'organizador_id' => $request->input('organizador_id'),
                'compania_id' => $request->input('compania_id'),
                'activo' => $request->input('activo'),
            ]);
            return response($codigo_organizador, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function destroy($id)
    {
        try {
            $codigo_organizador = CodigoOrganizador::find($id);
            if (CodigoProductor::where("codigo_organizador_id", $codigo_organizador->id)->exists()) {
                return response('', 202);
            } else {
                $codigo_organizador->delete();
                return response('', 200);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function busquedaCO(Request $request)
    {
        $codigo = $request->input('codigo');
        $compania_id = $request->input('compania_id');
        // dump($codigo, $compania_id);
        return ['usado' => CodigoOrganizador::where([['compania_id', $compania_id], ['codigo_organizador', $codigo]])->exists()];
    }
}
