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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="lg:flex items-start justify-center py-12 2xl:px-20 sm:!px-8 px-4">
                    <div class="xl:w-3/5 lg:w-2/5 w-80 lg:mx-6 hidden lg:!block">
                        <img class="w-full" alt="image of a girl posing"
                            src="https://i.ibb.co/QMdWfzX/component-image-one.png" />
                        <img class="mt-6 w-full" alt="image of a girl posing"
                            src="https://i.ibb.co/qxkRXSq/component-image-two.png" />
                    </div>
                    <div class="lg:hidden">
                        <img class="w-full" alt="image of a girl posing" src="https://i.ibb.co/QMdWfzX/component-image-one.png"/>
                        <div class="flex items-center justify-between mt-3 space-x-3">
                            <img alt="image-tag-one" class="lg:w-48 lg:h-48 w-full"
                                src="https://i.ibb.co/cYDrVGh/Rectangle-245.png" />
                            <img alt="image-tag-one" class="lg:w-48 lg:h-48 w-full"
                                src="https://i.ibb.co/f17NXrW/Rectangle-244.png" />
                            <img alt="image-tag-one" class="lg:w-48 lg:h-48 w-full"
                                src="https://i.ibb.co/cYDrVGh/Rectangle-245.png" />
                            <img alt="image-tag-one" class="lg:w-48 lg:h-48 w-full"
                                src="https://i.ibb.co/f17NXrW/Rectangle-244.png" />
                        </div>
                    </div>

                    <div>
                        <!-- Aquí va el contenido de las imágenes del producto -->
                        {{-- <img src="producto1.jpg" alt="Producto 1" class="w-full">
                        <img src="producto2.jpg" alt="Producto 2" class="w-full"> --}}
                        <!-- Agrega más imágenes según sea necesario -->
                    </div>

                    <div class="lg:mx-4 xl:w-3/4 lg:w-1/2 lg:ml-8 lg:ml-4">
                        <div class="product-details">
                            <div id=titulo-botonCompra class="dark:text-white">
                                <div id="title" class="mb-4 mt-4 lg:!mt-0 flex flex-col md:!flex-row lg:items-center justify-between">
                                    <h2 class="text-2xl sm:!text-3xl font-bold m-auto">{{ $titulo }}</h2>
                                    <div id="precio" class="flex flex-col shrink-0 md:ml-6 items-end mr-2 lg:mr-0">
                                        <div class="flex items-center mt-4">
                                            <div class="previous-price text-xs lg:!text-sm font-semibold line-through mt-auto mr-2">{{ str_replace('.', ',', number_format($precio, 2)) }}&nbsp;€ nuevo</div>
                                            <div class="current-price text-xl lg:!text-2xl font-semibold">{{ str_replace('.', ',', number_format($precio * 0.95, 2)) }}&nbsp;€</div>
                                        </div>
                                        <div class="iva text-xs lg:text-sm ml-auto">IVA incluido*</div>
                                    </div>
                                    
                                </div>                                
                                

                                <div class="actions mb-4 flex flex-row">
                                    <div class="opinions hidden lg:!block mr-20 mr-auto">
                                        <!-- Aquí va el contenido de las opiniones -->
                                        <p>Opiniones de los usuarios...</p>
                                    </div>
                                    <div class="buy-button mx-auto lg:!mx-0 md:mt-4">
                                        <button class="px-20 py-3 md:px-40 lg:px-16 text-xl md:text-2xl bg-slate-900 dark:bg-slate-50 dark:hover:bg-slate-200 text-white dark:!text-neutral-800 rounded motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out">Comprar</button>
                                    </div>
                                </div>
                            </div>

                            <div id="detalles-envio">
                                <hr class="border-t my-6 bg-gray-200">
                                <div class="shipping mb-2 flex flex-row">
                                    <div class="bg-indigo-100 rounded-md mr-2 flex h-10 w-10 shrink-0 items-center justify-center">
                                        <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M19.95 18h1.55a.5.5 0 0 0 .5-.5v-4.575a.5.5 0 0 0-.135-.34L15.4 5.66a.5.5 0 0 0-.365-.16H3.5A.5.5 0 0 0 3 6v2a.5.5 0 1 0 1 0V6.5h10.815L21 13.12V17h-1.05a2.5 2.5 0 0 0-4.9 0h-4.6a2.5 2.5 0 0 0-4.9 0H4v-3a.5.5 0 0 0-1 0v3.5a.5.5 0 0 0 .5.5h2.05a2.499 2.499 0 0 0 4.9 0h4.6a2.499 2.499 0 0 0 4.9 0M8 16a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m9.5 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3" clip-rule="evenodd"></path>
                                            <path d="M6.854 12.354a.5.5 0 0 1-.354.146h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .354.854m-4.708-2a.5.5 0 0 0 .354.146h2a.5.5 0 0 0 0-1h-2a.5.5 0 0 0-.354.854"></path>
                                        </svg>
                                    </div>
                                    <div class="my-auto font-bold text-sm dark:text-white">
                                        Envío gratis
                                    </div>
                                </div>
                                <div class="flex flex-col md:!flex-row">
                                    <div class="mb-2 md:mr-20 flex flex-row">
                                        <div class="bg-indigo-100 rounded-md mr-2 flex h-10 w-10 shrink-0 items-center justify-center">
                                            <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.808 13.793a.505.505 0 0 0 .192.037.5.5 0 0 0 .37-.145l5.665-5.665a.5.5 0 1 0-.71-.705L11 12.625l-3.325-3.33a.5.5 0 1 0-.71.705l3.68 3.685a.499.499 0 0 0 .163.108"></path>
                                                <path fill-rule="evenodd" d="M11.825 21.965A.435.435 0 0 0 12 22a.5.5 0 0 0 .17-.03C17.78 19.93 21 16.115 21 11.5v-9a.5.5 0 0 0-.5-.5h-17a.5.5 0 0 0-.5.5v9c0 6.275 5.53 9.25 8.825 10.465M4 11.5V3h16v8.5c0 2.43-1.045 6.875-8 9.465-3.065-1.165-8-3.9-8-9.465" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="dark:text-white">
                                            <div class="my-auto font-bold text-sm">
                                                Garantía de 1 año
                                            </div>
                                            <div class="my-auto font-bold text-sm">
                                                30 días de prueba
                                            </div>
                                        </div>
                                    </div>
                                    <div class="expert flex flex-row">
                                        <div class="rounded-md mr-2 flex h-10 w-10 shrink-0 items-center justify-center">
                                            <img alt="Reacondicionado por FullRenew" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="40" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/verified-refurbished/logo-with-bg.svg 3840w" width="40">
                                        </div>
                                        <div class="my-auto font-bold text-sm dark:text-white">
                                            Reacondicionado por expertos
                                        </div>
                                    </div>
                                </div> 
                                <hr class="border-t mt-4 mb-8 bg-gray-200">
                            </div>
                        
                            <div id="caracteristicas-producto">
                                <div id="lo_mejor" class="mb-6 lg:!mb-12">
                                    <div class="flex flex-row justify-between font-bold dark:!text-white">
                                        <p class="mb-3 block">Lo mejor de lo mejor</p>
                                        <div>
                                            <div class="">
                                                <button class= "rounded-md font-weight-link cursor-pointer hover-hover underline" type="button">Ver más</button>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="grid list-none gap-2 grid-cols-2">
                                        <li data-qa="recommended_variants-0">
                                            <a aria-current="page" href="#" class="text-blue-700 cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-blue-500 lg:px-8 py-3 no-underline hover:bg-slate-100 dark:bg-slate-50 dark:hover:bg-slate-200 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="ef5660d2-6883-4b81-b47d-86e5720687ef" grade="[object Object]" disabled="false" role="link">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-1">
                                                        <svg aria-hidden="true" fill="#0052cc" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-action-brand-hi">
                                                            <path fill-rule="evenodd" d="M3.15 6.28C4 4 5.2 2.65 6.57 2.65c2.43 0 4.31 4.17 4.31 9.49 0 3.39-.8 6.53-2.08 8.2a2.89 2.89 0 0 1-2.23 1.3c-2.42 0-4.31-4.18-4.31-9.5a18 18 0 0 1 .89-5.86m1.45 6.5h.07a.51.51 0 0 0 .44-.57A11.46 11.46 0 0 1 5 9.29a.502.502 0 1 0-1-.09 13.07 13.07 0 0 0 .1 3.15.51.51 0 0 0 .5.43m-.41-6.42c1.42.64 5.68 3.23 4.8 11.42l.01.05c.611-1.83.908-3.75.88-5.68 0-5.01-1.74-8.5-3.31-8.5-.86 0-1.74 1.01-2.38 2.71m9.31.49c.8-2.67 2.11-4.2 3.61-4.2 2.42 0 4.31 4.17 4.36 9.54 0 4.15-1.23 7.78-3 9-.395.29-.87.45-1.36.46-2.42 0-4.31-4.18-4.31-9.5-.018-1.79.218-3.575.7-5.3m1.62 6.59h.07a.5.5 0 0 0 .42-.58 11.4 11.4 0 0 1-.11-2.91.51.51 0 0 0-.45-.55.52.52 0 0 0-.55.45 12.758 12.758 0 0 0 .12 3.16.51.51 0 0 0 .5.43m-.61-6.56c1.24.53 5.64 3.16 4 12.9 1.15-1.43 1.91-4.37 1.87-7.63 0-5.01-1.74-8.5-3.31-8.5-.95 0-1.92 1.23-2.56 3.23" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="shrink truncate text-center">Mejor precio</span>
                                                    </div>
                                                    <div class="text-static-brand-hi text-center">430,09&nbsp;€</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="recommended_variants-1">
                                            <a aria-current="page" href="#" class="text-purple-700 cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-purple-700 lg:px-8 py-3 no-underline motion-safe:transition-colors hover:bg-slate-100 dark:bg-slate-50 dark:hover:bg-slate-200 motion-safe:duration-300 motion-safe:ease-out border-action-brand-mid" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" grade="[object Object]" disabled="false" role="link">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-1">
                                                        <svg aria-hidden="true" fill="#6B46C1" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="shrink-0 text-action-brand-mid"><path fill-rule="evenodd" d="M6.357 10.957A.5.5 0 0 0 6.55 11a.49.49 0 0 0 .49-.3.45.45 0 0 0 0-.2c-.01-.91.16-2.43.67-2.91a4.35 4.35 0 0 1 2.48-.6h.32A.49.49 0 0 0 11 6.5a.5.5 0 0 0-.46-.5h-.32a4.7 4.7 0 0 1-2.6-.7 4.4 4.4 0 0 1-.61-2.5v-.3a.5.5 0 0 0-.49-.5.53.53 0 0 0-.52.5v.33a4.67 4.67 0 0 1-.66 2.57 4.75 4.75 0 0 1-2.78.6.46.46 0 0 0-.37.19.5.5 0 0 0 0 .72.53.53 0 0 0 .36.15c.83 0 2.45.16 2.92.65a4.78 4.78 0 0 1 .59 2.77.5.5 0 0 0 .297.477M6.18 7a2.52 2.52 0 0 0-.72-.45c.214-.1.407-.24.57-.41.203-.2.366-.438.48-.7.097.205.225.394.38.56.19.2.413.366.66.49a2 2 0 0 0-.55.38 2.22 2.22 0 0 0-.44.68A2.69 2.69 0 0 0 6.18 7m9.2 9.86a.501.501 0 0 1-.48-.52c0-.89-.07-3.43-1-4.35-.93-.92-3.6-1.08-4.56-1.08a.43.43 0 0 1-.34-.15.47.47 0 0 1-.15-.35A.58.58 0 0 1 9 10a.61.61 0 0 1 .37-.15c.9 0 3.43-.08 4.36-1 .865-.855.986-2.714 1.063-3.895l.017-.255v-.39a.5.5 0 1 1 1 0v.44c0 1.1.2 3.14 1 4 .793.853 2.914.998 4.09 1.078l.03.002h.46a.5.5 0 0 1 .48.5.51.51 0 0 1-.49.5h-.45c-1.11 0-3.13.2-3.93 1-.8.8-1.1 3.6-1.09 4.56a.45.45 0 0 1 0 .2.49.49 0 0 1-.53.27m-2.32-6.45a3.86 3.86 0 0 1 1.57.88c.348.384.61.837.77 1.33a3.81 3.81 0 0 1 .88-1.57 3.72 3.72 0 0 1 1.32-.76 3.75 3.75 0 0 1-1.53-.87 3.64 3.64 0 0 1-.77-1.33 3.691 3.691 0 0 1-.88 1.54 3.77 3.77 0 0 1-1.36.78M8 22a.501.501 0 0 1-.48-.52 4.8 4.8 0 0 0-.6-2.77C6.45 18.18 4.85 18 4 18a.51.51 0 0 1-.36-.15.47.47 0 0 1-.15-.35.56.56 0 0 1 .15-.37A.61.61 0 0 1 4 17a4.68 4.68 0 0 0 2.77-.59 4.71 4.71 0 0 0 .67-2.63v-.32A.5.5 0 0 1 8 13a.51.51 0 0 1 .49.5v.31a4.46 4.46 0 0 0 .6 2.49 4.63 4.63 0 0 0 2.6.67H12a.51.51 0 0 1 .48.51.5.5 0 0 1-.48.52 3.163 3.163 0 0 1-.32 0 4.41 4.41 0 0 0-2.47.6c-.51.49-.68 2-.67 2.91.01.06.01.12 0 .18A.51.51 0 0 1 8 22m-1.1-4.46c.267.11.512.27.72.47.155.166.283.355.38.56.117-.266.28-.51.48-.72a2.21 2.21 0 0 1 .56-.38 2.25 2.25 0 0 1-.68-.47 2.51 2.51 0 0 1-.36-.58 2.37 2.37 0 0 1-.47.71c-.18.159-.382.29-.6.39z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="shrink truncate text-center text-action-brand-mid text-blue-">Más populares</span>
                                                    </div>
                                                    <div class="text-static-brand-mid text-center">481,42&nbsp;€</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div id="estado" class="mb-9">
                                    <div class="flex flex-row justify-between font-bold">
                                        <p class="mb-3 block">Estado</p>
                                        <div>
                                            <div class="font-bold">
                                                <button class="rounded-md font-weight-link cursor-pointer hover-hover underline" type="button">Ver más</button>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="grid list-none gap-2 grid-cols-3 mb-4">
                                        <li data-qa="grades-0">
                                            <a aria-current="page" href="#" class="router-link-active router-link-exact-active cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-1 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" disabled="false" role="link" grade="[object Object]">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="font-bold text-center">Correcto</span>
                                                    </div>
                                                    <div class="text-static-default-low text-center text-gray-600">456,36&nbsp;€</div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="grades-1">
                                            <a aria-current="page" href="#" class="router-link-active router-link-exact-active cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border px-1 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" disabled="false" role="link" grade="[object Object]">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="font-bold text-center">Muy bueno</span>
                                                    </div>
                                                    <div class="text-static-default-low text-center text-gray-600">439,99&nbsp;€</div>
                                                </div>
                                                {{-- <span class="rounded-xs inline-block max-w-full truncate font-bold px-4 py-0 bg-static-info-max text-static-default-hi absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 -rotate-3" title="¡Oferta!">¡Oferta!</span> --}}
                                            </a>
                                        </li>
                                        <li data-qa="grades-2">
                                            <a aria-current="page" href="#" class="router-link-active router-link-exact-active cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border px-1 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" disabled="false" role="link" grade="[object Object]">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="font-bold text-center">Excelente</span>
                                                    </div>
                                                    <div class="text-static-default-low text-center">481,42&nbsp;€</div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <label for="battery-checkbox" class="cursor-pointer">
                                        <div class="battery-replacement flex items-center justify-between border border-black rounded-md p-3">
                                            <div class="flex items-center">
                                                <div class="rounded-md mr-3 lg:mr-16 border border-black p-1 bg-slate-100">
                                                    <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="peer:checked:text-green-600">
                                                        <path d="M11.665 12.715 12.82 9.5l.015-.04a.106.106 0 0 0-.035-.11.095.095 0 0 0-.135 0 1.73 1.73 0 0 0-.056.088.852.852 0 0 1-.034.052l-.07.115-.405.675L10.5 13l-.415.69h2.294l-.834 2.865a.105.105 0 0 0 .045.12.11.11 0 0 0 .14-.04l1.77-3.25.38-.67z"></path>
                                                        <path fill-rule="evenodd" d="M9 3.5v1H8A1.5 1.5 0 0 0 6.5 6v14.5A1.5 1.5 0 0 0 8 22h8a1.5 1.5 0 0 0 1.5-1.5V6A1.5 1.5 0 0 0 16 4.5h-1v-1A1.5 1.5 0 0 0 13.5 2h-3A1.5 1.5 0 0 0 9 3.5m7 2a.5.5 0 0 1 .5.5v14.5a.5.5 0 0 1-.5.5H8a.5.5 0 0 1-.5-.5V6a.5.5 0 0 1 .5-.5zm-2-1h-4v-1a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium mr-3">Batería nueva</span>
                                            </div>

                                            <label  class="relative h-8 w-14 rounded-full bg-gray-300 transition [-webkit-tap-highlight-color:_transparent] has-[:checked]:bg-green-500">
                                                <input type="checkbox" id="battery-checkbox" class="peer sr-only [&:checked_+_span_svg[data-checked-icon]]:block [&:checked_+_span_svg[data-unchecked-icon]]:hidden" />
                                                <span class="absolute inset-y-0 start-0 z-10 m-1 inline-flex size-6 items-center justify-center rounded-full bg-white text-gray-400 transition-all peer-checked:start-6 peer-checked:text-green-600" >
                                                    <svg data-unchecked-icon xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                    <svg data-checked-icon xmlns="http://www.w3.org/2000/svg" class="hidden h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </label>
                                        </div>
                                    </label>
                                </div>

                                <div id="capacidad" class="mb-9 font-bold">
                                    <div class="flex flex-row justify-between"><p class="mb-3 block">Almacenamiento (GB)</p></div>
                                    <ul class="grid list-none gap-3 grid-cols-3">
                                        <li data-qa="storage-0">
                                            <a aria-current="page" href="#" class="router-link-active router-link-exact-active cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-4 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="ec975bb8-df95-43a5-b04d-de63378f4a12" disabled="false" role="link" grade="[object Object]">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class="font-bold text-center">128 GB</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="storage-1">
                                            <a href="#" class="cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-4 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="58c0e06e-f4b5-4553-a0a2-1f2a5d1da186" disabled="false" role="link" grade="[object Object]">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class= "text-center">256 GB</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="storage-2">
                                            <a href="#" class="cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-4 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="81ab240b-44af-4bb1-a59a-1321ffdd74ba" disabled="false" role="link" grade="[object Object]">
                                                <div class="absolute left-0 top-1/2 -mt-12 flex w-full place-content-center"></div>
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-4">
                                                        <span class= "text-center">512 GB</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <div id="color" class="mb-12 font-bold">
                                    <div class="flex flex-row justify-between ">
                                        <p class="mb-2 block">Color</p>
                                    </div>
                                    <ul class="grid list-none gap-2 grid-cols-3">
                                        <li data-qa="color-0">
                                            <a href="#" class="cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-2 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-1">
                                                        <div aria-hidden="" class="rounded-full h-4 w-4 border border-black shrink-0" style="background-color: rgb(24, 32, 40);"></div>
                                                        <span class="shrink truncate text-center text-sm">Medianoche</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-1">
                                            <a href="#" class="cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-2 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-1">
                                                        <div aria-hidden="" class="rounded-full h-4 w-4 border border-black shrink-0" style="background-color: rgb(255, 0, 0);"></div>
                                                        <span class="shrink truncate text-center text-sm">Rojo</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-2">
                                            <a href="#" class="cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-2 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-1">
                                                        <div aria-hidden="" class="rounded-full h-4 w-4 border border-black shrink-0" style="background-color: rgb(156, 176, 196);"></div>
                                                        <span class="shrink truncate text-center text-sm">Azul</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-3">
                                            <a href="#" class="cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-2 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-1">
                                                        <div aria-hidden="" class="rounded-full h-4 w-4 border border-black shrink-0" style="background-color: rgb(217, 239, 213);"></div>
                                                        <span class="shrink truncate text-center text-sm">Verde</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-4">
                                            <a href="#" class="cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-2 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-1">
                                                        <div aria-hidden="" class="rounded-full h-4 w-4 border border-black shrink-0" style="background-color: rgb(238, 233, 229);"></div>
                                                        <span class="shrink truncate text-center text-sm">Blanco estrella</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li data-qa="color-5">
                                            <a href="#" class="cursor-pointer rounded-md relative flex w-full flex-col items-center justify-center border border-black px-2 py-3 no-underline hover:bg-slate-100 motion-safe:transition-colors motion-safe:duration-300 motion-safe:ease-out" rel="noreferrer noopener" aria-disabled="false" productid="c38d3dfb-6962-4d55-858b-7e1f15a0e665" grade="[object Object]" disabled="false" role="link">
                                                <div class="w-full">
                                                    <div class="flex flex-row flex-nowrap items-center justify-center gap-1">
                                                        <div aria-hidden="" class="rounded-full h-4 w-4 border border-black shrink-0" style="background-color: rgb(252, 231, 231);"></div>
                                                        <span class="shrink truncate text-center text-sm">Rosa</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div id="datos-tecnicos">
                                <div id="ficha-tecnica" class="mb-9">
                                    <ul class="divide-y">
                                        <li class="relative list-none tap-highlight-transparent first:rounded-t-inherit last:rounded-b-inherit">
                                            <button class="cursor-pointer rounded-inherit px-6 flex w-full items-start border-0 py-5 text-left no-underline hover:bg-slate-100 duration-200 motion-safe:transition" type="button">
                                                <div class="flex grow flex-col items-start overflow-hidden">
                                                    <span class= "w-full max-w-full">Ficha técnica</span>
                                                </div>
                                                <div class="self-center ml-24 text-right">
                                                    <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.503 17.023a.5.5 0 0 1-.355-.855l4.16-4.155-4.16-4.16a.5.5 0 1 1 .71-.705l4.5 4.5a.5.5 0 0 1 0 .705l-4.5 4.5a.5.5 0 0 1-.355.17"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="relative list-none tap-highlight-transparent first:rounded-t-inherit last:rounded-b-inherit">
                                            <button class="cursor-pointer rounded-inherit px-6 flex w-full items-start border-0 py-5 text-left no-underline hover:bg-slate-100 duration-200 motion-safe:transition" type="button">
                                                <div class="flex grow flex-col items-start overflow-hidden">
                                                    <span class= "w-full max-w-full">Extras incluidos</span>
                                                </div>
                                                <div class="self-center ml-24 text-right">
                                                    <svg aria-hidden="true" fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.503 17.023a.5.5 0 0 1-.355-.855l4.16-4.155-4.16-4.16a.5.5 0 1 1 .71-.705l4.5 4.5a.5.5 0 0 1 0 .705l-4.5 4.5a.5.5 0 0 1-.355.17"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </li>
                                        <li class="relative list-none tap-highlight-transparent first:rounded-t-inherit last:rounded-b-inherit">
                                            <button class="cursor-pointer rounded-inherit px-6 flex w-full items-start border-0 py-5 text-left no-underline hover:bg-slate-100 duration-200 motion-safe:transition" type="button">
                                                <div class="flex grow flex-col items-start overflow-hidden">
                                                    <span class="w-full max-w-full">Preguntas frecuentes</span>
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

                                <div id="agente-reacondicionador" class="bg-slate-100 rounded-lg p-6">
                                    <div class="mb-4 flex">
                                        <div class="relative shrink-0">
                                            <img class="mr-6 w-15 flex-initial h-auto max-h-full max-w-full leading-none" alt="" decoding="async" height="40" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/product/seller/SellerAvatar.svg 3840w" width="40">
                                        </div>
                                        <div>
                                            <p class="mb-3 overflow-hidden">
                                                Reacondicionado por 
                                                <a href="#" class="rounded-md font-bold font-weight-link cursor-pointer hover-hover underline" rel="noreferrer noopener" data-qa="seller-info">FullRenew</a>
                                            </p>
                                            <p class="text-sm">Reacondicionador desde 2020, origen: España</p>
                                        </div>
                                    </div>

                                    <div class="flex border-t pt-4 justify-between">
                                        <p class="text-md">Métodos de pago</p>
                                        <div class="flex flex-wrap items-center gap-1 ml-3 content-center justify-end">
                                            <img alt="Visa" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/visa.svg 3840w" width="36">
                                            <img alt="Mastercard" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/mastercard.svg 3840w" width="36">
                                            <img alt="American Express" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/amex.svg 3840w" width="36">
                                            <img alt="Oney" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/networks-v4/oney.svg 3840w" width="36">
                                            <img alt="PayPal" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/paypal.svg 3840w" width="36">
                                            <img alt="Apple Pay" class="h-auto max-h-full max-w-full leading-none" decoding="async" height="20" loading="lazy" sizes="100vw" src="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg" srcset="https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 260w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 640w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 750w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 828w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 1080w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 1200w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 1920w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 2048w,https://front-office.statics.backmarket.com/12cda31977eadf4334ee8c2516765201d2ece6c8/img/payment/methods-v4/apple_pay.svg 3840w" width="36">
                                        </div>
                                    </div>
                                </div>

                                <div class=" my-9 md:my-10 text-sm">
                                    *Todos los precios son con IVA incluído. Puedes solicitar la factura de tu compra desde tu espacio personal en backmarket.es 
                                    <a target="_blank" class="rounded-md font-bold font-weight-link cursor-pointer hover-hover underline" aria-disabled="false" href="https://help.backmarket.com/hc/es/articles/360030380754--Puedo-desgravar-el-IVA-si-soy-un-profesional-" rel="noreferrer noopener">
                                        Ver más
                                    </a>
                                </div>
                            </div>
                        </div>
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