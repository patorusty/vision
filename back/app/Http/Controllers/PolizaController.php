<?php

namespace App\Http\Controllers;

use App\Models\Poliza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Poliza::with(['codigo_productor', 'estado_polizas', 'clientes', 'companias', 'tipo_vigencias', 'riesgo_automotor'])->get();
    }

    public function chequeoRenovada($poliza_actual)
    {
        $poliza = Poliza::where('renueva_numero', $poliza_actual)->get();
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
            $poliza = Poliza::create($request->all());
            return response($poliza, 201);
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
    public function show($numero_solicitud)
    {
        $poliza = Poliza::where('numero_solicitud', $numero_solicitud)->with(['codigo_productor.productores', 'estado_polizas', 'clientes', 'companias', 'tipo_vigencias', 'endosos.tipo_endoso', 'endosos.detalle_endoso', 'siniestros'])->get();
        return $poliza[0];
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
            $poliza = Poliza::findOrFail($id);
            $poliza->fill($request->all())->save();
            return response($poliza, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function searchPoliza()
    {
        // if ($search = Request::get('q')) {
        //     $numero = Poliza::where('numero', $search)->get();
        // } 
        // // dd($numero);
    }

    public function numeroDeSolicitud()
    {
        $poliza =  DB::table('polizas')->orderBy('numero_solicitud', 'DESC')->take(1)->get();
        return $poliza;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poliza $poliza)
    {
        try {
            $poliza->delete();
            return response('', 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function vigencias()
    {
        $polizas = Poliza::All();
        $hoy = date("Y-m-d H:i:s");
        $hola = 'hola';
        // dd($polizas);
        // foreach ($polizas as $poliza => $value) {
        //     var_dump($value);
        //     // dd($poliza->numero);
        // }
        // dd($hoy);
        $polizas->map(function ($poliza) {
            return dd($poliza);
        });
    }

    function checkPolizas()
    {
        $hoy = Carbon::now();

        $polizas = Poliza::all();
        foreach ($polizas as $poliza) {
            if (!$poliza["estado_id" == 1]) {
                switch ($poliza) {
                    case $hoy->isAfter($poliza['vigencia_desde']) && $hoy->isBefore($poliza['vigencia_hasta']->subMonth()) && !$poliza['renueva_numero']:
                        $poliza["estado_id"] = 2;
                        //VIGENTE
                        break;
                    case $hoy->isAfter($poliza['vigencia_hasta']->subMonth()) && $hoy->isBefore($poliza['vigencia_hasta']) && !$poliza['renueva_numero']:
                        $poliza["estado_id"] = 3;
                        //VIGENTE A RENOVAR
                        break;
                    case $hoy->isAfter($poliza['vigencia_desde']) && $hoy->isBefore($poliza['vigencia_hasta']) && $poliza['renueva_numero']:
                        $poliza["estado_id"] = 6;
                        //VIGENTE RENOVADA
                        break;
                    case $hoy->isAfter($poliza['vigencia_hasta']) && $poliza['renueva_numero']:
                        $poliza["estado_id"] = 4;
                        //CUMPLIDA RENOVADA
                        break;
                    case $hoy->isAfter($poliza['vigencia_hasta']):
                        $poliza["estado_id"] = 5;
                        //CUMPLIDA
                        break;
                    case $hoy->isBefore($poliza['vigencia_desde']):
                        $poliza["estado_id"] = 1;
                        // PENDIENTE
                        break;
                    default:
                        # code...
                        break;
                }
            }
        }
    }
}
