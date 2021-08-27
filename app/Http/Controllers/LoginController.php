<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->active) {
                //if ($user->role=='administer' or $user->role=='super') {
                    if (Auth::attempt($credentials)) {
                        return response()->json(['success' => true, 'message' => 'Iniciando la sesión, redireccionando...', 'url' => url('dashboard')], 200);
                    }else {
                        return response()->json(['error' => true,  'message' => 'Accceso no autorizado, Error en las credenciales.'], 401);
                    }
               /*  }else{
                    return response()->json(['error' => true,  'message' => 'Accceso no autorizado, verifique sus credenciales.'], 401);
                } */
            }else {
                return response()->json(['error' => true,  'message' => 'Accceso no autorizado, Usuario Inactivo.'], 401);
            }
        }else {
            return response()->json(['error' => true,  'message' => 'Accceso no autorizado, Credenciales no existen.'], 401);
        }
    }

    /**
     * Handle an destroy authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['success' => true, 'message' => 'Sesión cerrada correctamente, redireccionando...', 'url' => url('login')], 200);
    }
}
