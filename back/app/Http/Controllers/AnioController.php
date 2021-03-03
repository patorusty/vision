<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use Illuminate\Http\Request;


class AnioController extends Controller
{
    public function index()
    {
        return Anio::all();
    }
}
