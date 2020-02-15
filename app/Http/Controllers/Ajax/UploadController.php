<?php

namespace App\Http\Controllers\Ajax;

use Aws\S3\S3Client;
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
            $datePath = date("Y") . "/" . date("m") . "/" . date('d') . "/";
            $randomized = rand();
            $documents = str_replace(' ', '', $fileNameOnly) . '-' . $randomized . '' . time() . '.' . $extension;
            $storePath = env('UPLOAD_IMAGE_PRODUCT') . $datePath;

            if (env('S3_KEY') && env('S3_SECRET')) {
                // Upload to S3
                $bucket = env('S3_BUCKET');

                $s3 = new S3Client([
                    'credentials' => [
                        'key'    => env('S3_KEY'),
                        'secret' => env('S3_SECRET')
                    ],
                    'version' => 'latest',
                    'region'  => 'ap-southeast-1'
                ]);

                try {
                    $s3->putObject([
                        'Bucket' => $bucket,
                        // To
                        'Key'    => 'images/' . $datePath . $documents,
                        // From
                        'Body'   => fopen($file, 'r'),
                        'ACL'    => 'public-read',
                    ]);
                } catch (Aws\Exception\S3Exception $e) {
                    return false;
                }
            } else {
                $file->storeAs($storePath, $documents);
            }


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
