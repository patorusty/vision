<?php

namespace App\Http\Controllers;

use App\Models\AutomotorModelo;
use Illuminate\Http\Request;
use App\Http\Resources\AutomotorModelo as AutomotorModelosResource;
use DB;

class AutomotorModeloController extends Controller
{
    public function index()
    {
        return AutomotorModelo::with('automotor_version')->get();
    }
    public function show($id)
    {
        return AutomotorModelo::findOrFail($id);
    }
    public function filtro($id)
    {
        return AutomotorModelo::with('automotor_marca')->where('automotor_marca_id', $id)->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, []);

        $automotor_modelo = AutomotorModelo::create([
            'automotor_marca_id' => $request->input('automotor_marca_id'),
            'nombre' => $request->input('nombre'),
        ]);

        return (['message' => 'guardado']);
    }

    public function update(Request $request, $id)
    {
        $automotor_modelo = AutomotorModelo::find($id);
        $automotor_modelo->update([
            'automotor_marca_id' => $request->input('automotor_marca_id'),
            'nombre' => $request->input('nombre'),
        ]);
    }
    public function destroy($id)
    {
        $automotor_modelo = AutomotorModelo::find($id);

        $automotor_modelo->delete();

        return ['message' => 'Eliminado'];
    }

    public function searchModelo()
    {
        if ($search = \Request::get('q')) {
            $modelo = AutomotorModelo::where('nombre', $search)->get();
        }
        return $modelo;
    }
}
