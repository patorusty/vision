<?php

namespace App\Http\Controllers;

use App\Models\CodigoProductor;
use App\Models\Productor;
use Illuminate\Http\Request;

class ProductorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Productor::orderBy('apellido', 'ASC')->get();
    }

    public function productoresActivos()
    {
        return Productor::where("activo", 1)->get();
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
            $productor = Productor::create($request->all());
            return response($productor, 201);
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
        return Productor::findOrFail($id);
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
            $productor = Productor::findOrFail($id);
            $productor->fill($request->all())->save();
            return response($productor, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function searchCuit(Request $req)
    {
        $cuit = $req->input('cuit');
        return ['usado' => Productor::where('cuit', $cuit)->exists()];
    }

    public function searchMatricula(Request $req)
    {
        $matricula = $req->input('matricula');
        return ['usado' => Productor::where('matricula', $matricula)->exists()];
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
            $productor = Productor::findOrFail($id);
            if (CodigoProductor::where("productor_id", $productor->id)->exists()) {
                return response('', 202);
            } else {
                $productor->delete();
                return response('', 200);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
