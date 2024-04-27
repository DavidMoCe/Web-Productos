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
    public function redirect(){
        $userType = Auth::user()->usertype;

        if ($userType == '1') {
            //redirige a la carppeta admin y dashboard
            return Redirect::route('admin.dashboard');
        } else {
            //redirige a dashboard
            return Redirect::route('dashboard');
        }

    }

}
