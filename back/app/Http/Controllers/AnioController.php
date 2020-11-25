<?php

namespace App\Http\Controllers;

use App\Anio;
use Illuminate\Http\Request;
use App\Http\Resources\Anio as AniosResource;


class AnioController extends Controller
{
    public function index()
    {
        $anio = Anio::all();

        return AniosResource::collection($anio);
    }
}
