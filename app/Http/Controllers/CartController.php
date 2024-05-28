<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BackMarketApi; // Asegúrate de importar la clase BackMarketApi
use Illuminate\Support\Facades\DB;
use App\Mail\OrderProcessed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrito;
use App\Models\Producto;

class CartController extends Controller{
    protected $backMarketApi;
    protected $backMarketController;
    // Constructor para inyectar la instancia de BackMarketApi y BackMarketController
    public function __construct(BackMarketApi $backMarketApi, BackMarketController $backMarketController){
        $this->backMarketApi = $backMarketApi;
        $this->backMarketController = $backMarketController;
    }

    //Funcion para separar el sku por partes
    public function separarSku($sku){
        // Expresión regular para capturar las partes principales del SKU
        $regex = '/^(.*?)\s*(\d+[MTG]B)\s-\s*([^-\s]+(?:\s+[^-\s]+)*)\s*-\s*(\bLibre\b)?\s*(\w+)?\s*(\w+)?/';
        
        if (preg_match($regex, $sku, $matches)) {
            // El nombre del teléfono
            $nombre = isset($matches[1]) ? trim($matches[1]) : '';
            // La capacidad
            $capacidad = isset($matches[2]) ? trim($matches[2]) : '';
            // El color
            $color = isset($matches[3]) ? trim($matches[3]) : '';
            // Libre
            $libre = isset($matches[4]) ? trim($matches[4]) : '';
            // Batería
            $bateria = isset($matches[5]) ? trim($matches[5]) : '';
            // Estado
            $estado = isset($matches[6]) ? trim($matches[6]) : '';

            if($capacidad){
                $capacidad= preg_replace('/(\d+)([A-Za-z]+)/', '$1 $2', $capacidad);
            }
            //echo $nombre,$capacidad,$color,$libre,$bateria,$estado;
            return [
                'nombre' => $nombre, 'capacidad' => $capacidad, 'color' => $color, 'libre' => $libre, 'bateria' => $bateria, 'estado' => $estado
                ];
        } else {
            echo "No se pudo encontrar coincidencia".$sku;
            
        }
    }

    //Asociar el carrito con el usuario
    public function associateCartWithUser(){
        $user = Auth::user(); // Obtener al usuario autenticado
        if ($user) {
            echo "Usuario autenticado correctamente." . PHP_EOL;
            $cart = session()->get('cart', []); // Obtener el carrito actual de la sesión
            if (!empty($cart)) {
                echo "Carrito obtenido de la sesión correctamente." . PHP_EOL;
                DB::beginTransaction();
                try {
                    // Crear o actualizar el carrito asociado con el usuario
                    $carrito = Carrito::updateOrCreate(
                        ['usuario_id' => $user->id]
                    );
                    echo "Carrito creado o actualizado correctamente." . PHP_EOL;

                    // Recorrer cada elemento del carrito
                    foreach ($cart as $detalle) {
                        $sku = $detalle['titulo_sku']; // Obtener el SKU del producto desde el carrito
                        // Separar el SKU en partes (nombre, capacidad, color, libre, bateria, estado)
                        $partesSku = $this->separarSku($sku);
                        
                        // Verificar si la función devolvió un resultado válido
                        if (!empty($partesSku)) {
                            echo "partes emcontradas";
                            // Aquí puedes utilizar $partesSku para acceder a las partes del SKU
                            $nombre = $partesSku['nombre'];
                            $capacidad = $partesSku['capacidad'];
                            $color = $partesSku['color'];
                            $libre = $partesSku['libre'];
                            if(isset($libre) && $libre!=''){
                                $libre= true;
                            } else{
                                $libre= false;
                            }
                            $bateria = $partesSku['bateria'];
                            $estado = $partesSku['estado'];
                        
                            // Buscar el producto en la base de datos basándose en los valores obtenidos del SKU
                            $producto = Producto::where('nombre', $nombre)
                                ->where('capacidad', $capacidad)
                                ->where('color', $color)
                                ->where('libre',$libre)
                                ->where('bateria', $bateria)
                                ->where('estado', $estado)
                                ->first();
                            if ($producto) {
                                echo "Producto encontrado en la base de datos." . PHP_EOL;
                                $productoId = $producto->id; // Obtener el ID del producto
                                $unidades = $detalle['cantidad']; // Obtener las unidades del producto
                                
                                // Verificar si ya existe una entrada en la tabla pivot para evitar duplicados
                                $existingPivot = $carrito->productos()
                                ->where('producto_id', $productoId)
                                ->exists();
                                echo $existingPivot ? 'true' : 'false';
                                if ($existingPivot) {
                                    echo "Producto ya existente en el carrito. Actualizando unidades." . PHP_EOL;
                                    // Si ya existe, actualizar la cantidad
                                    $carrito->productos()
                                        ->updateExistingPivot($productoId, ['unidades' => DB::raw("unidades + $unidades")]);
                                } else {
                                    echo "Producto nuevo en el carrito. Adjuntando producto." . PHP_EOL;
                                    // Si no existe, adjuntar el producto con las unidades
                                    $carrito->productos()->attach($productoId, ['unidades' => $unidades]);
                                }
                            }else{
                                return response()->json(['error' => 'Error al obtener el producto'], 500);
                            }
                            
                        }
                    }
                    // Limpiar el carrito de la sesión
                    session()->forget('cart');
                    // Borrar la cookie del carrito
                    $cookie = cookie()->forget('cart');
                    DB::commit();
                }catch (\Exception $e){
                    DB::rollBack();
                    return response()->json(['error' => 'Error al asociar el carrito con el usuario'], 500);
                }
            }
        }
    }

