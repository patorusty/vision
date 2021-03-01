<?php

namespace App\Http\Controllers;

use App\Models\TipoEndoso;
use Illuminate\Http\Request;

class TipoEndosoController extends Controller
{
    public function index()
    {
        return TipoEndoso::all();
    }
}
