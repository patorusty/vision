<?php

namespace App\Http\Controllers;

use App\NotaSiniestroAut;
use Illuminate\Http\Request;
use App\Http\Resources\NotaSiniestroAut as NotaSiniestroAutsResource;


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
    public function indexFiltrado($siniestro_automotor_id)
    {
        $nota = NotaSiniestroAut::where('siniestro_automotor_id', $siniestro_automotor_id)->orderBy('id', 'DESC')->get();

        return NotaSiniestroAutsResource::collection($nota);
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
        // $this->validate($request, [
     
        // ]);

        $nota = NotaSiniestroAut::create([
            'siniestro_automotor_id' => $request->input('siniestro_automotor_id'),
            'user_id' => $request->input('user_id'),
            'nota' => $request->input('nota'),
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
        $nota = NotaSiniestroAut::findOrFail($id);

        return new NotaSiniestroAutsResource($nota);
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
        $nota = NotaSiniestroAut::find($id);
        $nota->update([
            'siniestro_automotor_id' => $request->input('siniestro_automotor_id'),
            'user_id' => $request->input('user_id'),
            'nota' => $request->input('nota'),            
        ]);
    }

   

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $nota = NotaSiniestroAut::find($id);
        
        $nota->delete();

        return ['message'=> 'Eliminado'];
    }
}
