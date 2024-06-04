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
            @if (isset($success))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ $success }}
                </div>
            @endif
            @if (isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ $error }}
                </div>
            @endif
            <div class="mx-auto max-w-2xl px-4 py-10 md:px-6 md:!py-16 lg:max-w-7xl lg:px-8">
                <div class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 sm:w-full sm:h-full m-auto">
                    <div class="container md:pt-4 md:p-8 mx-auto">
                        <div class="w-full overflow-x-auto">     
                            <h1 class="text-2xl font-bold mb-4">Método de pago</h1>
                            <form action="{{ route('cart.processOrder') }}" method="POST">
                                @csrf
                                <!-- Input fields for payment method -->
                                <div class="mb-4 ml-1">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" id="credit_card" name="payment_method" value="credit_card" {{ old('payment_method') == 'Tarjeta de credito' ? 'checked' : '' }}>
                                        <span class="ml-2">Tarjeta de crédito</span>
                                    </label>
                                </div>
                                <div class="mb-4 ml-1">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" id="paypal" name="payment_method" value="paypal" {{ old('payment_method') == 'Paypal' ? 'checked' : '' }}>
                                        <span class="ml-2">PayPal</span>
                                    </label>
                                </div>
                                <div class="mb-4 ml-1">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" id="recogida_Tienda" name="payment_method" value="recogida_Tienda" {{ old('payment_method') == 'Recogida en Tienda' ? 'checked' : '' }}>
                                        <span class="ml-2">Recogida en tienda</span>
                                    </label>
                                </div>
                                <div class="mb-4 ml-1">
                                    <label class="inline-flex items-center">
                                        <input type="radio" class="form-radio" id="contra_reembolso" name="payment_method" value="contra_reembolso" {{ old('payment_method') == 'Contra Reembolso' ? 'checked' : '' }}>
                                        <span class="ml-2">Contra Reembolso</span>
                                    </label>
                                </div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Submit Payment
                                </button>
                            </form>

                            <!-- Mostrar el carrito -->
                            <h3 class="text-xl font-semibold mt-8 mb-4">Contenido del carrito:</h3>
                            {{-- @if ($carrito && $carrito->productos)
                            <ul class="list-disc list-inside">
                                @foreach ($carrito->productos as $producto)
                                    <li>{{ $producto->nombre }} {{ $producto->capacidad }} - Cantidad: {{ $producto->pivot->unidades }} - Precio: {{ number_format($producto->precioD, 2, ',', '.') . ' €' }}</li>
                                @endforeach
                            </ul>
                            @else
                                <p>El carrito está vacío.</p>
                            @endif --}}
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>