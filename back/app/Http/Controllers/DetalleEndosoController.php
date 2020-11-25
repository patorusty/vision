<?php

namespace App\Http\Controllers;

use App\DetalleEndosos;
use Illuminate\Http\Request;
use App\Http\Resources\DetalleEndoso as DetalleEndososResource;


class DetalleEndosoController extends Controller
{
    public function filtro($id)
    {
        $detalles = DetalleEndosos::with('tipo_endoso')->where('tipo_endoso_id', $id)->get();
        return DetalleEndososResource::collection($detalles);
    }
}
