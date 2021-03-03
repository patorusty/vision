<?php

namespace App\Http\Controllers;

use App\Models\AutomotorMarca;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class AutomotorMarcaController extends Controller
{
    public function index()
    {
        return AutomotorMarca::with(['modelos.versiones.anios' => function ($query) {
            $query->where('anio_id', "=", "2013");
        }])->get();
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
        $this->validate($request, []);

        $automotor_marca = AutomotorMarca::create([
            'nombre' => $request->input('nombre'),
        ]);

        return (['message' => 'guardado']);
    }

    public function update(Request $request, $id)
    {
        $automotor_marca = AutomotorMarca::find($id);
        $automotor_marca->update([
            'nombre' => $request->input('nombre'),
        ]);
    }
    public function destroy($id)
    {
        $automotor_marca = AutomotorMarca::find($id);

        $automotor_marca->delete();

        return ['message' => 'Eliminado'];
    }

    public function searchMarca()
    {
        // if ($search = \Request::get('q')) {
        //     $marca = AutomotorMarca::where('nombre', $search)->get();
        // }
        // return $marca;
    }
}
