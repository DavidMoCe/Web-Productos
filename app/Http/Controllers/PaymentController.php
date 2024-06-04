<?php
namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;

class PaymentController extends Controller
{
    private $payPal;

    public function __construct()
    {
        $payPalConfig = Config::get('paypal');
        $this->payPal = new ApiContext(
            new OAuthTokenCredential(
                // config('services.paypal.client_id'),
                // config('services.paypal.secret')
                $payPalConfig['AZEgwHrYEiBy4McSWOswsl952_iRBy1w4TkNtCYqa2agvj6G3ENMHRx0bpKIG7tJa2E_jqp-eJFHG87y'],
                $payPalConfig['EBTUrdEaczo_4woGfMFkbx46cA0smmw8ewIOD8Q1trluejSo161ODm_CtMguZ1ruBJoE0Pbq8uOhjLjC']
            )
        );
        // $this->payPal->setConfig([
        //     'mode' => config('services.paypal.mode'),
        // ]);
        $this->payPal->setConfig([
            $payPalConfig('sandbox'),
        ]);
    }

    public function borrarCarrito(){
        $user = Auth::user();
        $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();

        //Eliminar carrito
        $carrito->delete();
    }

    // Realizar pedido
    public function processOrder(Request $request)
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
            $envioId = $user->direccionEnvio->id;
            $facturacionId = $user->direccionFacturacion->id;
            $metodoPago = $request->input('payment_method');
            $carrito = Carrito::where('usuario_id', $user->id)->with('productos')->first();

            if (!isset($metodoPago)) {
                return view('orders.payment', compact('carrito'))->with('error', 'No se eligió metodo de pago.');
            }

            if ($carrito == null) {
                return redirect()->route('products');
            }

            $pedido = Pedido::create([
                'usuario_id' => $userId,
                'envio_id' => $envioId,
                'facturacion_id' => $facturacionId,
                'metodoPago' => $metodoPago,
            ]);

            if ($carrito && $carrito->productos) {
                foreach ($carrito->productos as $producto) {
                    $pedido->productos()->attach($producto->id, ['unidades' => $producto->pivot->unidades]);
                }
            }

            // Guardar detalles del pedido en la sesión
            $orderDetails = [
                'order_number' => $pedido->id,
                'order_date' => now()->format('d/m/Y H:i'),
                'payment_method' => $metodoPago,
                'products' => $carrito->productos->map(function ($product) {
                    return [
                        'name' => $product->nombre,
                        'quantity' => $product->pivot->unidades,
                        'price' => $product->precioD,
                    ];
                })->toArray(),
            ];
            session(['order_details' => $orderDetails]);

            // Redirigir según el método de pago
            switch ($metodoPago) {
                case 'credit_card':
                    return $this->payWithCreditCard();
                case 'paypal':
                    return $this->payWithPayPal($orderDetails);
                case 'recogida_Tienda':
                    return $this->payWithStorePickup($orderDetails);
                case 'contra_reembolso':
                    return $this->payWithCashOnDelivery($orderDetails);
                default:
                return view('orders.payment')->with('error', 'Método de pago no válido.');
            }
        } catch (\Exception $e) {
            return view('orders.payment')->with('error', 'Error al procesar el pedido: ' . $e->getMessage());
        }
    }

    private function payWithCreditCard(){

        // Implementar lógica de pago con tarjeta de crédito
        $this->borrarCarrito();
        return redirect()->route('confirmation')->with('success', 'Pago con tarjeta de crédito procesado con éxito.');
    }

    private function payWithPayPal($orderDetails){
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $items = [];
        $total = 0;

        foreach ($orderDetails['products'] as $product) {
            $item = new Item();
            $item->setName($product['name'])
                ->setCurrency('EUR')
                ->setQuantity($product['quantity'])
                ->setPrice($product['price']);
            
            $items[] = $item;
            $total += $product['quantity'] * $product['price'];
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $amount = new Amount();
        $amount->setCurrency('EUR')
            ->setTotal($total);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Pago en TouchPhone');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.status'))
            ->setCancelUrl(route('paypal.status'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($this->payPal);
            return redirect()->away($payment->getApprovalLink());
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return view('orders.payment')->with('error', 'Hubo un problema al procesar el pago con PayPal.');
        }
    }

    public function payPalStatus(Request $request){
        $paymentId = $request->paymentId;
        $payerId = $request->PayerID;

        if (empty($paymentId) || empty($payerId)) {
            return redirect()->route('cart.index')->with('error', 'El pago ha sido cancelado.');
        }

        $payment = Payment::get($paymentId, $this->payPal);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $result = $payment->execute($execution, $this->payPal);

            if ($result->getState() == 'approved') {
                return redirect()->route('confirmation')->with('success', 'Pago realizado con éxito.');
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return redirect()->route('cart.index')->with('error', 'El pago ha fallado.');
        }

        return redirect()->route('cart.index')->with('error', 'El pago ha fallado.');
    }

    private function payWithStorePickup($orderDetails){
        // Implementar lógica de recogida en tienda
        $this->borrarCarrito();
        return redirect()->route('confirmation')->with('success', 'Recogida en tienda seleccionada con éxito.');
    }

    private function payWithCashOnDelivery($orderDetails){
        // Implementar lógica de pago contra reembolso
        $this->borrarCarrito();
        return redirect()->route('confirmation')->with('success', 'Pago contra reembolso seleccionado con éxito.');
    }
}
