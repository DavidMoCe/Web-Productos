<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductoController;//para obtener productos de la bd
use App\Http\Controllers\BackMarketController;//para utilizar la api
use App\Http\Middleware\AdminMiddleware;//para limitar el acceso del usuario
use App\Http\Controllers\CartController;//para manejar las funciones del carrito
use Illuminate\Http\Request;

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
Route::get('/info_products',[BackMarketController::class, 'OntenerUnProducto'])->name('info_products');
//ruta para las peticiones de la ppagina dellates del producto
Route::post('/peticionProductos',[BackMarketController::class,'peticionProductos'])->name('peticionProductos');

//mostrar carrito de la compra
// Route::get('/carrito', function (){
//     return view('carrito.cart');
// })->name('carrito');

//carrito
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{sku}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{sku}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/order/shipping-address', [CartController::class, 'addAddress'])->middleware(['auth'])->name('order-address');
Route::post('/cart/processOrder', [CartController::class, 'processOrder'])->middleware(['auth'])->name('cart.processOrder');



//Actualizar productos en la BD
Route::get('/actualizarProductosBD',[BackMarketController::class,'actualizarProductosBD'])->name('actualizarProductos');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


                                        