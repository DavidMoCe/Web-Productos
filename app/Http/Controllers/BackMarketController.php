<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BackMarketApi;

class BackMarketController extends Controller
{
    protected $backMarketApi;

    // Constructor del controlador
    public function __construct(BackMarketApi $backMarketApi)
    {
        $this->backMarketApi = $backMarketApi;
    }

    // Método para obtener los listados de Back Market
    public function mostrarCategorias()
    {
        try {
            // Utiliza el método apiget() de BackMarketApi para obtener los listados
            $listings = $this->backMarketApi->apiGet('/listings/detail?sku=iPhone+14+Plus+128GB+-+Blanco+Estrella+-+Libre+NEWBATTERY+COR');
            
            // Devuelve los listados en una respuesta JSON

            // echo  'Producto = '.$listings['sku'];
            // echo  'Precio = '.$listings['price']."\n";

            // return response()->json($listings);

            // Llama al método productos() del ProductoController y pasa los listados como argumento
            return app('App\Http\Controllers\ProductoController')->productos($listings);


        } catch (\Exception $e) {
            // Maneja cualquier excepción que pueda ocurrir durante la solicitud
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Otros métodos para manejar otras operaciones con la API de Back Market...
}