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

        <!-- <link href="css/styles.css" rel="stylesheet" /> -->

        <!-- <link href="{!! asset('css/styles.css') !!}" rel="stylesheet" /> -->

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Direcciones') }}
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
                            {{-- <form action="{{ route('process.shipping') }}" method="POST"> --}}
                            <form action="{{ route('payment') }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <h3 class="text-2xl font-bold text-gray-700">Dirección de facturación</h3>
                                </div>
                                <div class="form-group">
                                    {{-- <div class="md:min-w-[20rem] md:flex-1 relative">
                                        <input data-cs-mask="" name="firstName" id="firstName" type="text" aria-label="Nombre" class="peer rounded-sm input-normalize relative w-full min-w-0 pl-12 motion-safe:transition-colors motion-safe:duration-200 bg-static-default-low hover:bg-static-default-low-hover disabled:bg-static-default-low-disabled text-action-default-hi input-normalize h-48 border border-solid disabled:border-action-default-low-disabled disabled:text-action-default-hi-disabled border-action-default-low pt-14 pr-48 type-date:pr-8" data-test="input-text-input">
                                        <label class="text-static-default-low caption top-5 transform-none pr-36 pointer-events-none absolute left-[calc(0.75rem+1px)] right-12 z-[inherit] max-w-full-translate-y-1/2 truncate peer-disabled:text-onaction-default-mid-disabled motion-safe:transition-all motion-safe:duration-200" for="firstName">
                                            Nombre
                                        </label>
                                        <div class="rounded-sm pointer-events-none absolute right-1 top-1 z-[1] h-40 w-40 motion-safe:transition-colors motion-safe:duration-200 peer-disabled:bg-transparent">
                                            <button class="rounded-full flex shrink-0 cursor-pointer appearance-none items-center justify-center border-0 no-underline disabled:cursor-not-allowed
                                            motion-safe:transition motion-safe:duration-300 motion-safe:ease-in h-40 w-40 disabled:bg-action-default-min-disabled disabled:text-action-default-hi-disabled pointer-events-auto absolute right-4 top-[calc(0.25rem-1px)] disabled:pointer-events-none" data-test="clear-button" type="button">
                                                <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.53 7.47a.75.75 0 0 0-1.06 1.06L10.94 12l-3.47 3.47a.75.75 0 1 0 1.06 1.06L12 13.06l3.47 3.47a.75.75 0 1 0 1.06-1.06L13.06 12l3.47-3.47a.75.75 0 0 0-1.06-1.06L12 10.94z"></path>
                                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25M3.75 12a8.25 8.25 0 1 1 16.5 0 8.25 8.25 0 0 1-16.5 0" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div> --}}


                                      <!-- Mostrar los campos nombre y apellido del usuario logueado -->
                                      <div class="grid md:grid-cols-2 md:gap-6 mb-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre:</label>
                                            <input type="text" id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name', $name) }}" required>
                                        </div>
                                        <div>
                                            <label for="lastname" class="block text-sm font-medium text-gray-700">Apellido:</label>
                                            <input type="text" id="lastname" name="lastname" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('lastname', $lastname) }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="address" class="block text-sm font-medium text-gray-700">Dirección:</label>
                                        <input type="text" id="address" name="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('address', $direccion_1) }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="address_2" class="block text-sm font-medium text-gray-700">Dirección 2:</label>
                                        <input type="text" id="address_2" name="address_2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('address_2', $direccion_2) }}">
                                    </div>
                                    <div class="mb-4">
                                        <label for="company" class="block text-sm font-medium text-gray-700">Empresa:</label>
                                        <input type="text" id="company" name="company" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('company', $empresa) }}">
                                    </div>
                                    <div class="mb-4">
                                        <label for="city" class="block text-sm font-medium text-gray-700">Ciudad:</label>
                                        <input type="text" id="city" name="city" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('city', $ciudad) }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Código Postal:</label>
                                        <input type="text" id="postal_code" name="postal_code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('postal_code', $codigo_postal) }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="country" class="block text-sm font-medium text-gray-700">País:</label>
                                        <input type="text" id="country" name="country" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('country', $pais) }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="nif_dni" class="block text-sm font-medium text-gray-700">NIF/DNI:</label>
                                        <input type="text" id="nif_dni" name="nif_dni" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('nif_dni', $nif_dni) }}" required>
                                    </div>
                                    <div class="mb-6">
                                        <h3 class="text-xl font-semibold mt-8 mb-4">Contenido del carrito:</h3>
                                        @if ($carrito && $carrito->productos)
                                        <ul class="list-disc list-inside">
                                            @foreach ($carrito->productos as $producto)
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
                                                <li><b>Nombre: </b>{{ $producto->nombre }} {{ $producto->capacidad }} {{ $producto->color }} {{$estado}} <b>- Cantidad:</b> {{ $producto->pivot->unidades }} <b>- Precio:</b> {{ number_format($producto->precioD, 2, ',', '.') . ' €' }}</li>
                                            @endforeach
                                        </ul>
                                        @else
                                            <p>El carrito está vacío.</p>
                                        @endif
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="mr-1 mb-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Confirmar Dirección</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>