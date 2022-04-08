<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cobertura;
use App\Models\CodigoOrganizador;
use App\Models\Compania;
use App\Models\Poliza;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


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
        return  Compania::with('polizas_vigentes.riesgo_automotor')->orderBy('nombre')->get();
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
