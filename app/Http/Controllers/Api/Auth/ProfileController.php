<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\V1\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;

class ProfileController extends ResponseController
{
    public function profile()
    {
        return $this->sendResponse(Auth::user()->email,'Responded successfully');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }
        if (Hash::check($request->current_password, Auth::user()->password) && $request->filled('current_password')) {
            Auth::user()->password = Hash::make($request->password);
            Auth::user()->save();
            return $this->sendResponse('Password changed', []);
        } else {
            return $this->sendError('Password doesnt match', []);   
        }

        
    }
}
