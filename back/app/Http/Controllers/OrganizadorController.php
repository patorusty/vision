<?php

namespace App\Http\Controllers;

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
        return Organizador::all();

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
        try {
            $organizador = Organizador::create([
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'cuit' => $request->input('cuit'),
                'matricula' => $request->input('matricula'),
                'email' => $request->input('email'),
                'telefono_1' => $request->input('telefono_1'),
                'telefono_2' => $request->input('telefono_2'),
                'activo' => $request->input('activo'),
            ]);
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
        try {
            $organizador = Organizador::find($id);
            $organizador->update([
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'cuit' => $request->input('cuit'),
                'matricula' => $request->input('matricula'),
                'email' => $request->input('email'),
                'telefono_1' => $request->input('telefono_1'),
                'telefono_2' => $request->input('telefono_2'),
                'activo' => $request->input('activo'),
            ]);
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
            $organizador = Organizador::find($id);
            $organizador->delete();
            return response('', 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
