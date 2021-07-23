<?php

namespace App\Http\Controllers;

use App\Models\RiesgoAutomotor;
use Illuminate\Http\Request;

class RiesgoAutomotorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RiesgoAutomotor::all();
    }

    public function searchPoliza($id)
    {
        return RiesgoAutomotor::where('poliza_id', $id)->with('automotor_marca', 'automotor_modelo', 'automotor_version', 'automotor_anio', 'cobertura')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $ra = RiesgoAutomotor::create($request->all());
            return response($ra, 201);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return RiesgoAutomotor::with('imagenes')->findOrFail($id);
        // $riesgo_automotor = $riesgo_automotor->imagenes->toArray();
    }


    public function indexFiltrado($poliza_id)
    {
        return RiesgoAutomotor::with(['automotor_marca', 'automotor_modelo', 'automotor_version', 'automotor_anio', 'cobertura'])->where('poliza_id', $poliza_id)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $ra = RiesgoAutomotor::findOrFail($id);
            $ra->fill($request->all())->save();
            $ra->load(["marca", "modelo", "version", "anio", "cobertura"]);
            return response($ra, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $ra = RiesgoAutomotor::findOrFail($id);
            $ra->delete();
            return response('', 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
