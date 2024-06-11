<?php

namespace App\Console\Commands;

use App\BackMarketApi;
use App\Http\Controllers\BackMarketController;
use App\Models\Producto;
use Illuminate\Console\Command;

class ActualizarProductosEnLinea extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ActualizarProductosEnLinea';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * 
     */
    protected $backMarketApi;
    Protected $productosPorPaginas= 50;
    // Constructor del controlador
    public function __construct(){
        $this->backMarketApi = app(BackMarketApi::class);
        parent::__construct();
    }

    public function handle()
    {
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
                        
                        Producto::updateOrCreate(
                            [
                                'nombre' => $nombre,
                                'capacidad' => $capacidad,
                                'descripcion' => $productoData['comment'],
                                'precioA' => $productoData['min_price'],
                                'precioD' => $productoData['min_price'],
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
            
           
        } catch (\Exception $e) {
        }
    }

    private function separarSku($sku)
    {
        $controller = app(BackMarketController::class);
        return $controller->separarSku($sku); // Llama a la función del controlador
    }
}
