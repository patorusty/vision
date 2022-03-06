<?php

namespace App\Http\Controllers;

use App\Models\NotaSiniestroAut;
use Illuminate\Http\Request;


class NotaSiniestroAutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            $nota = NotaSiniestroAut::create($request->all());
            return response($nota, 201);
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
        return NotaSiniestroAut::findOrFail($id);
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
            $nota = NotaSiniestroAut::findOrFail($id);
            $nota->fill($request->all())->save();
            return response($nota, 200);
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
            $nota = NotaSiniestroAut::find($id);
            $nota->delete();
            return response('', 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
