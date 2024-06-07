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
                            <form action="{{ route('billing-address') }}" method="POST" class="mx-1">
                                @csrf
                                <div class="mb-6">
                                    <h3 class="text-2xl font-bold text-gray-700">Dirección de envío</h3>
                                </div>
                                <div class="form-group">

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
                                    <div class="grid md:grid-cols-2 md:gap-6 mb-4">
                                        <div class="mb-4 md:!mb-0">
                                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre:</label>
                                            <input type="text" id="name" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('name', isset($name) ? $name : '') }}" required>
                                        </div>
                                        <div>
                                            <label for="lastname" class="block text-sm font-medium text-gray-700">Apellido:</label>
                                            <input type="text" id="lastname" name="lastname" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('lastname', isset($lastname) ? $lastname : '') }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="company" class="block text-sm font-medium text-gray-700">Empresa:</label>
                                        <input type="text" id="company" name="company" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('company', isset($empresa) ? $empresa : '') }}">
                                    </div>
                                    <div class="mb-4">
                                        <label for="country" class="block text-sm font-medium text-gray-700">País:</label>
                                        <input type="text" id="country" name="country" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('country', isset($pais) ?  $pais : '') }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="address" class="block text-sm font-medium text-gray-700">Dirección:</label>
                                        <input type="text" id="address" name="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('address', isset($direccion_1) ? $direccion_1 : '') }}" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="address_2" class="block text-sm font-medium text-gray-700">Dirección 2:</label>
                                        <input type="text" id="address_2" name="address_2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('address_2', isset($direccion_2) ? $direccion_2 : '') }}">
                                    </div>
                                    <div class="grid md:grid-cols-2 md:gap-6 mb-4">
                                        <div class="mb-4 md:!mb-0">
                                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Código Postal:</label>
                                            <input type="text" id="postal_code" name="postal_code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('postal_code', isset($codigo_postal) ? $codigo_postal : '') }}" required>
                                        </div>
                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700">Ciudad:</label>
                                            <input type="text" id="city" name="city" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('city', isset($ciudad) ? $ciudad : '') }}" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono:</label>
                                        <input type="text" id="phone" name="phone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('phone', isset($telefono) ? $telefono : '') }}" required>
                                        <p class="mt-2 text-slate-800 text-sm">Es solo por si hace falta localizarte durante la entrega.</p>
                                    </div>
                                    <div class="mb-6 border-t border-t-gray-300 mt-7 hidden" id="dni_field">
                                        <p class="font-bold text-xl mt-6 mb-3">Información personal</p>
                                        <label for="nif_dni" class="block text-sm font-medium text-gray-700">NIF/DNI:</label>
                                        <input type="text" id="nif_dni" name="nif_dni" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('nif_dni', isset($nif_dni) ? $nif_dni : '') }}" required>
                                        <p class="font-bold mt-2 mb-3 text-slate-800">Para la emisión de tu factura.</p>
                                    </div>
                                    <div class="flex items-center mt-4 h-5">
                                        <input type="checkbox" id="same_as_billing" name="same_as_billing" class="mr-3 rounded-md h-5 w-5">
                                        <label for="same_as_billing" class="text-md font-bold">Misma dirección de facturación</label>
                                    </div>
                                    <div class="flex justify-end">
                                        <button class="float-right right-4 bottom-4 md:right-8 md:bottom-8 px-4 py-3 bg-neutral-950 text-white rounded-md hover:bg-zinc-800 transition-colors duration-300">
                                            Continuar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const checkbox = document.getElementById('same_as_billing');
                    const dniField = document.getElementById('dni_field');

                    checkbox.addEventListener('change', function() {
                        if (checkbox.checked) {
                            dniField.classList.remove('hidden');
                        } else {
                            dniField.classList.add('hidden');
                        }
                    });
                });
            </script>
        </x-app-layout>
    </body>
</html>