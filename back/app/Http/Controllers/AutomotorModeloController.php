<?php

namespace App\Http\Controllers;

use App\Models\AutomotorModelo;
use App\Models\AutomotorVersion;
use Illuminate\Http\Request;

class AutomotorModeloController extends Controller
{
    public function index()
    {
        return AutomotorModelo::all();
    }
    public function show($id)
    {
        return AutomotorModelo::findOrFail($id);
    }
    // public function filtroMarca($id)
    // {
    //     $modelo = AutomotorModelo::with('marca')->where('automotor_marca_id', $id)->get();
    //     return $modelo[0];
    // }

    public function store(Request $request)
    {
        try {
            $modelo = AutomotorModelo::create($request->all());
            return response($modelo, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $modelo = AutomotorModelo::findOrFail($id);
            $modelo->fill($request->all())->save();
            return response($modelo, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function destroy($id)
    {
        try {
            $modelo = AutomotorModelo::findOrFail($id);
            if (AutomotorVersion::where("automotor_modelo_id", $modelo->id)->exists()) {
                return response('', 202);
            } else {
                $modelo->delete();
                return response('', 200);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function searchModelo(Request $req)
    {
        $nombre = $req->input('nombre');
        $marca_id = $req->input('id');
        return ['usado' => AutomotorModelo::where('automotor_marca_id', $marca_id)->where('nombre', $nombre)->exists()];
    }
}
