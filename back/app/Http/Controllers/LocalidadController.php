<?php

namespace App\Http\Controllers;

use App\Models\Localidades;

class LocalidadController extends Controller
{
    public function index()
    {
        return Localidades::all();
    }
}
