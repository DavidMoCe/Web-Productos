<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
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
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Products') }}
                </h2>
            </x-slot>

            <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                @if ( (!isset($productos) && !isset($productosAPI)))
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("No se han encontrado productos") }}
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 xl:gap-x-8">
                    @if (isset($productos))
                        <!-- obtenemos los productos de la BD -->
                        @foreach($productos as $producto)
                            @php
                                // Aplicar la expresión regular para capturar la capacidad
                                if (preg_match('/\S*\s*(?!-)\S+/', $producto->descripcion, $matches)) {
                                    // Obtener la coincidencia y asignarla a una variable
                                    $capacidad = $matches[0];
                                }
                                //sacamos el nombre para la imagen
                                //quitamos la capacidad y los espacios
                                $nombreImagen= str_replace($capacidad,'',$producto->descripcion);
                                $nombreImagen= str_replace(' ','',$producto->nombre.$nombreImagen);
                                //la ponemos en minuscula
                                $nombreImagen= strtolower($nombreImagen);
                            @endphp

                            <div class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 w-11/12 sm:w-full sm:h-full m-auto">
                                <a href='info-products' rel="noreferrer noopener" class="group focus:outline-none group md:box-border relative grid grid-cols-3 sm:grid-cols-1 items-center">
                                    <div class="h-auto w-full overflow-hidden rounded-lg xl:aspect-h-8 xl:aspect-w-7 w-2/3 sm:w-fit m-1">
                                        <img src="./imagenes/{{$nombreImagen}}.jpg" alt="{{ $producto->nombre." ".$producto->descripcion }}" class="h-full w-full object-cover object-center">
                                    </div>
                                    <div class="col-span-2">
                                        <h3 class="mt-4 text-base text-gray-700 font-bold">{{ $producto->nombre }}</h3>
                                        <h3 class="mt-2 text-sm text-gray-700 mb-2">{{ $producto->descripcion }} </h3>
                                        <div class="flex items-center">
                                            <svg aria-label="filledStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                <path d="m25.75 6.07 4.73 9.43A1.93 1.93 0 0032 16.55l10.56 1.51a1.92 1.92 0 011.09 3.28L36 28.68a1.91 1.91 0 00-.56 1.71l1.8 10.36a1.94 1.94 0 01-2.83 2l-9.45-4.89a2 2 0 00-1.82 0l-9.45 4.89a1.94 1.94 0 01-2.83-2l1.8-10.36a1.91 1.91 0 00-.56-1.71l-7.7-7.34a1.92 1.92 0 011.09-3.28l10.56-1.51a1.93 1.93 0 001.47-1.05l4.73-9.43a2 2 0 013.5 0Z"></path>
                                            </svg>
                                            <svg aria-label="filledStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                <path d="m25.75 6.07 4.73 9.43A1.93 1.93 0 0032 16.55l10.56 1.51a1.92 1.92 0 011.09 3.28L36 28.68a1.91 1.91 0 00-.56 1.71l1.8 10.36a1.94 1.94 0 01-2.83 2l-9.45-4.89a2 2 0 00-1.82 0l-9.45 4.89a1.94 1.94 0 01-2.83-2l1.8-10.36a1.91 1.91 0 00-.56-1.71l-7.7-7.34a1.92 1.92 0 011.09-3.28l10.56-1.51a1.93 1.93 0 001.47-1.05l4.73-9.43a2 2 0 013.5 0Z"></path>
                                            </svg>
                                            <svg aria-label="filledStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                <path d="m25.75 6.07 4.73 9.43A1.93 1.93 0 0032 16.55l10.56 1.51a1.92 1.92 0 011.09 3.28L36 28.68a1.91 1.91 0 00-.56 1.71l1.8 10.36a1.94 1.94 0 01-2.83 2l-9.45-4.89a2 2 0 00-1.82 0l-9.45 4.89a1.94 1.94 0 01-2.83-2l1.8-10.36a1.91 1.91 0 00-.56-1.71l-7.7-7.34a1.92 1.92 0 011.09-3.28l10.56-1.51a1.93 1.93 0 001.47-1.05l4.73-9.43a2 2 0 013.5 0Z"></path>
                                            </svg>
                                            <svg aria-label="filledStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                <path d="m25.75 6.07 4.73 9.43A1.93 1.93 0 0032 16.55l10.56 1.51a1.92 1.92 0 011.09 3.28L36 28.68a1.91 1.91 0 00-.56 1.71l1.8 10.36a1.94 1.94 0 01-2.83 2l-9.45-4.89a2 2 0 00-1.82 0l-9.45 4.89a1.94 1.94 0 01-2.83-2l1.8-10.36a1.91 1.91 0 00-.56-1.71l-7.7-7.34a1.92 1.92 0 011.09-3.28l10.56-1.51a1.93 1.93 0 001.47-1.05l4.73-9.43a2 2 0 013.5 0Z"></path>
                                            </svg>
                                            <svg aria-label="emptyStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                <path d="M13.39 43a2.88 2.88 0 01-1.68-.55 2.79 2.79 0 01-1.11-2.76l1.7-9.81a.82.82 0 00-.24-.72l-7.2-7a2.82 2.82 0 011.57-4.81l10-1.44a.82.82 0 00.57-.41l4.45-8.93a2.84 2.84 0 015.08 0L31 15.5a.82.82 0 00.63.45l10 1.44a2.82 2.82 0 011.57 4.81l-7.2 7a.82.82 0 00-.24.72l1.7 9.81a2.79 2.79 0 01-1.11 2.76 2.83 2.83 0 01-3 .22L24.39 38a.83.83 0 00-.78 0l-8.9 4.63a2.84 2.84 0 01-1.32.37ZM24 7a.8.8 0 00-.75.46l-4.45 8.93a2.82 2.82 0 01-2.14 1.54l-9.94 1.44a.82.82 0 00-.47 1.4l7.2 7a2.81 2.81 0 01.82 2.5L12.57 40a.79.79 0 00.32.79.83.83 0 00.9.07l8.89-4.63a2.86 2.86 0 012.64 0l8.89 4.63a.83.83 0 00.9-.07.79.79 0 00.32-.79l-1.7-9.82a2.81 2.81 0 01.82-2.5l7.2-6.95a.82.82 0 00-.47-1.4l-9.94-1.44a2.82 2.82 0 01-2.14-1.54l-4.45-8.89A.8.8 0 0024 7Z"></path>
                                            </svg>
                                            <p class="text-sm font-medium text-black-500 dark:text-white-400 ml-2">(13654)</p>
                                        </div>
                                        <span class="md:mt-2 text-gray-700">
                                            Desde
                                        </span> 
                                        <div>
                                            <span class="text-primary body-2-bold text-sm font-medium text-black-900">
                                                <b> {{ str_replace('.', ',', number_format($producto->precioD * 0.95, 2)) }}&nbsp;€</b>
                                            </span>
                                            <span class="text-primary-light line-through body-2-light text-sm font-medium text-gray-600">
                                                {{ str_replace('.', ',', number_format($producto->precioA, 2)) }}&nbsp;€
                                                <span>
                                                    nuevo
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                    @if (isset($productosAPI))
                        {{-- Ejemplo con producto de back market --}}
                        
                        {{-- Agrupar los productos por nombre --}}
                        {{-- @foreach($productosAPI->groupBy('title') as $nombreProducto => $productos) --}}
                            @foreach($productosAPI as $productoAPI2)
                                @php
                                    // Obtén el primer producto de cada grupo
                                    // $productoAPI2 = $productos->first();

                                    // Encuentra el nombre del teléfono, la capacidad y el resto de la frase
                                    if (preg_match('/^(.*?)\s*(\d+[TG]B\b)(\s.*)$/i', $productoAPI2['title'], $matches)) {
                                        // El nombre del teléfono
                                        $nombreTelefono = trim($matches[1]);
                                        // La capacidad
                                        $capacidad = isset($matches[2]) ? $matches[2] : '';
                                        // El resto de la frase
                                        $restoFrase = isset($matches[3]) ? trim($matches[3]) : '';

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

                                <div class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 w-11/12 sm:w-full sm:h-full m-auto">
                                    <a href='info-products' rel="noreferrer noopener" class="group focus:outline-none group md:box-border relative grid grid-cols-3 sm:grid-cols-1 items-center">
                                        <div class="h-auto w-full overflow-hidden rounded-lg xl:aspect-h-8 xl:aspect-w-7 w-2/3 sm:w-fit m-1">
                                            <img src="./imagenes/{{$nombreImagenAPI}}.jpg" alt="{{ $productoAPI2['title'] }}" class="h-full w-full object-cover object-center">
                                        </div>
                                        <div class="col-span-2">
                                            <h3 class="mt-4 text-base text-gray-700 font-bold">{{$nombreTelefono}}</h3>
                                            <h3 class="mt-2 text-sm text-gray-700 mb-2">{{$descripcionProductoAPI}} </h3>
                                            <h3 class="mt-2 text-sm text-gray-700 mb-2">{{$productoAPI2['quantity']}} </h3>
                                            <div class="flex items-center">
                                                <svg aria-label="filledStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                    <path d="m25.75 6.07 4.73 9.43A1.93 1.93 0 0032 16.55l10.56 1.51a1.92 1.92 0 011.09 3.28L36 28.68a1.91 1.91 0 00-.56 1.71l1.8 10.36a1.94 1.94 0 01-2.83 2l-9.45-4.89a2 2 0 00-1.82 0l-9.45 4.89a1.94 1.94 0 01-2.83-2l1.8-10.36a1.91 1.91 0 00-.56-1.71l-7.7-7.34a1.92 1.92 0 011.09-3.28l10.56-1.51a1.93 1.93 0 001.47-1.05l4.73-9.43a2 2 0 013.5 0Z"></path>
                                                </svg>
                                                <svg aria-label="filledStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                    <path d="m25.75 6.07 4.73 9.43A1.93 1.93 0 0032 16.55l10.56 1.51a1.92 1.92 0 011.09 3.28L36 28.68a1.91 1.91 0 00-.56 1.71l1.8 10.36a1.94 1.94 0 01-2.83 2l-9.45-4.89a2 2 0 00-1.82 0l-9.45 4.89a1.94 1.94 0 01-2.83-2l1.8-10.36a1.91 1.91 0 00-.56-1.71l-7.7-7.34a1.92 1.92 0 011.09-3.28l10.56-1.51a1.93 1.93 0 001.47-1.05l4.73-9.43a2 2 0 013.5 0Z"></path>
                                                </svg>
                                                <svg aria-label="filledStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                    <path d="m25.75 6.07 4.73 9.43A1.93 1.93 0 0032 16.55l10.56 1.51a1.92 1.92 0 011.09 3.28L36 28.68a1.91 1.91 0 00-.56 1.71l1.8 10.36a1.94 1.94 0 01-2.83 2l-9.45-4.89a2 2 0 00-1.82 0l-9.45 4.89a1.94 1.94 0 01-2.83-2l1.8-10.36a1.91 1.91 0 00-.56-1.71l-7.7-7.34a1.92 1.92 0 011.09-3.28l10.56-1.51a1.93 1.93 0 001.47-1.05l4.73-9.43a2 2 0 013.5 0Z"></path>
                                                </svg>
                                                <svg aria-label="filledStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                    <path d="m25.75 6.07 4.73 9.43A1.93 1.93 0 0032 16.55l10.56 1.51a1.92 1.92 0 011.09 3.28L36 28.68a1.91 1.91 0 00-.56 1.71l1.8 10.36a1.94 1.94 0 01-2.83 2l-9.45-4.89a2 2 0 00-1.82 0l-9.45 4.89a1.94 1.94 0 01-2.83-2l1.8-10.36a1.91 1.91 0 00-.56-1.71l-7.7-7.34a1.92 1.92 0 011.09-3.28l10.56-1.51a1.93 1.93 0 001.47-1.05l4.73-9.43a2 2 0 013.5 0Z"></path>
                                                </svg>
                                                <svg aria-label="emptyStar" fill="currentColor" height="16" role="img" viewBox="0 0 48 48" width="16" xmlns="http://www.w3.org/2000/svg" class="text-primary">
                                                    <path d="M13.39 43a2.88 2.88 0 01-1.68-.55 2.79 2.79 0 01-1.11-2.76l1.7-9.81a.82.82 0 00-.24-.72l-7.2-7a2.82 2.82 0 011.57-4.81l10-1.44a.82.82 0 00.57-.41l4.45-8.93a2.84 2.84 0 015.08 0L31 15.5a.82.82 0 00.63.45l10 1.44a2.82 2.82 0 011.57 4.81l-7.2 7a.82.82 0 00-.24.72l1.7 9.81a2.79 2.79 0 01-1.11 2.76 2.83 2.83 0 01-3 .22L24.39 38a.83.83 0 00-.78 0l-8.9 4.63a2.84 2.84 0 01-1.32.37ZM24 7a.8.8 0 00-.75.46l-4.45 8.93a2.82 2.82 0 01-2.14 1.54l-9.94 1.44a.82.82 0 00-.47 1.4l7.2 7a2.81 2.81 0 01.82 2.5L12.57 40a.79.79 0 00.32.79.83.83 0 00.9.07l8.89-4.63a2.86 2.86 0 012.64 0l8.89 4.63a.83.83 0 00.9-.07.79.79 0 00.32-.79l-1.7-9.82a2.81 2.81 0 01.82-2.5l7.2-6.95a.82.82 0 00-.47-1.4l-9.94-1.44a2.82 2.82 0 01-2.14-1.54l-4.45-8.89A.8.8 0 0024 7Z"></path>
                                                </svg>
                                                <p class="text-sm font-medium text-black-500 dark:text-white-400 ml-2">(13654)</p>
                                            </div>
                                            <span class="md:mt-2 text-gray-700">
                                                Desde
                                            </span> 
                                            <div>
                                                <span class="text-primary body-2-bold text-sm font-medium text-black-900">
                                                    <b> {{ str_replace('.', ',', number_format($productoAPI2['price'] * 0.95, 2)) }}&nbsp;€</b>
                                                </span>
                                                <span class="text-primary-light line-through body-2-light text-sm font-medium text-gray-600">
                                                    {{ str_replace('.', ',', number_format($productoAPI2['price'], 2)) }}&nbsp;€
                                                    <span>
                                                        nuevo
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            {{-- @endforeach --}}
                        @endforeach  
                    @endif

                    <!-- More products... -->
                </div>
                <!-- Mostrar enlaces de paginación -->
                <div class="flex justify-center mt-4">
                    <nav class="block">
                        <ul class="flex pl-0 rounded list-none flex-wrap">
                            @php
                                $Totalpaginas = ceil($Totalproductos / $productosPorPaginas);
                            @endphp
                            @for ($x = 1; $x <= $Totalpaginas; $x++)
                                <li>
                                    <a href="{{ route('products', ['pagina' => 'page='.$x]) }}" class="pagination-link block hover:bg-gray-200 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">{{ $x }}</a>
                                </li>
                            @endfor
                        </ul>
                    </nav>
                </div>    
            </div>
        </x-app-layout>
    </body>
</html>
