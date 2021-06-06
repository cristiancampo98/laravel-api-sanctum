<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\V1\ResponseController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ValidateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Trash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->tokenCan('user:index')) {
            return $this->sendResponse(UserResource::collection(User::all()), 'Response success');
        }
        return $this->sendError('Error', 'Inauthorized',501);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        if (Auth::user()->tokenCan('user:store')) {

            $user = (new User)->fill($request->all());
            $user->password = Hash::make($user->password);
            $user->save();

            return $this->sendResponse($user, 'Response success');
        }
        return $this->sendError('Error', 'Inauthorized',501);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (Auth::user()->tokenCan('user:show')) {
            return $this->sendResponse(new UserResource($user), 'Response success');
        }
        return $this->sendError('Error', 'Inauthorized',501);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (Auth::user()->tokenCan('user:update')) {
            $user->update([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return $this->sendResponse(new UserResource($user), 'Response success');
        }
        return $this->sendError('Error', 'Inauthorized',501);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (Auth::user()->tokenCan('user:destroy')) {
            Trash::create([
                'email' => $user->email,
                'consecutive' => $user->id,
            ]);

            $user->delete();
            return $this->sendResponse('User destroy', 'Response success');
        }
        return $this->sendError('Error', 'Inauthorized',501);
    }

    /**
     * Suspend a specific user in storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function suspend(User $user)
    {
        if (Auth::user()->tokenCan('user:suspend')) {
            $user->user_status = 0;
            $user->save();
            return $this->sendResponse('User suspended', 'Response success');
        }
        return $this->sendError('Error', 'Inauthorized',501);
    }

    public function validateCovid(ValidateUserRequest $request)
    {
        $type_alert = 'verde';
        if ($request->get('temperatura') > 28 || $request->filled('sintomas')) {
            $type_alert = 'rojo';
            Auth::user()->user_alert = $type_alert;
            Auth::user()->save();
            return $this->sendResponse($type_alert, 'Response success');
        } 

        if ($request->get('contacto')) {
            $type_alert = 'amarillo';
            Auth::user()->user_alert = $type_alert;
            Auth::user()->save();
            return $this->sendResponse($type_alert, 'Response success');
        } 

        return $this->sendResponse($type_alert, 'Response success');

    }


}
