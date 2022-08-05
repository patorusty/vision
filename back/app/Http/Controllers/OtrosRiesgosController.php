<?php

namespace App\Http\Controllers;

use App\Models\OtroRiesgo;
use Illuminate\Http\Request;

class OtrosRiesgosController extends Controller
{
    public function store(Request $request)
    {
        try {
            $or = OtroRiesgo::create($request->all());
            return response($or, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $or = OtroRiesgo::findOrFail($id);
            $or->fill($request->all())->save();
            return response($or, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
