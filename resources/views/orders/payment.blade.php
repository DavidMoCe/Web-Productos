<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <title>Shop Homepage - Start Bootstrap Template</title> -->
        <title>TouchPhone</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Elegir método de pago') }}
                </h2>
            </x-slot>
            <!-- Mostrar mensajes flash -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="mx-auto max-w-2xl px-4 py-10 md:px-6 md:!py-16 lg:max-w-7xl lg:px-8">
                <div class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 sm:w-full sm:h-full m-auto">
                    <div class="container md:pt-4 md:p-8 mx-auto">
                        <div class="w-full overflow-x-auto">     
                            <h1>Metodo de pago</h1>
                            <form action="{{ route('cart.processOrder') }}" method="POST">
                                @csrf
                                <!-- Input fields for payment method -->
                                <div>
                                    <label for="credit_card">Credit Card</label>
                                    <input type="radio" id="credit_card" name="payment_method" value="Tarjeta de credito"  {{ old('payment_method') == 'Tarjeta de credito' ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label for="paypal">PayPal</label>
                                    <input type="radio" id="paypal" name="payment_method" value="Paypal" {{ old('payment_method') == 'Paypal' ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label for="recogida_Tienda">Recogida en tienda</label>
                                    <input type="radio" id="recogida_Tienda" name="payment_method" value="Recogida en Tienda" {{ old('payment_method') == 'Recogida en Tienda' ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label for="contra_reembolso">Contra Reembolso</label>
                                    <input type="radio" id="contra_reembolso" name="payment_method" value="Contra Reembolso" {{ old('payment_method') == 'Contra Reembolso' ? 'checked' : '' }}>
                                </div>
                                
                        
                                <button type="submit">Submit Payment</button>
                            </form>


                            <!-- Mostrar el carrito -->
                            <h3>Contenido del carrito:</h3>
                            @if ($carrito && $carrito->productos)
                            @foreach ($carrito->productos as $producto)
                                <li>{{ $producto->nombre }} {{ $producto->capacidad }}- Cantidad: {{ $producto->pivot->unidades }}- Precio: {{ number_format($producto->precioD, 2, ',', '.') . ' €' }}</li>
                            @endforeach
                            @else
                                <p>El carrito está vacío.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>