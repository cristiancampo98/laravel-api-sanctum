<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\V1\ResponseController;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends ResponseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }
        $admin = true;
        if (count(User::all())) {
            $admin = false;
        }

        $user = (new User)->fill($request->all());
        $user->admin = $admin;
        $user->password = Hash::make($request->get('password'));
        $user->save();

        return $this->sendResponse($user, 'Registered successfully');

    }
}
