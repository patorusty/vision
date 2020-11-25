<?php

namespace App\Http\Controllers;

use App\Clientes;
use App\Localidades;
use App\Productores;
use Illuminate\Http\Request;
use App\Http\Resources\Cliente as ClientesResource;


class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes = Clientes::with('productores')->get();
        // $localidades = Localidades::all();

        return ClientesResource::collection($clientes);
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
        $this->validate($request, [

        ]);


        $cliente = Clientes::create([
            'tipo_persona' => $request->input('tipo_persona'),
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'razon_social' => $request->input('razon_social'),
            'tipo_doc' => $request->input('tipo_doc'),
            'nro_dni' => $request->input('nro_dni'),
            'sexo' => $request->input('sexo'),
            'nacimiento' => $request->input('nacimiento'),
            'condicion_fiscal' => $request->input('condicion_fiscal'),
            'cuit' => $request->input('cuit'),
            'registro' => $request->input('registro'),
            'vencimiento_registro' => $request->input('vencimiento_registro'),
            'email' => $request->input('email'),
            'email_alt' => $request->input('email_alt'),
            'direccion' => $request->input('direccion'),
            'direccion_nro' => $request->input('direccion_nro'),
            'direccion_piso' => $request->input('direccion_piso'),
            'direccion_depto' => $request->input('direccion_depto'),
            'localidad_id' => $request->input('localidad_id'),
            'barrio_cerrado' => $request->input('barrio_cerrado'),
            'lote' => $request->input('lote'),
            'celular' => $request->input('celular'),
            'telefono_1' => $request->input('telefono_1'),
            'telefono_2' => $request->input('telefono_2'),
            'img_registro' => "ejemplo",
            'observaciones_1' => $request->input('observaciones_1'),
            'observaciones_2' => $request->input('observaciones_2'),
            'productor_id' => $request->input('productor_id'),
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
        $cliente = Clientes::findOrFail($id);

        return new ClientesResource($cliente);
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
        $cliente = Clientes::find($id);

        $cliente->update([
            'tipo_persona' => $request->input('tipo_persona'),
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'razon_social' => $request->input('razon_social'),
            'tipo_doc' => $request->input('tipo_doc'),
            'nro_dni' => $request->input('nro_dni'),
            'sexo' => $request->input('sexo'),
            'nacimiento' => $request->input('nacimiento'),
            'condicion_fiscal' => $request->input('condicion_fiscal'),
            'cuit' => $request->input('cuit'),
            'registro' => $request->input('registro'),
            'vencimiento_registro' => $request->input('vencimiento_registro'),
            'email' => $request->input('email'),
            'email_alt' => $request->input('email_alt'),
            'direccion' => $request->input('direccion'),
            'direccion_nro' => $request->input('direccion_nro'),
            'direccion_piso' => $request->input('direccion_piso'),
            'direccion_depto' => $request->input('direccion_depto'),
            'localidad_id' => $request->input('localidad_id'),
            'barrio_cerrado' => $request->input('barrio_cerrado'),
            'lote' => $request->input('lote'),
            'celular' => $request->input('celular'),
            'telefono_1' => $request->input('telefono_1'),
            'telefono_2' => $request->input('telefono_2'),
            'img_registro' => "ejemplo",
            'observaciones_1' => $request->input('observaciones_1'),
            'observaciones_2' => $request->input('observaciones_2'),
            'productor_id' => $request->input('productor_id'),
        ]);
    }

    public function searchDNI()
    {
        if ($search = \Request::get('q')) {
            $dni = Clientes::where('nro_dni', $search)->get();
        } 
        return ClientesResource::collection($dni);
    }

    public function searchCuit()
    {
        if ($search = \Request::get('q')) {
            $cuit = Clientes::where('cuit', $search)->get();
        } 
        return ClientesResource::collection($cuit);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = Clientes::find($id);

        $cliente->delete();

        return ['message'=>'Eliminado'];
    }
}
