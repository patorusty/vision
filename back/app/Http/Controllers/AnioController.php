<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use Illuminate\Http\Request;


class AnioController extends Controller
{
    public function index()
    {
        return Anio::all();
    }

    public function filtro(Request $request)
    {
        $modelo_id = $request->input('modelo_id');
        $anio_id = $request->input('anio_id');
        return Anio::with(["versiones" => function ($query) use ($modelo_id) {
            $query->where('automotor_modelo_id', $modelo_id);
        }])->findOrFail($anio_id);
    }
}
