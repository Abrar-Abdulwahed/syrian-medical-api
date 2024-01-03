<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    protected function uploadFile($newFile, $path, $oldFile = null, $fileName = null)
    {
        if (!empty($newFile) && !is_null($newFile)) {
            $this->removeFile('public/' . $path . '/' . $oldFile);
            if (empty($fileName)) {
                $fileName = time() . '_' . $newFile->hashName();
            } else {
                $fileName = "{$fileName}.{$newFile->extension()}";
            }

            $newFile->storeAs("public/$path", $fileName);

            return $fileName;
        }
        return $oldFile;
    }


    protected function removeFile($path)
    {
        if (!empty($path) && Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
    }
}