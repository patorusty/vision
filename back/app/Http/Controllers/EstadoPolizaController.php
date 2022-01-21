<?php

namespace App\Http\Controllers;

use App\Models\Estadopoliza;

class EstadoPolizaController extends Controller
{
    public function index()
    {
        return EstadoPoliza::all();
    }
}
