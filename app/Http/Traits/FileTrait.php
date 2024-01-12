<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    protected function uploadFile($newFile, $path, $oldFile = null, $fileName = null)
    {
        if ($newFile) {
            $this->removeFile($path . '/' . $oldFile);
            if (empty($fileName)) {
                $fileName = time() . '_' . $newFile->hashName();
            } else {
                $fileName = "{$fileName}.{$newFile->extension()}";
            }

            Storage::disk('public')->putFileAs($path, $newFile, $fileName);

            return $fileName;
        }
        return $oldFile;
    }


    protected function removeFile($path)
    {
        if (!empty($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function removeDirectory($path){
        if (!empty($path) && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->deleteDirectory($path);
        }
    }
}