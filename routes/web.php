<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductoController;//para obtener productos de la bd
use App\Http\Controllers\BackMarketController;//para utilizar la api
use App\Http\Middleware\AdminMiddleware;



// Route::get('/', function () {
//     return view('welcome');
// });

//mostrar la pagina por defecto (es la pagina de productos)
Route::get('/', [BackMarketController::class, 'mostrarProductos']);

//mostrar la pagina de dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', AdminMiddleware::class])->name('dashboard');

//redireccion que utiliza el controlador LoginController si el usuario se identifica
Route::get('/redirect',[LoginController::class,'redirect'])->name('redireccionar-panel')->middleware('auth');

// mostrar pagina de moviles, modelos y editar Stock-inventario
Route::get('/admin',[BackMarketController::class, 'obtenerMoviles'])->middleware(['auth', 'verified', AdminMiddleware::class])->name('admin.dashboard');

Route::get('/modelo',[BackMarketController::class, 'obtenerModelos'])->middleware(['auth','verified', AdminMiddleware::class])->name('admin.modelo');

Route::get('/stock',[BackMarketController::class, 'obtenerStock'])->middleware(['auth','verified', AdminMiddleware::class])->name('admin.stock');

// mostrar pagina de productos
Route::get('/products',[BackMarketController::class, 'mostrarProductos'])->name('products');

// mostrar pagina detalles del producto
Route::get('/info-products', function () {
    return view('producto.info-products');
})->name('info-products');

//mostrar carrito de la compra
Route::get('/cart', function (){
    return view('carrito.cart');
})->name('cart');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
