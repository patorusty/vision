<?php

namespace App\Http\Controllers;

use App\Models\Localidad;

class LocalidadController extends Controller
{
    public function index()
    {
        return Localidad::all();
    }
}
