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
        @php
            // Buscar el primer producto con un título que contenga una palabra diferente
            $productoDiferente = $info_producto->first(function ($producto) use ($capacidad) {
                // Especifica aquí la palabra diferente que estás buscando en el título
                return strpos($producto['title'], $capacidad) !== false;
            });

            // Verificar si se encontró un producto con el título que contiene la palabra diferente
            if ($productoDiferente) {
                // Acceder a las propiedades del producto encontrado
                $sku_title = $productoDiferente['sku'];
                $titulo = $productoDiferente['title'];
                $precio = $productoDiferente['price'];
                $stock = $productoDiferente['quantity'];
                $descripcion = $productoDiferente['comment'];
                $garantia = $productoDiferente['warranty_delay'];
                $moneda = $productoDiferente['currency'];
            }

        @endphp
        <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="md:flex items-start justify-center py-12 2xl:px-20 md:px-6 px-4">
                    <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->

                    <div class="xl:w-3/5 lg:w-2/5 w-80 md:mx-6 hidden md:!block">
                        <img class="w-full" alt="image of a girl posing"
                            src="https://i.ibb.co/QMdWfzX/component-image-one.png" />
                        <img class="mt-6 w-full" alt="image of a girl posing"
                            src="https://i.ibb.co/qxkRXSq/component-image-two.png" />
                    </div>
                    <div class="md:hidden">
                        <img class="w-full" alt="image of a girl posing" src="https://i.ibb.co/QMdWfzX/component-image-one.png"
                            hola />
                        <div class="flex items-center justify-between mt-3 space-x-4 md:space-x-0">
                            <img alt="image-tag-one" class="md:w-48 md:h-48 w-full"
                                src="https://i.ibb.co/cYDrVGh/Rectangle-245.png" />
                            <img alt="image-tag-one" class="md:w-48 md:h-48 w-full"
                                src="https://i.ibb.co/f17NXrW/Rectangle-244.png" />
                            <img alt="image-tag-one" class="md:w-48 md:h-48 w-full"
                                src="https://i.ibb.co/cYDrVGh/Rectangle-245.png" />
                            <img alt="image-tag-one" class="md:w-48 md:h-48 w-full"
                                src="https://i.ibb.co/f17NXrW/Rectangle-244.png" />
                        </div>
                    </div>

                    <div>
                        <!-- Aquí va el contenido de las imágenes del producto -->
                        {{-- <img src="producto1.jpg" alt="Producto 1" class="w-full">
                        <img src="producto2.jpg" alt="Producto 2" class="w-full"> --}}
                        <!-- Agrega más imágenes según sea necesario -->
                    </div>

                    <div class="my-10 mx-10 xl:w-3/4 md:mt-10 md:w-1/2 lg:ml-8 md:ml-6 md:mt-0">
                        <div class="product-details">
                            <div id=titulo-botonCompra>
                                <div id="title" class="mb-4 flex flex-row items-center justify-between">
                                    <h2 class="text-2xl font-bold">{{ $titulo }}</h2>
                                    <div id="precio" class="flex flex-col items-center">
                                        <div class="flex items-center">
                                            <div class="previous-price text-sm font-semibold text-black line-through mr-1 mt-auto">{{ str_replace('.', ',', number_format($precio, 2)) }}&nbsp;€ nuevo</div>
                                            <div class="current-price text-2xl font-semibold text-black">{{ str_replace('.', ',', number_format($precio * 0.95, 2)) }}&nbsp;€</div>
                                        </div>
                                        <div class="iva text-sm text-black ml-auto">IVA incluido*</div>
                                    </div>
                                    
                                </div>                                
                                

                                <div class="actions mb-4 flex flex-row">
                                    <div class="opinions mr-20">
                                        <!-- Aquí va el contenido de las opiniones -->
                                        <p>Opiniones de los usuarios...</p>
                                    </div>
                                    <div class="buy-button">
                                        <button class="px-4 py-2 bg-blue-500 text-white rounded">Comprar</button>
                                    </div>
                                </div>
                            </div>

                            <div id="detalles-envio">
                                <hr class="border-static-default-low border-t mb-12 mt-14 md:mb-16 bg-gray-200">
                                <div class="shipping mb-2">Envío gratis</div>
                                <div class="flex flex-row">
                                    <div class="mr-20">
                                        <div class="warranty mb-2">Garantía de 1 año</div>
                                        <div class="trial mb-2">30 días de prueba</div>
                                    </div>
                                    <div class="expert mb-2">Reacondicionado por expertos</div>
                                    <hr class="border-static-default-low border-t mb-12 mt-14 md:mb-16 bg-gray-200">
                                </div> 
                            </div>
                        
                            <div id="caracteristicas-producto">
                                <div id="lo_mejor" class="mb-32">
                                    <div class="flex flex-row justify-between">
                                        <p class="text-static-default-hi body-1 mb-8 block">Lo mejor de lo mejor</p>
                                        <div>
                                            <div class="body-2-bold">
                                                <button class="text-action-default-hi focus-visible-outline-default-hi rounded-md font-weight-body-1-link cursor-pointer hover:text-action-default-hi-hover underline" type="button">Ver más</button>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="grid list-none gap-8 grid-cols-2">
                                        <li data-qa="recommended_variants-0">
                                            <a href="/es-es/p/iphone-13-128-gb-libre/ef5660d2-6883-4b81-b47d-86e5720687ef#l=12&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-brand-hi" rel="noreferrer noopener" aria-disabled="false" productid="ef5660d2-6883-4b81-b47d-86e5720687ef" grade="[object Object]" disabled="false" role="link">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-brand-hi">
                                                </div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-action-brand-hi">
                                                            <path fill-rule="evenodd" d="M3.15 6.28C4 4 5.2 2.65 6.57 2.65c2.43 0 4.31 4.17 4.31 9.49 0 3.39-.8 6.53-2.08 8.2a2.89 2.89 0 0 1-2.23 1.3c-2.42 0-4.31-4.18-4.31-9.5a18 18 0 0 1 .89-5.86m1.45 6.5h.07a.51.51 0 0 0 .44-.57A11.46 11.46 0 0 1 5 9.29a.502.502 0 1 0-1-.09 13.07 13.07 0 0 0 .1 3.15.51.51 0 0 0 .5.43m-.41-6.42c1.42.64 5.68 3.23 4.8 11.42l.01.05c.611-1.83.908-3.75.88-5.68 0-5.01-1.74-8.5-3.31-8.5-.86 0-1.74 1.01-2.38 2.71m9.31.49c.8-2.67 2.11-4.2 3.61-4.2 2.42 0 4.31 4.17 4.36 9.54 0 4.15-1.23 7.78-3 9-.395.29-.87.45-1.36.46-2.42 0-4.31-4.18-4.31-9.5-.018-1.79.218-3.575.7-5.3m1.62 6.59h.07a.5.5 0 0 0 .42-.58 11.4 11.4 0 0 1-.11-2.91.51.51 0 0 0-.45-.55.52.52 0 0 0-.55.45 12.758 12.758 0 0 0 .12 3.16.51.51 0 0 0 .5.43m-.61-6.56c1.24.53 5.64 3.16 4 12.9 1.15-1.43 1.91-4.37 1.87-7.63 0-5.01-1.74-8.5-3.31-8.5-.95 0-1.92 1.23-2.56 3.23" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="shrink truncate body-2 text-center text-action-brand-hi">Mejor precio</span>
                                                    </div>
                                                    <div class="text-static-brand-hi body-2 text-center">430,09&nbsp;€</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="recommended_variants-1">
                                            <a aria-current="page" href="/es-es/p/iphone-13-128-gb-libre/ec975bb8-df95-43a5-b04d-de63378f4a12#l=10&amp;scroll=false" class="router-link-active router-link-exact-active bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-brand-mid" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" grade="[object Object]" disabled="false" role="link">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-brand-mid"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-action-brand-mid"><path fill-rule="evenodd" d="M6.357 10.957A.5.5 0 0 0 6.55 11a.49.49 0 0 0 .49-.3.45.45 0 0 0 0-.2c-.01-.91.16-2.43.67-2.91a4.35 4.35 0 0 1 2.48-.6h.32A.49.49 0 0 0 11 6.5a.5.5 0 0 0-.46-.5h-.32a4.7 4.7 0 0 1-2.6-.7 4.4 4.4 0 0 1-.61-2.5v-.3a.5.5 0 0 0-.49-.5.53.53 0 0 0-.52.5v.33a4.67 4.67 0 0 1-.66 2.57 4.75 4.75 0 0 1-2.78.6.46.46 0 0 0-.37.19.5.5 0 0 0 0 .72.53.53 0 0 0 .36.15c.83 0 2.45.16 2.92.65a4.78 4.78 0 0 1 .59 2.77.5.5 0 0 0 .297.477M6.18 7a2.52 2.52 0 0 0-.72-.45c.214-.1.407-.24.57-.41.203-.2.366-.438.48-.7.097.205.225.394.38.56.19.2.413.366.66.49a2 2 0 0 0-.55.38 2.22 2.22 0 0 0-.44.68A2.69 2.69 0 0 0 6.18 7m9.2 9.86a.501.501 0 0 1-.48-.52c0-.89-.07-3.43-1-4.35-.93-.92-3.6-1.08-4.56-1.08a.43.43 0 0 1-.34-.15.47.47 0 0 1-.15-.35A.58.58 0 0 1 9 10a.61.61 0 0 1 .37-.15c.9 0 3.43-.08 4.36-1 .865-.855.986-2.714 1.063-3.895l.017-.255v-.39a.5.5 0 1 1 1 0v.44c0 1.1.2 3.14 1 4 .793.853 2.914.998 4.09 1.078l.03.002h.46a.5.5 0 0 1 .48.5.51.51 0 0 1-.49.5h-.45c-1.11 0-3.13.2-3.93 1-.8.8-1.1 3.6-1.09 4.56a.45.45 0 0 1 0 .2.49.49 0 0 1-.53.27m-2.32-6.45a3.86 3.86 0 0 1 1.57.88c.348.384.61.837.77 1.33a3.81 3.81 0 0 1 .88-1.57 3.72 3.72 0 0 1 1.32-.76 3.75 3.75 0 0 1-1.53-.87 3.64 3.64 0 0 1-.77-1.33 3.691 3.691 0 0 1-.88 1.54 3.77 3.77 0 0 1-1.36.78M8 22a.501.501 0 0 1-.48-.52 4.8 4.8 0 0 0-.6-2.77C6.45 18.18 4.85 18 4 18a.51.51 0 0 1-.36-.15.47.47 0 0 1-.15-.35.56.56 0 0 1 .15-.37A.61.61 0 0 1 4 17a4.68 4.68 0 0 0 2.77-.59 4.71 4.71 0 0 0 .67-2.63v-.32A.5.5 0 0 1 8 13a.51.51 0 0 1 .49.5v.31a4.46 4.46 0 0 0 .6 2.49 4.63 4.63 0 0 0 2.6.67H12a.51.51 0 0 1 .48.51.5.5 0 0 1-.48.52 3.163 3.163 0 0 1-.32 0 4.41 4.41 0 0 0-2.47.6c-.51.49-.68 2-.67 2.91.01.06.01.12 0 .18A.51.51 0 0 1 8 22m-1.1-4.46c.267.11.512.27.72.47.155.166.283.355.38.56.117-.266.28-.51.48-.72a2.21 2.21 0 0 1 .56-.38 2.25 2.25 0 0 1-.68-.47 2.51 2.51 0 0 1-.36-.58 2.37 2.37 0 0 1-.47.71c-.18.159-.382.29-.6.39z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="shrink truncate body-2 text-center text-action-brand-mid">Más populares</span>
                                                    </div>
                                                    <div class="text-static-brand-mid body-2 text-center">481,42&nbsp;€</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div id="estado" class="mb-32">
                                    <div class="flex flex-row justify-between">
                                        <p class="text-static-default-hi body-1 mb-8 block">Estado</p>
                                        <div>
                                            <div class="body-2-bold">
                                                <button class="text-action-default-hi focus-visible-outline-default-hi rounded-md font-weight-body-1-link cursor-pointer hover:text-action-default-hi-hover underline" type="button">Ver más</button>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="grid list-none grap-8 grid-cols-3">
                                        <li data-qa="grades-0">
                                            <a aria-current="page" href="/es-es/p/iphone-13-128-gb-libre/ec975bb8-df95-43a5-b04d-de63378f4a12#l=12&amp;scroll=false" class="router-link-active router-link-exact-active bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low bg-surface-brand-hi border-action-default-low-pressed" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" disabled="false" role="link" grade="[object Object]">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi-pressed"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="body-2-bold text-center text-action-default-hi-pressed">Correcto</span>
                                                    </div>
                                                    <div class="text-static-default-low body-2 text-center">456,36&nbsp;€</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="grades-1">
                                            <a aria-current="page" href="/es-es/p/iphone-13-128-gb-libre/ec975bb8-df95-43a5-b04d-de63378f4a12#l=12&amp;scroll=false" class="router-link-active router-link-exact-active bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low bg-surface-brand-hi border-action-default-low-pressed" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" disabled="false" role="link" grade="[object Object]">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi-pressed"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="body-2-bold text-center text-action-default-hi-pressed">Muy bueno</span>
                                                    </div>
                                                    <div class="text-static-default-low body-2 text-center">439,99&nbsp;€</div>
                                                </div>
                                                <span class="rounded-xs inline-block max-w-full truncate body-2-bold px-4 py-0 bg-static-info-max text-static-default-hi absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 -rotate-3" title="¡Oferta!">¡Oferta!</span>
                                            </a>
                                        </li>
                                        <li data-qa="grades-2">
                                            <a aria-current="page" href="/es-es/p/iphone-13-128-gb-libre/ec975bb8-df95-43a5-b04d-de63378f4a12#l=12&amp;scroll=false" class="router-link-active router-link-exact-active bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low bg-surface-brand-hi border-action-default-low-pressed" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" disabled="false" role="link" grade="[object Object]">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi-pressed"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="body-2-bold text-center text-action-default-hi-pressed">Excelente</span>
                                                    </div>
                                                    <div class="text-static-default-low body-2 text-center">481,42&nbsp;€</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="battery-replacement flex items-center justify-between border border-gray-300 rounded-md p-3">
                                        <span class="text-sm font-medium mr-3">Batería nueva</span>
                                        <label for="battery-checkbox" class="relative cursor-pointer">
                                            <div class="rounded-full border w-6 h-6 bg-white shadow-md absolute top-0 right-0"></div>
                                            <div class="slider bg-gray-300 rounded-full w-12 h-6 transition duration-300 ease-in-out"></div>
                                            <input type="checkbox" id="battery-checkbox" class="hidden">
                                        </label>
                                    </div>
                                </div>

                                <div id="capacidad" class="mb-32">
                                    <div class="flex flex-row justify-between"><p class="text-static-default-hi body-1 mb-8 block">Almacenamiento (GB)</p></div>
                                    <ul class="grid list-none gap-8 grid-cols-3">
                                        <li data-qa="storage-0">
                                            <a aria-current="page" href="/es-es/p/iphone-13-128-gb-libre/ec975bb8-df95-43a5-b04d-de63378f4a12#l=10&amp;scroll=false" class="router-link-active router-link-exact-active bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-brand-mid bg-action-default-brand-mid border-action-brand-mid-pressed" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" disabled="false" role="link" grade="[object Object]">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-brand-mid-pressed"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="body-2-bold text-center text-action-brand-mid-pressed">128 GB</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="storage-1">
                                            <a href="/es-es/p/iphone-13-256-gb-libre/58c0e06e-f4b5-4553-a0a2-1f2a5d1da186#l=10&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low" rel="noreferrer noopener" aria-disabled="false" productid="58c0e06e-f4b5-4553-a0a2-1f2a5d1da186" disabled="false" role="link" grade="[object Object]">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="body-2 text-center text-action-default-hi">256 GB</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="storage-2">
                                            <a href="/es-es/p/iphone-13-512-gb-libre/81ab240b-44af-4bb1-a59a-1321ffdd74ba#l=10&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low" rel="noreferrer noopener" aria-disabled="false" productid="81ab240b-44af-4bb1-a59a-1321ffdd74ba" disabled="false" role="link" grade="[object Object]">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="body-2 text-center text-action-default-hi">512 GB</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div id="color" class="mb-32">
                                    <div class="flex flex-row justify-between"><p class="text-static-default-hi body-1 mb-8 block">Almacenamiento (GB)</p></div>
                                    <ul class="grid list-none gap-8 grid-cols-3">
                                        <li data-qa="color-0">
                                            <a href="/es-es/p/iphone-13-256-gb-libre/c38d3dfb-6962-4d55-858b-7e1f15a0e665#l=10&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <div aria-hidden="" class="border-static-default-hi rounded-full h-16 w-16 border shrink-0 text-action-default-hi" style="background-color: rgb(24, 32, 40);"></div>
                                                        <span class="shrink truncate body-2 text-center text-action-default-hi">Medianoche</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-1">
                                            <a href="/es-es/p/iphone-13-256-gb-libre/c38d3dfb-6962-4d55-858b-7e1f15a0e665#l=10&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <div aria-hidden="" class="border-static-default-hi rounded-full h-16 w-16 border shrink-0 text-action-default-hi" style="background-color: rgb(255, 0, 0);"></div>
                                                        <span class="shrink truncate body-2 text-center text-action-default-hi">Rojo</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-2">
                                            <a href="/es-es/p/iphone-13-256-gb-libre/c38d3dfb-6962-4d55-858b-7e1f15a0e665#l=10&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <div aria-hidden="" class="border-static-default-hi rounded-full h-16 w-16 border shrink-0 text-action-default-hi" style="background-color: rgb(156, 176, 196);"></div>
                                                        <span class="shrink truncate body-2 text-center text-action-default-hi">Azul</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-3">
                                            <a href="/es-es/p/iphone-13-256-gb-libre/c38d3dfb-6962-4d55-858b-7e1f15a0e665#l=10&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <div aria-hidden="" class="border-static-default-hi rounded-full h-16 w-16 border shrink-0 text-action-default-hi" style="background-color: rgb(217, 239, 213);"></div>
                                                        <span class="shrink truncate body-2 text-center text-action-default-hi">Verde</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-4">
                                            <a href="/es-es/p/iphone-13-256-gb-libre/c38d3dfb-6962-4d55-858b-7e1f15a0e665#l=10&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <div aria-hidden="" class="border-static-default-hi rounded-full h-16 w-16 border shrink-0 text-action-default-hi" style="background-color: rgb(238, 233, 229);"></div>
                                                        <span class="shrink truncate body-2 text-center text-action-default-hi">Blanco estrella</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-5">
                                            <a href="/es-es/p/iphone-13-256-gb-libre/c38d3dfb-6962-4d55-858b-7e1f15a0e665#l=10&amp;scroll=false" class="bg-static-default-low cursor-pointer focus-visible-outline-default-hi rounded-sm relative flex w-full flex-col items-center justify-center border px-8 py-12 no-underline hover:bg-surface-default-mid motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out border-action-default-low" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center text-action-default-hi"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <div aria-hidden="" class="border-static-default-hi rounded-full h-16 w-16 border shrink-0 text-action-default-hi" style="background-color: rgb(252, 231, 231);"></div>
                                                        <span class="shrink truncate body-2 text-center text-action-default-hi">Rosa</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div id="datos-tecnicos">
                                <div id="ficha-tecnica" class="mb-32">
                                    <ul class="divide-static-default-low divide-y border-static-default-low">
                                        <li class="border-static-default-low relative list-none tap-highlight-transparent first:rounded-t-inherit last:rounded-b-inherit">
                                            <button class="focus-visible-outline-inset-hi cursor-pointer rounded-inherit px-24 hover:bg-static-default-min-hover bg-static-default-min text-static-default-low body-2 flex w-full items-start border-0 py-20 text-left no-underline duration-200 motion-safe:transition" type="button">
                                                <div class="flex grow flex-col items-start overflow-hidden">
                                                    <span class="text-action-default-hi body-1 w-full max-w-full">Ficha técnica</span>
                                                </div>
                                                <div class="self-center ml-24 text-right">
                                                    <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.503 17.023a.5.5 0 0 1-.355-.855l4.16-4.155-4.16-4.16a.5.5 0 1 1 .71-.705l4.5 4.5a.5.5 0 0 1 0 .705l-4.5 4.5a.5.5 0 0 1-.355.17"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="border-static-default-low relative list-none tap-highlight-transparent first:rounded-t-inherit last:rounded-b-inherit">
                                            <button class="focus-visible-outline-inset-hi cursor-pointer rounded-inherit px-24 hover:bg-static-default-min-hover bg-static-default-min text-static-default-low body-2 flex w-full items-start border-0 py-20 text-left no-underline duration-200 motion-safe:transition" type="button">
                                                <div class="flex grow flex-col items-start overflow-hidden">
                                                    <span class="text-action-default-hi body-1 w-full max-w-full">Extras incluidos</span>
                                                </div>
                                                <div class="self-center ml-24 text-right">
                                                    <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.503 17.023a.5.5 0 0 1-.355-.855l4.16-4.155-4.16-4.16a.5.5 0 1 1 .71-.705l4.5 4.5a.5.5 0 0 1 0 .705l-4.5 4.5a.5.5 0 0 1-.355.17"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="border-static-default-low relative list-none tap-highlight-transparent first:rounded-t-inherit last:rounded-b-inherit">
                                            <button class="focus-visible-outline-inset-hi cursor-pointer rounded-inherit px-24 hover:bg-static-default-min-hover bg-static-default-min text-static-default-low body-2 flex w-full items-start border-0 py-20 text-left no-underline duration-200 motion-safe:transition" type="button">
                                                <div class="flex grow flex-col items-start overflow-hidden">
                                                    <span class="text-action-default-hi body-1 w-full max-w-full">Preguntas frecuentes</span>
                                                </div>
                                                <div class="self-center ml-24 text-right">
                                                    <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.503 17.023a.5.5 0 0 1-.355-.855l4.16-4.155-4.16-4.16a.5.5 0 1 1 .71-.705l4.5 4.5a.5.5 0 0 1 0 .705l-4.5 4.5a.5.5 0 0 1-.355.17"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <div id="agente-reacondicionador" class="bg-surface-default-mid rounded-lg p-24">
                                    <div class="mb-16 flex">
                                        <div class="relative shrink-0">
                                            <img class="mr-16 w-40 flex-initial h-auto max-h-full max-w-full leading-none" alt="" decoding="async" height="40" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 3840w" width="40">
                                        </div>
                                        <div>
                                            <p class="body-1 mb-4 overflow-hidden">
                                                Reacondicionado por 
                                                <a href="/es-es/s/chancenborse/e5a33e92-6f48-4f6b-9906-a6f3fd7c4e25" class="text-action-default-hi focus-visible-outline-default-hi rounded-md font-weight-body-1-link cursor-pointer hover:text-action-default-hi-hover underline" rel="noreferrer noopener" data-qa="seller-info">ChancenBörse</a>
                                            </p>
                                            <p class="text-static-default-low body-2">Reacondicionador desde 2020, origen: Alemania</p>
                                        </div>
                                    </div>

                                    <div class="border-static-default-low flex border-t pt-20">
                                        <p class="body-2">Métodos de pago</p>
                                        <div class="flex flex-wrap items-center gap-4 ml-24 content-center justify-end">
                                            <img alt="Visa" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 3840w" width="36">
                                            <img alt="Mastercard" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 3840w" width="36">
                                            <img alt="American Express" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 3840w" width="36">
                                            <img alt="Oney" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 3840w" width="36">
                                            <img alt="PayPal" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 3840w" width="36">
                                            <img alt="Apple Pay" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 3840w" width="36">
                                        </div>
                                    </div>
                                </div>

                                <div class="body-2 my-32 md:my-56">
                                    *Todos los precios son con IVA incluído. Puedes solicitar la factura de tu compra desde tu espacio personal en backmarket.es 
                                    <a target="_blank" class="text-action-default-hi focus-visible-outline-default-hi rounded-md font-weight-body-1-link cursor-pointer hover:text-action-default-hi-hover underline" aria-disabled="false" href="https://help.backmarket.com/hc/es/articles/360030380754--Puedo-desgravar-el-IVA-si-soy-un-profesional-" rel="noreferrer noopener">
                                        Ver más
                                    </a>
                                </div>
                            </div>


                        </div>

















                        {{-- <div class="border-b border-gray-200 pb-6">
                            <p class="text-sm leading-none text-gray-600 dark:text-gray-300 ">Balenciaga Fall Collection</p>
                            <h1
                                class="lg:text-2xl text-xl font-semibold lg:leading-6 leading-7 text-gray-800 dark:text-white mt-2">
                                Iphone 13 128GB - Blanc747o</h1>
                        </div>
                        <div class="py-4 border-b border-gray-200 flex items-center justify-between">
                            <p class="text-base leading-4 text-gray-800 dark:text-gray-300">Colours</p>
                            <div class="flex items-center justify-center">
                                <p class="text-sm leading-none text-gray-600 dark:text-gray-300">Smoke Blue with red accents
                                </p>
                                <div class="w-6 h-6 bg-gradient-to-b from-gray-900 to-indigo-500 ml-3 mr-4 cursor-pointer">
                                </div>
                                <svg class="cursor-pointer text-gray-300 dark:text-white" width="6" height="10"
                                    viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L5 5L1 9" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="py-4 border-b border-gray-200 flex items-center justify-between">
                            <p class="text-base leading-4 text-gray-800 dark:text-gray-300">Size</p>
                            <div class="flex items-center justify-center">
                                <p class="text-sm leading-none text-gray-600 dark:text-gray-300 mr-3">38.2</p>
                                <svg class="text-gray-300 dark:text-white cursor-pointer" width="6" height="10"
                                    viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 1L5 5L1 9" stroke="currentColor" stroke-width="1.25" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <button
                            class="dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800 text-base flex items-center justify-center leading-none text-white bg-gray-800 w-full py-4 hover:bg-gray-700 focus:outline-none">
                            <svg class="mr-3 text-white dark:text-gray-900" width="16" height="17"
                                viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.02301 7.18999C7.48929 6.72386 7.80685 6.12992 7.93555 5.48329C8.06425 4.83666 7.9983 4.16638 7.74604 3.55724C7.49377 2.94809 7.06653 2.42744 6.51835 2.06112C5.97016 1.6948 5.32566 1.49928 4.66634 1.49928C4.00703 1.49928 3.36252 1.6948 2.81434 2.06112C2.26615 2.42744 1.83891 2.94809 1.58665 3.55724C1.33439 4.16638 1.26843 4.83666 1.39713 5.48329C1.52583 6.12992 1.8434 6.72386 2.30968 7.18999L4.66634 9.54749L7.02301 7.18999Z"
                                    stroke="currentColor" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M4.66699 4.83333V4.84166" stroke="currentColor" stroke-width="1.25"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M13.69 13.8567C14.1563 13.3905 14.4738 12.7966 14.6025 12.15C14.7312 11.5033 14.6653 10.8331 14.413 10.2239C14.1608 9.61476 13.7335 9.09411 13.1853 8.72779C12.6372 8.36148 11.9926 8.16595 11.3333 8.16595C10.674 8.16595 10.0295 8.36148 9.48133 8.72779C8.93314 9.09411 8.5059 9.61476 8.25364 10.2239C8.00138 10.8331 7.93543 11.5033 8.06412 12.15C8.19282 12.7966 8.51039 13.3905 8.97667 13.8567L11.3333 16.2142L13.69 13.8567Z"
                                    stroke="currentColor" stroke-width="1.25" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M11.333 11.5V11.5083" stroke="currentColor" stroke-width="1.25"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Check availability in store
                        </button>
                        <div>
                            <p
                                class="xl:pr-48 text-base lg:leading-tight leading-normal text-gray-600 dark:text-gray-300 mt-7">
                                It is a long established fact that a reader will be distracted by thereadable content of a page
                                when looking at its layout. The point of usingLorem Ipsum is that it has a more-or-less normal
                                distribution of letters.</p>
                            <p class="text-base leading-4 mt-7 text-gray-600 dark:text-gray-300">Product Code: 8BN321AF2IF0NYA
                            </p>
                            <p class="text-base leading-4 mt-4 text-gray-600 dark:text-gray-300">Length: 13.2 inches</p>
                            <p class="text-base leading-4 mt-4 text-gray-600 dark:text-gray-300">Height: 10 inches</p>
                            <p class="text-base leading-4 mt-4 text-gray-600 dark:text-gray-300">Depth: 5.1 inches</p>
                            <p class="md:w-96 text-base leading-normal text-gray-600 dark:text-gray-300 mt-4">Composition: 100%
                                calf leather, inside: 100% lamb leather</p>
                        </div>
                        <div>
                            <div class="border-t border-b py-4 mt-7 border-gray-200">
                                <div data-menu class="flex justify-between items-center cursor-pointer">
                                    <p class="text-base leading-4 text-gray-800 dark:text-gray-300">Shipping and returns</p>
                                    <button
                                        class="cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 rounded"
                                        role="button" aria-label="show or hide">
                                        <svg class="transform text-gray-300 dark:text-white" width="10" height="6"
                                            viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 1L5 5L1 1" stroke="currentColor" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="hidden pt-4 text-base leading-normal pr-12 mt-4 text-gray-600 dark:text-gray-300"
                                    id="sect">You will be responsible for paying for your own shipping costs for returning
                                    your item. Shipping costs are nonrefundable</div>
                            </div>
                        </div>
                        <div>
                            <div class="border-b py-4 border-gray-200">
                                <div data-menu class="flex justify-between items-center cursor-pointer">
                                    <p class="text-base leading-4 text-gray-800 dark:text-gray-300">Contact us</p>
                                    <button
                                        class="cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 rounded"
                                        role="button" aria-label="show or hide">
                                        <svg class="transform text-gray-300 dark:text-white" width="10" height="6"
                                            viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 1L5 5L1 1" stroke="currentColor" stroke-width="1.25"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="hidden pt-4 text-base leading-normal pr-12 mt-4 text-gray-600 dark:text-gray-300"
                                    id="sect">If you have any questions on how to return your item to us, contact us.
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <script>
            let elements = document.querySelectorAll("[data-menu]");
            for (let i = 0; i < elements.length; i++) {
                let main = elements[i];
                main.addEventListener("click", function() {
                    let element = main.parentElement.parentElement;
                    let andicators = main.querySelectorAll("svg");
                    let child = element.querySelector("#sect");
                    child.classList.toggle("hidden");
                    andicators[0].classList.toggle("rotate-180");
                });
            }



            document.addEventListener('DOMContentLoaded', function() {
                // Obtener la colección de productos
                var productos = {!! json_encode($info_producto) !!};

                // Buscar el primer producto con un título que contenga una palabra diferente
                var productoDiferente = productos.find(function(producto) {
                    // Especifica aquí la palabra diferente que estás buscando en el título
                    return producto.title.includes('palabra_diferente');
                });

                // Verificar si se encontró un producto con el título que contiene la palabra diferente
                if (productoDiferente) {
                    // Acceder a las propiedades del producto encontrado
                    var sku_title = productoDiferente.sku;
                    var titulo = productoDiferente.title;
                    var precio = productoDiferente.price;
                    var stock = productoDiferente.quantity;
                    var descripcion = productoDiferente.comment;
                    var garantia = productoDiferente.warranty_delay;
                    var moneda = productoDiferente.currency;

                    // Mostrar las propiedades del producto en la consola (puedes eliminar esto en producción)
                    console.log('SKU: ' + sku_title);
                    console.log('Título: ' + titulo);
                    console.log('Precio: ' + precio);
                    console.log('Stock: ' + stock);
                    console.log('Descripción: ' + descripcion);
                    console.log('Garantía: ' + garantia);
                    console.log('Moneda: ' + moneda);
                } else {
                    console.log('No se encontraron productos con la palabra diferente en el título.');
                }
            });
        </script>

    </x-app-layout>


</body>

</html>
