<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function showDetails(Request $request)
    {
        try{
            $user = $request->user();
            return $this->returnJSON(new UserResource($user->loadMissing('serviceProviderProfile')), 'User Data retrieved!');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
