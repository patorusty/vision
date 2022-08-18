<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cobertura;
use App\Models\CodigoOrganizador;
use App\Models\Compania;
use App\Models\Poliza;
use App\Models\TipoRiesgo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CompaniaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $companias = Companias::all();
        // $localidades = Localidades::all();

        return Compania::all();
    }

    public function companiasActivas()
    {
        return Compania::where("activo", 1)->get();
    }

    public function polizasVigentes()
    {
        $companias =  Compania::orderBy('nombre')->get();
        $companiasConRaCount = [];
        for ($i = 0; $i < count($companias); $i++) {
            $polizasPorCompania = DB::select('select COUNT(ra.id) as raCount
                                                FROM riesgo_automotors AS ra
                                                WHERE ra.poliza_id in
                                                (SELECT polizas.id
                                                from polizas
                                                WHERE (compania_id = ?)
                                                AND (tipo_riesgo_id = 1)
                                                AND (estado_poliza_id = 3
                                                OR estado_poliza_id = 4 
                                                OR estado_poliza_id = 7))', [$companias[$i]['id']]);
            $polizasVigentesRenovar = DB::select('select COUNT(id) as renovarCount
                                                FROM polizas
                                                WHERE (compania_id = ?)
                                                AND (tipo_riesgo_id = 1)
                                                AND (estado_poliza_id = 4)', [$companias[$i]['id']]);
            if ($polizasPorCompania[0]->{'raCount'} > 0) {
                $companiasConRaCount[$i] = [
                    'nombre' =>  $companias[$i]['nombre'],
                    'cantidad' => $polizasPorCompania[0]->{'raCount'},
                    'renovar' => $polizasVigentesRenovar[0]->{'renovarCount'},
                    'color' => $companias[$i]['color']
                ];
            }
        }
        return array_values($companiasConRaCount);
    }
    // return  Compania::with('polizas_vigentes.riesgo_automotor')->orderBy('nombre')->get();

    private function countPorRiesgoYCompania($tipo_id, $companias)
    {
        $count = [];
        for ($i = 0; $i < count($companias); $i++) {
            $cant = Poliza::where([['tipo_riesgo_id', $tipo_id], ['compania_id', $companias[$i]['id']]], function ($q) {
                $q->Where('estado_poliza_id', 3)
                    ->orWhere('estado_poliza_id', 4)
                    ->orWhere('estado_poliza_id', 7);
            })->count();
            if ($cant > 0) {
                $count[$i] = [
                    'nombre' => $companias[$i]['nombre'],
                    'cantidad' => $cant,
                ];
            }
        }
        return array_values($count);
    }


    public function polizasVigentesOR()
    {
        $tipos = TipoRiesgo::orderBy('nombre')->get();
        $companias =  Compania::orderBy('nombre')->get();
        $otros_riesgos = [];
        for ($i = 2; $i < count($tipos); $i++) {
            $companiaDelRiesgo = $this->countPorRiesgoYCompania($tipos[$i]['id'], $companias);
            if (count($companiaDelRiesgo) > 0) {
                $otros_riesgos[$i] = [
                    'nombre' => $tipos[$i]['nombre'],
                    'companias' => $companiaDelRiesgo,
                    'tipo' => $tipos[$i]['id']
                ];
            }
        }
        return array_values($otros_riesgos);
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

            $compania = Compania::create($request->all());
            return response($compania, 201);
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
    public function show($nombre)
    {
        $compania = Compania::with(["codigo_organizador.organizadores"])->where('nombre', $nombre)->get();
        return $compania[0];
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
            $compania = Compania::findOrFail($id);
            $compania->fill($request->all())->save();
            return response($compania, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function search(Request $req)
    {
        $cuit = $req->input('cuit');
        return ['usado' => Compania::where('cuit', $cuit)->exists()];
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
            $compania = Compania::findOrFail($id);
            if (CodigoOrganizador::where("compania_id", $compania->id)->exists()) {
                return response('', 202);
            } else if (Cobertura::where("compania_id", $compania->id)->exists()) {
                return response('', 203);
            } else if (Poliza::where("compania_id", $compania->id)->exists()) {
                return response('', 204);
            } else {
                $compania->delete();
                return response('', 200);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
