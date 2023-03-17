<?php

namespace App\Http\Controllers;

use App\Models\Cobertura;
use Illuminate\Http\Request;



class CoberturaController extends Controller
{
    public function index()
    {
        return Cobertura::orderBy('nombre', 'ASC')->get();
    }


    public function show(Cobertura $cobertura)
    {
        return $cobertura;
    }

    public function indexFiltrado($compania_id)
    {
        return Cobertura::where('compania_id', $compania_id)->get();
    }

    public function indexFiltradoActivo($compania_id)
    {
        return Cobertura::where([['compania_id', $compania_id], ["activo", 1]])->get();
    }


    public function store(Request $request)
    {

        try {
            $this->validate($request, []);
            $cobertura = Cobertura::create($request->all());
            return response($cobertura, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cobertura = Cobertura::findOrfail($id);
            $cobertura->fill($request->all())->save();
            return response($cobertura, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {
        try {
            $cobertura = Cobertura::findOrfail($id);
            $cobertura->delete();
            return response($cobertura, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
