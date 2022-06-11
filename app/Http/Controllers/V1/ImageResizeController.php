<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageResizeRequest;
use App\Http\Requests\UpdateImageResizeRequest;
use App\Models\ImageResize;

class ImageResizeController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageResizeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageResizeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageResize  $imageResize
     * @return \Illuminate\Http\Response
     */
    public function show(ImageResize $imageResize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateImageResizeRequest  $request
     * @param  \App\Models\ImageResize  $imageResize
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageResizeRequest $request, ImageResize $imageResize)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageResize  $imageResize
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageResize $imageResize)
    {
        //
    }
}
