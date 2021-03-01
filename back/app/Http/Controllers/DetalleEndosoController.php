<?php

namespace App\Http\Controllers;

use App\Models\DetalleEndoso;
use Illuminate\Http\Request;


class DetalleEndosoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DetalleEndoso::all();
    }

    public function filtro($id)
    {
        return DetalleEndoso::with('tipo_endoso')->where('tipo_endoso_id', $id)->get();
    }
}
