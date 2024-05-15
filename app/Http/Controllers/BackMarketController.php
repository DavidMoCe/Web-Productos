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
        $scroll=false;
        //print_r($productoSeleccionado);

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

    public function peticionBateria(Request $request){
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

            do{

            
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
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], $productoCapacidad) !== false);
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
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], $productoColor) !== false);
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
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], $productoCapacidad) !== false);
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
                            return $nombreTelefono === $tituloProducto && (strpos($producto['sku'], $productoColor) !== false);
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
                    $precioCOR = $precioCOR1['price'];
                }else{
                    $precioCOR = 0;
                }
                if(isset($precioBUE1)){
                    $precioBUE = $precioBUE1['price'];
                }else{
                    $precioBUE = 0;
                }
                if(isset($precioIMP1)){
                    $precioIMP = $precioIMP1['price'];
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

            }while(!$productosFiltrados);//Esto es una prueba

            $productBat='NoHay';
            //si hay productos con barteria mandar conBat y si hay productos sin bateria mandar sinBat
            if($estadoCheckbox=='true' && $productoConBateria ==false && $productoSinBateria ==true){
                $productBat = 'haySinBat';
            }
            if($estadoCheckbox=='false' && $productoSinBateria ===false && $productoConBateria ===true){
                $productBat = 'hayConBat';
            }
            
            
            if($productosFiltrados){
                $productosFiltradosPrecio = $productosFiltrados['price'];
            }else{
                $productosFiltradosPrecio = 0;
            }
            if($productosFiltrados){
                $productosFiltradosSku = $productosFiltrados['sku'];
            }else{
                $productosFiltradosSku = '';
            }


            //SI NO EXISTE NINGUN PRODUCTO, ACTIVAR EL CHECKBOX DE LA BATERIA Y COMPPROBAR DE NUEVO





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

            // Devolver los precios y estados de los productos filtrados como respuesta JSON
            return response()->json(['titulo1'=>$tituloProducto,'capacidadActivar'=>$capacidadesUnicasArray, 'colorActivar'=>$coloresUnicosArray, 'imput' => $estadoCheckbox, 'titulo' => $parteExtraida, 'sku' => $productosFiltradosSku, 'precio' => $productosFiltradosPrecio,
             'estado' => $estadoProducto, 'productoSinBateria' => $productoSinBateria, 'productoConBateria' => $productoConBateria,'precioCOR'=>$precioCOR,'precioBUE'=>$precioBUE,'precioIMP'=>$precioIMP,'productBat'=>$productBat, 'capacidad'=>$productoCapacidad, 'nombreIMG'=>$nombreImagenAPI]);

        } catch (\Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir durante la solicitud
            return response()->json(['noStock' => 'No hay productos','error' => $e->getMessage()], 500);
        }
    }
}




