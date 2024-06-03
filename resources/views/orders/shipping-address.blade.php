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
                            {{-- <form action="{{ route('process.shipping') }}" method="POST"> --}}
                            <form action="{{ route('billing-address') }}" method="POST">
                                @csrf
                                <div class="mb-3 font-bold text-xl">
                                    <h3>Dirección de envío</h3>
                                </div>
                                <div class="form-group">
                                    {{-- <div class="md:min-w-[20rem] md:flex-1 relative">
                                        <input data-cs-mask="" name="firstName" id="firstName" type="text" aria-label="Nombre" class="peer rounded-md input-normalize relative w-full min-w-0 pl-12 motion-safe:transition-colors motion-safe:duration-200  input-normalize h-18 border border-solid" data-test="input-text-input">
                                        <label class="text-static-default-low caption transform-none pr-36 pointer-events-none absolute left-[calc(0.75rem+1px)] right-12 z-[inherit] max-w-full-translate-y-1/2 truncate peer-disabled:text-onaction-default-mid-disabled motion-safe:transition-all motion-safe:duration-200" for="firstName">
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



                                    {{-- <div class="relative z-0 w-full mb-5 group">
                                        <input type="email" name="floating_email" id="floating_email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                        <label for="floating_email" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email address</label>
                                    </div>
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input type="password" name="floating_password" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                        <label for="floating_password" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Password</label>
                                    </div>
                                    <div class="relative z-0 w-full mb-5 group">
                                        <input type="password" name="repeat_password" id="floating_repeat_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                        <label for="floating_repeat_password" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm password</label>
                                    </div>
                                    <div class="grid md:grid-cols-2 md:gap-6">
                                        <div class="relative z-0 w-full mb-5 group">
                                            <input type="text" name="floating_first_name" id="floating_first_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                            <label for="floating_first_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First name</label>
                                        </div>
                                        <div class="relative z-0 w-full mb-5 group">
                                        <input type="text" name="floating_last_name" id="floating_last_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                        <label for="floating_last_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last name</label>
                                        </div>
                                    </div>
                                    <div class="grid md:grid-cols-2 md:gap-6">
                                        <div class="relative z-0 w-full mb-5 group">
                                            <input type="tel" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" name="floating_phone" id="floating_phone" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                            <label for="floating_phone" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone number (123-456-7890)</label>
                                        </div>
                                        <div class="relative z-0 w-full mb-5 group">
                                            <input type="text" name="floating_company" id="floating_company" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                                            <label for="floating_company" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Company (Ex. Google)</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                     --}}
                                      





                                    <!-- Mostrar los campos nombre y apellido del usuario logueado -->
                                    <label for="name">Nombre:</label>
                                    <input type="text" id="name" name="name" class="form-control mt-4" value="{{ old('name', $name) }}" required>
                                    <br>
                                    <label for="lastname">Apellido:</label>
                                    <input type="text" id="lastname" name="lastname" class="form-control mt-4" value="{{ old('lastname', $lastname) }}" required>
                                    <br>
                                    <!-- Mostrar los campos direccion_1 del envío del usuario logueado -->
                                    <label for="address">Dirección:</label>
                                    <input type="text" id="address" name="address" class="form-control mt-4" value="{{ old('address', $direccion_1) }}" required>
                                    <br>
                                    <!-- Mostrar los campos direccion_2 del envío del usuario logueado -->
                                    <label for="address_2">Dirección 2:</label>
                                    <input type="text" id="address_2" name="address_2" class="form-control mt-4" value="{{ old('address_2', $direccion_2) }}">
                                    <br>
                                    <!-- Mostrar el campo empresa del envío del usuario logueado -->
                                    <label for="company">Empresa:</label>
                                    <input type="text" id="company" name="company" class="form-control mt-4" value="{{ old('company', $empresa) }}">
                                    <br>
                                    <!-- Mostrar los campos ciudad del envío del usuario logueado -->
                                    <label for="city">Ciudad:</label>
                                    <input type="text" id="city" name="city" class="form-control mt-4" value="{{ old('city', $ciudad) }}" required>
                                    <br>
                                    <!-- Mostrar los campos código postal del envío del usuario logueado -->
                                    <label for="postal_code">Código Postal:</label>
                                    <input type="text" id="postal_code" name="postal_code" class="form-control mt-4" value="{{ old('postal_code', $codigo_postal) }}" required>
                                    <br>
                                    <!-- Mostrar los campos país del envío del usuario logueado -->
                                    <label for="country">País:</label>
                                    <input type="text" id="country" name="country" class="form-control mt-4" value="{{ old('country', $pais) }}" required>
                                    <br>
                                    <!-- Mostrar el campo teléfono del envío del usuario logueado -->
                                    <label for="phone">Teléfono:</label>
                                    <input type="text" id="phone" name="phone" class="form-control mt-4" value="{{ old('phone', $telefono) }}" required>

                            
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