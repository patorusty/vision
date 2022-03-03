<?php

namespace App\Http\Controllers;

use App\Models\EstadoPoliza;

class EstadoPolizaController extends Controller
{
    public function index()
    {
        return EstadoPoliza::orderBy('nombre', 'DESC')->get();
    }
}
