<?php

namespace App\Http\Controllers;

use App\Models\AutomotorVersion;
use Illuminate\Http\Request;

class AutomotorVersionController extends Controller
{
    public function index()
    {
        return AutomotorVersion::all();
    }
    public function show($id)
    {
        return AutomotorVersion::findOrFail($id);
    }
    public function filtro($id)
    {
        return AutomotorVersion::where('automotor_modelo_id', $id)->get();
    }

    public function store(Request $request)
    {
        try {
            $version = AutomotorVersion::create($request->all());
            return response($version, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $version = AutomotorVersion::findOrFail($id);
            $version->fill($request->all())->save();
            return response($version, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function destroy($id)
    {
        $automotor_version = AutomotorVersion::find($id);

        $automotor_version->delete();

        return ['message' => 'Eliminado'];
    }
    public function searchVersion(Request $req)
    {
        $nombre = $req->input('nombre');
        $modelo_id = $req->input('id');
        return ['usado' => AutomotorVersion::where('automotor_modelo_id', $modelo_id)->where('nombre', $nombre)->exists()];
    }
}
