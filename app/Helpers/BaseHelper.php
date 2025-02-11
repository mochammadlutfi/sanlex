<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use DB;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\AutoEncoder;
use Illuminate\Support\Str;

class BaseHelper
{

    public static function uploadFile($file, $dest, $image = false)
    {
        // Generate unique filename
        $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), '-');
        $fileName .= '_' . uniqid();
        $fileName .= '.' . $file->getClientOriginalExtension();
        
        // Create destination path if not exists
        if (!Storage::disk('public')->exists($dest)) {
            Storage::disk('public')->makeDirectory($dest);
        }

        if ($image) {
            // Handle image file
            $imgFile = Image::read($file->getRealPath())
                ->encode(new AutoEncoder(quality: 90));
            $imgFileData = (string) $imgFile;
            
            // Save the file
            Storage::disk('public')->put($dest . '/' . $fileName, $imgFileData);
        } else {
            // Handle regular file
            Storage::disk('public')->putFileAs($dest, $file, $fileName);
        }

        // Return the relative path only
        return $dest . '/' . $fileName;
    }
}