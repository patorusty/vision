<?php

namespace App\Http\Controllers;

use App\ImagenRA;
use Illuminate\Http\Request;

use App\Http\Resources\ImagenRA as ImagenRAResource;


class ImagenRAController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function uploadFile(Request $request) {

            $riesgoId = $request->get('riesgoId');
            if($request->file('file'));
            {
                $image = $request->file('file');
                $size = $image->getClientSize();
                $name = time().$image->getClientOriginalName();
                $path = '/images/'.$name;
                $mime = $image->getClientMimeType();
                $image->move(public_path().'/images/', $name); 
            }
            
            $image= new ImagenRA();
            $image->filename = $name;
            $image->path = $path;
            $image->size = $size;
            $image->size = $size;
            $image->mime = $mime;
            $image->save();
            $image->riesgo_automotor()->sync($riesgoId);
     
            return response()->json(['success' => 'You have successfully uploaded an image'], 200);
          
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ImagenRA  $imagenRA
     * @return \Illuminate\Http\Response
     */
    public function show(ImagenRA $imagenRA)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ImagenRA  $imagenRA
     * @return \Illuminate\Http\Response
     */
    public function edit(ImagenRA $imagenRA)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ImagenRA  $imagenRA
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImagenRA $imagenRA)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ImagenRA  $imagenRA
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imagen = ImagenRA::find($id);

        $imagen->riesgo_automotor()->detach();

        $imagen->delete();

        return ['message'=>'Eliminado'];
    }
}
