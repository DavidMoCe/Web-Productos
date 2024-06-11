<?php

use App\BackMarketApi;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductoController;//para obtener productos de la bd
use App\Http\Controllers\BackMarketController;//para utilizar la api
use App\Http\Middleware\AdminMiddleware;//para limitar el acceso del usuario
use App\Http\Controllers\CartController;//para manejar las funciones del carrito
use App\Http\Controllers\PaymentController;
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
Route::get('/pedidos',[BackMarketController::class, 'obtenerPedidoPendiente'])->middleware(['auth','verified', AdminMiddleware::class])->name('verPedidos');
Route::post('/actualizar-estado-pedido', [BackMarketController::class, 'actualizarEstadoPedido'])->name('actualizarEstadoPedido');

// mostrar pagina de productos
Route::get('/products',[BackMarketController::class, 'mostrarProductos'])->name('products');

// mostrar pagina detalles del producto
Route::get('/info_products',[BackMarketController::class, 'ObtenerUnProducto'])->name('info_products');
//ruta para las peticiones de la ppagina dellates del producto
Route::post('/peticionProductos',[BackMarketController::class,'peticionProductos'])->name('peticionProductos');

//carrito
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{sku}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{sku}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/order/confirm-address', function (){
    return view('orders.confirm-address');
})->name('confirm-address');

Route::match(['get','post'],'/order/confirmation-address',[CartController::class, 'checkAddress'])->middleware(['auth'])->name('confirm-address');
Route::match(['get','post'],'/order/shipping-address', [CartController::class, 'addAddress_shipping'])->middleware(['auth'])->name('shipping-address');
Route::match(['get','post'],'/order/billing-address', [CartController::class, 'submitShippingAddressForm'])->middleware(['auth'])->name('billing-address');
Route::match(['get','post'],'/order/payment', [CartController::class, 'submitBillingAddressForm'])->middleware(['auth'])->name('payment');
Route::post('/order/processOrder', [PaymentController::class, 'processOrder'])->middleware(['auth'])->name('cart.processOrder');
Route::get('/payment/status', [PaymentController::class, 'payPalStatus'])->name('payment.status');

Route::post('/order/confirm-payment', function (){
    return view('orders.payment');
})->middleware(['auth'])->name('payment_direct');

Route::get('/order/confirmation', function (){
         return view('orders.confirmation');
    })->name('confirmation');

//Actualizar productos en la BD
Route::get('/actualizarProductosBD',[BackMarketController::class,'actualizarProductosBD'])->name('actualizarProductos');

//Obtener listado de pedidos
Route::get('/listadoPedidos',[ProfileController::class, 'obtenerPedidoBD'])->name('listadoPedidos');
// Route::get('/listadoPedidos',[BackMarketApi::class,'getAllOrders'])->name('listadoPedidos');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


                                        