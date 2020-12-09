<?php

namespace App\Http\Controllers;

use App\Models\TipoRiesgo;


class TipoRiesgoController extends Controller
{
    public function index()
    {
        return TipoRiesgo::all();

    }

}
