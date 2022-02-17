<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use Illuminate\Http\Request;


class AnioController extends Controller
{
    public function index()
    {
        return Anio::orderBy('id', 'DESC')->get();
    }

    public function filtro(Request $request)
    {
        $modelo_id = $request->input('modelo_id');
        $anio_id = $request->input('anio_id');
        return Anio::with(["versiones" => function ($query) use ($modelo_id) {
            $query->where('automotor_modelo_id', $modelo_id);
        }])->findOrFail($anio_id);
    }

    public function store(Request $request)
    {
        try {
            $anio = Anio::create($request->all());
            $a = [
                'id' => $anio->anio,
                'anio' => $anio->anio
            ];
            return response($a, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
