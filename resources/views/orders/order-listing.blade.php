<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- <title>Shop Homepage - Start Bootstrap Template</title> -->
        <title>TouchPhone</title>
        <!-- Favicon-->
        <!-- <link rel="icon" type="image/x-icon" href="assets/favicon.ico" /> -->

        <script src="https://cdn.tailwindcss.com"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Pedidos Realizados') }}
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
                    <div class="container md:pt-4 md:pb-8 md:px-5 mx-auto">
                        <div class="w-full overflow-x-auto">
                            @if ($pedidos->isEmpty())
                                <p>No hay pedidos disponibles en este momento</p>
                            
                            @else
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mx-1">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <!-- Cabecera de la tabla -->
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                ID Pedido
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Nombre producto
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Estado
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Unidades
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Precio
                                            </th>
                                            <th scope="col"  class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Método de pago
                                            </th>
                                            <th scope="col"  class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Fecha de realización
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- Cuerpo de la tabla -->
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        @foreach ($pedidos as $pedido)
                                            @foreach ($pedido->productos as $producto)
                                                @php
                                                    switch ($producto->estado) {
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
                                                <tr>
                                                    <td class="py-3 whitespace-nowrap text-center">{{ $pedido->id }}</td>
                                                    <td class="py-3 whitespace-nowrap text-center">{{ $producto->nombre." ". $producto->capacidad." ".$producto->color." ".($producto->libre ? 'Libre':'')." ".$producto->bateria}}</td>
                                                    <td class="py-3 whitespace-nowrap text-center">{{ $estado }}</td>
                                                    <td class="py-3 whitespace-nowrap text-center">{{ $producto->pivot->unidades }}</td>
                                                    <td class="py-3 whitespace-nowrap text-center">{{ number_format($producto->precioD, 2, ',', '.') . ' €' }}</td>
                                                    <td class="py-3 whitespace-nowrap text-center">{{ $pedido->metodoPago }}</td>
                                                    <td class="py-3 whitespace-nowrap text-center">{{ $pedido->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>