<?php

namespace App\Http\Controllers;

use App\Models\TipoCarroceria;
use Illuminate\Http\Request;

class TipoCarroceriaController extends Controller
{
    public function index()
    {
        return TipoCarroceria::all();
    }
}
