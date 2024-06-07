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
                    {{ __('Verifica las direcciones') }}
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
            {{-- Formulario de direccion --}}
            <div class="mx-auto max-w-2xl px-6 py-10 md:px-6 md:!py-16 lg:max-w-7xl lg:px-8">
                <div class="mb-3 md:mb-6">
                    <h3 class="md:text-2xl font-bold text-gray-700">Confirma tu dirección de envío</h3>
                </div>
                <div class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 sm:w-full sm:h-full m-auto">                      
                    <div class="container md:p-1 mx-auto relative">
                        <div class="w-full overflow-x-auto">
                            <form action="{{ route('shipping-address') }}" method="POST" class="mx-1 relative">
                                @csrf
                                <div class="form-group">
                                    <div class="mb-2 font-bold">
                                        <span>
                                            {{ old('name', isset($name) ? $name : '') }}
                                            {{ old('lastname', isset($lastname) ? $lastname : '') }}
                                        </span>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('company', isset($empresa) ? $empresa : '') }}
                                        </span>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('address', isset($direccion_1) ? $direccion_1 : '') }}
                                        </span>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('address_2', isset($direccion_2) ? $direccion_2 : '') }}
                                        </span>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('postal_code', isset($codigo_postal) ? $codigo_postal : '') }}
                                            {{ old('city', isset($ciudad) ? $ciudad : '') }}
                                        </span>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('country', isset($pais) ?  $pais : '') }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <span>
                                            {{ old('phone', isset($telefono) ? $telefono : '') }}
                                        </span>
                                    </div>
                                    <div class="h-full">
                                        <button type="submit" class="relative md:!absolute bottom-2/4 right-0 px-4 py-1 border border-black rounded-md hover:bg-stone-100 font-bold transition-colors duration-300 flex items-center">
                                            <span>Editar dirección de envío</span>
                                            <svg aria-hidden="true" fill="currentColor" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg" class="ml-2">
                                                <path fill-rule="evenodd" d="M20.27 3.73a2.871 2.871 0 0 0-4.06 0L4.724 15.214a.742.742 0 0 0-.183.278l-1.56 3.901c-.408 1.02.604 2.033 1.625 1.625l3.903-1.561a.75.75 0 0 0 .278-.184L20.27 7.79a2.871 2.871 0 0 0 0-4.06m-3 1.06a1.371 1.371 0 0 1 1.94 1.94l-.675.674-1.94-1.939zM5.519 17.09l1.391 1.392-2.319.928zM15.53 6.53l-9.226 9.226 1.939 1.94 9.226-9.227z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Formulario de facturacion --}}
            <div class="mx-auto max-w-2xl px-6 pb-10 md:px-6 md:!pb-14 lg:max-w-7xl lg:px-8">
                <div class="mb-3 md:mb-6">
                    <h3 class="md:text-2xl font-bold text-gray-700">Confirma tu dirección de facturación</h3>
                </div>
                <div class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 sm:w-full sm:h-full m-auto">                      
                    <div class="container md:p-1 mx-auto relative">
                        <div class="w-full overflow-x-auto">
                            <form action="{{ route('billing-address') }}" method="POST" class="mx-1 relative">
                                @csrf
                                <div class="form-group">
                                    <div class="mb-2 font-bold">
                                        <span>
                                            {{ old('name', isset($name) ? $name : '') }}
                                            {{ old('lastname', isset($lastname) ? $lastname : '') }}
                                        </span>
                                        <input type="text" id="name" name="name" value="{{ old('name', isset($name) ? $name : '') }}" hidden readonly>
                                        <input type="text" id="lastname" name="lastname" value="{{ old('lastname', isset($lastname) ? $lastname : '') }}" hidden readonly>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('company', isset($empresa) ? $empresa : '') }}
                                        </span>
                                        <input type="text" id="company" name="company" value="{{ old('company', isset($empresa) ? $empresa : '') }}" hidden readonly>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('address', isset($direccion_1) ? $direccion_1 : '') }}
                                        </span>
                                        <input type="text" id="address" name="address" value="{{ old('address', isset($direccion_1) ? $direccion_1 : '') }}" hidden readonly>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('address_2', isset($direccion_2) ? $direccion_2 : '') }}
                                        </span>
                                        <input type="text" id="address_2" name="address_2" value="{{ old('address_2', isset($direccion_2) ? $direccion_2 : '') }}" hidden readonly>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('postal_code', isset($codigo_postal) ? $codigo_postal : '') }}
                                            {{ old('city', isset($ciudad) ? $ciudad : '') }}Málaga
                                        </span>
                                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', isset($codigo_postal) ? $codigo_postal : '') }}" hidden readonly>
                                        <input type="text" id="city" name="city" value="{{ old('city', isset($ciudad) ? $ciudad : '') }}" hidden readonly>
                                    </div>
                                    <div class="">
                                        <span>
                                            {{ old('country', isset($pais) ?  $pais : '') }}
                                        </span>
                                        <input type="text" id="country" name="country"value="{{ old('country', isset($pais) ?  $pais : '') }}" hidden readonly>
                                    </div>
                                    <div class="mb-2">
                                        <span>
                                            {{ old('phone', isset($telefono) ? $telefono : '') }}
                                        </span>
                                        <input type="text" id="phone" name="phone" value="{{ old('phone', isset($telefono) ? $telefono : '') }}" hidden readonly>
                                    </div>
                                    <div class="h-full">
                                        <button type="submit" class="relative md:!absolute bottom-2/4 right-0 px-4 py-1 border border-black rounded-md hover:bg-stone-100 font-bold transition-colors duration-300 flex items-center">
                                            <span>Editar dirección de facturación</span>
                                            <svg aria-hidden="true" fill="currentColor" height="16" viewBox="0 0 24 24" width="16" xmlns="http://www.w3.org/2000/svg" class="ml-2">
                                                <path fill-rule="evenodd" d="M20.27 3.73a2.871 2.871 0 0 0-4.06 0L4.724 15.214a.742.742 0 0 0-.183.278l-1.56 3.901c-.408 1.02.604 2.033 1.625 1.625l3.903-1.561a.75.75 0 0 0 .278-.184L20.27 7.79a2.871 2.871 0 0 0 0-4.06m-3 1.06a1.371 1.371 0 0 1 1.94 1.94l-.675.674-1.94-1.939zM5.519 17.09l1.391 1.392-2.319.928zM15.53 6.53l-9.226 9.226 1.939 1.94 9.226-9.227z" clip-rule="evenodd"></path>
                                            </svg>
                                            <input type="text" id="edicionConfirmacion" name="edicionConfirmacion" value="true" hidden readonly>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón fijo fuera de los divs existentes -->
            <div class="mx-auto max-w-2xl px-6 pb-16 md:px-6 lg:max-w-7xl lg:px-8">
                <form action="{{ route('payment_direct') }}" method="POST">
                    @csrf
                    <button class="float-right right-4 bottom-4 md:right-8 md:bottom-8 px-4 py-3 bg-neutral-950 text-white rounded-md hover:bg-zinc-800 transition-colors duration-300">
                        Continuar
                    </button>
                </form>
                
            </div>
        </x-app-layout>
    </body>
</html>