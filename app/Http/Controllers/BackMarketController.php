<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BackMarketApi;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Session;

class BackMarketController extends Controller{
    protected $backMarketApi;
    Protected $productosPorPaginas= 10;
    // Constructor del controlador
    public function __construct(BackMarketApi $backMarketApi){
        $this->backMarketApi = $backMarketApi;
    }

    // Método para obtener los productos en linea de Back Market Pagina por pagina, para la página de productos
    //(PUEDE OMITIRSE E IR DIRECTAMENTE A ProductController)
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
            //$listings = $this->backMarketApi->apiGet('/listings/detail?sku=iPhone 15 Pro 256GB - Titanio Blanco - Libre COR');
            $productosPorPaginas = $this->productosPorPaginas;
            $listings = $this->backMarketApi->apiGet('listings/?publication_state=2&page='.$numeroPagina.'&page-size='.$productosPorPaginas);
            // Devuelve los listados en una respuesta JSON
            //return response()->json($listings);

            // Verificar que la respuesta contiene la clave "results"
            if (is_array($listings) && array_key_exists('results', $listings)) {
                $listings1 = $listings['results'];
                $totalProductos = $listings['count'];

                // Filtrar los productos que empiezan por "iPhone"
                $productosFiltrados = array_filter($listings1, function($producto) {
                    return is_array($producto) && isset($producto['sku']) && stripos($producto['sku'], 'iPhone') === 0;
                });

                // Reindexar el array filtrado
                $productosFiltrados = array_values($productosFiltrados);

                // Llama al método productos() del ProductoController y pasa los listados filtrados como argumento
                return app('App\Http\Controllers\ProductoController')->productos($productosFiltrados, $productosPorPaginas,$totalProductos);
            } else {
                // Si la respuesta no contiene "results", lanza una excepción
                throw new \Exception("La respuesta de la API no contiene 'results'.");
            }


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
    public function ObtenerUnProductoBD(Request $request){
        // Obtener los parametros del producto
        $productoSeleccionado= $this->tipoTelefono($request);
        $capacidad= $this->capacidad($request);
        $color= $this->color($request);
        $mejor_precio= $this->mejor_Precio($request);
        $mas_popular= $this->mas_popular($request);
        $scroll=false;
     
        // Verificar si el producto existe
        if ($productoSeleccionado) {
            //obtenemos los productos que coincidan con el productoseleccionado de la bd
            $productosFiltrados = Producto::where('nombre', $productoSeleccionado)->where('stock', '>', 0)->get();

            //añadimos los datos que no son nulos a la vista
            $data = ['info_producto' => $productosFiltrados];
            $variables = ['productoSeleccionado', 'capacidad', 'color','mejor_precio','mas_popular'];
            //comprobamos si es null o no 
            foreach ($variables as $variable) {
                if (isset($$variable)) {
                    $data[$variable] = $$variable;
                }
            }
            return view('producto.info_productsBD',$data);
        }else{
            return redirect()->route('products');
        }
    }

