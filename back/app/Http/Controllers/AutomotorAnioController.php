<?php

namespace App\Http\Controllers;

use App\Models\AnioVersion;
use Illuminate\Http\Request;

class AnioVersionController extends Controller
{
    public function index()
    {
        return AnioVersion::all();
    }
    public function show($id)
    {
        return AnioVersion::findOrFail($id);
    }
    public function filtro($anio, $modelo)
    {
        // $automotor_anio = AnioVersion::where('anio_id', $anio)->with(['automotor_version.automotor_modelo'])->whereIn(['automotor_version->automotor_modelo_id', $modelo])->get();

        $automotor_anio = AnioVersion::where('anio_id', $anio)->with(['automotor_version.automotor_modelo'])->whereHas('automotor_version', function ($q) use ($modelo) {
            $q->whereHas('automotor_modelo', function ($q) use ($modelo) {
                $q->where('id', $modelo);
            });
        })->get();
        return $automotor_anio;
    }

    public function filtroXAnio($id)
    {
        $automotor_anio = AnioVersion::where('anio_id', $id)->with(['versiones.modelo.marca', 'anios'])->get();
        return $automotor_anio;
    }

    public function store(Request $request)
    {
        $this->validate($request, []);

        $automotor_anio = AnioVersion::create([
            'automotor_version_id' => $request->input('automotor_version_id'),
            'anio_id' => $request->input('anio_id'),
        ]);

        return (['message' => 'guardado']);
    }

    public function update(Request $request, $id)
    {
        $automotor_anio = AnioVersion::find($id);
        $automotor_anio->update([
            'automotor_version_id' => $request->input('automotor_version_id'),
            'anio_id' => $request->input('anio_id'),
        ]);
    }
    public function destroy($id)
    {
        $automotor_anio = AnioVersion::find($id);

        $automotor_anio->delete();

        return ['message' => 'Eliminado'];
    }
}
