<?php

namespace App\Http\Controllers;

use App\Models\SiniestroAutomotor;
use Illuminate\Http\Request;
use App\Http\Resources\SiniestroAutomotor as SiniestroAutomotorsResource;


class SiniestroAutomotorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return SiniestroAutomotor::with(['poliza.cliente', 'poliza.compania'])->get();
    }
    public function indexFiltrado($poliza_id)
    {
        return SiniestroAutomotor::where('poliza_id', $poliza_id)->get();
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
            $siniestro = SiniestroAutomotor::create($request->all());
            return response($siniestro, 201);
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
        return SiniestroAutomotor::with(['poliza.cliente', 'poliza.riesgo_automotor', 'poliza.compania', 'poliza.riesgo_automotor', 'poliza.riesgo_automotor.marca', 'poliza.riesgo_automotor.version', 'poliza.riesgo_automotor.cobertura'])->findOrFail($id);
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
            $siniestro = SiniestroAutomotor::findOrFail($id);
            $siniestro->fill($request->all())->save();
            return response($siniestro, 200);
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
            $siniestro = SiniestroAutomotor::find($id);
            $siniestro->delete();
            return response('', 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