    public function ObtenerUnProducto(Request $request){
        // Obtener los parametros del producto
        $productoSeleccionado= $this->tipoTelefono($request);
        $capacidad= $this->capacidad($request);
        $color= $this->color($request);
        $mejor_precio= $this->mejor_Precio($request);
        $mas_popular= $this->mas_popular($request);
        $scroll=false;
        

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

            //añadimos los datos que no son nulos a la vista
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

    //SE PUEDE BORRAR
    public function peticionProductos(Request $request){
        try {
            // Definir una colección para almacenar los colores únicos y las capacidades unicas
            $coloresUnicos = new \Illuminate\Support\Collection();
            $capacidadesUnicas = new \Illuminate\Support\Collection();

            // Obtener todos los productos en línea
            $productosColeccion = $this->obtenerTodosEnLinea();

            // Obtener el título del producto de la solicitud
            $tituloProducto = $request->input('titulo_producto');
            $estadoCheckbox = $request->input('estadoCheckbox');
            $estadoProducto = $request->input('estadoProducto');
            $productoCapacidad = $request->input('productoCapacidad');
            $productoColor = $request->input('productoColor');
            $productoSinBateria= false;
            $productoConBateria= false;

                //si el checkbox esta marcado
                if($estadoCheckbox==='true'){
                    //comprobamos si existe el producto con NEWBATTERY
                    if($estadoProducto === "estado_correcto"){
                        // Filtrar productos que coincidan con el título, tengan en el sku NEWBATTERY, la capacidad seleccionada y el color
                        $productosFiltrados = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'COR') !== false || strpos($producto['sku'], 'STA') !== false) && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                        });
                        $productoEncontradoSinBateria = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, &$productoSinBateria, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            if ($nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'COR') !== false || strpos($producto['sku'], 'STA') !== false) && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false)) {
                                $productoSinBateria = true;
                            }
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'COR') !== false || strpos($producto['sku'], 'STA') !== false) && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                        });
                    }elseif($estadoProducto === "estado_bueno"){
                        // Filtrar productos que coincidan con el título y tengan en el sku NEWBATTERY
                        $productosFiltrados = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'BUE') || strpos($producto['sku'], 'MBU')) && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                        });
                        $productoEncontradoSinBateria = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, &$productoSinBateria, $productoCapacidad,$productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            if ($nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'BUE') || strpos($producto['sku'], 'MBU')) && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false)) {
                                $productoSinBateria = true;
                            }
                        });
                    }elseif($estadoProducto === "estado_impecable"){
                        // Filtrar productos que coincidan con el título y tengan en el sku NEWBATTERY
                        $productosFiltrados = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            return $nombreTelefono === $tituloProducto && strpos($producto['sku'], 'IMP') && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                        });
                        $productoEncontradoSinBateria = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, &$productoSinBateria, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            if ($nombreTelefono === $tituloProducto && strpos($producto['sku'], 'IMP') && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') == false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false)) {
                                $productoSinBateria = true;
                            }
                            return $nombreTelefono === $tituloProducto && strpos($producto['sku'], 'IMP') && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') == false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                        });
                    }
                    //obtener el precio de los tres estados con NEWBATTERY para ponerlo en el estado
                    $precioCOR1= collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                        if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                            // El nombre del teléfono
                            $nombreTelefono = trim($matches[1]);
                        } 
                        return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'COR') !== false || strpos($producto['sku'], 'STA') !== false) && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                    });
                    $precioBUE1= collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                        if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                            // El nombre del teléfono
                            $nombreTelefono = trim($matches[1]);
                        } 
                        return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'BUE') || strpos($producto['sku'], 'MBU')) && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                    });
                    $precioIMP1= collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                        if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                            // El nombre del teléfono
                            $nombreTelefono = trim($matches[1]);
                        } 
                        return $nombreTelefono === $tituloProducto && strpos($producto['sku'], 'IMP') && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                    });
                    //obtener los colores disponibles en una determinada capacidad
                        // Filtrar productos para obtener los que coinciden con el título del producto y ciertas condiciones de SKU
                        $coloresProducto = collect($productosColeccion)->filter(function ($producto) use ($tituloProducto, $productoCapacidad) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], $productoCapacidad) !== false)  && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false);
                        });
                        // Iterar sobre los productos filtrados para extraer los colores
                        $coloresProducto->each(function ($producto) use ($coloresUnicos) {
                            preg_match('/-\s*([a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+)\s*-\s*/', $producto['sku'], $matches);
                            if (isset($matches[1])) {
                                $colorUnico = $matches[1];
                                $colorUnico= rtrim($colorUnico);
                                $coloresUnicos->add($colorUnico);
                            }
                        });
                        // Convertir la colección en un array y eliminar duplicados
                        $coloresUnicosArray = $coloresUnicos->unique()->values()->toArray();
                    //
                    //obtener la capacidad segun el color
                        // Filtrar productos para obtener los que coinciden con el título del producto y ciertas condiciones de SKU
                        $capacidadesProducto = collect($productosColeccion)->filter(function ($producto) use ($tituloProducto, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], $productoColor) !== false)  && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false);
                        });
                        // Iterar sobre los productos filtrados para extraer las capacidades
                        $capacidadesProducto->each(function ($producto) use ($capacidadesUnicas) {
                            preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/', $producto['sku'], $matches);
                            if (isset($matches[2])) {
                                $capacidadUnico = $matches[2];
                                $capacidadUnico= rtrim($capacidadUnico);
                                $capacidadesUnicas->add($capacidadUnico);
                            }
                        });
                        // Convertir la colección en un array y eliminar duplicados
                        $capacidadesUnicasArray = $capacidadesUnicas->unique()->values()->toArray();
                    //
                }else{
                    if($estadoProducto === "estado_correcto"){
                        // Filtrar productos que coincidan con el título y NO tengan en el sku NEWBATTERY
                        $productosFiltrados = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad ,$productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            } 
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'COR') || strpos($producto['sku'], 'STA')) && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                        });
                        $productoEncontradoConBateria = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, &$productoConBateria, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            } 
                            if ($nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'COR') !== false || strpos($producto['sku'], 'STA') !== false) && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false)) {
                                $productoConBateria = true;
                            }
                        });
                    }elseif($estadoProducto === "estado_bueno"){
                        // Filtrar productos que coincidan con el título y NO tengan en el sku NEWBATTERY
                        $productosFiltrados = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            } 
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'BUE') || strpos($producto['sku'], 'MBU')) && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                        });
                        $productoEncontradoConBateria = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, &$productoConBateria, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            } 
                            if ($nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'BUE') || strpos($producto['sku'], 'MBU')) && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false)) {
                                $productoConBateria = true;
                            }
                        });
                    }elseif($estadoProducto === "estado_impecable"){
                        // Filtrar productos que coincidan con el título y NO tengan en el sku NEWBATTERY
                        $productosFiltrados = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            } 
                            return $nombreTelefono === $tituloProducto && strpos($producto['sku'], 'IMP') && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                        });
                        $productoEncontradoConBateria = collect($productosColeccion)->first(function ($producto) use ($tituloProducto, &$productoConBateria, $productoCapacidad, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            } 
                            if ($nombreTelefono === $tituloProducto && strpos($producto['sku'], 'IMP') && (strpos($producto['sku'], 'NEWBATTERY') !== false || strpos($producto['sku'], 'NEW BATTERY') !== false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false)) {
                                $productoConBateria = true;
                            }
                        });
                    }
                    //obtener el precio de los tres estados sin NEWBATTERY para ponerlo en el estado
                    $precioCOR1= collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) { 
                        if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                            // El nombre del teléfono
                            $nombreTelefono = trim($matches[1]);
                        } 
                        return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'COR') !== false || strpos($producto['sku'], 'STA') !== false) && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                    });
                    $precioBUE1= collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                        if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                            // El nombre del teléfono
                            $nombreTelefono = trim($matches[1]);
                        } 
                        return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], 'BUE') || strpos($producto['sku'], 'MBU')) && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                    });
                    $precioIMP1= collect($productosColeccion)->first(function ($producto) use ($tituloProducto, $productoCapacidad, $productoColor) {
                        if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                            // El nombre del teléfono
                            $nombreTelefono = trim($matches[1]);
                        } 
                        return $nombreTelefono === $tituloProducto && strpos($producto['sku'], 'IMP') && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false) && (strpos($producto['sku'], $productoCapacidad) !== false) && (strpos($producto['sku'], $productoColor) !== false);
                    });
                    //obtener los colores disponibles en una determinada capacidad
                        // Filtrar productos para obtener los que coinciden con el título del producto y ciertas condiciones de SKU
                        $coloresProducto = collect($productosColeccion)->filter(function ($producto) use ($tituloProducto, $productoCapacidad) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], $productoCapacidad) !== false)  && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false);
                        });
                        // Iterar sobre los productos filtrados para extraer los colores
                        $coloresProducto->each(function ($producto) use ($coloresUnicos) {
                            preg_match('/-\s*([a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+)\s*-\s*/', $producto['sku'], $matches);
                            if (isset($matches[1])) {
                                $colorUnico = $matches[1];
                                $colorUnico= rtrim($colorUnico);
                                $coloresUnicos->add($colorUnico);
                            }
                        });
                        // Convertir la colección en un array y eliminar duplicados
                        $coloresUnicosArray = $coloresUnicos->unique()->values()->toArray();
                    //
                    //obtener la capacidad segun el color
                        // Filtrar productos para obtener los que coinciden con el título del producto y ciertas condiciones de SKU
                        $capacidadesProducto = collect($productosColeccion)->filter(function ($producto) use ($tituloProducto, $productoColor) {
                            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches)) {
                                // El nombre del teléfono
                                $nombreTelefono = trim($matches[1]);
                            }
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], $productoColor) !== false) && (strpos($producto['sku'], 'NEWBATTERY') === false && strpos($producto['sku'], 'NEW BATTERY') === false);
                        });
                        // Iterar sobre los productos filtrados para extraer las capacidades
                        $capacidadesProducto->each(function ($producto) use ($capacidadesUnicas) {
                            preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/', $producto['sku'], $matches);
                            if (isset($matches[2])) {
                                $capacidadUnico = $matches[2];
                                $capacidadUnico= rtrim($capacidadUnico);
                                $capacidadesUnicas->add($capacidadUnico);
                            }
                        });
                        // Convertir la colección en un array y eliminar duplicados
                        $capacidadesUnicasArray = $capacidadesUnicas->unique()->values()->toArray();
                    //
                }
                //si existe el producto en los estados, se pone el precio si no se pone 0
                if(isset($precioCOR1)){
                    $precioCOR = $precioCOR1['min_price'];
                }else{
                    $precioCOR = 0;
                }
                if(isset($precioBUE1)){
                    $precioBUE = $precioBUE1['min_price'];
                }else{
                    $precioBUE = 0;
                }
                if(isset($precioIMP1)){
                    $precioIMP = $precioIMP1['min_price'];
                }else{
                    $precioIMP = 0;
                }

                //si productosFiltradosSku es null, si precioCOR==0 miramos el precioBUE y si no precioIMP y mostramos el primero que  encuentres en cualquiera de los tres estados
                if(!isset($productosFiltrados)){
                    if($precioCOR!=0){
                        $productosFiltrados = $precioCOR1;
                    }elseif($precioBUE!=0){
                        $productosFiltrados = $precioBUE1;
                    }elseif($precioIMP!=0){
                        $productosFiltrados = $precioIMP1;
                    }
                }

            $productBat='NoHay';
            //si hay productos con barteria mandar conBat y si hay productos sin bateria mandar sinBat
            if($estadoCheckbox=='true' && $productoConBateria ==false && $productoSinBateria ==true){
                $productBat = 'haySinBat';
            }
            if($estadoCheckbox=='false' && $productoSinBateria ===false && $productoConBateria ===true){
                $productBat = 'hayConBat';
            }
               
            if($productosFiltrados){
                $productosFiltradosPrecio = $productosFiltrados['min_price'];
            }else{
                $productosFiltradosPrecio = 0;
            }
            if($productosFiltrados){
                $productosFiltradosSku = $productosFiltrados['sku'];
            }else{
                $productosFiltradosSku = '';
            }

            //sacar el titulo del producto para mostrarlo
            $frase = $productosFiltradosSku;
            $patron = '/^(.*?Libre)/i'; // El patrón busca cualquier texto antes de "Libre"
            if (preg_match($patron, $frase, $matches)) {
                $parteExtraida = trim($matches[1]); // El texto extraído estará en $matches[1]
            } else {
                echo "No se encontró 'Libre' en la frase.";
            }

            //sacamos el nombre para cambiar la imagen del producto
            // Encuentra el nombre del teléfono, la capacidad y el resto de la frase
            if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $frase, $matches)) {
                // El nombre del teléfono
                $nombreTelefonoIMG = trim($matches[1]);
                // La capacidad
                $capacidadIMG = isset($matches[2]) ? $matches[2] : '';
                // El resto de la frase
                $restoFraseIMG = isset($matches[3]) ? trim($matches[3]) : '';
                //sacamos el color
                preg_match('/-\s*([^-\s]+(?:\s+[^-\s]+)*)\s*-\s*/', $restoFraseIMG, $matches);
                $colorIMG = isset($matches[1]) ? trim($matches[1]) : '';
                $descripcionProductoAPIimg = $capacidadIMG . ' ' . $restoFraseIMG;
            } else {
                echo 'No se pudo encontrar el nombre del teléfono y la capacidad en el título del producto.';
            }

            //sacamos el nombre para la imagen
            //quitamos los espacios
            preg_match('/(\S*\s?){2}/', $nombreTelefonoIMG, $matches);
            $nombreImagenAPI = isset($matches[0]) ? trim($matches[0]) : '';

            preg_match('/\s*-\s*[\p{L}\s]+?(?=\s*-)/', $restoFraseIMG, $matches);
            $nombreImagenAPI .= isset($matches[0]) ? trim($matches[0]) : $nombreImagenAPI;
            $nombreImagenAPI = str_replace(' ', '', $nombreImagenAPI);
            //la ponemos en minuscula
            $nombreImagenAPI = strtolower($nombreImagenAPI);

            //obtenemos el stock total del producto
            if($productosFiltrados){
                $stock_total=$productosFiltrados['quantity'];
            }else{
                $stock_total=0;
            }

            // Devolver los precios y estados de los productos filtrados como respuesta JSON
            return response()->json(['stock_total'=> $stock_total,'titulo1'=>$tituloProducto,'capacidadActivar'=>$capacidadesUnicasArray, 'colorActivar'=>$coloresUnicosArray, 'imput' => $estadoCheckbox, 'titulo' => $parteExtraida, 'sku' => $productosFiltradosSku, 'precio' => $productosFiltradosPrecio,
             'estado' => $estadoProducto, 'productoSinBateria' => $productoSinBateria, 'productoConBateria' => $productoConBateria,'precioCOR'=>$precioCOR,'precioBUE'=>$precioBUE,'precioIMP'=>$precioIMP,'productBat'=>$productBat, 'capacidad'=>$productoCapacidad, 'nombreIMG'=>$nombreImagenAPI]);

        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir durante la solicitud
            return response()->json(['noStock' => 'No hay productos','error' => $e->getMessage()], 500);
        }
    }

    public function peticionProductosBD(Request $request){
        try {
            // Definir una colección para almacenar los colores únicos y las capacidades unicas
            $coloresUnicos = new \Illuminate\Support\Collection();
            $capacidadesUnicas = new \Illuminate\Support\Collection();

            // Obtener todos los productos en línea
            $productosColeccion = $this->obtenerTodosEnLinea();

            // Obtener el título del producto de la solicitud
            $tituloProducto = $request->input('titulo_producto');
            $estadoCheckbox = $request->input('estadoCheckbox');
            $estadoProducto = $request->input('estadoProducto');
            $productoCapacidad = $request->input('productoCapacidad');
            $productoColor = $request->input('productoColor');
            $productoSinBateria= false;
            $productoConBateria= false;

                //si el checkbox esta marcado
                if($estadoCheckbox==='true'){
                    //comprobamos si existe el producto con NEWBATTERY
                    if($estadoProducto === "estado_correcto"){
                        //preg_match('/NEW\s*BATTERY/i', $producto->bateria)
                        // Filtrar productos que coincidan con el título, tengan NEWBATTERY, la capacidad seleccionada y el color
                        $productosFiltrados = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'COR')
                                  ->orWhere('estado', 'STA');
                        }) ->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();

                        $productoEncontradoSinBateria = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'COR')
                                  ->orWhere('estado', 'STA');
                        })->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')
                        ->where('stock', '>', 0)->first();

                        if ($productoEncontradoSinBateria) {
                            $productoSinBateria = true;
                        }
                    }elseif($estadoProducto === "estado_bueno"){
                        // Filtrar productos que coincidan con el título y tengan en el sku NEWBATTERY
                        $productosFiltrados = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'BUE')
                                  ->orWhere('estado', 'MBU');
                        }) ->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();

                        $productoEncontradoSinBateria = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'BUE')
                                  ->orWhere('estado', 'MBU');
                        })->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')
                        ->where('stock', '>', 0)->first();

                        if ($productoEncontradoSinBateria) {
                            $productoSinBateria = true;
                        }
                    }elseif($estadoProducto === "estado_impecable"){
                        // Filtrar productos que coincidan con el título y tengan en el sku NEWBATTERY
                        $productosFiltrados = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where('estado', 'IMP')->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();

                        $productoEncontradoSinBateria = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where('estado', 'IMP')->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')
                        ->where('stock', '>', 0)->first();

                        if ($productoEncontradoSinBateria) {
                            $productoSinBateria = true;
                        }
                    }
                    //obtener el precio de los tres estados con NEWBATTERY para ponerlo en el estado
                    $precioCOR1 = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'COR')
                                  ->orWhere('estado', 'STA');
                        }) ->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();
                    
                    $precioBUE1 = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'BUE')
                                  ->orWhere('estado', 'MBU');
                        }) ->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();

                    $precioIMP1 = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where('estado', 'IMP')->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();

                    //obtener los colores disponibles en una determinada capacidad
                        // Filtrar productos para obtener los que coinciden con el título del producto y ciertas condiciones
                        $coloresProducto = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)
                        ->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->get();

                        $coloresUnicos = collect();

                        // Iterar sobre los productos filtrados para extraer los colores
                        $coloresProducto->each(function ($producto) use ($coloresUnicos) {
                            preg_match('/\s*([a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+)/', $producto->color, $matches);
                            if (isset($matches[1])) {
                                $colorUnico= trim($matches[1]);
                                 // Reemplazar valores específicos
                                if (strtolower($colorUnico) === "product") {
                                    $colorUnico = "Rojo";
                                }
                                $coloresUnicos->add($colorUnico);
                            }
                        });
                        // Convertir la colección en un array y eliminar duplicados
                        $coloresUnicosArray = $coloresUnicos->unique()->values()->toArray();

                    //obtener la capacidad segun el color
                        // Filtrar productos para obtener los que coinciden con el título del producto y ciertas condiciones
                        $capacidadesProducto = Producto::where('nombre', $tituloProducto)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->get();

                        $coloresUnicos = collect();

                        // Iterar sobre los productos filtrados para extraer las capacidades
                        $capacidadesProducto->each(function ($producto) use ($capacidadesUnicas) {
                            preg_match('/^(\d+)\s*([MTG]B\b)$/i', $producto->capacidad, $matches);
                            if (isset($matches)) {
                                // Obtener la capacidad encontrada
                                $capacidadUnica = '';
                                if (strtoupper($matches[2]) === "GB") {
                                    $capacidadUnica = $matches[1] . "GB";
                                } else if (strtoupper($matches[2]) === "MB") {
                                    $capacidadUnica = $matches[1] . "MB";
                                } else if (strtoupper($matches[2]) === "TB") {
                                    $capacidadUnica = $matches[1] . "TB";
                                } else {
                                    // Si no es GB, MB ni TB, asumimos GB por defecto
                                    $capacidadUnica = $matches[1] . "GB";
                                }
                                $capacidadesUnicas->add(trim($capacidadUnica));
                            }
                        });
                        // Convertir la colección en un array y eliminar duplicados
                        $capacidadesUnicasArray = $capacidadesUnicas->unique()->values()->toArray();

                }else{
                    if($estadoProducto === "estado_correcto"){
                        // Filtrar productos que coincidan con el título y NO tengan en el sku NEWBATTERY
                        $productosFiltrados = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'COR')
                                  ->orWhere('estado', 'STA');
                        })->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')
                        ->where('stock', '>', 0)->first();
                        
                        
                        $productoEncontradoConBateria = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'COR')
                                  ->orWhere('estado', 'STA');
                        }) ->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();

                        if ($productoEncontradoConBateria) {
                            $productoConBateria = true;
                        }
                    }elseif($estadoProducto === "estado_bueno"){
                        // Filtrar productos que coincidan con el título y NO tengan en el sku NEWBATTERY
                        $productosFiltrados = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'BUE')
                                  ->orWhere('estado', 'MBU');
                        })->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')
                        ->where('stock', '>', 0)->first();

                        $productoEncontradoConBateria = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where(function ($query) {
                            $query->where('estado', 'BUE')
                                  ->orWhere('estado', 'MBU');
                        }) ->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();

                        if ($productoEncontradoConBateria) {
                            $productoConBateria = true;
                        }
                    }elseif($estadoProducto === "estado_impecable"){
                        // Filtrar productos que coincidan con el título y NO tengan en el sku NEWBATTERY
                        $productosFiltrados = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where('estado', 'IMP')->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')
                        ->where('stock', '>', 0)->first();

                        $productoEncontradoConBateria = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where('estado', 'IMP')->where(function ($query) {
                            $query->where('bateria', 'NEW BATTERY')
                                  ->orWhere('bateria', 'NEWBATTERY');
                        })->where('stock', '>', 0)->first();

                        if ($productoEncontradoConBateria) {
                            $productoConBateria = true;
                        }
                    }
                    //obtener el precio de los tres estados sin NEWBATTERY para ponerlo en el estado
                    $precioCOR1 = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                    ->where(function ($query) {
                        $query->where('estado', 'COR')
                              ->orWhere('estado', 'STA');
                    })->where('bateria', '!=', 'NEW BATTERY')
                    ->where('bateria', '!=', 'NEWBATTERY')
                    ->where('stock', '>', 0)->first();

                    $precioBUE1 = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                    ->where(function ($query) {
                        $query->where('estado', 'BUE')
                              ->orWhere('estado', 'MBU');
                    }) ->where('bateria', '!=', 'NEW BATTERY')
                    ->where('bateria', '!=', 'NEWBATTERY')
                    ->where('stock', '>', 0)->first();

                    $precioIMP1 = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)->where('color', $productoColor)
                        ->where('estado', 'IMP')->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')
                        ->where('stock', '>', 0)->first();

                    //obtener los colores disponibles en una determinada capacidad
                        // Filtrar productos para obtener los que coinciden con el título del producto y ciertas condiciones
                        $coloresProducto = Producto::where('nombre', $tituloProducto)->where('capacidad', $productoCapacidad)
                        ->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')->where('stock', '>', 0)->get();

                        $coloresUnicos = collect();

                        // Iterar sobre los productos filtrados para extraer los colores
                        $coloresProducto->each(function ($producto) use ($coloresUnicos) {
                            preg_match('/\s*([a-zA-ZáéíóúñÑÁÉÍÓÚ\s]+)/', $producto->color, $matches);
                            if (isset($matches[1])) {
                                $colorUnico= trim($matches[1]);
                                 // Reemplazar valores específicos
                                if (strtolower($colorUnico) === "product") {
                                    $colorUnico = "Rojo";
                                }
                                $coloresUnicos->add($colorUnico);
                            }
                        });
                        // Convertir la colección en un array y eliminar duplicados
                        $coloresUnicosArray = $coloresUnicos->unique()->values()->toArray();
                        
                    //obtener la capacidad segun el color
                        // Filtrar productos para obtener los que coinciden con el título del producto y ciertas condiciones de SKU
                        $capacidadesProducto = Producto::where('nombre', $tituloProducto)->where('color', $productoColor)
                        ->where('bateria', '!=', 'NEW BATTERY')
                        ->where('bateria', '!=', 'NEWBATTERY')->where('stock', '>', 0)->get();

                        $coloresUnicos = collect();

                        // Iterar sobre los productos filtrados para extraer las capacidades
                        $capacidadesProducto->each(function ($producto) use ($capacidadesUnicas) {
                            preg_match('/^(\d+)\s*([MTG]B\b)$/i', $producto->capacidad, $matches);
                            if (isset($matches)) {
                                // Obtener la capacidad encontrada
                                $capacidadUnica = '';
                                if (strtoupper($matches[2]) === "GB") {
                                    $capacidadUnica = $matches[1] . "GB";
                                } else if (strtoupper($matches[2]) === "MB") {
                                    $capacidadUnica = $matches[1] . "MB";
                                } else if (strtoupper($matches[2]) === "TB") {
                                    $capacidadUnica = $matches[1] . "TB";
                                } else {
                                    // Si no es GB, MB ni TB, asumimos GB por defecto
                                    $capacidadUnica = $matches[1] . "GB";
                                }
                                $capacidadesUnicas->add(trim($capacidadUnica));
                            }
                        });
                        // Convertir la colección en un array y eliminar duplicados
                        $capacidadesUnicasArray = $capacidadesUnicas->unique()->values()->toArray();
                    
                }

                //si existe el producto en los estados, se pone el precio si no se pone 0
                if(isset($precioCOR1)){
                    $precioCOR = $precioCOR1->precioD;
                }else{
                    $precioCOR = 0;
                }
                if(isset($precioBUE1)){
                    $precioBUE = $precioBUE1->precioD;
                }else{
                    $precioBUE = 0;
                }
                if(isset($precioIMP1)){
                    $precioIMP = $precioIMP1->precioD;
                }else{
                    $precioIMP = 0;
                }

                //si productosFiltrados es null, si precioCOR==0 miramos el precioBUE y si no precioIMP y mostramos el primero que  encuentres en cualquiera de los tres estados
                if(!isset($productosFiltrados)){
                    if($precioCOR!=0){
                        $productosFiltrados = $precioCOR1;
                    }elseif($precioBUE!=0){
                        $productosFiltrados = $precioBUE1;
                    }elseif($precioIMP!=0){
                        $productosFiltrados = $precioIMP1;
                    }
                }

            $productBat='NoHay';
            //si hay productos con barteria mandar conBat y si hay productos sin bateria mandar sinBat
            if($estadoCheckbox=='true' && $productoConBateria ==false && $productoSinBateria ==true){
                $productBat = 'haySinBat';
            }
            if($estadoCheckbox=='false' && $productoSinBateria ===false && $productoConBateria ===true){
                $productBat = 'hayConBat';
            }
               
            if($productosFiltrados){
                $productosFiltradosPrecio = $productosFiltrados->precioD;
            }else{
                $productosFiltradosPrecio = 0;
            }
            if($productosFiltrados){
                //sacamos el sku
                $productosFiltradosSku = $productosFiltrados->nombre.' '.$productosFiltrados->capacidad.' - '.$productosFiltrados->color.($productosFiltrados->libre ? ' - Libre ' : '').($productosFiltrados->bateria ? ' '.$productosFiltrados->bateria.' ' : '').$productosFiltrados->estado;
                //sacamos el titulo
                $titulo_producto= $productosFiltrados->nombre.' '.$productosFiltrados->capacidad.' '.$productosFiltrados->color.($productosFiltrados->libre ? ' - Libre' : '');
                
                //sacamos el nombre de la imagen
                $nombre= $productosFiltrados->nombre;
                            
                $patron = '/iphone \d+/i';
                if (preg_match($patron, $nombre, $matches)) {
                    $nombre= $matches[0];
                }
                $nombreImagen= str_replace(' ','',$nombre.'-'.$productosFiltrados->color);
                //la ponemos en minuscula
                $nombreImagen= strtolower($nombreImagen);
            }else{
                $productosFiltradosSku = '';
                $titulo_producto='';
                $nombreImagen='';
            }

            //obtenemos el stock total del producto
            if($productosFiltrados){
                $stock_total=$productosFiltrados->stock;
            }else{
                $stock_total=0;
            }

            // Devolver los precios y estados de los productos filtrados como respuesta JSON
            return response()->json(['stock_total'=> $stock_total,'titulo1'=>$tituloProducto,'capacidadActivar'=>$capacidadesUnicasArray, 'colorActivar'=>$coloresUnicosArray, 'imput' => $estadoCheckbox, 'titulo' => $titulo_producto, 'sku' => $productosFiltradosSku, 'precio' => $productosFiltradosPrecio,
             'estado' => $estadoProducto, 'productoSinBateria' => $productoSinBateria, 'productoConBateria' => $productoConBateria,'precioCOR'=>$precioCOR,'precioBUE'=>$precioBUE,'precioIMP'=>$precioIMP,'productBat'=>$productBat, 'capacidad'=>$productoCapacidad, 'nombreIMG'=>$nombreImagen]);

        } catch (\Exception $e) {
            // Verificar si el checkbox está activado
            $checkboxActivado = $request->input('estadoCheckbox') === 'true';

            // Si el checkbox está activado, intentar nuevamente la solicitud o realizar las comprobaciones nuevamente
            if ($checkboxActivado) {
               return $this->peticionProductosBD($request->merge(['estadoCheckbox' => 'false']));
            } else {
               return $this->peticionProductosBD($request->merge(['estadoCheckbox' => 'true']));
            }

            // Manejar cualquier excepción que pueda ocurrir durante la solicitud
            return response()->json(['noStock' => 'No hay productos','error' => $e->getMessage()], 500);
        }
    }

    //parte carrito
    public function mostrarUnProducto($sku){
        try {           
            // Hacer una solicitud a la API de BackMarket para obtener detalles del producto
            $producto = $this->backMarketApi->apiGet("listings/detail?sku=".$sku);
            // Devolver los detalles del producto
            return $producto;
           
        } catch (\Exception $e) {
            // Manejar errores y devolver una respuesta vacía o un mensaje de error según corresponda
            return  null;
        }
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
    //Actualizar tolos los productos en la BD
    public function actualizarProductosBD(){
        try {
            $productosPorPaginas = $this->productosPorPaginas;
            $Totalpaginas=1;
            $allListings=[];
            for ($x = 1; $x <= $Totalpaginas; $x++){
                // Hacer una solicitud a la API de BackMarket para obtener detalles del producto
                //$listing = $this->backMarketApi->apiGet('listings_bi/?page='.$x.'&page-size='.$productosPorPaginas);
                $listing = $this->backMarketApi->apiGet('listings/?page='.$x.'&page-size='.$productosPorPaginas);
                $Totalpaginas = ceil($listing['count'] / $productosPorPaginas);
                $allListings = array_merge($allListings, $listing['results']);
            }
            
            // Guardar los productos en la base de datos
            foreach ($allListings as $productoData) {
                // Obtener el SKU del producto desde el detalle
                $sku = $productoData['sku'];

                // Separar el SKU en partes (nombre, capacidad, color, libre, batería, estado)
                $partesSku = $this->separarSku($sku);

                if (!empty($partesSku)) {
                    if (strpos($partesSku['nombre'], 'iPhone') !== 0) {
                        continue; // Si el nombre no comienza por "iPhone", omitir el producto y continuar con el siguiente
                    }
                    try {
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
                        
                        Producto::updateOrCreate(
                            [
                                'nombre' => $nombre,
                                'capacidad' => $capacidad,
                                'color' => $color,
                                'libre' => $libre,
                                'bateria' => $bateria,
                                'estado' => $estado,
                            ],
                            [
                                // Este son los valores del stock que deseas establecer
                                'descripcion' => $productoData['comment'],
                                'precioA' => $productoData['min_price'],
                                'precioD' => $productoData['min_price'],
                                'stock' => $productoData['quantity']
                            ]
                        );
                    }  catch (\Exception $e) {
                    }
                }
            }
            $response = [
                'productosAPI' => $allListings,
                'Totalproductos' => $listing['count'],
                'productosPorPaginas' => $productosPorPaginas
            ];
            // Devolver la respuesta JSON
            // return response()->json($response);
            return redirect()->route('admin.dashboard')->with('success','Base de datos actualizada');
           
        } catch (\Exception $e) {
            // Manejar errores y devolver una respuesta vacía o un mensaje de error según corresponda
            //return response()->json(['error' => $e->getMessage()], 500);
            return redirect()->route('admin.dashboard')->with('error','Error al actualizar la base de datos' . $e->getMessage());
        }
    }
    //Actualizar los productos en línea (No se utiliza)
    public function actualizarProductosEnLineaBD(){
        try {
            $productosPorPaginas = $this->productosPorPaginas;
            $Totalpaginas=1;
            $allListings=[];
            for ($x = 1; $x <= $Totalpaginas; $x++){
                // Hacer una solicitud a la API de BackMarket para obtener detalles del producto
                //$listing = $this->backMarketApi->apiGet('listings_bi/?page='.$x.'&page-size='.$productosPorPaginas);
                $listing = $this->backMarketApi->apiGet('listings/?publication_state=2&page='.$x.'&page-size='.$productosPorPaginas);
                $Totalpaginas = ceil($listing['count'] / $productosPorPaginas);
                $allListings = array_merge($allListings, $listing['results']);
            }
            
            // Guardar los productos en la base de datos
            foreach ($allListings as $productoData) {
                // Obtener el SKU del producto desde el detalle
                $sku = $productoData['sku'];

                // Separar el SKU en partes (nombre, capacidad, color, libre, batería, estado)
                $partesSku = $this->separarSku($sku);

                if (!empty($partesSku)) {
                    if (strpos($partesSku['nombre'], 'iPhone') !== 0) {
                        continue; // Si el nombre no comienza por "iPhone", omitir el producto y continuar con el siguiente
                    }
                    try {
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
                        if(isset($productoData['min_price'])&& $productoData['min_price']!=0){
                            $precio= $productoData['min_price'];
                        }else{
                            $precio = 0;
                        }
                        Producto::updateOrCreate(
                            [
                                'nombre' => $nombre,
                                'capacidad' => $capacidad,
                                'descripcion' => $productoData['comment'],
                                'precioA' => $precio,
                                'precioD' => $precio,
                                'color' => $color,
                                'libre' => $libre,
                                'bateria' => $bateria,
                                'estado' => $estado,
                            ],
                            [
                                'stock' => $productoData['quantity'] // Este es el valor del stock que deseas establecer
                            ]
                        );
                    }  catch (\Exception $e) {
                    }
                }
            }
            $response = [
                'productosAPI' => $allListings,
                'Totalproductos' => $listing['count'],
                'productosPorPaginas' => $productosPorPaginas
            ];
            // Devolver la respuesta JSON
           // return response()->json($response);
            return redirect()->route('admin.dashboard')->with('success','Base de datos actualizada');
           
        } catch (\Exception $e) {
            // Manejar errores y devolver una respuesta vacía o un mensaje de error según corresponda
            //return response()->json(['error' => $e->getMessage()], 500);
            return redirect()->route('admin.dashboard')->with('error','Error al actualizar la base de datos' . $e->getMessage());
        }
    }
    
    // Actualizar stock en back market
    public function actualizarStockBM($sku,$cantidad){
        

        // Hacer una solicitud a la API de Back Market para obtener detalles del producto en el que se incluye el stock
        $producto = $this->mostrarUnProducto($sku);
        // Endpoint para actualizar el stock de un producto en Back Market
        $end_point = "listings/".$producto['listing_id'];

        if (isset($producto['quantity'])) {
            $cantidadFinal=$producto['quantity']-$cantidad;

            // Datos a enviar en la solicitud POST
            $request = [
                "quantity" => $cantidadFinal,
            ];
            try {
                // Realizar la solicitud POST a la API de Back Market para actualizar el stock
                $this->backMarketApi->apiPost($end_point, $request);
                
                // Devolver un mensaje de éxito si la actualización del stock fue exitosa
                return 'Stock actualizado correctamente en Back Market.';
                //return redirect()->route('products')->with('success','Stock actualizado correctamente en Back Market.');
            } catch (\Exception $e) {
                // Capturar y manejar cualquier excepción que ocurra durante la actualización del stock
                return 'Error al actualizar el stock en Back Market: ' . $e->getMessage();
            }
        }else{
            return 'Error: No se pudo obtener la cantidad del producto desde Back Market.';
        }
    }

    // Obtener pedidos de la base de datos
    public function obtenerPedidos($vista){
        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Verificar si el usuario está autenticado
        if($user){
            switch ($vista){
                case 'Recibidos':
                    // Obtener todos los pedidos
                    $pedidos= Pedido::with(['usuario','direccionEnvio','direccionFacturacion'])->get();
                    break;
                case 'Pendientes':
                    // Obtener los pedidos pendientes
                    $pedidos = Pedido::with('usuario')->where('enviado', 'pendiente')->get();
                    break;
                case 'Aceptados':
                    // Obtener los pedidos enviados
                    $pedidos = Pedido::with('usuario')->where('enviado', 'aceptado')->get();
                    break;
                case 'Procesados':
                    // Obtener los pedidos procesados
                    $pedidos = Pedido::with('usuario')->where('enviado', 'procesado')->get();
                    break;
                case 'Rechazados':
                    // Obtener los pedidos rechazados
                    $pedidos = Pedido::with('usuario')->where('enviado','rechazado')->get();
                    break;
                default:
                    // Si no se especifica
                    $pedidos =  Pedido::with('usuario')->get();
                    break;
            } 
           
            // Devolver los pedidos obtenidos
            return view('admin.verPedidos', ['pedidos' => $pedidos, 'estado'=>$vista]);
        } else {
            // Si el usuario no está autenticado, devolver un mensaje de error o manejarlo según sea necesario
            return "Usuario no autenticado";
        }
    }

    // Método para actualizar el estado del pedido
    public function actualizarestadopedido(Request $request) {
        // Obtén el ID del pedido y el nuevo estado del pedido desde la solicitud
        $pedidoId = $request->input('pedido_id');
        $nuevoEstado = $request->input('nuevo_estado');
        
        // Actualizar el pedido en la base de datos
        Pedido::where('id', $pedidoId)->update(['enviado' => $nuevoEstado]);
    
        // Retorna una respuesta apropiada (puedes retornar JSON si lo deseas)
        return response()->json(['success' => true]);
    }

    // Método para obtener los datos del cliente
    public function obtenerDatosCliente(Request $request) {
        // Obtén el ID del pedido y el nuevo estado del pedido desde la solicitud
        $pedido= Pedido::find($request->input('pedido_id'));
        $user= User::find($pedido->usuario_id);

        // Obtén el ID del producto asociado al pedido desde la tabla pivot
        $productoId = $pedido->productos()->first()->id;
        // Busca el producto asociado al pedido
        $producto = Producto::find($productoId);
        
        // Obtener el nombre, apellidos, gmail, telefono de la tabla usuario
        $name = $user->name ?? '';
        $lastname = $user->lastname ?? '';
        $email= $user->email ?? '';
        $telefono= $user->phone ?? '';

        //obtener pais, direccion_1,direccion_2,ciudad,cp,empresa,telefono de la tabla envio
        //accedemos a la funcion direccion envio del modelo user.php
        $direccionEnvio = $user->direccionEnvio;
        $E_pais= $direccionEnvio->pais ?? '';
        $E_direccion_1= $direccionEnvio->direccion_1 ?? '';
        $E_direccion_2= $direccionEnvio->direccion_2 ?? '';
        $E_ciudad= $direccionEnvio->ciudad ?? '';
        $E_codigo_postal= $direccionEnvio->codigo_postal ?? '';
        $E_empresa= $direccionEnvio->empresa ?? '';
        $E_telefono= $direccionEnvio->telefono ?? '';

        //obtener pais, direccion_1,direccion_2,ciudad,cp,empresa,nif_dni de la tabla facturacion
        //accedemos a la funcion direccion envio del modelo user.php
        $direccionFacturacion = $user->direccionFacturacion;
        // Obtener la dirección_1 del usuario autenticado, si existe
        $F_direccion_1 = $direccionFacturacion ? $direccionFacturacion->direccion_1 : '';
        $F_direccion_2 = $direccionFacturacion ? $direccionFacturacion->direccion_2 : '';
        $F_pais = $direccionFacturacion ? $direccionFacturacion->pais : '';
        $F_ciudad = $direccionFacturacion ? $direccionFacturacion->ciudad : '';
        $F_codigo_postal = $direccionFacturacion ? $direccionFacturacion->codigo_postal : '';
        $F_empresa = $direccionFacturacion ? $direccionFacturacion->empresa : '';
        $F_nif_dni = $direccionFacturacion ? $direccionFacturacion->nif_dni : '';
        
        //Obtenemos los datos del producto
        $idPedido = $pedido->id;
        $skuProducto = $producto->nombre." ". $producto->capacidad." - ".$producto->color." - ".($producto->libre ? 'Libre ':'').($producto->bateria ? $producto->bateria:'')." ".$producto->estado;
        $articulo = $producto->nombre." ". $producto->capacidad." - ".$producto->color." - ".($producto->libre ? 'Libre ':'');
        $grado = $producto->estado;
        // Accede a las unidades del producto desde la relación pivot
        $cantidadProducto = $pedido->productos()->where('producto_id', $producto->id)->first()->pivot->unidades;
        $precioProducto = $producto->precioD;

        // Retorna una respuesta apropiada (puedes retornar JSON si lo deseas)
        return response()->json(['nombre'=> $name, 'apellido'=>$lastname,'email'=>$email,'telefono'=>$telefono,
            'E_pais'=>$E_pais,'E_direccion_1'=>$E_direccion_1,'E_direccion_2'=>$E_direccion_2,'E_ciudad'=>$E_ciudad,
            'E_codigo_postal'=>$E_codigo_postal,'E_empresa'=>$E_empresa,'E_telefono'=>$E_telefono,
            'F_direccion_1'=>$F_direccion_1,'F_direccion_2'=>$F_direccion_2,'F_pais'=>$F_pais,'F_ciudad'=>$F_ciudad,
            'F_codigo_postal'=>$F_codigo_postal,'F_empresa'=>$F_empresa,'F_nif_dni'=>$F_nif_dni,
            'idPedido'=>$idPedido,'skuProducto'=>$skuProducto,'articulo'=>$articulo,'grado'=>$grado,
            'cantidadProducto'=>$cantidadProducto,'precioProducto'=>$precioProducto]);
    }



}




