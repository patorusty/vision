<?php

namespace App\Http\Controllers;

use App\AutomotorAnio;
use App\AutomotorVersion;
use Illuminate\Http\Request;
use App\Http\Resources\AutomotorAnio as AutomotorAniosResource;

class AutomotorAnioController extends Controller
{
    public function index()
    {
        return AutomotorAnio::all();
    }
    public function show($id)
    {
        return AutomotorAnio::findOrFail($id);
    }
    public function filtro($anio, $modelo)
    {
        // $automotor_anio = AutomotorAnio::where('anio_id', $anio)->with(['automotor_version.automotor_modelo'])->whereIn(['automotor_version->automotor_modelo_id', $modelo])->get();

        $automotor_anio = AutomotorAnio::where('anio_id', $anio)->with(['automotor_version.automotor_modelo'])->whereHas('automotor_version', function ($q) use ($modelo) {
            $q->whereHas('automotor_modelo', function ($q) use ($modelo) {
                $q->where('id', $modelo);
            });
        })->get();



        return $automotor_anio;
    }

    public function filtroXAnio($id)
    {
        $automotor_anio = AutomotorAnio::where('anio_id', $id)->with(['automotor_version.automotor_modelo', 'anios'])->get();
        return $automotor_anio;
    }

    public function store(Request $request)
    {
        $this->validate($request, []);

        $automotor_anio = AutomotorAnio::create([
            'automotor_version_id' => $request->input('automotor_version_id'),
            'anio_id' => $request->input('anio_id'),
        ]);

        return (['message' => 'guardado']);
    }

    public function update(Request $request, $id)
    {
        $automotor_anio = AutomotorAnio::find($id);
        $automotor_anio->update([
            'automotor_version_id' => $request->input('automotor_version_id'),
            'anio_id' => $request->input('anio_id'),
        ]);
    }
    public function destroy($id)
    {
        $automotor_anio = AutomotorAnio::find($id);

        $automotor_anio->delete();

        return ['message' => 'Eliminado'];
    }
}
