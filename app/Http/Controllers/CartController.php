<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BackMarketApi; // Asegúrate de importar la clase BackMarketApi
use Illuminate\Support\Facades\DB;

class CartController extends Controller{
    protected $backMarketApi;

     // Constructor para inyectar la instancia de BackMarketApi
     public function __construct(BackMarketApi $backMarketApi){
        $this->backMarketApi = $backMarketApi;
    }

    public function index(){

        // Método para mostrar el contenido del carrito
        $cart = session()->get('cart', []);
        return view('carrito.cart', compact('cart'));

        // Pasar los datos de las sesiones activas a la vista
        //return view('carrito.cart', ['sesionesActivas' => $sesionesActivas]);
    }

    public function add(Request $request){
        try {
             // Obtener el ID del producto desde la solicitud
            $productId = $request->id;

            // Verificar si hay una cookie de carrito
            $cookieCart = json_decode($request->cookie('cart'), true) ?: [];

            // Inicializar la variable productExists
            $productExists = false;

            // Verificar si el producto ya está en el carrito
            foreach ($cookieCart as $item) {
                if ($item['id'] === $productId) {
                    // Si el producto ya está en el carrito, aumentar la cantidad y actualizar el carrito en la sesión
                    $item['cantidad'] += 1;
                    $productExists = true;
                    break;
                }
            }
            // Si el producto no está en el carrito, obtener los datos del producto utilizando la instancia de BackMarketApi
            if (!$productExists) {
                $producto = $this->backMarketApi->apiGet('listings/detail?sku='.$request->id);
    
                // Añadir el producto al carrito
                $cookieCart[] = [
                    'id' => $producto['id'],
                    'nombre' => $producto['title'],
                    'precio' => $producto['price'],
                    'cantidad' => 1,
                ];
            }

            // Actualizar el carrito en la sesión
            session()->put('cart', $cookieCart);

            // Crear una nueva cookie con el carrito actualizado
            return redirect()->route('cart.index')->withCookie(cookie()->forever('cart', json_encode($cookieCart)));
        } catch (\Exception $e) {
            // Si hay algún error, manejarlo apropiadamente
            abort(500, 'Error al agregar el producto al carrito: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id){
        // Método para actualizar la cantidad de un elemento en el carrito
        $quantity = $request->input('quantity');

        $cart = session()->get('cart', []);
        if (array_key_exists($id, $cart)) {
            // Actualizar la cantidad del producto en el carrito
            $cart[$id]['cantidad'] = $quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function remove($id){
        // Método para eliminar un elemento del carrito
        $cart = session()->get('cart', []);
        if (array_key_exists($id, $cart)) {
            // Eliminar el producto del carrito
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function clear(){
         // Método para vaciar todo el carrito
         session()->forget('cart');
         return redirect()->route('cart.index');
    }
}
