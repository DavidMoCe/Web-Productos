<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Obtener pedidos de la base de datos
    public function obtenerPedidoBD(){
        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Verificar si el usuario está autenticado
        if($user){
            // Obtener el ID del usuario autenticado
            $userId = $user->id;
            
            // Obtener los pedidos asociados al usuario autenticado con los detalles de productos
            $pedidos = Pedido::where('usuario_id', $userId)->with('productos')->get();
            
            // Devolver los pedidos obtenidos
            return view('orders.order-listing', ['pedidos' => $pedidos]);
        } else {
            // Si el usuario no está autenticado, devolver un mensaje de error o manejarlo según sea necesario
            return "Usuario no autenticado";
        }
    }


}
