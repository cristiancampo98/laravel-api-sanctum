<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $data = [];
        $flag = false;

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = User::where('email',$request->email)->first();
        }

        if (empty($user)) {
            return response()->json([
                'message' => 'NOT FOUND',
                'data' => $user
            ],200);
        }
        if ($user->admin) {
            return $next($request);
        }
        
        if (!$user->user_status) {
            $flag = true;
            $data['messages']['Aviso suspensión'] = 'Estas suspendido. Comunicarse con el administrador';
        }
        if ($user->user_alert == 'rojo') {
            $flag = true;
            $data['messages']['Alerta Roja'] = 'Usted tiene sintomas no puede entrar a la sede, por favor visite su medico';
        }

        if ($flag) {
            return response()->json([
                'data' => $data
            ],200);
        }

        return $next($request);
    }
}
