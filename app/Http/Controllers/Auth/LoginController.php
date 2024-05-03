<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Auth;
use App\Models\User;


class LoginController extends Controller
{
    //
    public function redirect(Request $request){
        $userType = Auth::user()->usertype;

        if ($userType == '1') {
            //redirige a la carppeta admin y dashboard
            return Redirect::route('admin.dashboard');
        } else {
            //redirige a dashboard
            return Redirect::route('dashboard');

            // Transferir los datos del carrito de la cookie a la sesión del usuario si existe una cookie de carrito
            $cookieCart = json_decode($request->cookie('cart'), true) ?: [];
            session()->put('cart', $cookieCart);

            // Eliminar la cookie del carrito ahora que los datos han sido transferidos a la sesión del usuario
            return Redirect::route('dashboard')->withCookie(cookie()->forget('cart'));
            }

    }

}
