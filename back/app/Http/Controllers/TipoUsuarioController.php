<?php

namespace App\Http\Controllers;

use App\Models\TipoUsuario;


class TipoUsuarioController extends Controller
{
    public function index()
    {
        return TipoUsuario::all();
    }
}
