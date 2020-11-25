<?php

namespace App\Http\Controllers;

use App\AutomotorMarca;
use Illuminate\Http\Request;
use App\Http\Resources\AutomotorMarca as AutomotorMarcasResource;


class AutomotorMarcaController extends Controller
{
    public function index()
    {
        $automotor_marca = AutomotorMarca::all();
        
        return AutomotorMarcasResource::collection($automotor_marca);
    }
    public function show($id)
    {
        $automotor_marca = AutomotorMarca::findOrFail($id);

        return new AutomotorMarcasResource($automotor_marca);
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
        if ($search = \Request::get('q')) {
            $marca = AutomotorMarca::where('nombre', $search)->get();
        }
        return AutomotorMarcasResource::collection($marca);
    }
    
}
