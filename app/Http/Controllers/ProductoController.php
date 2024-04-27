<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function productos($listings)
    {
        $productos = Producto::all(); // Obtener todos los productos de la base de datos
        // return view('producto.products', ['productos' => $productos]); // Pasar los productos a la vista
        return view('producto.products', ['productos' => $productos,'listado'=>$listings]); // Pasar los productos a la vista
    }
}
