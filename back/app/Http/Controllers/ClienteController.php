<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cliente::with('productores')->orderBy('apellido')->get();
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
            $cliente = Cliente::create($request->all());
            $cliente->load(['productores']);
            $cliente::with(['productores']);
            return response($cliente, 201);
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
        return Cliente::findOrFail($id);
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
            $cliente = Cliente::find($id);
            $cliente->fill($request->all())->save();
            return response($cliente, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function searchDNI(Request $req)
    {
        $dni = $req->input('dni');
        return ['usado' => Cliente::where('nro_dni', $dni)->exists()];
    }

    public function searchCuit(Request $req)
    {
        $cuit = $req->input('cuit');
        return ['usado' => Cliente::where('cuit', $cuit)->exists()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Cliente::find($id);

        $cliente->delete();

        return ['message' => 'Eliminado'];
    }
}
