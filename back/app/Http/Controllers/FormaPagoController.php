<?php

namespace App\Http\Controllers;

use App\FormaPago;
use Illuminate\Http\Request;
use App\Http\Resources\FormaPago as FormaPagosResource;


class FormaPagoController extends Controller
{
    public function index()
    {
        $formapago = FormaPago::all();

        return FormaPagosResource::collection($formapago);

    }
}
