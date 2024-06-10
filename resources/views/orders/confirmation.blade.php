<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Confirmación de Pedido</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Confirmación de Pedido') }}
            </h2>
        </x-slot>
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
                        <h1 class="text-2xl font-bold">¡Gracias por tu pedido!</h1>
                        <p>Tu pedido ha sido procesado con éxito.</p>
                        <!-- Mostrar detalles del pedido -->
                        @if (session('order_details'))
                            <h3 class="text-xl font-semibold mt-4">Detalles del Pedido:</h3>
                            <ul>
                                <li><strong>Número de Pedido:</strong> {{ session('order_details.order_number') }}</li>
                                <li><strong>Fecha:</strong> {{ session('order_details.order_date') }}</li>
                                <li><strong>Método de Pago:</strong> {{ session('order_details.payment_method') }}</li>
                            </ul>
                            <h3 class="text-xl font-semibold mt-4">Productos:</h3>
                            <ul class="list-disc list-inside">
                                @foreach (session('order_details.products') as $product)
                                    @php
                                        switch ($product['state']) {
                                            case 'STA':
                                            case 'COR':
                                                $estado="Correcto";
                                                break;
                                            case 'BUE':
                                            case 'MBU':
                                                $estado="Muy bueno";
                                                break;
                                            case 'IMP':
                                                $estado="Excelente";
                                            default:
                                                $estado="";
                                                break;
                                        }
                                    @endphp
                                    <li><b>Nombre: </b>{{  $product['name']." ". $product['capacity']." ".$product['color']." ".($product['libre'] ? 'Libre':'')." ".$product['battery']}}</li>
                                    <li><b>Estado:</b> {{$estado}}</li>
                                    <li><b>Cantidad:</b> {{ $product['quantity'] }}</li>
                                    <li><b>Precio:</b> {{ number_format($product['price'], 2, ',', '.') . ' €' }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>No se encontraron detalles del pedido.</p>
                        @endif
                        <a href="{{ route('products') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Volver a la tienda</a>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
</body>
</html>
