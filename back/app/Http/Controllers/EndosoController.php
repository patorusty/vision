<?php

namespace App\Http\Controllers;

use App\Models\Endoso;
use Illuminate\Http\Request;


class EndosoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $endosos = Endoso::with(['tipo_endoso', 'detalle_endoso', 'poliza.cliente', 'poliza.compania', 'poliza.riesgo_automotor'])->where('completo', 0)->orderBy('id', 'DESC')->get();
        return $endosos;
    }
    public function indexFiltrado($poliza_id)
    {
        $endosos = Endoso::with(['tipo_endoso', 'detalle_endoso'])->where('poliza_id', $poliza_id)->orderBy('id', 'DESC')->get();
        return $endosos;
    }

    public function anulaciones($poliza_id)
    {
        $endoso = Endoso::where('poliza_id', $poliza_id)->where('tipo_endoso_id', 1)->get();
        return $endoso;
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
            $this->validate($request, []);

            $endoso = Endoso::create($request->all());
            $endoso->load(['tipo_endoso', 'detalle_endoso']);
            $endoso::with(['tipo_endoso', 'detalle_endoso']);
            return response($endoso, 201);
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
        return Endoso::with(['tipo_endoso', 'poliza.cliente', 'poliza.riesgo_automotor', 'poliza.compania', 'poliza.riesgo_automotor', 'poliza.riesgo_automotor.marca', 'poliza.riesgo_automotor.version', 'poliza.riesgo_automotor.cobertura'])->findOrFail($id);
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
            $endoso = Endoso::findOrFail($id);
            $endoso->fill($request->all())->save();
            $endoso->load(['tipo_endoso', 'detalle_endoso']);
            $endoso::with(['tipo_endoso', 'detalle_endoso']);
            return response($endoso, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function search()
    {
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
            $endoso = Endoso::findOrFail($id);
            $endoso->delete();
            return response('', 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