    //Mostrar la vista del carrito
    public function index(){
        // Obtener el contenido del carrito desde la sesión
        $cookieCart = session()->get('cart', []);
        // Verificar si la cookie 'cart' está presente y cargar los datos
        if (empty($cookieCart)) {
            $cookieCart = json_decode(request()->cookie('cart'), true) ?: [];
        }
        // Verificar el stock del carrito antes de mostrarlo al usuario
        $cookieCart = $this->verificarStockCarrito($cookieCart);
        //dd($cookieCart);
        // Pasar los datos del carrito a la vista
        return view('carrito.cart', compact('cookieCart'));
    }
    //comprobar el stock de los productos del carrito
    public function verificarStockCarrito($carrito){
        try {
            // Recorrer los productos del carrito para verificar el stock
            foreach ($carrito as &$item) {
                // Obtener todos los productos en línea desde BackMarketController
                $producto = $this->backMarketController->mostrarUnProducto($item['titulo_sku']);
                //$producto = $productosEnLinea->firstWhere('sku', $item['titulo_sku']);
                if ($producto && $producto['quantity'] > 0) {
                    // Si hay stock disponible, actualiza la cantidad en el carrito
                    $item['stock_total'] = $producto['quantity'];
                    $item['mensaje_stock'] = "HayStock";
                    //$item['mensaje_stock'] = "Hay {$producto['quantity']} disponibles";
                } else {
                    // Si no hay stock disponible, establece el stock en 0 y agrega un mensaje
                    $item['stock_total'] = 0;
                    $item['mensaje_stock'] = "NoStock";
                }
            }
            //si el usuario tiene sesion iniciada, se guarda en el carrito
            $this->associateCartWithUser();
            return $carrito;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar el stock: ' . $e->getMessage()], 500);
        }
    }
    //comprobar stock de un producto solo
    public function verificarStockProducto($productoSku){
        try {
            // Obtener todos los productos en línea desde BackMarketController
            $producto = $this->backMarketController->mostrarUnProducto($productoSku);
            //$producto = $productosEnLinea->firstWhere('sku', $item['titulo_sku']);
            if ($producto && $producto['quantity'] > 0) {
                // Si hay stock disponible, actualiza la cantidad en el carrito
                $item['stock_total'] = $producto['quantity'];
                $item['mensaje_stock'] = "HayStock";
                //$item['mensaje_stock'] = "Hay {$producto['quantity']} disponibles";
            } else {
                // Si no hay stock disponible, establece el stock en 0 y agrega un mensaje
                $item['stock_total'] = 0;
                $item['mensaje_stock'] = "NoStock";
            }
    
            return $producto;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar el stock: ' . $e->getMessage()], 500);
        }
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
            $precioProducto_antiguo = $request->input('precio_producto_antiguo');
            $estado = $request->input('estado');
            $capacidad = $request->input('capacidad');
            $color = $request->input('color');
            $stock_total = $request->input('stock_producto');
            // Inicializar la variable productExists
            $productExists = false;
            // Verificar si el producto ya está en el carrito
            foreach ($cookieCart as &$item) {
                if ($item['titulo_sku'] === $tituloSku) {
                    //si hay mas stock de ese producto, se suma si no se queda igual
                    $productoCarrito = $this->verificarStockProducto($tituloSku);
                    if($productoCarrito['quantity']>$item['cantidad']){
                        // Si el producto ya está en el carrito, aumentar la cantidad y actualizar el carrito en la sesión
                        $item['cantidad'] += $cantidadProducto;
                    }
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
                    'precio_producto_antiguo' => $precioProducto_antiguo,
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
    //actualizar el stock
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
                    //se verifica el stock del producto
                    $productoCarrito = $this->verificarStockProducto($sku);
                    //si hay mas stock de ese producto se suma, si no se queda igual
                    if($productoCarrito['quantity']>= $item['cantidad']){  
                        // Actualizar la cantidad del producto en el carrito
                        $cart[$key]['cantidad'] = $cantidad_sumar;
                        $productoEncontrado = true;
                    }
                    break;
                }
            }
            if (!$productoEncontrado) {
                throw new \RuntimeException('El producto con SKU ' . $sku . ' no se encontró en el carrito.');
            }
            session()->put('cart', $cart);

            //redirect()->route('cart.index');
            return response()->json(['cantidad_seleccionada'=>$cantidad_sumar,'sku' => $sku, 'carrito' => $cart])->withCookie(cookie()->forever('cart', json_encode($cart)));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el producto en el carrito: ' . $e->getMessage()], 500);
        }
    }
    //eliminar el producto del carrito
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
                // Si el carrito está vacío después de eliminar el producto, también eliminamos la sesión del carrito
                if (empty($cart)) {
                    session()->forget('cart');
                    $cookie = cookie()->forget('cart');
                    return redirect()->route('cart.index')->with('success', 'Carrito eliminado con éxito')->withCookie($cookie);
                }
                // Actualiza la cookie del carrito
                return redirect()->route('cart.index')->withCookie(cookie()->forever('cart', json_encode($cart)))->with('success', 'El producto ha sido eliminado del carrito.');
            } else {
                return redirect()->route('cart.index')->with('error', 'El producto no se encontró en el carrito.');
            }
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Error al eliminar el producto del carrito: ' . $e->getMessage());
        }
    }
    //vaciar carrito
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
    //realizar pedido
    public function processOrder(Request $request){
        try {
            // Supongamos que obtienes los detalles del pedido de la solicitud
            $orderDetails = $request->all();

            // Aquí tendrías lógica para procesar el pedido y obtener los detalles del pedido

            // Supongamos que tienes los detalles del pedido en las variables $userId y $orderDetails

            // Insertar los detalles del pedido en la base de datos
            // DB::table('orders')->insert([
            //     'user_id' => $userId, // Asignar el ID del usuario actual
            //     // Asignar otros detalles del pedido, como los productos, el total, etc.
            //     // 'total' => $orderDetails['total'],
            //     // Otros detalles del pedido
            //     'created_at' => now(), // Opcional: agregar la fecha y hora actual
            //     'updated_at' => now(), // Opcional: agregar la fecha y hora actual
            // ]);

            // Envía un correo electrónico al usuario para notificarle que su pedido ha sido procesado
            Mail::to(Auth::user()->email)->send(new OrderProcessed($orderDetails));

            // Retorna una respuesta apropiada, como una redirección a una página de confirmación o un mensaje JSON
            return response()->json(['message' => 'Pedido procesado correctamente.','hola'=>$orderDetails]);
        } catch (\Exception $e) {
            // Si hay algún error, manejarlo apropiadamente y retornar una respuesta de error
            return response()->json(['error' => 'Error al procesar el pedido: ' . $e->getMessage()], 500);
        }
    }
}
