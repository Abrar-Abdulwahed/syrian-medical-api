<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\FileTrait;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Profile\PictureStoreRequest;
use App\Http\Requests\Profile\LocationStoreRequest;

class BaseProfileController extends Controller
{
    use FileTrait;
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function showDetails(Request $request)
    {
        try {
            $user = $request->user();
            return $this->returnJSON(new UserResource($user->loadMissing(['patientProfile', 'serviceProviderProfile'])), __('message.user_retrieved'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function updatePicture(PictureStoreRequest $request)
    {
        try {
            $user = $request->user();
            $fileName = $this->uploadFile($request->file('picture'), $user->attachment_path, $user->picture);
            $user->update(['picture' => $fileName]);
            return $this->returnSuccess(__('message.picture_saved'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function updateLocation(LocationStoreRequest $request)
    {
        try {
            $request->user()->profile()->update([
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return $this->returnSuccess(__('message.location_saved'));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
