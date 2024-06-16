<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class ProductoController extends Controller{

    // Función principal que puede ser llamada con o sin parámetros
    public function productos($listings = null, $productosPorPaginas,$totalProductos = null){
        $productos = Producto::where('stock', '>', 0)->get(); // Obtener todos los productos con stock mayor que 0
        
        $productosPorPaginas=10;

        $numeroGrupos= $productos->filter(function($producto) {
            return $producto->stock > 0;
        })->groupBy(function($producto){
            return $producto->nombre . '|' . $producto->color;
        });

        // Convertir la colección de grupos a un array
        $gruposArray = $numeroGrupos->values()->toArray();

        // Contar el número de grupos
        $totalGrupos = count($gruposArray);

        // Obtener la página actual desde la petición
        $paginaActual = Paginator::resolveCurrentPage();

        // Calcular el índice de inicio
        $offset = ($paginaActual - 1) * $productosPorPaginas;

        // Crear una instancia de LengthAwarePaginator
        $gruposPaginados = new LengthAwarePaginator(
            array_slice($gruposArray, $offset, $productosPorPaginas), // Items para la página actual
            $totalGrupos, // Total de items
            $productosPorPaginas, // Items por página
            $paginaActual, // Página actual
            ['path' => request()->url(), 'query' => request()->query()] // Parámetros adicionales
        );


       $listings = null;//----------------------------------------------------------------

        // Verificar si tanto $productos como $listings son distintos de null
        if (!is_null($productos) && !is_null($listings)) {
            // Si ambos están disponibles, mostrar tanto los productos como el listado
            return view('producto.products', ['productos' => $productos, 'productosAPI' => collect($listings),'Totalproductos' => $totalProductos,'productosPorPaginas' => $productosPorPaginas]);
        } elseif (!is_null($productos)) {
            // Si solo hay productos en la base de datos, mostrarlos
            return view('producto.products', ['productos' => $productos, 'Totalproductos' => $totalGrupos,'productosPorPaginas' => $productosPorPaginas,'gruposPaginados' => $gruposPaginados]);
        } elseif (!is_null($listings)) {
            // Si solo hay un listado, mostrarlo
            return view('producto.products', ['productosAPI' =>  collect($listings), 'Totalproductos' => $totalProductos, 'productosPorPaginas' => $productosPorPaginas]);
        } else {
            // Si no hay ni productos ni listado, retornar una vista vacía o manejar el caso según tu necesidad
            return view('producto.products');
        }
    }
}
