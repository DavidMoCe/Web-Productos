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
                    {{ __('Dashboard') }}
                </h2>
            </x-slot>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{-- {{ __("You're logged in!") }} --}}
                            {{-- <h1>Bienvenido {{ auth()->user()->name }}</h1>--}}
                            <button id="actualizarBD" class="bg-gray-50 p-3 rounded-md border border-black">Actualizar BD</button>
                            {{-- <button id="verPedidos" class="bg-gray-50 p-3 rounded-md border border-black">Ver pedidos</button> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                @if ((!isset($productos)) ||  $productos->isEmpty())
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("No se han encontrado productos") }}
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 xl:gap-x-8">
                        <!-- Mostrar lista de modelos de iPhone -->
                        @php
                            $productosAgrupados = $productos->filter(function ($producto) {
                                    //mostramos solo los productos que emppiezen por iPhone
                                    return stripos($producto['title'], 'iPhone') === 0;
                                })->groupBy(function ($producto){
                                preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $producto['title'], $matches);
                                if (isset($matches[1])) {
                                    $nombreTelefono = trim($matches[1]);
                                    preg_match('/(\S*\s?){2}/', $nombreTelefono, $matches);
                                    $nombreTelefono = trim($matches[0]);
                                    return $nombreTelefono;
                                } else {
                                    // Manejar el caso donde no se encuentra el nombre del teléfono
                                    return 'Nombre no encontrado';
                                }
                            });
                        @endphp

                        @foreach($productosAgrupados as $nombreProducto => $productos)
                            @php
                                // Obtén el primer producto de cada grupo
                                $productoAPI2 = $productos->first();

                                // Encuentra el nombre del teléfono, la capacidad y el resto de la frase
                                if (preg_match('/^(.*?)\s*(\d+[MTG]B\b)(\s.*)$/i', $productoAPI2['title'], $matches)) {
                                     // La capacidad
                                     $capacidad = isset($matches[2]) ? $matches[2] : '';
                                    // El resto de la frase
                                    $restoFrase = isset($matches[3]) ? trim($matches[3]) : '';

                                    // El nombre del teléfono sin el modelo
                                    $nombreTelefono = trim($matches[1]);
                                    preg_match('/^(\S*\s?){2}/i', $nombreTelefono, $matches);
                                    $nombreTelefono = trim($matches[0]);

                                    $descripcionProductoAPI =$capacidad." ". $restoFrase;
                                } else {
                                    echo "No se pudo encontrar el nombre del teléfono y la capacidad en el título del producto.";
                                }

                                //sacamos el color del telefono
                                preg_match('/\s*-\s*[\p{L}\s]+?(?=\s*-)/', $restoFrase, $matches);
                                $nombreImagenAPIResto = isset($matches[0]) ? trim($matches[0]) : $nombreImagenAPI;

                                //sacamos el nombre para la imagen
                                $nombreImagenAPI = $nombreTelefono.$nombreImagenAPIResto;
                                $nombreImagenAPI= str_replace(' ','',$nombreImagenAPI);//quitamos los espacios
                                
                                //la ponemos en minuscula
                                $nombreImagenAPI= strtolower($nombreImagenAPI);
                            @endphp
                            <div id="iphone-list" class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 w-11/12 sm:w-full sm:h-full m-auto">
                                <a href={{ route('admin.modelo', ['tipo' => $nombreTelefono]) }} rel="noreferrer noopener" class="group focus:outline-none group md:box-border relative grid grid-cols-3 sm:grid-cols-1 items-center">
                                    <div class="h-auto w-full overflow-hidden rounded-lg xl:aspect-h-8 xl:aspect-w-7 w-2/3 sm:w-fit m-1">
                                        <img src="./imagenes/{{$nombreImagenAPI}}.jpg" alt="{{ $nombreImagenAPI }}" class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="col-span-2">
                                        <h3 class="mt-4 text-xl text-gray-700 font-bold text-center">{{$nombreTelefono}}</h3>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>                
                @endif
            </div>
        </x-app-layout>
    </body>
    <script>
        document.getElementById('actualizarBD').addEventListener('click', function() {
            window.location.href = "{{ route('actualizarProductos') }}";
        });

        // document.getElementById('verPedidos').addEventListener('click', function() {
        //     window.location.href = "{{ route('verTodosPedidos', ['vista' => 'Recibidos']) }}";
        // });
      
    </script>
</html>
