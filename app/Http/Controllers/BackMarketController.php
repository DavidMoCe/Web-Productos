<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BackMarketApi;

class BackMarketController extends Controller{
    protected $backMarketApi;
    Protected $productosPorPaginas= 50;
    // Constructor del controlador
    public function __construct(BackMarketApi $backMarketApi)
    {
        $this->backMarketApi = $backMarketApi;
    }

    // Método para obtener los productos en linea de Back Market
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

    // Método para obtener todos los productos en linea de Back Market
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
                return strtolower($tipoProducto) === strtolower($modelo);;
            } else {
                // Si no coincide, devolver falso
                return false;
            }
        });

        return view('admin.stock',['productos' => $productosFiltrados]);
    }

    // Otros métodos para manejar otras operaciones con la API de Back Market...
}
