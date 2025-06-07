<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function saveImage($image = null, $path = 'public')
    {
        if (!$image) {
            return null;
        }
        $fileName = time() . '.png';
        Storage::disk($path)->put($fileName, base64_decode($image));
        return  URL::to('/') . '/storage/' . $path . '/' . $fileName;
    }


    public function deleteImage($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
            return true;
        }
        return false;
    }
    public function getImageUrl($imagePath, $defaultImage = 'default.png')
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        return asset('storage/profiles/' . $defaultImage);
    }
    public function getImagePath($imagePath, $defaultImage = 'default.png')
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return $imagePath;
        }
        return 'storage/profiles/' . $defaultImage;
    }
    public function getImageName($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return basename($imagePath);
        }
        return null;
    }
    public function getImageExtension($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return pathinfo($imagePath, PATHINFO_EXTENSION);
        }
        return null;
    }
    public function getImageSize($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return filesize(public_path($imagePath));
        }
        return null;
    }
    public function getImageDimensions($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            list($width, $height) = getimagesize(public_path($imagePath));
            return ['width' => $width, 'height' => $height];
        }
        return null;
    }
    public function getImageType($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return mime_content_type(public_path($imagePath));
        }
        return null;
    }
    public function getImageBase64($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            $imageData = base64_encode(file_get_contents(public_path($imagePath)));
            return 'data:' . $this->getImageType($imagePath) . ';base64,' . $imageData;
        }
        return null;
    }
    public function getImageUrlWithDefault($imagePath, $defaultImage = 'default.png')
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }
        return asset('storage/profiles/' . $defaultImage);
    }
}
