<?php

namespace App\Http\Controllers;

use App\RiesgoAutomotor;
use Illuminate\Http\Request;
use App\Http\Resources\RiesgoAutomotor as RiesgoAutomotorsResource;


class RiesgoAutomotorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $riesgo_automotor = RiesgoAutomotor::all();

        return RiesgoAutomotorsResource::collection($riesgo_automotor);
    }

    public function searchPoliza($id)
    {
        $polizaId = RiesgoAutomotor::where('poliza_id', $id)->with('automotor_marca', 'automotor_modelo', 'automotor_version', 'automotor_anio', 'cobertura')->get();
        return RiesgoAutomotorsResource::collection($polizaId);
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
        

        $riesgo_automotor = RiesgoAutomotor::create([
            'poliza_id' => $request->input('poliza_id'),
            'automotor_tipo' => $request->input('automotor_tipo'),
            'automotor_anio_id' => $request->input('automotor_anio_id'),
            'automotor_marca_id' => $request->input('automotor_marca_id'),
            'automotor_modelo_id' => $request->input('automotor_modelo_id'),
            'automotor_version_id' => $request->input('automotor_version_id'),
            'tipo_patente' => $request->input('tipo_patente'),
            'patente' => $request->input('patente'),
            'nro_motor' => $request->input('nro_motor'),
            'nro_chasis' => $request->input('nro_chasis'),
            'uso' => $request->input('uso'),
            'tipo_carroceria' => $request->input('tipo_carroceria'),
            'combustible' => $request->input('combustible'),
            'okm' => $request->input('okm'),
            'estado_general' => $request->input('estado_general'),
            'color' => $request->input('color'),
            'equipo_rastreo' => $request->input('equipo_rastreo'),
            'gnc' => $request->input('gnc'),
            'gnc_nro_oblea' => $request->input('gnc_nro_oblea'),
            'gnc_vencimiento_oblea' => $request->input('gnc_vencimiento_oblea'),
            'gnc_nro_regulador' => $request->input('gnc_nro_regulador'),
            'gnc_marca_regulador' => $request->input('gnc_marca_regulador'),
            'gnc_nro_cilindro' => $request->input('gnc_nro_cilindro'),
            'gnc_marca_cilindro' => $request->input('gnc_marca_cilindro'),
            'cubiertas_medida' => $request->input('cubiertas_medida'),
            'cubiertas_marca' => $request->input('cubiertas_marca'),
            'accesorio_01' => $request->input('accesorio_01'),
            'valor_accesorio_01' => $request->input('valor_accesorio_01'),
            'accesorio_02' => $request->input('accesorio_02'),
            'valor_accesorio_02' => $request->input('valor_accesorio_02'),
            'acreedor_prendario' => $request->input('acreedor_prendario'),
            'acreedor_rs' => $request->input('acreedor_rs'),
            'acreedor_cuit' => $request->input('acreedor_cuit'),
            'observaciones' => $request->input('observaciones'),
            'cobertura_id' => $request->input('cobertura_id'),
            'franquicia' => $request->input('franquicia'),
            'ajuste' => $request->input('ajuste'),
            'valor_vehiculo' => $request->input('valor_vehiculo'),
            'valor_gnc' => $request->input('valor_gnc'),
            'valor_accesorios' => $request->input('valor_accesorios'),
            'valor_total' => $request->input('valor_total'),
            'vigencia_desde' => $request->input('vigencia_desde'),
            'vigencia_hasta' => $request->input('vigencia_hasta'),
        ]);

        return new RiesgoAutomotorsResource($riesgo_automotor);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $riesgo_automotor = RiesgoAutomotor::with('imagenes')->findOrFail($id);
        // $riesgo_automotor = $riesgo_automotor->imagenes->toArray();

        return new RiesgoAutomotorsResource($riesgo_automotor);
    }


    public function indexFiltrado($poliza_id)
    {
        $riesgo_automotor = RiesgoAutomotor::with(['automotor_marca', 'automotor_modelo', 'automotor_version', 'automotor_anio', 'cobertura'])->where('poliza_id', $poliza_id)->get();


        return RiesgoAutomotorsResource::collection($riesgo_automotor);
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
        $riesgo_automotor = RiesgoAutomotor::find($id);
        $riesgo_automotor->update([
            'poliza_id' => $request->input('poliza_id'),
            'automotor_tipo' => $request->input('automotor_tipo'),
            'automotor_anio_id' => $request->input('automotor_anio_id'),
            'automotor_marca_id' => $request->input('automotor_marca_id'),
            'automotor_modelo_id' => $request->input('automotor_modelo_id'),
            'automotor_version_id' => $request->input('automotor_version_id'),
            'tipo_patente' => $request->input('tipo_patente'),
            'patente' => $request->input('patente'),
            'nro_motor' => $request->input('nro_motor'),
            'nro_chasis' => $request->input('nro_chasis'),
            'uso' => $request->input('uso'),
            'tipo_carroceria' => $request->input('tipo_carroceria'),
            'combustible' => $request->input('combustible'),
            'okm' => $request->input('okm'),
            'estado_general' => $request->input('estado_general'),
            'color' => $request->input('color'),
            'equipo_rastreo' => $request->input('equipo_rastreo'),
            'gnc' => $request->input('gnc'),
            'gnc_nro_oblea' => $request->input('gnc_nro_oblea'),
            'gnc_vencimiento_oblea' => $request->input('gnc_vencimiento_oblea'),
            'gnc_nro_regulador' => $request->input('gnc_nro_regulador'),
            'gnc_marca_regulador' => $request->input('gnc_marca_regulador'),
            'gnc_nro_cilindro' => $request->input('gnc_nro_cilindro'),
            'gnc_marca_cilindro' => $request->input('gnc_marca_cilindro'),
            'cubiertas_medida' => $request->input('cubiertas_medida'),
            'cubiertas_marca' => $request->input('cubiertas_marca'),
            'accesorio_01' => $request->input('accesorio_01'),
            'valor_accesorio_01' => $request->input('valor_accesorio_01'),
            'accesorio_02' => $request->input('accesorio_02'),
            'valor_accesorio_02' => $request->input('valor_accesorio_02'),
            'acreedor_prendario' => $request->input('acreedor_prendario'),
            'acreedor_rs' => $request->input('acreedor_rs'),
            'acreedor_cuit' => $request->input('acreedor_cuit'),
            'observaciones' => $request->input('observaciones'),
            'cobertura_id' => $request->input('cobertura_id'),
            'franquicia' => $request->input('franquicia'),
            'ajuste' => $request->input('ajuste'),
            'valor_vehiculo' => $request->input('valor_vehiculo'),
            'valor_gnc' => $request->input('valor_gnc'),
            'valor_accesorios' => $request->input('valor_accesorios'),
            'valor_total' => $request->input('valor_total'),
            'vigencia_desde' => $request->input('vigencia_desde'),
            'vigencia_hasta' => $request->input('vigencia_hasta'),
        ]);

        return ['message'=> 'Updated'];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $riesgo_automotor = RiesgoAutomotor::find($id);
        
        $riesgo_automotor->delete();

        return ['message'=> 'Eliminado'];
    }
}
