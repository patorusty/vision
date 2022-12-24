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
        $this->checkPolizas();
        return Poliza::with(['codigo_productor:id,codigo_productor', 'estado:id,nombre', 'cliente:id,nombre,apellido,razon_social', 'compania:id,nombre', 'tipo_vigencias:id,vigencia', 'riesgo_automotor', 'otro_riesgo', 'riesgo_automotor.cobertura:id,tipo_cobertura'])->orderBy('vigencia_hasta', 'DESC')->get(
            [
                'numero',
                'tipo_riesgo_id',
                'codigo_productor_id',
                'estado_poliza_id',
                'cliente_id',
                'compania_id',
                'tipo_vigencia_id',
                'estado_poliza_id',
                "vigencia_desde",
                "vigencia_hasta",
                'fecha_solicitud',
                'fecha_emision',
                'fecha_recepcion',
                'fecha_entrega_original',
                'fecha_entrega_email',
                'forma_pago_id',
                'id'
            ]
        );
    }

    public function polizasPendientes()
    {
        return Poliza::with(['codigo_productor', 'estado', 'cliente', 'compania', 'tipo_vigencias', 'riesgo_automotor', 'tipo_de_riesgo', 'otro_riesgo', 'riesgo_automotor.anio', "riesgo_automotor.marca", "riesgo_automotor.modelo", "riesgo_automotor.version"])->whereNull(["numero", "renueva_numero"])->orderBy('vigencia_hasta', 'DESC')->get();
    }

    public function polizasARenovar()
    {
        return Poliza::with(['codigo_productor', 'estado', 'cliente', 'compania', 'tipo_vigencias', 'riesgo_automotor', 'tipo_de_riesgo', 'otro_riesgo', 'riesgo_automotor.anio', "riesgo_automotor.marca", "riesgo_automotor.modelo", "riesgo_automotor.version"])->whereNull("numero")->whereNotNull("renueva_numero")->orderBy('vigencia_hasta', 'DESC')->get();
    }

    public function polizasVigentes()
    {
        return Poliza::with(['compania:id,nombre', 'otro_riesgo:id,poliza_id', 'riesgo_automotor:id,poliza_id', 'tipo_de_riesgo:id,nombre'])->where('estado_poliza_id', 3)->orWhere('estado_poliza_id', 4)->orWhere('estado_poliza_id', 7)->get(
            [
                'tipo_riesgo_id',
                'estado_poliza_id',
                'compania_id',
                'id'
            ]
        );
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
            $this->checkOnePoliza($poliza);
            $poliza = $poliza->refresh();
            $poliza->load(['codigo_productor.productores', 'estado', 'cliente', 'compania', 'tipo_vigencias', 'riesgo_automotor', 'tipo_de_riesgo', 'otro_riesgo',]);
            $poliza::with(['codigo_productor.productores', 'estado', 'cliente', 'compania', 'tipo_vigencias', 'riesgo_automotor', 'tipo_de_riesgo', 'otro_riesgo',]);
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
    public function show($id)
    {
        $poliza = Poliza::with(['codigo_productor.productores', 'estado', 'cliente', 'compania', 'tipo_vigencias', 'endosos.tipo_endoso', 'endosos.detalle_endoso', 'siniestros', 'riesgo_automotor.marca', 'riesgo_automotor.modelo', 'riesgo_automotor.version', 'riesgo_automotor.cobertura', 'riesgo_automotor.anio', 'otro_riesgo', 'tipo_de_riesgo'])->findOrFail($id);
        return $poliza;
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
            $this->checkOnePoliza($poliza);
            $poliza->load(['codigo_productor.productores', 'estado', 'cliente', 'compania', 'tipo_vigencias', 'endosos.tipo_endoso', 'endosos.detalle_endoso', 'siniestros', 'riesgo_automotor.marca', 'riesgo_automotor.modelo', 'riesgo_automotor.version', 'riesgo_automotor.cobertura', 'otro_riesgo', 'tipo_de_riesgo']);
            $poliza::with(['codigo_productor.productores', 'estado', 'cliente', 'compania', 'tipo_vigencias', 'endosos.tipo_endoso', 'endosos.detalle_endoso', 'siniestros', 'riesgo_automotor.marca', 'riesgo_automotor.modelo', 'riesgo_automotor.version', 'riesgo_automotor.cobertura', 'otro_riesgo', 'tipo_de_riesgo']);
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
        // dd($polizas);
        foreach ($polizas as $poliza) {
            if ($poliza->estado_poliza_id != 1 && $poliza->estado_poliza_id != 5) {
                switch ($poliza) {
                    case $hoy->isAfter($poliza->vigencia_desde) && $hoy->isBefore($poliza->vigencia_hasta->subMonth()) && $poliza->renovada == 0:
                        $poliza->estado_poliza_id = 3;
                        $poliza->save();
                        //VIGENTE
                        break;
                    case $hoy->isAfter($poliza->vigencia_hasta->subMonth()) && $hoy->isBefore($poliza->vigencia_hasta) && $poliza->renovada == 0:
                        $poliza->estado_poliza_id = 4;
                        $poliza->save();
                        //VIGENTE A RENOVAR
                        break;
                    case $hoy->isAfter($poliza->vigencia_desde) && $hoy->isBefore($poliza->vigencia_hasta) && $poliza->renovada == 1:
                        $poliza->estado_poliza_id = 7;
                        $poliza->save();
                        //VIGENTE RENOVADA
                        break;
                    case $hoy->isAfter($poliza->vigencia_hasta) && $poliza->renovada == 1:
                        $poliza->estado_poliza_id = 5;
                        $poliza->save();
                        //CUMPLIDA RENOVADA
                        break;
                    case $hoy->isAfter($poliza->vigencia_hasta) && $poliza->renovada == 0:
                        $poliza->estado_poliza_id = 6;
                        $poliza->save();
                        //CUMPLIDA
                        break;
                    case $hoy->isBefore($poliza->vigencia_desde):
                        $poliza->estado_poliza_id = 2;
                        $poliza->save();
                        // PENDIENTE
                        break;
                    default:
                        break;
                }
            }
        }
        return ["message" => "listo"];
    }

    function checkOnePoliza($poliza)
    {
        $hoy = Carbon::now();

        if ($poliza->estado_poliza_id != 1 && $poliza->estado_poliza_id != 5) {
            switch ($poliza) {
                case $hoy->isAfter($poliza->vigencia_desde) && $hoy->isBefore($poliza->vigencia_hasta->subMonth()) && $poliza->renovada == 0:
                    $poliza->estado_poliza_id = 3;
                    $poliza->save();
                    //VIGENTE
                    break;
                case $hoy->isAfter($poliza->vigencia_hasta->subMonth()) && $hoy->isBefore($poliza->vigencia_hasta) && $poliza->renovada == 0:
                    $poliza->estado_poliza_id = 4;
                    $poliza->save();
                    //VIGENTE A RENOVAR
                    break;
                case $hoy->isAfter($poliza->vigencia_desde) && $hoy->isBefore($poliza->vigencia_hasta) && $poliza->renovada == 1:
                    $poliza->estado_poliza_id = 7;
                    $poliza->save();
                    //VIGENTE RENOVADA
                    break;
                case $hoy->isAfter($poliza->vigencia_hasta) && $poliza->renovada == 1:
                    $poliza->estado_poliza_id = 5;
                    $poliza->save();
                    //CUMPLIDA RENOVADA
                    break;
                case $hoy->isAfter($poliza->vigencia_hasta) && $poliza->renovada == 0:
                    $poliza->estado_poliza_id = 6;
                    $poliza->save();
                    //CUMPLIDA
                    break;
                case $hoy->isBefore($poliza->vigencia_desde):
                    $poliza->estado_poliza_id = 2;
                    $poliza->save();
                    // PENDIENTE
                    break;
                default:
                    break;
            }
            return $poliza;
        }
    }
}
