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

        @vite('resources/css/app.css')

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
                <div id="iphone-list" class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 sm:w-full sm:h-full m-auto">                      
                    <div class="container md:pt-4 md:p-8 mx-auto">
                        <div class="w-full overflow-x-auto">
                            {{-- <form action="{{ route('process.shipping') }}" method="POST"> --}}
                            <form action="" method="POST">
                                @csrf
                                <div class="mb-3 font-bold text-xl">
                                    <h3>Dirección de envío</h3>
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
                                    <label for="name">Nombre:</label>
                                    <input type="text" id="name" name="name" class="form-control mt-4" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                                    <br>
                                    <label for="lastname">Apellido:</label>
                                    <input type="text" id="lastname" name="lastname" class="form-control mt-4" value="{{ old('lastname', Auth::user()->lastname ?? '') }}" required>
                                    <br>
                                    <!-- Mostrar los campos direccion_1 del envío del usuario logueado -->
                                    <label for="address">Dirección:</label>
                                    <input type="text" id="address" name="address" class="form-control mt-4" value="{{ old('address', Auth::user()->direccionEnvio->direccion_1 ?? '') }}" required>
                                    <br>
                                    <!-- Mostrar los campos direccion_2 del envío del usuario logueado -->
                                    <label for="address_2">Dirección 2:</label>
                                    <input type="text" id="address_2" name="address_2" class="form-control mt-4" value="{{ old('address_2', Auth::user()->direccionEnvio->direccion_2 ?? '') }}">
                                    <br>
                                    <!-- Mostrar el campo empresa del envío del usuario logueado -->
                                    <label for="company">Empresa:</label>
                                    <input type="text" id="company" name="company" class="form-control mt-4" value="{{ old('company', Auth::user()->direccionEnvio->empresa ?? '') }}">
                                    <br>
                                    <!-- Mostrar los campos ciudad del envío del usuario logueado -->
                                    <label for="city">Ciudad:</label>
                                    <input type="text" id="city" name="city" class="form-control mt-4" value="{{ old('city', Auth::user()->direccionEnvio->ciudad ?? '') }}" required>
                                    <br>
                                    <!-- Mostrar los campos código postal del envío del usuario logueado -->
                                    <label for="postal_code">Código Postal:</label>
                                    <input type="text" id="postal_code" name="postal_code" class="form-control mt-4" value="{{ old('postal_code', Auth::user()->direccionEnvio->codigo_postal ?? '') }}" required>
                                    <br>
                                    <!-- Mostrar los campos país del envío del usuario logueado -->
                                    <label for="country">País:</label>
                                    <input type="text" id="country" name="country" class="form-control mt-4" value="{{ old('country', Auth::user()->direccionEnvio->pais ?? '') }}" required>
                                    <br>
                                    <!-- Mostrar el campo teléfono del envío del usuario logueado -->
                                    <label for="phone">Teléfono:</label>
                                    <input type="text" id="phone" name="phone" class="form-control mt-4" value="{{ old('phone', Auth::user()->direccionEnvio->telefono ?? '') }}" required>

                            
                                    <!-- Mostrar el carrito -->
                                    <h3>Contenido del carrito:</h3>
                                    @if ($carrito && $carrito->productos)
                                    @foreach ($carrito->productos as $producto)
                                        <li>{{ $producto->nombre }} {{ $producto->capacidad }}- Cantidad: {{ $producto->pivot->unidades }}- Precio: {{ number_format($producto->precioD, 2, ',', '.') . ' €' }}</li>
                                    @endforeach
                                    @else
                                        <p>El carrito está vacío.</p>
                                    @endif
                                    <button type="submit" class="btn btn-primary mt-3">Confirmar Dirección</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>