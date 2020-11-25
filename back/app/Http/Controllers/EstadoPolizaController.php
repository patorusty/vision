<?php

namespace App\Http\Controllers;

use App\Estadopoliza;
use Illuminate\Http\Request;
use App\Http\Resources\Estadopoliza as EstadopolizasResource;


class EstadoPolizaController extends Controller
{
    public function index()
    {
        $estadopolizas = EstadoPoliza::all();

        return EstadoPolizasResource::collection($estadopolizas);

    }
}
