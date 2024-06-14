<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Envio;
use Carbon\Carbon;
use App\Models\Facturacion;

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
     * Update the user's shipping address.
     */
    public function updateShipping(Request $request){
        // Valida los datos de entrada
        $validatedData = $request->validate([
            'company' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Definir los criterios de búsqueda
        $criterio = [
            'user_id' => auth()->id(),
        ];

       // Definir los datos a actualizar o crear
       $updateData = [
            'user_id' => auth()->id(),
            'pais' => $validatedData['country'],
            'direccion_1' => $validatedData['address'],
            'direccion_2' => $validatedData['address_2'] ?? null,
            'ciudad' => $validatedData['city'],
            'codigo_postal' => $validatedData['postal_code'],
            'empresa' => $validatedData['company'] ?? null,
            'telefono' => $validatedData['phone'],
            'updated_at' => Carbon::now(),
        ];

        // Crear un nuevo registro en la tabla envios o actualizar uno existente
        Envio::updateOrCreate($criterio, $updateData);

        // Redirecciona de nuevo a la página de edición del perfil con un mensaje de éxito
        return Redirect::route('profile.edit')->with('status', 'shipping-updated');
    }

    /**
     * Update the user's billing address.
     */
    public function updateBilling(Request $request){
        // Valida los datos de entrada
        $validatedData = $request->validate([
            'company' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'nif_dni' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]{8}[a-zA-Z]$/',
                    'max:9', // Para asegurarse de que el DNI tiene exactamente 9 caracteres
                ],
        ]);

        // Definir los criterios de búsqueda
        $criterio = [
            'user_id' => auth()->id(),
        ];

       // Definir los datos a actualizar o crear
       $updateData = [
            'user_id' => auth()->id(),
            'pais' => $validatedData['country'],
            'direccion_1' => $validatedData['address'],
            'direccion_2' => $validatedData['address_2'] ?? null,
            'ciudad' => $validatedData['city'],
            'codigo_postal' => $validatedData['postal_code'],
            'empresa' => $validatedData['company'] ?? null,
            'telefono' => $validatedData['phone'],
            'updated_at' => Carbon::now(),
        ];

        // Crear un nuevo registro en la tabla facturaciones o actualizar uno existente
        Facturacion::updateOrCreate($criterio, $updateData);

        // Redirecciona de nuevo a la página de edición del perfil con un mensaje de éxito
        return Redirect::route('profile.edit')->with('status', 'billing-updated');
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
