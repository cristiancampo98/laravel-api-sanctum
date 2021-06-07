<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\V1\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends ResponseController
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {

            return $this->sendError('Error validation',$validator->errors(), 422);
        }

        if (Auth::attempt($validator->getData())) {

            if (Auth::user()->admin) {
                $token = Auth::user()->createToken('auth_token')->plainTextToken;    
            } else {
                $token = Auth::user()->createToken('auth_token', [
                    'profile',
                    'change-password'
                ])->plainTextToken;
            }

            $data = [
                'user' => Auth::user()->only(['email']),
                'Alerta' => Auth::user()->user_alert,
                'token' => $token
            ];
            return $this->sendResponse($data, 'User signed in');
        }

        return $this->sendError('Error validation', 'The password does not match' , 422);   
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->tokens()->delete();
            return $this->sendResponse('All tokens delete', 'User log out');
        }
        return $this->sendError('Error validation', 'The password does not match' , 422);   
    }
}
