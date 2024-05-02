<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route()->named('admin.dashboard')) {
            // Si la solicitud es para la página de administrador, continuamos sin hacer nada.
            return $next($request);
        }

        // Verificar si la solicitud es para la página de modelo
        if ($request->route()->named('admin.modelo')) {
            // Si la solicitud es para admin.modelo, verificar la existencia de la variable 'tipo'
            if (!isset($request->tipo)) {
                return redirect()->route('admin.dashboard')->with('error', 'Elige el tipo de móvil');
            }
        }

        // Verificar si la solicitud es para la página de stock
        if ($request->route()->named('admin.stock')) {
            // Si la solicitud es para admin.modelo, verificar la existencia de la variable 'tipo'
            if (!isset($request->tipo)) {
                return redirect()->route('admin.dashboard')->with('error', 'Elige el tipo de móvil');
            }

            if (!isset($request->modelo)) {
                return redirect()->route('admin.modelo', ['tipo' => $request->tipo])->with('error', 'Elige el modelo de móvil');
            }
        }
       
        // Si el usuario no es admin, se redirige a la pagina de productos
        if ($request->user() && $request->user()->usertype == '1') {
            return $next($request);
        }
        return redirect()->route('products');
    }
}
