<?php

namespace App\Http\Controllers;

use App\AutomotorVersion;
use Illuminate\Http\Request;
use App\Http\Resources\AutomotorVersion as AutomotorVersionsResource;

class AutomotorVersionController extends Controller
{
    public function index()
    {
        $automotor_version = AutomotorVersion::all();

        return AutomotorVersionsResource::collection($automotor_version);
    }
    public function show($id)
    {
        $automotor_version = AutomotorVersion::with('automotor_anios')->find($id);

        return new AutomotorVersionsResource($automotor_version);
    }
    public function filtro($id)
    {
        $versiones = AutomotorVersion::where('automotor_modelo_id', $id)->with(['automotor_modelo.automotor_marca', 'automotor_anios'])->get();
        return AutomotorVersionsResource::collection($versiones);
    }
    
   

    public function store(Request $request)
    {
        $this->validate($request, []);

        $automotor_version = AutomotorVersion::create([
            'automotor_modelo_id' => $request->input('automotor_modelo_id'),
            'nombre' => $request->input('nombre'),
        ]);

        return (['message' => 'guardado']);
    }

    public function update(Request $request, $id)
    {
        $automotor_version = AutomotorVersion::find($id);
        $automotor_version->update([
            'automotor_modelo_id' => $request->input('automotor_modelo_id'),
            'nombre' => $request->input('nombre'),
        ]);
    }
    public function destroy($id)
    {
        $automotor_version = AutomotorVersion::find($id);

        $automotor_version->delete();

        return ['message' => 'Eliminado'];
    }
    public function searchVersion()
    {
        if ($search = \Request::get('q')) {
            $version = AutomotorVersion::where('nombre', $search)->get();
        }
        return AutomotorVersionsResource::collection($version);

    }
}
