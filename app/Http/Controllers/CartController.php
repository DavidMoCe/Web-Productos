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

    // //prueba para mostrar carrito con el post
    // public function mostrar(Request $request){
    //     return view('carrito.cart', ['datos' => $request->all()]);
    // }

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
        // Método para actualizar la cantidad de un elemento en el carrito
        $cantidad_sumar = $request->input('cantidad_sumar');
        $cart = session()->get('cart', []);
        $nueva_cantidad = 0;
        $puedeSumar=true;
        $stock_total= $request->input('stock_total');
        foreach ($cart as $key => $item) {
            // if ($item['titulo_sku'] === $sku) {
            //     //Si la cantidad es menor que el stock disponible, se suma
            //     if($item['cantidad'] < 10){
            //         // Actualizar la cantidad del producto en el carrito
            //         $cart[$key]['cantidad'] += $cantidad_sumar;
            //         $nueva_cantidad = $cart[$key]['cantidad'];
            //         session()->put('cart', $cart);
            //         $puedeSumar=true;
            //         break;
            //     }else{
            //         $nueva_cantidad = $cart[$key]['cantidad'];
            //         $puedeSumar=false;
            //     }
            // }
            if ($item['titulo_sku'] === $sku) {
                // Calcular la cantidad máxima que se puede agregar respetando el stock disponible
                $cantidad_maxima = $stock_total - $item['cantidad'];
    
                if ($cantidad_maxima > 0) {
                    // Verificar si la cantidad a sumar excede la cantidad máxima permitida
                    if ($cantidad_sumar <= $cantidad_maxima) {
                        // Actualizar la cantidad del producto en el carrito
                        $cart[$key]['cantidad'] += $cantidad_sumar;
                        $nueva_cantidad = $cart[$key]['cantidad'];
                        session()->put('cart', $cart);
                        $puedeSumar = true;
                    } else {
                        // Si la cantidad a sumar excede la cantidad máxima permitida, establecer la nueva cantidad como la cantidad máxima permitida
                        $cart[$key]['cantidad'] = $stock_total;
                        $nueva_cantidad = $stock_total;
                        session()->put('cart', $cart);
                        $puedeSumar = false;
                    }
                } else {
                    // Si el stock disponible es cero, no se puede agregar más cantidad
                    $puedeSumar = false;
                    $nueva_cantidad = $item['cantidad'];
                }
    
                break;
            }
        }
        redirect()->route('cart.index');
        return response()->json(['stock_total'=>$stock_total, 'sku'=> $sku, "carrito"=>$cart,'cantidad_actualizada' => $nueva_cantidad,'puedeSumar'=>$puedeSumar ]);
    }

    public function remove(Request $request, $sku){
        // Método para eliminar un elemento del carrito
        $cantidad_restar = $request->input('cantidad_restar');
        $cart = session()->get('cart', []);
        $nueva_cantidad = 0;
        $puedeRestar=true;
        foreach ($cart as $key => $item) {
            if ($item['titulo_sku'] === $sku) {
                //Si la cantidad es mayor que 0, se resta
                if($item['cantidad'] > 1){
                    // Actualizar la cantidad del producto en el carrito
                    $cart[$key]['cantidad'] -= $cantidad_restar;
                    $nueva_cantidad = $cart[$key]['cantidad'];
                    session()->put('cart', $cart);
                    $puedeRestar=true;
                    break;
                }else{
                    $nueva_cantidad = $cart[$key]['cantidad'];
                    $puedeRestar=false;
                }
            }
        }
        redirect()->route('cart.index');
        return response()->json(['sku'=> $sku, "carrito"=>$cart,'cantidad_actualizada' => $nueva_cantidad,'puedeRestar'=>$puedeRestar ]);
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
