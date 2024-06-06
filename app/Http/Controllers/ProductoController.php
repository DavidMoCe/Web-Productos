<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller{

    // Función principal que puede ser llamada con o sin parámetros
    public function productos($listings = null, $productosPorPaginas = 50,$totalProductos = null){
        $productos = Producto::all(); // Obtener todos los productos de la base de datos
        
       $productos = null;//----------------------------------------------------------------

        // Verificar si tanto $productos como $listings son distintos de null
        if (!is_null($productos) && !is_null($listings)) {
            // Si ambos están disponibles, mostrar tanto los productos como el listado
            return view('producto.products', ['productos' => $productos, 'productosAPI' => collect($listings),'Totalproductos' => $totalProductos,'productosPorPaginas' => $productosPorPaginas]);
        } elseif (!is_null($productos)) {
            // Si solo hay productos en la base de datos, mostrarlos
            return view('producto.products', ['productos' => $productos]);
        } elseif (!is_null($listings)) {
            // Si solo hay un listado, mostrarlo
            return view('producto.products', ['productosAPI' =>  collect($listings), 'Totalproductos' => $totalProductos, 'productosPorPaginas' => $productosPorPaginas]);
        } else {
            // Si no hay ni productos ni listado, retornar una vista vacía o manejar el caso según tu necesidad
            // Aquí, se devuelve una vista sin datos
            return view('producto.products');
        }
    }
}
