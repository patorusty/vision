<?php

namespace App\Http\Controllers;

use App\SiniestroAutomotor;
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
        
        $siniestros = SiniestroAutomotor::with(['polizas.clientes','polizas.companias'])->get();

        return SiniestroAutomotorsResource::collection($siniestros);

    }
    public function indexFiltrado($poliza_id)
    {
        $siniestros = SiniestroAutomotor::where('poliza_id', $poliza_id)->get();

        return SiniestroAutomotorsResource::collection($siniestros);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request, [
     
        // ]);

        $siniestro = SiniestroAutomotor::create([
            'poliza_id' => $request->input('poliza_id'),
            'numero_siniestro' => $request->input('numero_siniestro'),
            'fecha_denuncia' => $request->input('fecha_denuncia'),
            'fecha_siniestro' => $request->input('fecha_siniestro'),
            'fecha_completo' => $request->input('fecha_completo'),
            'tipo_reclamo' => $request->input('tipo_reclamo'),
            'estado_siniestro' => $request->input('estado_siniestro'),
            'inspeccion' => $request->input('inspeccion'),
            'orden_trabajo' => $request->input('orden_trabajo'),
            'cleas' => $request->input('cleas'),
            'ciavscia' => $request->input('ciavscia'),
            'culpabilidad' => $request->input('culpabilidad'),
            'taller' => $request->input('taller'),
            'detalle' => $request->input('detalle'),
        ]);

        return (['message' => 'guardado']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $siniestro = SiniestroAutomotor::findOrFail($id);
        $siniestro = SiniestroAutomotor::with(['polizas.clientes', 'polizas.riesgo_automotor', 'polizas.companias', 'polizas.riesgo_automotor', 'polizas.riesgo_automotor.automotor_marca', 'polizas.riesgo_automotor.automotor_version', 'polizas.riesgo_automotor.cobertura'])->findOrFail($id);


        return new SiniestroAutomotorsResource($siniestro);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $siniestro = SiniestroAutomotor::find($id);
        $siniestro->update([
            'poliza_id' => $request->input('poliza_id'),
            'numero_siniestro' => $request->input('numero_siniestro'),
            'fecha_denuncia' => $request->input('fecha_denuncia'),
            'fecha_siniestro' => $request->input('fecha_siniestro'),
            'fecha_completo' => $request->input('fecha_completo'),
            'tipo_reclamo' => $request->input('tipo_reclamo'),
            'estado_siniestro' => $request->input('estado_siniestro'),
            'inspeccion' => $request->input('inspeccion'),
            'orden_trabajo' => $request->input('orden_trabajo'),
            'cleas' => $request->input('cleas'),
            'ciavscia' => $request->input('ciavscia'),
            'culpabilidad' => $request->input('culpabilidad'),
            'taller' => $request->input('taller'),
            'detalle' => $request->input('detalle'),
        ]);
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $siniestro = SiniestroAutomotor::find($id);
        
        $siniestro->delete();

        return ['message'=> 'Eliminado'];
    }
}
