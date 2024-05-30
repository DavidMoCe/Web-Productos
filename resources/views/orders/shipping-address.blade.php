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
                            // Seleccionar el elemento que contiene el precio
                            const precioElemento = producto.querySelector('[data-qa="price"]');
                            // Extraer el texto del precio, eliminar el símbolo de moneda y los espacios
                            let precioTexto = precioElemento.textContent.replace('€', '').replace(/\s/g, '');
                            // Reemplazar los puntos de los miles por nada
                            precioTexto = precioTexto.replace(/\./g, '');
                            // Reemplazar la coma decimal por un punto
                            precioTexto = precioTexto.replace(',', '.');
                            // Convertir el precio a número flotante
                            const precio = parseFloat(precioTexto);
                            // Obtener la cantidad del selector correspondiente
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
                            
                            document.querySelector('#cantidad_producto').value= response.cantidad_seleccionada;
                            calcularTotal();
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
                //print_r(session('cart'));
                if(!function_exists('clasificarEstado')){
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
                }
                
            @endphp
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dirección de Envío') }}
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
                                <div class="form-group">
                                    <label for="address">Dirección:</label>
                                    <input type="text" id="address" name="address" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Teléfono:</label>
                                    <input type="text" id="phone" name="phone" class="form-control" required>
                                </div>
                        
                                <!-- Mostrar el carrito -->
                                <h3>Contenido del carrito:</h3>
                                @if (session('carrito') && session('carrito')['productos'])
                                    
                                        @foreach (session('carrito')['productos'] as $producto)
                                            {{-- {{print_r($producto)}} --}}
                                            <li>{{ $producto['titulo_producto'] }}</li>
                                        @endforeach
                                @else
                                    <p>El carrito está vacío.</p>
                                @endif
                                <button type="submit" class="btn btn-primary mt-3">Confirmar Dirección</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>


{{-- <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Dirección de Envío') }}
    </h2>
</x-slot>

<div class="container mt-5"> --}}
    {{-- <form action="{{ route('process.shipping') }}" method="POST"> --}}
        {{-- <form action="" method="POST">
        @csrf
        <div class="form-group">
            <label for="address">Dirección:</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="phone">Teléfono:</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
        </div>

        <!-- Mostrar el carrito -->
        <h3>Contenido del carrito:</h3>
        @if (session('carrito') && session('carrito')['productos'])
            <ul>
                @foreach (session('carrito')['productos'] as $producto)
                    {{print_r($producto)}}
                    <li>{{ $producto['titulo_producto'] }}</li>
                @endforeach
            </ul>
        @else
            <p>El carrito está vacío.</p>
        @endif

        <button type="submit" class="btn btn-primary mt-3">Confirmar Dirección</button>
    </form>
</div> --}}