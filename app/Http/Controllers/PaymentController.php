<?php

namespace App\Http\Controllers;

use App\BackMarketApi;
use App\Mail\OrderProcessed;
use App\Models\Carrito;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller{
    protected $backMarketController;
    // Constructor para inyectar la instancia de BackMarketApi y BackMarketController
    public function __construct(BackMarketController $backMarketController){
        $this->backMarketController = $backMarketController;
    }

    private function getPayPalAccessToken(){
        // Obtener las credenciales de PayPal del archivo .env
        $clientId = config('services.paypal.client_id');
        $secret = config('services.paypal.secret');

        // Codificar las credenciales en Base64
        $credentials = base64_encode("$clientId:$secret");
        
        // Crear un cliente Guzzle
        $client = new \GuzzleHttp\Client();
    
        // Realizar la solicitud para obtener el token de acceso
        $response = $client->post('https://api-m.paypal.com/v1/oauth2/token', [
            'headers' => [
                'Authorization' => 'Basic ' . $credentials, // Aquí se incluye la codificación Base64 en el encabezado de autorización
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);
    
        // Verificar si la solicitud fue exitosa y obtener el token de acceso
        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            return [
                'access_token' => $data['access_token'],
                'expires_in' => $data['expires_in'], // Tiempo de validez del token en segundos
            ];
        } else {
            // Manejar errores si la solicitud no fue exitosa
            return null;
        }
    }

    public function borrarCarrito($metodoPago){
        $user = Auth::user();
        $userId = $user->id;
        $envioId = $user->direccionEnvio->id;
        $facturacionId = $user->direccionFacturacion->id;
        $metodoPago = $metodoPago;
        $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
        // Verifica si el carrito es null antes de intentar eliminarlo
        if ($carrito) {
            switch ($metodoPago) {
                case 'credit_card':
                    $metodoPago="Tarjeta de crédito";
                    break;
                case 'paypal':
                    $metodoPago="Paypal";
                    break;
                case 'recogida_Tienda':
                    $metodoPago="Recogida en tienda";
                    break;
                case 'contra_reembolso':
                    $metodoPago="Contra Reembolso";
                    break;
                default:
                    $metodoPago="";
                    break;
            }

            //Crear el pedido MOVERLO A LA FFUNCION BORRAR CARRITO
            $pedido = Pedido::create([
                'usuario_id' => $userId,
                'envio_id' => $envioId,
                'facturacion_id' => $facturacionId,
                'metodoPago' => $metodoPago,
            ]);
            $total = 0;
            if ($carrito && $carrito->productos) {
                foreach ($carrito->productos as $producto) {
                    //vinculamos cada producto a la tabla de pedidos guardando los datos en la tabla pivot
                    $pedido->productos()->attach($producto->id, ['unidades' => $producto->pivot->unidades]);
                    //sumamos el precio de cada unidad
                    $total += $producto->precioD * $producto->pivot->unidades;
                }
            }

            $orderDetails = [
                'order_number' => $pedido->id,
                'order_date' => now()->format('d/m/Y H:i'),
                'payment_method' => $metodoPago,
                'products' => $carrito->productos->map(function ($product) {
                    return [
                        'name' => $product->nombre,
                        'capacity' => $product->capacidad,
                        'color' => $product->color,
                        'state' => $product->estado,
                        'quantity' => $product->pivot->unidades,
                        'price' => $product->precioD,
                    ];
                })->toArray(),
                'total' => $total,
            ];

            //Enviar correo con los detalles del pedido
            Mail::to($user->email)->send(new OrderProcessed($orderDetails));
            
            //Guardar los datos del carrito en la sesion para mostrarlo en la coonfirmación
            session(['order_details' => $orderDetails]);

            DB::beginTransaction();
            // Restar la cantidad del producto del stock
            foreach ($carrito->productos as $producto) {
                  // Restar la cantidad del producto del stock
                  $producto->update([
                    'stock' => $producto->stock - $producto->pivot->unidades
                ]);

                // Actualizar stock en Back Market
                //creamos el sku para pasarlo a la url
                $sku= "$producto->nombre $producto->capacidad - $producto->color - Libre ".(isset($producto->bateria) ? "$producto->bateria ":'')."$producto->estado";
                //$this->backMarketController->actualizarStockBM($sku, $producto->pivot->unidades);
                
            }
            // Elimina el carrito
            $carrito->delete();
            // Confirma la transacción
            DB::commit();
        }
    }

    public function processOrder(Request $request){
        try {
            $user = Auth::user();
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
            $metodoPago = $request->input('payment_method');
            if (!isset($metodoPago)) {
                return view('orders.payment', compact('carrito'))->with('error', 'No se eligió metodo de pago.');
            }

            if ($carrito == null) {
                return redirect()->route('products');
            }
            
            // Calcular el total del pedido sumando los precios de los productos en el carrito
            $total = 0;
            foreach ($carrito->productos as $producto) {
                $total += $producto->precioD * $producto->pivot->unidades;
            }

            $orderDetails = [
                'total' => $total,
            ];

            switch ($metodoPago) {
                case 'credit_card':
                    return $this->payWithCreditCard($metodoPago);
                case 'paypal':
                    return $this->payWithPayPal($orderDetails);
                case 'recogida_Tienda':
                    return $this->payWithStorePickup($metodoPago);
                case 'contra_reembolso':
                    return $this->payWithCashOnDelivery($metodoPago);
                default:
                    return view('orders.payment')->with('error', 'Método de pago no válido.');
            }
        } catch (\Exception $e) {
            $user = Auth::user();
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
            return view('orders.payment',compact('carrito'))->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    private function payWithCreditCard($metodoPago){
        $this->borrarCarrito($metodoPago);
        return redirect()->route('confirmation')->with('success', 'Pago con tarjeta de crédito procesado con éxito.');
    }

    private function payWithPayPal($orderDetails) {
        try {
            // Obtener el token de acceso de PayPal
            $accessToken = $this->getPayPalAccessToken();
            // Verificar si se obtuvo el token de acceso correctamente
            if ($accessToken) {
                // Preparar los datos para la solicitud

                $data = [
                    'intent' => 'sale',
                    'payer' => [
                        'payment_method' => 'paypal',
                    ],
                    'redirect_urls' => [
                        'return_url' => route('payment.status', ['success' => 'true']),
                        'cancel_url' => route('payment.status', ['success' => 'false']),
                    ],
                    'transactions' => [
                        [
                            'amount' => [
                                'total' => $orderDetails['total'], // Total a cobrar
                                'currency' => 'EUR', // Moneda (debes ajustarla según tu configuración)
                            ],
                            'description' => 'Compra en nuestra tienda', // Descripción de la transacción
                        ],
                    ],
                ];
                
                // Realizar la solicitud POST a PayPal para crear el pago
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken['access_token'], // Usar el token de acceso en el encabezado de autorización
                    'Content-Type' => 'application/json'
                ])->post("https://api-m.paypal.com/v1/payments/payment", $data);
                
                // Verificar si la solicitud fue exitosa
                if ($response->successful()) {
                    // Obtener el ID del pago
                    $paymentId = $response['id'];
                    // Redirigir al usuario a la página de PayPal para completar el pago
                    return redirect("https://www.paypal.com/checkoutnow?token=$paymentId");
                } else {
                    // Manejar errores si la solicitud no fue exitosa
                    $user = Auth::user();
                    $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
                    return view('orders.payment',compact('carrito'))->with('error', 'Error al procesar el pago con PayPal.');
                }
            } else {
                // Manejar errores si no se pudo obtener el token de acceso
                $user = Auth::user();
                $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
                return view('orders.payment',compact('carrito'))->with('error', 'Error al obtener el token de acceso de PayPal.');
            }
        } catch (\Exception $e) {
            // Manejar errores
            $user = Auth::user();
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
            return view('orders.payment',compact('carrito'))->with('error', 'Error al procesar el pago con PayPal: ' . $e->getMessage());
        }
    }

    public function payPalStatus(Request $request){
        // Verificar si el pago fue exitoso
        if ($request->has('success') && $request->success == 'true') {
            // Aquí puedes agregar la lógica para manejar el éxito del pago de PayPal
            // Por ahora, simplemente redirigir al usuario a una página de éxito
            return view('orders.confirmation');
        } else {
            // Aquí puedes agregar la lógica para manejar la cancelación del pago de PayPal
            // Por ahora, simplemente redirigir al usuario a una página de cancelación
            $user = Auth::user();
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();
            return view('orders.payment',compact('carrito'));
        }
    }


    private function payWithStorePickup($metodoPago){
        $this->borrarCarrito($metodoPago);
        return redirect()->route('confirmation')->with('success', 'Recogida en tienda seleccionada con éxito.');
    }

    private function payWithCashOnDelivery($metodoPago){
        $this->borrarCarrito($metodoPago);
        return redirect()->route('confirmation')->with('success', 'Pago contra reembolso seleccionado con éxito.');
    }
}
