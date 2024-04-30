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

            {{-- <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in!") }}
                            <h1>Bienvenido {{ auth()->user()->name }}</h1>
                            
                            @if (!isset($tipo))
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    {{ __("Elige el movil") }}
                                </div>

                            <!-- Mostrar lista de modelos de iPhone -->
                            <div id="iphone-list">
                                @php
                                    $productosColeccion = json_decode($productos, true);
                                    $productosAgrupados = collect($productosColeccion)->groupBy('title');
                                @endphp

                                @foreach($productosAgrupados as $nombreProducto => $productos)
                                    @php
                                        // Obtén el primer producto de cada grupo
                                        $productoAPI2 = $productos->first();
                                    @endphp
                                    
                                    <ul>
                                        <li>{{ $productoAPI2['title'] }}</li>
                                    </ul>
                            @endforeach 
                            </div>







                            @else
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    {{ __("Elige el movil") }}
                                </div>
                            @endif
                            
                            
                            @if (!isset($modelo) && isset($tipo))
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    {{ __("Elige el modelo") }}
                                </div>
                            @endif
                            
                            @if (isset($tipo) && isset($modelo))
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    {{ __("Se muestran los modelos") }}
                                </div>
                            @endif
                                
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                @if ((!isset($productos)))
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("No se han encontrado productos") }}
                        </div>
                    </div>
                @else
                    {{-- <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 xl:gap-x-8">
                        <!-- Mostrar lista de modelos de iPhone -->
                        @php
                            $productosColeccion = json_decode($productos, true);
                            $productosAgrupados = collect($productosColeccion)->groupBy(function ($producto){
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

                                    // El nombre del teléfono
                                    $nombreTelefono = trim($matches[1]);
                                    preg_match('/^(\S*\s?){2}/i', $nombreTelefono, $matches);
                                    $nombreTelefono2 = trim($matches[0]);

                                    $descripcionProductoAPI =$capacidad." ". $restoFrase;
                                } else {
                                    echo "No se pudo encontrar el nombre del teléfono y la capacidad en el título del producto.";
                                }

                                    //sacamos el nombre para la imagen
                                    //quitamos los espacios
                                    $nombreImagenAPI = $nombreTelefono." ".$restoFrase;
                                    $nombreImagenAPI= str_replace(' ','',$nombreImagenAPI);
                                    
                                    //la ponemos en minuscula
                                    $nombreImagenAPI= strtolower($nombreImagenAPI);
                            @endphp
                            <div id="iphone-list" class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 w-11/12 sm:w-full sm:h-full m-auto">
                                <a href={{ route('admin.modelo') }} rel="noreferrer noopener" class="group focus:outline-none group md:box-border relative grid grid-cols-3 sm:grid-cols-1 items-center">
                                    <div class="h-auto w-full overflow-hidden rounded-lg xl:aspect-h-8 xl:aspect-w-7 w-2/3 sm:w-fit m-1">
                                        <img src="./imagenes/{{$nombreImagenAPI}}.jpg" alt="{{ $nombreImagenAPI }}" class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="col-span-2">
                                        <h3 class="mt-4 text-xl text-gray-700 font-bold text-center">{{$nombreTelefono2}}</h3>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>                 --}}
                @endif
            </div>
        </x-app-layout>
    </body>
</html>
