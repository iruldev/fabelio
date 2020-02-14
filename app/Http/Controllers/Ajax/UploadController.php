<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Import Models
use App\Models\Product_image;

class UploadController extends Controller
{
    public function uploadImageProduct(Request $request)
    {
        $response = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $completeFileName = $file->getClientOriginalName();
            $fileNameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $randomized = rand();
            $documents = str_replace(' ', '', $fileNameOnly) . '-' . $randomized . '' . time() . '.' . $extension;
            $datePath = date("Y") . "/" . date("m") . "/" . date('d') . "/";
            $storePath = env('UPLOAD_IMAGE_PRODUCT') . $datePath;
            $file->storeAs($storePath, $documents);
            $response['image_path'] = $datePath . $documents;
            $response['image_id'] = Product_image::create(['image_path' => $datePath . $documents])->id;
        }

        return $response;
    }

    public function getImageProduct(Request $request)
    {
        $imageIds = explode(',', $request->imageIds);
        $getImage = Product_image::find($imageIds);
        return $getImage;
    }
}
