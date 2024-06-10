<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BackMarketApi; // Asegúrate de importar la clase BackMarketApi
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Carrito;
use App\Models\Producto;
use App\Models\Envio;
use App\Models\Facturacion;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cookie;

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
        $regex = '/^(.*?)\s*(\d+\s*[MTG]B)\s*-\s*([^-\s]+(?:\s+[^-\s]+)*)\s*(?:-\s*(\bLibre\b))?(\s*-\s*)?(.*)/i';
        $regex1 = '/\bNew\s*battery\b/i';
        $regex2 = '/\b(\w+)\b$/i';

        // Inicializar las variables fuera de los bloques if
        $bateria = '';
        $estado = '';
        if (preg_match($regex, $sku, $matches)) {
            // El nombre del teléfono
            $nombre = isset($matches[1]) ? trim($matches[1]) : '';
            // La capacidad
            $capacidad = isset($matches[2]) ? trim($matches[2]) : '';
            // El color
            $color = isset($matches[3]) ? trim($matches[3]) : '';
            // Libre
            $libre = isset($matches[4]) ? trim($matches[4]) : '';
            //echo $nombre,$capacidad,$color,$libre,$bateria,$estado;
    
            //sacar la bateria
            if ((preg_match($regex1, $sku, $matches))) {
                // Batería
                $bateria = isset($matches[0]) ? trim($matches[0]) : '';
            }
            //sacar el estado
            if((preg_match($regex2, $sku, $matches))){
                // Batería
                $estado = isset($matches[0]) ? trim($matches[0]) : '';
            }
            return [
                'nombre' => $nombre, 'capacidad' => $capacidad, 'color' => $color, 'libre' => $libre, 'bateria' => $bateria, 'estado' => $estado
                ];
        } else {
           // echo "<br>No se pudo encontrar coincidencia".$sku;
            
        } 
    }

    //Asociar el carrito con el usuario
    public function associateCartWithUser(){
        $user = Auth::user(); // Obtener al usuario autenticado
        if ($user) {
            $cart = session()->get('cart', []); // Obtener el carrito actual de la sesión
            if (!empty($cart)) {
                DB::beginTransaction();
                try {
                    // Crear o actualizar el carrito asociado con el usuario
                    $carrito = Carrito::updateOrCreate(
                        ['usuario_id' => $user->id]
                    );

                    // Recorrer cada elemento del carrito
                    foreach ($cart as $detalle) {
                        $sku = $detalle['titulo_sku']; // Obtener el SKU del producto desde el carrito
                        // Separar el SKU en partes (nombre, capacidad, color, libre, bateria, estado)
                        $partesSku = $this->separarSku($sku);
                        
                        // Verificar si la función devolvió un resultado válido
                        if (!empty($partesSku)) {
                            // Aquí puedes utilizar $partesSku para acceder a las partes del SKU
                            $nombre = $partesSku['nombre'];
                            $capacidad = $partesSku['capacidad'];
                            $color = $partesSku['color'];
                            $libre = $partesSku['libre'];
                            $libre = isset($libre) && $libre != '' ? true : false;
                            $bateria = $partesSku['bateria'];
                            $estado = $partesSku['estado'];
                            //separar en la capacidad los numeros de las palabras
                            // if($capacidad){
                            //     $capacidad= preg_replace('/(\d+)([A-Za-z]+)/', '$1 $2', $capacidad);
                            // }
                            // Buscar el producto en la base de datos basándose en los valores obtenidos del SKU
                            $producto = Producto::where('nombre', $nombre)
                                ->where('capacidad', $capacidad)
                                ->where('color', $color)
                                ->where('libre',$libre)
                                ->where('bateria', $bateria)
                                ->where('estado', $estado)
                                ->first();
                            if ($producto) {
                                $productoId = $producto->id; // Obtener el ID del producto
                                $unidades = $detalle['cantidad']; // Obtener las unidades del producto
                                // Verificar si ya existe una entrada en la tabla pivot para evitar duplicados
                                $existingPivot = DB::table('carrito_producto')
                                ->where('carrito_id', $carrito->id)
                                ->where('producto_id', $productoId)
                                ->first();
                                if ($existingPivot) {
                                    // Si ya existe, actualizar la cantidad
                                    $carrito->productos()
                                        ->updateExistingPivot($productoId, ['unidades' => DB::raw("$unidades")]);
                                } else {
                                    // Si no existe, adjuntar el producto con las unidades
                                    $carrito->productos()->attach($productoId, ['unidades' => $unidades]);
                                }
                            }else{
                               // return response()->json(['error' => 'Error al obtener el producto'], 500);
                            }
                        }
                    }
                    // Limpiar el carrito de la sesión
                    session()->forget('cart');
                    // Borrar la cookie del carrito
                    Cookie::queue(Cookie::forget('cart'));
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
        // Verificar si el usuario ha iniciado sesión
        $user = Auth::user();

        if ($user) {
            // Si el carrito no está asociado con el usuario, asociarlo
            $this->associateCartWithUser();

            // El usuario ha iniciado sesión, obtener el carrito desde la base de datos
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();

            if ($carrito) {
                // Convertir los productos del carrito en el formato esperado
                $dbCart = [];
                foreach ($carrito->productos as $producto) {
                    try{
                     //De momento saco solo el nombre hasta el numero
                    preg_match('/(\S*\s?){2}/', $producto->nombre, $matches);
                    $nombre= isset($matches[0]) ? trim($matches[0]) : '';

                    if($producto->capacidad){
                        $capacidad= preg_replace('/(\d+)([A-Za-z]+)/', '$1 $2', $producto->capacidad);
                    }

                    $dbCart[] = [
                        'id_producto'=>$producto->id,'titulo_sku' => $producto->nombre . ' ' . $producto->capacidad . ' - ' . $producto->color . ' - ' . ($producto->libre ? 'Libre' : '') . ($producto->bateria ? ' ' . $producto->bateria : '') . ' ' . $producto->estado,
                        'titulo_producto'=> $producto->nombre.' '.$capacidad.' - '.$producto->color.' - '.($producto->libre ? 'Libre' : ''),'estado_producto'=> $producto->estado,'cantidad' => $producto->pivot->unidades,
                        'precio_producto'=>  number_format($producto->precioD, 2, ',', '.') . ' €', 'nombre'=> $producto->nombre, 'capacidad'=> $producto->capacidad, 'color'=> $producto->color,
                        'libre'=> $producto->libre, 'bateria'=> $producto->bateria,'titulo_imagen'=> str_replace(" ","",$nombre) .'-'.str_replace(" ","",$producto->color), 'precio_producto_antiguo'=>number_format($producto->precioA, 2, ',', '.') . ' €',
                    ];
                    } catch (Exception $e){
                        // Si ocurre un error, omitir este producto y continuar con el siguiente
                        continue;
                    }
                }
            } else {
                $dbCart = [];
            }

            // Verificar el stock del carrito de la base de datos antes de mostrarlo al usuario
            $cookieCart = $this->verificarStockCarritoBD($dbCart);
        } else {
            // El usuario no ha iniciado sesión, obtener el carrito desde la cookie
            $cookieCart = session()->get('cart', []);
            if (empty($cookieCart)) {
                $cookieCart = json_decode(request()->cookie('cart'), true) ?: [];
            }

            // Verificar el stock del carrito de la cookie antes de mostrarlo al usuario
            $cookieCart = $this->verificarStockCarrito($cookieCart);
        }

        // Pasar los datos del carrito a la vista
        return view('carrito.cart', compact('cookieCart'));
    }
    //comprobar el stock de los productos del carrito de la bd MUESTRA EL STOCK DE LA BASE DE DATOS
    public function verificarStockCarritoBD($carrito){
        try {
            // Recorrer los productos del carrito para verificar el stock
            foreach ($carrito as &$item) {
                $user = Auth::user();
                if($user){
                    // Recorrer los productos del carrito para verificar el stock en la base de datos
                    // Obtener el producto desde la base de datos
                    $producto = Producto::where('nombre', $item['nombre'])
                                        ->where('capacidad', $item['capacidad'])
                                        ->where('color', $item['color'])
                                        ->where('libre', $item['libre'])
                                        ->where('bateria', $item['bateria'])
                                        ->where('estado', $item['estado_producto'])
                                        ->first();

                    if ($producto && $producto->stock > 0) {
                        // Si hay stock disponible, actualizar la cantidad en el carrito
                        $item['stock_total'] = $producto->stock;
                        $item['mensaje_stock'] = "HayStock";
                    } else {
                        // Si no hay stock disponible, establecer el stock en 0 y agregar un mensaje
                        $item['stock_total'] = 0;
                        $item['mensaje_stock'] = "NoStock";
                    }
                }   
            }
            return $carrito;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al verificar el stock: ' . $e->getMessage()], 500);
        }
    }

    //comprobar el stock de los productos del carrito en backmarket MUESTRA EL STOCK DE BACKMARKET
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

            $user = Auth::user();
            if ($user) {
                // El usuario ha iniciado sesión, agregar el producto a la tabla pivot carrito_producto
                $carrito = Carrito::firstOrCreate(['usuario_id' => $user->id]);
                // Separar el SKU en partes (nombre, capacidad, color, libre, bateria, estado)
                $partesSku = $this->separarSku($tituloSku);
                // Verificar si la función devolvió un resultado válido
                if (!empty($partesSku)) {
                    // Aquí puedes utilizar $partesSku para acceder a las partes del SKU
                    $nombre = $partesSku['nombre'];
                    $capacidad = $partesSku['capacidad'];
                    $color = $partesSku['color'];
                    $libre = $partesSku['libre'];
                    $libre = isset($libre) && $libre != '' ? true : false;
                    $bateria = $partesSku['bateria'];
                    $estado = $partesSku['estado'];
                    // //separar en la capacidad los numeros de las palabras
                    // if($capacidad){
                    //     $capacidad= preg_replace('/(\d+)([A-Za-z]+)/', '$1 $2', $capacidad);
                    // }
                    // Buscar el producto en la base de datos basándose en los valores obtenidos del SKU
                    $producto = Producto::where('nombre', $nombre)
                        ->where('capacidad', $capacidad)
                        ->where('color', $color)
                        ->where('libre',$libre)
                        ->where('bateria', $bateria)
                        ->where('estado', $estado)
                        ->first();
                    if ($producto) {
                        $productoId = $producto->id; // Obtener el ID del producto
                        $unidades = $cantidadProducto; // Obtener las unidades del producto
                        $existingPivot = DB::table('carrito_producto')
                        ->where('carrito_id', $carrito->id)
                        ->where('producto_id', $productoId)
                        ->first();
                        if ($existingPivot) {
                            // Si ya existe, actualizar la cantidad
                            DB::table('carrito_producto')
                            ->where('carrito_id', $carrito->id)
                            ->where('producto_id', $productoId)
                            ->increment('unidades', $unidades);
                        } else {
                            // Si no existe, adjuntar el producto con las unidades
                            $carrito->productos()->attach($productoId, ['unidades' => $unidades]);
                        }
                        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito exitosamente.');
                    }else{
                        return response()->json(['error' => 'Error al obtener el producto'.$capacidad], 500);
                    }
                }
            } else {
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

                // Establecer el tiempo de expiración en 30 días (en minutos)
                $expirationTime = 30 * 24 * 60;
                // Crear una nueva cookie con el carrito actualizado
                return redirect()->route('cart.index')->withCookie(cookie()->forever('cart', json_encode($cookieCart), $expirationTime))->with('success', 'Producto agregado al carrito exitosamente.');
            }
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
            $user = Auth::user();
            if ($user) {
                // El usuario ha iniciado sesión, actualizar el carrito en la base de datos
                $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
                // Actualizar la cantidad del producto en el carrito
                foreach ($carrito->productos as $producto) {
                    $titulo_sku = $producto->nombre . ' ' . $producto->capacidad . ' - ' . $producto->color . ' - ' . ($producto->libre ? 'Libre' : '') . ' ' . $producto->bateria . ' ' . $producto->estado;
                    if ($titulo_sku === $sku) {
                        // Actualizar la cantidad del producto en la base de datos
                        $carrito->productos()->updateExistingPivot($producto->id, ['unidades' => DB::raw($cantidad_sumar)]);
                        break;
                    }
                }
                // Guardar el carrito actualizado en la base de datos
                $carrito->save();
                return response()->json(['cantidad_seleccionada'=>$cantidad_sumar,'sku' => $sku]);
            }else {
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
            }  

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el producto en el carrito: ' . $e->getMessage()], 500);
        }
    }
    //eliminar el producto del carrito
    public function remove(Request $request, $sku){
        try {
            $user = Auth::user();
            if($user){
                $id= $request->input('id_producto');
                // El usuario ha iniciado sesión, eliminar el producto del carrito en la base de datos
                $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
                if ($carrito) {
                    // Buscar el producto en el carrito del usuario
                    $producto = $carrito->productos()->where('productos.id', $id)->first();
                    if ($producto) {
                        // Eliminar el producto del carrito
                        $carrito->productos()->detach($producto->id);
                        return redirect()->route('cart.index')->with('success', 'El producto ha sido eliminado del carrito.');
                    } else {
                        return redirect()->route('cart.index')->with('error', 'El producto no se encontró en el carrito.');
                    }
                } else {
                    return redirect()->route('cart.index')->with('error', 'No se encontró el carrito del usuario.');
                }
            }else{
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
            }
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Error al eliminar el producto del carrito: ' . $e->getMessage());
        }
    }
    //vaciar carrito
    public function clear(){
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

    //Comprobar si tiene direccion de envio y de facturación 
    public function checkAddress(){
        // Obtener el usuario autenticado
        $user = Auth::user();

        //obtenemos los datos
        $name = $user->name ?? '';
        $lastname = $user->lastname ?? '';
        //accedemos a la funcion direccion envio del modelo user.php
        $direccionEnvio = $user->direccionEnvio;
        // Obtener la dirección_1 del usuario autenticado, si existe
        $direccion_1 = $direccionEnvio ? $direccionEnvio->direccion_1 : '';
        $direccion_2 = $direccionEnvio ? $direccionEnvio->direccion_2 : '';
        $pais = $direccionEnvio ? $direccionEnvio->pais : '';
        $ciudad = $direccionEnvio ? $direccionEnvio->ciudad : '';
        $codigo_postal = $direccionEnvio ? $direccionEnvio->codigo_postal : '';
        $empresa = $direccionEnvio ? $direccionEnvio->empresa : '';
        $telefono = $direccionEnvio ? $direccionEnvio->telefono : '';

        //accedemos a la funcion direccion envio del modelo user.php
        $direccionFacturacion = $user->direccionFacturacion;
        $Fdireccion_1 = $direccionFacturacion ? $direccionFacturacion->direccion_1 : '';
        $Fdireccion_2 = $direccionFacturacion ? $direccionFacturacion->direccion_2 : '';
        $Fpais = $direccionFacturacion ? $direccionFacturacion->pais : '';
        $Fciudad = $direccionFacturacion ? $direccionFacturacion->ciudad : '';
        $Fcodigo_postal = $direccionFacturacion ? $direccionFacturacion->codigo_postal : '';
        $Fempresa = $direccionFacturacion ? $direccionFacturacion->empresa : '';
        $Fnif_dni = $direccionFacturacion ? $direccionFacturacion->nif_dni : '';

        //si hay direcciones se muestra, si no se llama a la funcion addAddress_shippping
        if($direccionEnvio && $direccionFacturacion){
            return view('orders.confirm-address', 
                compact('name', 'lastname','direccion_1','direccion_2','pais','ciudad',
                'codigo_postal','empresa','telefono','Fdireccion_1','Fdireccion_2',
                'Fpais','Fciudad','Fcodigo_postal','Fempresa','Fnif_dni'));
        }else{
            return $this->addAddress_shipping();
        }
    }

    //anadir direccion de envio
    public function addAddress_shipping(){
        try {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Obtener los datos del usuario autenticado
            $name = $user->name ?? '';
            $lastname = $user->lastname ?? '';
            //accedemos a la funcion direccion envio del modelo user.php
            $direccionEnvio = $user->direccionEnvio;
            // Obtener la dirección_1 del usuario autenticado, si existe
            $direccion_1 = $direccionEnvio ? $direccionEnvio->direccion_1 : '';
            $direccion_2 = $direccionEnvio ? $direccionEnvio->direccion_2 : '';
            $pais = $direccionEnvio ? $direccionEnvio->pais : '';
            $ciudad = $direccionEnvio ? $direccionEnvio->ciudad : '';
            $codigo_postal = $direccionEnvio ? $direccionEnvio->codigo_postal : '';
            $empresa = $direccionEnvio ? $direccionEnvio->empresa : '';
            $telefono = $direccionEnvio ? $direccionEnvio->telefono : '';
            
            $direccionFacturacion = $user->direccionFacturacion;
            $nif_dni = $direccionFacturacion ? $direccionFacturacion->nif_dni : '';
            // El usuario ha iniciado sesión, obtener el carrito desde la base de datos
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
            
            if($carrito==null){
                return redirect()->route('products');
            }

            return view('orders.shipping-address', compact('name', 'lastname','pais','direccion_1','direccion_2','ciudad','codigo_postal','empresa','telefono','nif_dni'));

        } catch (\Exception $e) {
            // Si hay algún error, manejarlo apropiadamente y retornar una respuesta de error
            return view('cart.index')->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }
    //validar y guardar los datos de la direccion de envio y mostrar la de facturacion
    public function submitShippingAddressForm(Request $request){
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'address_2' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'country' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
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

            // Crear un nuevo registro en la tabla envios o actualizar uno existente
            Envio::updateOrCreate($criterio, $updateData);

            // Si el checkbox de dirección de facturación es igual a la dirección de envío está marcado, copia los datos de envío a los de facturación
            if ($request->has('same_as_billing')) {
                $updateDataFacturacion = [
                    'pais' => $validatedData['country'],
                    'direccion_1' => $validatedData['address'],
                    'direccion_2' => $validatedData['address_2'] ?? null,
                    'ciudad' => $validatedData['city'],
                    'codigo_postal' => $validatedData['postal_code'],
                    'empresa' => $validatedData['company'] ?? null,
                    'nif_dni' => $validatedData['nif_dni'] ?? null,
                    'updated_at' => Carbon::now(),
                ];

                // Crear un nuevo registro en la tabla facturaciones o actualizar uno existente
                Facturacion::updateOrCreate($criterio, $updateDataFacturacion);
                return view('orders.payment')->with('success', 'Dirección de envío y facturación guardada con éxito.');
            }


            // Obtener el usuario autenticado
            $user = Auth::user();

            // Obtener los datos del usuario autenticado
            $name = $user->name ?? '';
            $lastname = $user->lastname ?? '';
            //accedemos a la funcion direccion envio del modelo user.php
            $direccionFacturacion = $user->direccionFacturacion;
            // Obtener la dirección_1 del usuario autenticado, si existe
            $direccion_1 = $direccionFacturacion ? $direccionFacturacion->direccion_1 : '';
            $direccion_2 = $direccionFacturacion ? $direccionFacturacion->direccion_2 : '';
            $pais = $direccionFacturacion ? $direccionFacturacion->pais : '';
            $ciudad = $direccionFacturacion ? $direccionFacturacion->ciudad : '';
            $codigo_postal = $direccionFacturacion ? $direccionFacturacion->codigo_postal : '';
            $empresa = $direccionFacturacion ? $direccionFacturacion->empresa : '';
            $nif_dni = $direccionFacturacion ? $direccionFacturacion->nif_dni : '';
            
            // El usuario ha iniciado sesión, obtener el carrito desde la base de datos
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();

            if($carrito==null){
                return redirect()->route('products');
            }
            //si la peticion es desde la modificacion en la vista de confirmation-address, entramos en el if
            $edicionConfirmacion=$request->edicionConfirmacion;
            if($edicionConfirmacion){
                return view('orders.billing-address', compact('name', 'lastname','pais','direccion_1','direccion_2','ciudad','codigo_postal','empresa','nif_dni'));
            }
            // Redirigir a la función que muestra la vista de dirección de facturación o a cualquier otra página
            return view('orders.billing-address', compact('name', 'lastname','pais','direccion_1','direccion_2','ciudad','codigo_postal','empresa','nif_dni'))->with('success', 'Dirección de envío guardada con éxito.');
        } catch (\Exception $e) {
            // Si hay algún error, manejarlo apropiadamente y retornar una respuesta de error con los datos de la tabla envios
            // Obtener el usuario autenticado
            $user = Auth::user();
            // Obtener los datos del usuario autenticado
            $name = $user->name ?? '';
            $lastname = $user->lastname ?? '';
            //accedemos a la funcion direccion envio del modelo user.php
            $direccionEnvio = $user->direccionEnvio;
            // Obtener la dirección_1 del usuario autenticado, si existe
            $direccion_1 = $direccionEnvio ? $direccionEnvio->direccion_1 : '';
            $direccion_2 = $direccionEnvio ? $direccionEnvio->direccion_2 : '';
            $pais = $direccionEnvio ? $direccionEnvio->pais : '';
            $ciudad = $direccionEnvio ? $direccionEnvio->ciudad : '';
            $codigo_postal = $direccionEnvio ? $direccionEnvio->codigo_postal : '';
            $empresa = $direccionEnvio ? $direccionEnvio->empresa : '';
            $telefono = $direccionEnvio ? $direccionEnvio->telefono : '';            

            return view('orders.shipping-address', compact('name', 'lastname','pais','direccion_1','direccion_2','ciudad','codigo_postal','empresa','telefono'))->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }
    //validar y guardar los datos de la direccion de envio y mostrar la de facturacion
    public function submitBillingAddressForm(Request $request){
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'address_2' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:10',
                'country' => 'required|string|max:255',
                'nif_dni' => 'required|string|max:20',
            ]);
            // Definir los criterios de búsqueda
            $criterio = [
                'user_id' => auth()->id(),
            ];
            // Definir los datos a actualizar o crear
            $updateData = [
                'pais' => $validatedData['country'],
                'direccion_1' => $validatedData['address'],
                'direccion_2' => $validatedData['address_2'] ?? null,
                'ciudad' => $validatedData['city'],
                'codigo_postal' => $validatedData['postal_code'],
                'empresa' => $validatedData['company'] ?? null,
                'nif_dni' => $validatedData['nif_dni'],
                'updated_at' => Carbon::now(),
            ];
            // Crear un nuevo registro en la tabla envios o actualizar uno existente
            Facturacion::updateOrCreate($criterio, $updateData);
            // Obtener el usuario autenticado
            $user = Auth::user();
            // El usuario ha iniciado sesión, obtener el carrito desde la base de datos
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
            if($carrito==null){
                return redirect()->route('products');
            }
            // Redirigir a la función que muestra la vista de dirección de facturación o a cualquier otra página
            return view('orders.payment')->with('success', 'Dirección de envío guardada con éxito.');
        } catch (\Exception $e) {
            // Si hay algún error, manejarlo apropiadamente y retornar una respuesta de error con los datos de la tabla facturacion
            // Obtener el usuario autenticado
            $user = Auth::user();
            // Obtener los datos del usuario autenticado
            $name = $user->name ?? '';
            $lastname = $user->lastname ?? '';
            //accedemos a la funcion direccion envio del modelo user.php
            $direccionFacturacion = $user->direccionFacturacion;
            // Obtener la dirección_1 del usuario autenticado, si existe
            $direccion_1 = $direccionFacturacion ? $direccionFacturacion->direccion_1 : '';
            $direccion_2 = $direccionFacturacion ? $direccionFacturacion->direccion_2 : '';
            $pais = $direccionFacturacion ? $direccionFacturacion->pais : '';
            $ciudad = $direccionFacturacion ? $direccionFacturacion->ciudad : '';
            $codigo_postal = $direccionFacturacion ? $direccionFacturacion->codigo_postal : '';
            $empresa = $direccionFacturacion ? $direccionFacturacion->empresa : '';
            $nif_dni = $direccionFacturacion ? $direccionFacturacion->nif_dni : '';             

            return view('orders.billing-address', compact('name', 'lastname','pais','direccion_1','direccion_2','ciudad','codigo_postal','empresa','nif_dni'))->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }


    
}
