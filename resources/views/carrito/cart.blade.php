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
    </head>
    <body>
        <x-app-layout>
            @php
                print_r(session('cart'));
            @endphp
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Cart') }}
                </h2>
            </x-slot>
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <div id="iphone-list" class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 w-11/12 sm:w-full sm:h-full m-auto">        
                    <div class="container p-8 mx-auto">
                        <div class="w-full overflow-x-auto">
                        <table class="w-full shadow-inner">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 font-bold whitespace-nowrap">Image</th>
                                <th class="px-6 py-3 font-bold whitespace-nowrap">Product</th>
                                <th class="px-6 py-3 font-bold whitespace-nowrap">Qty</th>
                                <th class="px-6 py-3 font-bold whitespace-nowrap">Price</th>
                                <th class="px-6 py-3 font-bold whitespace-nowrap">Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($cookieCart as $producto)
                                <tr>
                                    <td>
                                        <div class="flex justify-center">
                                            <img src="./imagenes/{{ $producto['titulo_imagen'] }}.jpg" class="object-cover h-28 w-28 rounded-2xl" alt="{{ $producto['titulo_imagen'] }}"/>
                                        </div>
                                    </td>
                                    <td class="p-4 px-6 text-center whitespace-nowrap">
                                        <div class="flex flex-col items-center justify-center">
                                            <h2 class="font-bold">{{ $producto['titulo_producto'] }}</h2>
                                        </div>
                                    </td>
                                    <td class="p-4 px-6 text-center whitespace-nowrap">
                                        <input name="stock_producto" value="{{ $producto['stock_total'] }}" class="hidden peer" readonly />
                                        <div>
                                            <button class="decrement-btn" data-product-sku="{{ $producto['titulo_sku'] }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-flex w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                                <input type="text" name="cantidad" id="{{ $producto['titulo_sku'] }}" value="{{ $producto['cantidad'] }}" class="w-12 text-center bg-gray-100 outline-none"/>
                                            <button class="increment-btn" data-product-sku="{{ $producto['titulo_sku'] }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-flex w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="p-4 px-6 text-center whitespace-nowrap">{{ $producto['precio_producto'] }}</td>
                                    <td class="p-4 px-6 text-center whitespace-nowrap">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="lg:w-2/4">
                            <div class="mt-4">
                                <div class="px-4 py-4 rounded-md">
                                    <label for="coupon code" class="font-semibold text-gray-600">Coupon Code</label>
                                    <input type="text" placeholder="coupon code" value="LARA#234" class="w-full px-2 py-2 border border-blue-600 rounded-md outline-none"/>
                                    <span class="block text-green-600">Coupon code applied successfully</span>
                                    <button class="px-6 py-2 mt-2 text-sm text-indigo-100 bg-indigo-600 rounded-md hover:bg-indigo-700">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="py-4 rounded-md shadow">
                                <h3 class="text-xl font-bold text-blue-600">Order Summary</h3>
                                <div class="flex justify-between px-4">
                                    <span class="font-bold">Subtotal</span>
                                    <span class="font-bold">$35.25</span>
                                </div>
                                <div class="flex justify-between px-4">
                                    <span class="font-bold">Discount</span>
                                    <span class="font-bold text-red-600">- $5.00</span>
                                </div>
                                <div class="flex justify-between px-4">
                                    <span class="font-bold">Sales Tax</span>
                                    <span class="font-bold">$2.25</span>
                                </div>
                                <div class="flex items-center justify-between px-4 py-2 mt-3 border-t-2">
                                    <span class="text-xl font-bold">Total</span>
                                    <span class="text-2xl font-bold">$37.50</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="w-full py-2 text-center text-white bg-blue-500 rounded-md shadow hover:bg-blue-600">
                                Proceed to Checkout
                            </button>
                            <button id="clear-cart-btn">Vaciar Carrito</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- incluir jquery --}}
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    // Incrementar cantidad
                    $('.increment-btn').click(function() {
                        var productSku = $(this).data('product-sku');
                        var stock_total = $("input[name='stock_producto']").val();
                        $.ajax({
                            url: "{{ route('cart.update', ['sku' => ':sku']) }}".replace(':sku', productSku),
                            type: 'POST',
                            data: { 
                                cantidad_sumar:1,
                                stock_total: stock_total
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                               console.log(response);
                               $("input[id='"+response.sku+"']").val(response.cantidad_actualizada);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    });

                    // Decrementar cantidad
                    $('.decrement-btn').click(function() {
                        var productSku = $(this).data('product-sku');
                        $.ajax({
                            url: "{{ route('cart.remove', ['sku' => ':sku']) }}".replace(':sku', productSku),
                            type: 'POST',
                            data: { 
                                cantidad_restar:1
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log(response);
                                $("input[id='"+response.sku+"']").val(response.cantidad_actualizada);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    });

                    // Botón para vaciar el carrito
                    $('#clear-cart-btn').click(function() {
                        $.ajax({
                            url: "{{ route('cart.clear') }}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                console.log(response);
                                //alert('Carrito eliminado con éxito');
                                // Redirigir o actualizar la página para reflejar el carrito vacío
                                window.location.href = "{{ route('cart.index') }}";
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                                alert('Error al eliminar el carrito');
                            }
                        });
                    });
                });



            </script>
        </x-app-layout>
    </body>
</html>
