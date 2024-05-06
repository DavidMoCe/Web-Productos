<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BackMarketApi;

// use Illuminate\Support\Facades\Session;

class BackMarketController extends Controller{
    protected $backMarketApi;
    Protected $productosPorPaginas= 50;
    // Constructor del controlador
    public function __construct(BackMarketApi $backMarketApi){
        $this->backMarketApi = $backMarketApi;
    }

    // Método para obtener los productos en linea de Back Market Pagina por pagina
    public function mostrarProductos(Request $request){
        try {
            // Obtener el valor del parámetro de página de la solicitud
            $pagina = $request->query('page', 1);

            // Extraer el número después de "page=" o el numero directamente
            if (preg_match('/page=(\d+)/', $pagina, $matches)) {
                $numeroPagina = $matches[1];
            } else if (preg_match('/\d+/', $pagina, $matches)) {
                $numeroPagina = $matches[0];
            }
            
            // Utiliza el método apiget() de BackMarketApi para obtener los listados
            // $listings = $this->backMarketApi->apiGet('/listings/detail?sku=iPhone+14+Plus+128GB+-+Blanco+Estrella+-+Libre+NEWBATTERY+COR');
            $productosPorPaginas = $this->productosPorPaginas;
            $listings = $this->backMarketApi->apiGet('listings/?publication_state=2&page='.$numeroPagina.'&page-size='.$productosPorPaginas);
            // Devuelve los listados en una respuesta JSON
            //return response()->json($listings);

            // Llama al método productos() del ProductoController y pasa los listados como argumento
            return app('App\Http\Controllers\ProductoController')->productos($listings, $productosPorPaginas);


        } catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir durante la solicitud
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método para obtener todos los productos en linea de Back Market Devuelve una coleccion
    public function obtenerTodosEnLinea(){
        try{
            $allProducts = []; // Inicializa un arreglo vacío para almacenar todos los productos
            $numeroPagina=1;
            $productosPorPaginas = $this->productosPorPaginas;
            
            do{
                $listings = $this->backMarketApi->apiGet('listings/?publication_state=2&page='.$numeroPagina.'&page-size='.$productosPorPaginas);
                // Verifica si hay productos en la respuesta
                if (!empty($listings['results'])) {
                    $allProducts = array_merge($allProducts, $listings['results']);
                    $numeroPagina++;
                    
                } else {
                    // Si no hay más productos disponibles, sal del bucle
                    break;
                }
                
            }while(isset($listings['next']));

            // Convierte los productos a una coleccion
            $productosColeccion = collect($allProducts);
            return $productosColeccion;

        } catch (\Exception $e){
            // Maneja cualquier excepción que pueda ocurrir durante la solicitud
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }    


    // Parte de administrador

    // Método para mostrar los móviles que tenemos en backMarket
    public function obtenerMoviles(){
        try{
            // Obtener los productos de la funcion obtenerTodosEnLínea
            $productosColeccion = $this->obtenerTodosEnLinea();
            return view('admin.dashboard',['productos' => $productosColeccion]);

        } catch (\Exception $e){
            // Maneja cualquier excepción que pueda ocurrir durante la solicitud
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }    

    // Método para mostrar los modelos que tenemos en backMarket
    public function ObtenerModelos(Request $request){
        // Obtener el valor del parámetro de página de la solicitud
        $tipo = $request->query('tipo', null);

        // Obtener los productos de la funcion obtenerTodosEnLínea
        $productosColeccion = $this->obtenerTodosEnLinea();

        //filtramos el producto que coincida con algo del titulo
        $productosFiltrados = $productosColeccion->filter(function ($producto) use ($tipo) {
            return strpos($producto['title'], $tipo) !== false;
        });

        return view('admin.modelo',['productos' => $productosFiltrados]);
    }

    // Método para mostrar los modelos que tenemos en backMarket
    public function ObtenerStock(Request $request){
        // Obtener el valor del parámetro de página de la solicitud
        $modelo = $request->query('modelo', null);

        // Obtener los productos de la funcion obtenerTodosEnLínea
        $productosColeccion = $this->obtenerTodosEnLinea();

        $productosFiltrados = $productosColeccion->filter(function ($producto) use ($modelo) {
             // Definir la expresión regular para capturar la palabra antes de la capacidad
            $patron = '/^(.*?)\s*\d+GB\b/';

            // Verificar si el tipo del producto coincide con el patrón
            if (preg_match($patron, $producto['title'], $matches)) {
                // Extraer el tipo capturado por la expresión regular
                $tipoProducto = trim($matches[1]);

                // Si coincide, devolver verdadero
                return strtolower($tipoProducto) === strtolower($modelo);
            } else {
                // Si no coincide, devolver falso
                return false;
            }
        });

        return view('admin.stock',['productos' => $productosFiltrados]);
    }


    // Parte de detalles del producto
    public function OntenerUnProducto(Request $request){
        // Obtener los parametros del producto
        $productoSeleccionado= $this->tipoTelefono($request);
        $capacidad= $this->capacidad($request);
        $color= $this->color($request);
        $mejor_precio= $this->mejor_Precio($request);
        $mas_popular= $this->mas_popular($request);

        //print_r($_POST);

        // Verificar si el producto existe
        if ($productoSeleccionado) {

            // Obtener los productos de la funcion obtenerTodosEnLínea
            $productosColeccion = $this->obtenerTodosEnLinea();
            //filtramos el producto que coincida con algo del titulo
            $productosFiltrados = $productosColeccion->filter(function ($producto) use ($productoSeleccionado) {
                // Definir la expresión regular para capturar la palabra antes de la capacidad
                $patron = '/^(.*?)\s*\d+GB\b/';

                // Verificar si el tipo del producto coincide con el patrón
                if (preg_match($patron, $producto['title'], $matches)) {
                    // Extraer el tipo capturado por la expresión regular
                    $tipoProducto = trim($matches[1]);

                    // Si coincide, devolver verdadero
                // return strpos($tipoProducto, $productoSeleccionado) !== false;
                    return strtolower($tipoProducto) === strtolower($productoSeleccionado);
                } else {
                    // Si no coincide, devolver falso
                    return false;
                }
            });

            $data = ['info_producto' => $productosFiltrados];
            $variables = ['productoSeleccionado', 'capacidad', 'color','mejor_precio','mas_popular'];
            //comprobamos si es null o no 
            foreach ($variables as $variable) {
                if (isset($$variable)) {
                    $data[$variable] = $$variable;
                }
            }
            return view('producto.info_products',$data);
        }else{
            return redirect()->route('products');
        }
    }

    public function tipoTelefono(Request $request){
        // Comprobar si el parámetro 'producto' está presente en la solicitud
        if ($request->has('producto')) {
            // Obtener el valor del parámetro 'producto'
            $parametro = $request->query('producto');

            // Guardar los parámetros en la sesión sirve para guardarlo cuando se elige otra opcion dentro del producto
            //Session::put('productoSeleccionado', $parametro);
            return $parametro;//devuelve por ejemplo iphone 12
        }
        //return Session::get('productoSeleccionado');
        return '';
    }

    public function capacidad(Request $request){
        // Comprobar si el parámetro 'capacidad' está presente en la solicitud
        if ($request->has('capacidad')) { 
            // Obtener el valor del parámetro 'capacidad'
            $parametro = $request->query('capacidad');

            // Guardar los parámetros en la sesión
            //Session::put('capacidad', $parametro);
            return $parametro;//devuelve por ejemplo 128GB
        }
        //return Session::get('capacidad');
        return '';
    }

    public function color(Request $request){
        // Comprobar si el parámetro 'color' está presente en la solicitud
        if ($request->has('color')) {
            // Obtener el valor del parámetro de página de la solicitud
            $parametro = $request->query('color');

            // Guardar los parámetros en la sesión
            //Session::put('color', $parametro);
            return $parametro;//devuelve por ejemplo Titanio Azul
        }
        //return Session::get('color');
        return '';
    }

    public function mejor_Precio(Request $request){
        // Comprobar si el parámetro 'mejor_precio' está presente en la solicitud
        if ($request->has('mejor_precio')) {
            // Obtener el valor del parámetro de página de la solicitud
            $parametro = $request->query('mejor_precio');

            // Guardar los parámetros en la sesión
            //Session::put('mejor_precio', $parametro);
            return $parametro;//devuelve por ejemplo si o no
        }
        return '';
    }

    public function mas_popular(Request $request){
        // Comprobar si el parámetro 'mas_popular' está presente en la solicitud
        if ($request->has('mas_popular')) {
            // Obtener el valor del parámetro de página de la solicitud
            $parametro = $request->query('mas_popular');

            // Guardar los parámetros en la sesión
            //Session::put('mas_popular', $parametro);
            return $parametro;//devuelve por ejemplo si o no
        }
        return '';
    }

    public function precioEstado(Request $request){
        try {
            // Obtener todos los productos en línea
            $productosColeccion = $this->obtenerTodosEnLinea();

            // Obtener el título del producto de la solicitud
            $tituloProducto = $request->input('titulo_producto');
            $estadoProducto = $request->input('productid');
            
            if($estadoProducto ==='estado_correcto'){
                // Filtrar productos que coincidan con el título y el estado
                $productosFiltrados = $productosColeccion->first(function ($producto) use ($tituloProducto) {
                    return $producto['title'] === $tituloProducto && ($producto['state'] == 3 || $producto['state'] == 4) && strpos($producto['sku'], 'NEWBATTERY') === false;
                });
            }

            if($estadoProducto ==='estado_muyBueno'){
                // Filtrar productos que coincidan con el título y el estado
                $productosFiltrados = $productosColeccion->first(function ($producto) use ($tituloProducto) {
                    return $producto['title'] === $tituloProducto && ($producto['state'] == 1 || $producto['state'] == 2) && strpos($producto['sku'], 'NEWBATTERY') === false;
                });
            }

            if($estadoProducto ==='estado_excelente'){
                // Filtrar productos que coincidan con el título y el estado
                $productosFiltrados = $productosColeccion->first(function ($producto) use ($tituloProducto) {
                    return $producto['title'] === $tituloProducto && ($producto['state'] == 0) && strpos($producto['sku'], 'NEWBATTERY') === false;
                });
            }

           // Verificar si se encontró un producto que coincida con los criterios
            if ($productosFiltrados) {
                // Formatear el precio: reemplazar punto por coma
                $precioFormateado = number_format($productosFiltrados['price'], 2, ",", ".");

                // Devolver los datos del producto filtrado como respuesta JSON
                return response()->json(['precio' => $productosFiltrados['price'], 'estado' => $productosFiltrados['state']]);
            } else {
                // No se encontró ningún producto que coincida con los criterios, puedes devolver un mensaje de error o algo así
                return response()->json(['noStock' => 'No hay stock','estado' => $estadoProducto]);
            }

            // Devolver los precios y estados de los productos filtrados como respuesta JSON
            //return response()->json(['datos' => $datosProductos]);

        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir durante la solicitud
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
