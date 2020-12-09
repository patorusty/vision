<?php

namespace App\Http\Controllers;

use App\Models\FormaPago;


class FormaPagoController extends Controller
{
    public function index()
    {
        return FormaPago::all();


    }
}
