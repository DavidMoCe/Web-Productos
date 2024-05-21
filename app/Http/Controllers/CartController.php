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
        // Obtener el contenido del carrito desde la sesión
        $cookieCart = session()->get('cart', []);
        // Verificar si la cookie 'cart' está presente y cargar los datos
        if (empty($cookieCart)) {
            $cookieCart = json_decode(request()->cookie('cart'), true) ?: [];
        }
        // Pasar los datos del carrito a la vista
        return view('carrito.cart', compact('cookieCart'));
    }

    //añadir producto al carrito
    public function add(Request $request){
        try {
            // Verificar si hay una cookie de carrito
            $cookieCart = json_decode($request->cookie('cart'), true) ?: [];

            // Obtener los datos del formulario POST
            $tituloImagen = $request->input('titulo_imagen');
            $tituloSku = $request->input('titulo_sku');
            $cantidadProducto = $request->input('cantidad_producto');
            $tituloProducto = $request->input('titulo_producto');
            $precioProducto = $request->input('precio_producto');
            $estado = $request->input('estado');
            $capacidad = $request->input('capacidad');
            $color = $request->input('color');
            $stock_total = $request->input('stock_producto');

            // Inicializar la variable productExists
            $productExists = false;

            // Verificar si el producto ya está en el carrito
            foreach ($cookieCart as &$item) {
                if ($item['titulo_sku'] === $tituloSku) {
                    // Si el producto ya está en el carrito, aumentar la cantidad y actualizar el carrito en la sesión
                    $item['cantidad'] += $cantidadProducto;
                    $productExists = true;
                    break;
                }
            }

            // Si el producto no está en el carrito, agregarlo
            if (!$productExists) {
                // Añadir el producto al carrito
                $cookieCart[] = [
                    'titulo_imagen' => $tituloImagen,
                    'titulo_sku' => $tituloSku,
                    'cantidad' => $cantidadProducto,
                    'titulo_producto' => $tituloProducto,
                    'precio_producto' => $precioProducto,
                    'estado' => $estado,
                    'capacidad' => $capacidad,
                    'color' => $color,
                    'stock_total'=>$stock_total,
                ];
            }

            // Actualizar el carrito en la sesión
            session()->put('cart', $cookieCart);

            // Crear una nueva cookie con el carrito actualizado
            return redirect()->route('cart.index')->withCookie(cookie()->forever('cart', json_encode($cookieCart)))->with('success', 'Producto agregado al carrito exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Error al agregar el producto al carrito: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $sku){
        try {
            // Método para actualizar la cantidad de un elemento en el carrito
            $cantidad_sumar = $request->input('cantidad_sumar');
            if (!is_numeric($cantidad_sumar) || $cantidad_sumar <= 0) {
                throw new \InvalidArgumentException('La cantidad sumar debe ser un número válido y mayor que cero.');
            }
            $cart = session()->get('cart', []);
            $productoEncontrado = false;

            foreach ($cart as $key => $item) {
                if ($item['titulo_sku'] === $sku) {    
                    // Actualizar la cantidad del producto en el carrito
                    $cart[$key]['cantidad'] = $cantidad_sumar;
                    $productoEncontrado = true;
                    break;
                }
            }

            if (!$productoEncontrado) {
                throw new \RuntimeException('El producto con SKU ' . $sku . ' no se encontró en el carrito.');
            }
            session()->put('cart', $cart);

            //redirect()->route('cart.index');
            return response()->json(['cantidad_seleccionada'=>$cantidad_sumar,'cantidad_carrito'=>$cart[0]['cantidad'] ,'sku' => $sku, 'carrito' => $cart])->withCookie(cookie()->forever('cart', json_encode($cart)));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el producto en el carrito: ' . $e->getMessage()], 500);
        }
    }

    public function remove($sku){
        try {
            // Obtener el carrito de la sesión
            $cart = session()->get('cart', []);
    
            // Encuentra el índice del producto con el SKU dado
            $index = null;
            foreach ($cart as $key => $item) {
                if ($item['titulo_sku'] === $sku) {
                    $index = $key;
                    break;
                }
            }
    
            // Verifica si se encontró el producto
            if (!is_null($index)) {
                // Elimina el producto del carrito
                unset($cart[$index]);
                // Reindexa el arreglo para evitar índices faltantes
                $cart = array_values($cart);
                // Actualiza el carrito en la sesión
                session()->put('cart', $cart);
    
                // Actualiza la cookie del carrito
                return redirect()->route('cart.index')->withCookie(cookie()->forever('cart', json_encode($cart)))->with('success', 'El producto ha sido eliminado del carrito.');
            } else {
                return redirect()->route('cart.index')->with('error', 'El producto no se encontró en el carrito.');
            }
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Error al eliminar el producto del carrito: ' . $e->getMessage());
        }
    }

    public function clear(Request $request){
        try {
            // Borrar la cookie del carrito
            $cookie = cookie()->forget('cart');
            
            // Limpiar el carrito de la sesión
            session()->forget('cart');
            
            // Redirigir a la página del carrito con un mensaje de éxito
            return redirect()->route('cart.index')->with('success', 'Carrito eliminado con éxito')->withCookie($cookie);
        } catch (\Exception $e) {
            // Si hay algún error, manejarlo apropiadamente
            abort(500, 'Error al eliminar el carrito: ' . $e->getMessage());
        }
    }
}
