<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>TouchPhone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

                @if (!request()->routeIs('admin.dashboard'))
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">{{ __('Dashboard') }}</a> ->
                @endif
                
                @if (request()->routeIs('admin.modelo'))
                    {{ __('Model') }}
                @else
                    @php
                        // Obtener el valor actual del parámetro 'tipo' de la URL
                        $tipo = request()->query('tipo');
                    @endphp
                    <a href="{{ route('admin.modelo',['tipo' => $tipo]) }}" class="text-blue-500 hover:underline">{{ __('Model') }}</a> ->
                @endif
                
                @if (request()->routeIs('admin.stock'))
                    {{ __('Stock') }}
                @else
                    <a href="{{ route('admin.stock') }}" class="text-blue-500 hover:underline">{{ __('Stock') }}</a>
                @endif

                
            </h2>
        </x-slot>
 
        @php
            function getEstado($state) {
                switch ($state) {
                    case 0:
                        return "Perfecto";
                    case 1:
                        return "Como Nuevo";
                    case 2:
                        return "Muy Buen Estado";
                    case 3:
                        return "Buen Estado";
                    case 4:
                        return "Aceptable";
                    default:
                        return "Desconocido";
                }
            }
        @endphp


       
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8 ">
            @if (!isset($productos) || $productos->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __('No se han encontrado productos') }}
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <!-- Cabecera de la tabla -->
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Nombre
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Stock
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Grado
                                                </th>
                                                <th scope="col" colspan="3" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Precio
                                                </th>
                                            </tr>
                                        </thead>
                                        <!-- Cuerpo de la tabla -->
                                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                            @foreach ($productos as $producto)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">{{ $producto['title'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" name="stock"
                                                            value="{{ $producto['quantity'] }}">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="hidden" name="grado" value="{{ $producto['state'] }}">
                                                        <input type="text" value="{{ getEstado($producto['state']) }}" readonly>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="text" name="precio"
                                                            value="{{ $producto['price'] }} €">
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                            Cambiar
                                                        </button>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <input type="checkbox" name="change_all"> Este campo estara oculto el cual se activa si recibe un cambio el producto
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mx-auto max-w-2xl px-4 py-4 sm:px-6 sm:py-6 lg:max-w-7xl lg:px-8 text-right">
                        <button type="button" class="change-selected bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Realizar cambios
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </x-app-layout>
</body>

</html>
