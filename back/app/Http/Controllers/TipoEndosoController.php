<?php

namespace App\Http\Controllers;

use App\TipoEndosos;
use Illuminate\Http\Request;
use App\Http\Resources\TipoEndoso as TipoEndososResource;

class TipoEndosoController extends Controller
{
    public function index()
    {
        $tipoendosos = TipoEndosos::all();

        return TipoEndososResource::collection($tipoendosos);
    }
}
