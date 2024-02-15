<?php

namespace App\Http\Controllers;
use Imagick;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\GalleryImage;

class AdminController extends Controller
{
    function getImage(Request $request, $id) {
        $image = GalleryImage::findOrFail($id);
        $image->url = Storage::url($image->file);
        $image->path = Storage::path($image->file);

        try {
            $i = new Imagick($image->path);
            $image->compression = $i->getImageCompression();
            $image->compressionQuality = $i->getImageCompressionQuality();
            $image->channels = $i->getImageChannelStatistics();
            $image->height = $i->getImageHeight();
            $image->width = $i->getImageWidth();
            $image->size = $i->getImageSize();
        }
        catch(\Exception $ex) {
        }

        return response()->json(['status' => 'success', 'data' => $image], 200);
    }

    //
    function modifyImage(Request $request) {
        $v = Validator::make($request->all(), [
            'path' => 'required',
            'effect' => 'required'
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $path = $request->input('path');
        if(Storage::exists($path)) {
            $path = Storage::path($path);
        }
        try {
            $i = new Imagick($path);

            switch($request->input('effect')) {
                case 'charcoal':
                    $i->charcoalImage(1, 15);
                    break;
                case 'wave':
                    $i->waveImage(10, 5);
                    break;
                case 'swirl':
                    $i->swirlImage(111);
                    break;
                case 'sepia':
                    $i->sepiaToneImage(111);
                    break;
            }
            
            return "data:image/jpeg;base64," . base64_encode($i->getImageBlob());
        }
        catch(\Exception $ex) {
            return response("bad image path", 422);
        }
        
    }

    function getUsers(Request $request) {
        return User::all();
    }
}
