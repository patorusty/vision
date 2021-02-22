<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Http\Resources\Usuario as UsuariosResource;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Usuario::with(['tipo_usuario'])->get();

        // return UsuariosResource::collection($usuarios);
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
            $usuario = Usuario::create($request->all());
            return $usuario;
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
        return Usuario::findOrFail($id);

        // return new UsuariosResource($usuario);
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
            $usuario = Usuario::find($id);
            $usuario->update([
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'compania' => $request->input('compania'),
                'tipo_usuario_id' => $request->input('tipo_usuario_id'),
                'activo' => $request->input('activo'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                // 'avatar' => $request->input('avatar'),
            ]);
            return $usuario;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
            $usuario = Usuario::find($id);
            $usuario->delete();
            return $usuario;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function searchMail(Request $req)
    {
        $email = $req->input('email');
        return ['usado' => Usuario::where('email', $email)->exists()];
    }
}
