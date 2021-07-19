<?php

namespace App\Http\Controllers;

use App\Models\AutomotorMarca;
use App\Models\AutomotorModelo;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AutomotorMarcaController extends Controller
{
    public function index()
    {
        // return AutomotorMarca::with(['modelos.versiones.anios'])->get();
        return AutomotorMarca::all();
    }

    public function filtroXanio($anio)
    {
        $autos = AutomotorMarca::with('modelos.versiones.anios')->whereHas(
            'modelos.versiones.anios',
            fn ($query) => $query->where('anio_id', $anio)
        )->get();

        return $autos;
    }
    public function show($id)
    {
        return AutomotorMarca::findOrFail($id);
    }
    public function store(Request $request)
    {
        try {
            $marca = AutomotorMarca::create($request->all());
            return response($marca, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $marca = AutomotorMarca::findOrFail($id);
            $marca->fill($request->all())->save();
            return response($marca, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function destroy($id)
    {
        try {
            $marca = AutomotorMarca::findOrFail($id);
            if (AutomotorModelo::where("automotor_marca_id", $marca->id)->exists()) {
                return response('', 202);
            } else {
                $marca->delete();
                return response('', 200);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function searchMarca(Request $req)
    {
        $nombre = $req->input('nombre');
        return ['usado' => AutomotorMarca::where('nombre', $nombre)->exists()];
    }
}
