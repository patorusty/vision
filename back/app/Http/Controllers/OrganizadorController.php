<?php

namespace App\Http\Controllers;

use App\Models\CodigoOrganizador;
use App\Models\Organizador;
use Illuminate\Http\Request;

class OrganizadorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Organizador::orderBy('apellido', 'ASC')->get();
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
            $organizador = Organizador::create($request->all());
            return response($organizador, 201);
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
        return Organizador::findOrFail($id);
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
            $organizador = Organizador::findOrFail($id);
            $organizador->fill($request->all())->save();
            return response($organizador, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function searchCuit(Request $req)
    {
        $cuit = $req->input('cuit');
        return ['usado' => Organizador::where('cuit', $cuit)->exists()];
    }

    public function searchMatricula(Request $req)
    {
        $matricula = $req->input('matricula');
        return ['usado' => Organizador::where('matricula', $matricula)->exists()];
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
            $organizador = Organizador::findOrFail($id);
            if (CodigoOrganizador::where("organizador_id", $organizador->id)->exists()) {
                return response('', 202);
            } else {
                $organizador->delete();
                return response('', 200);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
