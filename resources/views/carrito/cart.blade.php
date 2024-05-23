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
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                // Función para calcular el total
                function calcularTotal() {
                    let total = 0;
                    const productos = document.querySelectorAll('.cart-product');
                    if (productos.length > 0) {
                        productos.forEach(producto => {
                            const precio = parseFloat(producto.dataset.productPrice.replace(/[^\d.,]/g, '').replace(',', '.'));
                            const cantidad = parseInt(producto.querySelector('.cantidad_selector').value);
                            total += precio * cantidad;
                        });
                        // Formatear el total en euros con separador de miles y dos decimales
                        const formatterEuro = new Intl.NumberFormat("es-ES", {
                            style: "currency",
                            currency: "EUR",
                            minimumFractionDigits: 2,
                            // Especificar separador de miles
                            useGrouping: true,
                        });
                        const formattedTotal = formatterEuro.format(total);
                        document.querySelector('#total-price').textContent = `${formattedTotal}`;
                        document.querySelector('#subTotal-price').textContent = `${formattedTotal}`;
                        document.querySelector('#total').value= `${formattedTotal}`;
                    }
                }

                // Llamar a la función al cargar la página
                calcularTotal();
                
                // Incrementar cantidad
                $('.cantidad_selector').change(function() {
                    var cantidadSeleccionada = $(this).val();
                    var productSku = $(this).data('product-sku');
                    $.ajax({
                        url: "{{ route('cart.update', ['sku' => ':sku']) }}".replace(':sku', productSku),
                        type: 'POST',
                        data: { 
                            cantidad_sumar: cantidadSeleccionada
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response);
                            calcularTotal();
                            document.querySelector('#cantidad_producto').value= response.cantidad_seleccionada;
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

    </head>
    <body>
        <x-app-layout>
            @php
                // print_r(session('cart'));
                function clasificarEstado($frase) {
                    preg_match('/\w+$/', $frase, $coincidencias);

                    if (isset($coincidencias[0])) {
                        $ultima_palabra = $coincidencias[0];
                        switch (strtolower($ultima_palabra)) {
                            case 'sta':
                            case 'cor':
                                return 'Correcto';
                            case 'bue':
                            case 'mbu':
                                return 'Muy bueno';
                            case 'imp':
                                return 'Impecable';
                            default:
                                return 'Desconocido';
                        }
                    } else {
                        return 'Desconocido';
                    }
                }
            @endphp
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Cart') }}
                </h2>
            </x-slot>
            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <div id="iphone-list" class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 w-11/12 sm:w-full sm:h-full m-auto">                      
                    <div class="container pt-4 p-8 mx-auto">
                        <div class="w-full overflow-x-auto">
                            @if (count($cookieCart) > 0)
                                <form action="{{ route('cart.processOrder') }}" method="POST">
                                    @csrf
                                    @foreach ($cookieCart as $producto)
                            {{--------------------------------------------------------------------------------------------------------------------------------------------------}}
                                        <input type="hidden" name="productos[{{ $loop->index }}][sku_producto]" value="{{ $producto['titulo_sku'] }}">
                                        <input type="hidden" name="productos[{{ $loop->index }}][titulo_producto]" value="{{ $producto['titulo_producto'] }}">
                                        <input type="hidden" name="productos[{{ $loop->index }}][estado_producto]" value="{{ clasificarEstado($producto['titulo_sku']) }}">
                                        <input type="hidden" name="productos[{{ $loop->index }}][precio_producto]" value="{{ $producto['precio_producto'] }}">
                                        <input type="hidden" id="cantidad_producto" name="productos[{{ $loop->index }}][cantidad_producto]" value="{{ $producto['cantidad'] }}">

                                        <div class="mb-32 w-full md:mb-6 md:mt-4 md:flex md:items-start border-b-2 border-gray-200 pb-4 cart-product" data-product-price="{{ $producto['precio_producto'] }}" data-product-sku="{{ $producto['titulo_sku'] }}">
                                            <div class="flex grow items-start overflow-hidden">
                                                <a href="#scroll=false" rel="noreferrer noopener" >
                                                    <img class="h-60 w-60 max-w-60 cursor-pointer align-top md:h-[110px] md:w-[110px] md:max-w-[110px] h-auto max-h-full max-w-full leading-none" alt="{{ $producto['titulo_imagen'] }}" height="60" decoding="async" loading="lazy" sizes="100vw" src="./imagenes/{{ $producto['titulo_imagen'] }}.jpg" width="60">
                                                </a>
                                                <div class="ml-6 inline-block md:ml-10">
                                                    <a href="#scroll=false" rel="noreferrer noopener" class="rounded-md cursor-pointer no-underline hover:underline">
                                                        <div class="font-bold">{{ $producto['titulo_producto'] }}</div>
                                                    </a>
                                                    <div class="flex">
                                                        <p class="text-gray-700">Aspecto</p>
                                                        <p class="text-gray-700 ml-2">{{ clasificarEstado($producto['titulo_sku']) }}</p>
                                                    </div>
                                                    <div role="note">
                                                        <p class="text-gray-700">Incluye: Cable cargador</p>
                                                    </div>
                                                    <div class="mb-8 flex md:my-4">
                                                        <button class="rounded-md  cursor-pointer underline" type="button">
                                                            <span class="text-gray-600 font-bold">2 años de garantía comercial</span>
                                                        </button>
                                                    </div>
                                                    <div class="font-bold" data-qa="price">{{ $producto['precio_producto'] }}</div>
                                                </div>
                                            </div>
                                            <div class="my-20 flex justify-between md:my-0">
                                                @if( $producto['mensaje_stock']==="NoStock")
                                                    <span aria-expanded="false" aria-haspopup="true">
                                                        <div class="relative inline-block" has-selected-option="false">
                                                            <select data-product-sku="{{ $producto['titulo_sku'] }}" aria-label="Cantidad" class="cantidad_selector cursor-pointer text-red-700 font-bold bg-red-50 border-none py-2 pl-4 pr-10 rounded-md leading-tight" disabled>
                                                                <option class="bg-white" value="0" selected>No hay Stock</option>
                                                            </select>
                                                        </div>
                                                    </span>                                            
                                                @else
                                                    <span aria-expanded="false" aria-haspopup="true">
                                                        <div class="relative inline-block" has-selected-option="false">
                                                            <select data-product-sku="{{ $producto['titulo_sku'] }}" aria-label="Cantidad" class="cantidad_selector cursor-pointer font-bold transition duration-50 ease-in bg-gray-100 hover:bg-gray-200 border border-none focus:ring-gray-300 focus:ring-2 py-2 pl-4 pr-10 rounded-md leading-tight">
                                                                @for ($i = 1; $i <= $producto['stock_total']; $i++)
                                                                    <option class="bg-white" value="{{ $i }}" {{ $i == $producto['cantidad'] ? 'selected' : '' }}>{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </span>
                                                @endif
                                                <a href="{{ route('cart.remove', ['sku' => $producto['titulo_sku']]) }}" class="eliminar-btn font-bold rounded-md relative inline-flex cursor-pointer items-center justify-center px-3 no-underline transition duration-300 ease-in border border-solid ml-3 text-red-700 border-red-600 hover:bg-red-50">
                                                    <span class="flex items-center truncate">
                                                        <span class="flex items-center space-x-8 truncate visible">
                                                            <span class="truncate">Suprimir</span>
                                                        </span>
                                                    </span>
                                                </a>                                                
                                            </div>
                                        </div>
                            {{--------------------------------------------------------------------------------------------------------------------------------------------------}}
                                    @endforeach
        
                                    {{-- <div class="lg:w-2/4">
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
                                    </div> --}}
                                    <div class="">
                                        <div class="rounded-md">
                                            {{-- <h3 class="text-xl font-bold text-blue-600">Order Summary</h3> --}}
                                            <div class="flex justify-between px-4 pb-2">
                                                <span class="font-bold">Subtotal</span>
                                                <span class="font-bold" id="subTotal-price">0,00 €</span>
                                            </div>
                                            {{-- <div class="flex justify-between px-4">
                                                <span class="font-bold">Discount</span>
                                                <span class="font-bold text-red-600">- $5.00</span>
                                            </div>
                                            <div class="flex justify-between px-4">
                                                <span class="font-bold">Sales Tax</span>
                                                <span class="font-bold">$2.25</span>
                                            </div> --}}
                                            <div class="flex items-center justify-between px-4 py-3 mt-4 border-t-2 border-gray-400 border-dotted">
                                                <span class="text-xl font-bold">Total</span>
                                                <span class="text-2xl font-bold" id="total-price">0,00 €</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        
                                            
                                            <input type="hidden" name="total" id="total" value="0">
                                            <button class="w-full py-2 text-center text-white bg-blue-500 rounded-md shadow hover:bg-blue-600">
                                                Proceed to Checkout
                                            </button>
                                        
                                        {{-- <button id="clear-cart-btn">Vaciar Carrito</button> --}}
                                    </div>

                                </form>

                            @else
                                <p>El carrito está vacío.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>
