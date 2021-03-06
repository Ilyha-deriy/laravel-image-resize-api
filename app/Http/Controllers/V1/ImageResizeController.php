<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageResizeRequest;
use App\Http\Resources\V1\ImageResizeResource;
use App\Models\Album;
use App\Models\ImageResize;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
Use Image;
class ImageResizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return ImageResizeResource::collection(ImageResize::where('user_id', $request->user()->id)->paginate());
    }


    public function byAlbum(Request $request, Album $album) {
        if ($request->user()->id != $album->user_id) {
            return abort(403, 'Unauthorized');
        }
        $where = [
            'album_id' => $album->id,
        ];
        return ImageResizeResource::collection(ImageResize::where($where)->paginate());

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreImageResizeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function resize(ImageResizeRequest $request)
    {
        $all = $request->all();

        $image = $all['image'];

        unset($all['image']);

        $data = [
            'type' => ImageResize::TYPE_RESIZE,
            'data' => json_encode($all),
            'user_id' => $request->user()->id,
        ];

        if (isset($all['album_id'])) {
            $album = Album::find($all['album_id']);
            if ($request->user()->id != $album->user_id) {
                return abort(403, 'Unauthorized');
            }
            $data['album_id'] = $all['album_id'];
        }

        $dir = 'images/'.Str::random().'/';
        $absolutePath = public_path($dir);
        File::makeDirectory($absolutePath);

        if ($image instanceof UploadedFile) {
            $data['name'] = $image->getClientOriginalName();
            $filename = pathinfo($data['name'], PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $originalPath = $absolutePath . $data['name'];

            $image->move($absolutePath, $data['name']);
        }else{
            $data['name'] = pathinfo($image, PATHINFO_BASENAME);
            $filename = pathinfo($image, PATHINFO_FILENAME);
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $originalPath = $absolutePath.$data['name'];


            copy($image, $originalPath);
        }

        $data['path'] = $dir . $data['name'];


        $w = $all['w'];
        $h = $all['h'] ?? false;

        list($width, $height, $image) = $this->getImageWidthAndHeight($w, $h, $originalPath);

        $resizedFilename = $filename . '-resized.' . $extension;
        $image->resize($width, $height)->save($absolutePath . $resizedFilename);

        $data['output_path'] = $dir . $resizedFilename;

        $imageResize = ImageResize::create($data);

        return new ImageResizeResource($imageResize);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageResize  $imageResize
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ImageResize $image)
    {
        if ($request->user()->id != $image->user_id) {
            return abort(403, 'Unauthorized');
        }
        return new ImageResizeResource($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageResize  $imageResize
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ImageResize $image)
    {
        if ($request->user()->id != $image->user_id) {
            return abort(403, 'Unauthorized');
        }
        $image->delete();
        return response('', 204);
    }

    protected function getImageWidthAndHeight($w, $h, string $originalPath) {
        //1000 - 50% => 500px
        $image = Image::make($originalPath);
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        if (str_ends_with($w, '%')) {
            $ratioW = (float)str_replace('%', '', $w);
            $ratioH = $h ? (float)str_replace('%', '', $h) : $ratioW;

            $newWidth = $originalWidth * $ratioW / 100;
            $newHeight = $originalHeight * $ratioH / 100;
        } else {
            $newWidth = (float)$w;

            $newHeight = $h ? (float)$h : $originalHeight * $newWidth/$originalWidth;
        }

        return [$newWidth, $newHeight, $image];

    }
}
