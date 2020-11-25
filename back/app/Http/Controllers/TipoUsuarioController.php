<?php

namespace App\Http\Controllers;

use App\TipoUsuario;
use Illuminate\Http\Request;
use App\Http\Resources\TipoUsuario as TipoUsuariosResource;


class TipoUsuarioController extends Controller
{
    public function index()
    {
        $tipo_usuario = TipoUsuario::all();
    
        return TipoUsuariosResource::collection($tipo_usuario);
    }
}
