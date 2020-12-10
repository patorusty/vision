<?php

namespace App\Http\Controllers;

use App\Models\TipoVigencia;
class TipoVigenciaController extends Controller
{
    public function index()
    {
        return TipoVigencia::all();
    }
}
