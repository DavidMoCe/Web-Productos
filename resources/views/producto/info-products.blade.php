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

        <div class="md:flex items-start justify-center py-12 2xl:px-20 md:px-6 px-4">
            <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->

            <div class="xl:w-2/6 lg:w-2/5 w-80 hidden md:!block">
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


            <div class="xl:w-2/5 md:w-1/2 lg:ml-8 md:ml-6 md:mt-0 mt-6">

                <div class="hidden md:!block">
                    <div class="mb-24 grow flex-row items-center justify-between md:mb-20 md:flex">
                        <h1 class="text-5xl font-extrabold dark:text-white">iPhone 15 Pro Max 256GB - Titanio Natural - Libre - Dual eSIM</h1>
                        <div class="flex shrink-0 flex-col md:items-end ml-24 hidden md:!flex">
                            <div class="flex flex-col items-baseline md:flex-row">
                                <span class="z-[1] text-left">
                                    <span aria-expanded="false" aria-haspopup="true">
                                        <button class="caption cursor-pointer line-through mr-8">1469,00&nbsp;€ nuevo</button>
                                    </span>
                                    <div class="" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(815px, 346px); display: none;" data-popper-placement="bottom">
                                        <div class="mood-inverse bg-overlap-default-low text-static-default-mid rounded-sm body-2 relative inline-block max-w-224 px-16 py-12 text-center mt-8 mx-12">
                                            Este es el precio correspondiente al artículo vendido nuevo. Se indica para
                                            mostrar la diferencia de ahorro entre elegir un producto nuevo y uno
                                            renovado. Es el precio original del fabricante o el precio medio de venta en
                                            el mercado actualmente.
                                            <div class="absolute border-b-overlap-default-low -top-6 border-b-6 border-x-6 border-solid border-x-transparent left-[calc(50%-6px)]" data-popper-arrow="" style="position: absolute; left: 0px; transform: translate(0px);"></div>
                                        </div>
                                    </div>
                                </span>
                                <div class="flex items-baseline md:flex-col md:items-end">
                                    <div class="flex flex-row items-baseline">
                                        <h2 class="heading-2 md:text-right" data-qa="productpage-product-price">1250,00&nbsp;€</h2>
                                    </div>
                                </div>
                            </div>
                            <span class="caption">IVA incluido*</span>
                        </div>
                    </div>

                </div>

                <div class="border-b border-gray-200 pb-6">
                    <p class="text-sm leading-none text-gray-600 dark:text-gray-300 ">Balenciaga Fall Collection</p>
                    <h1
                        class="lg:text-2xl text-xl font-semibold lg:leading-6 leading-7 text-gray-800 dark:text-white mt-2">
                        Iphone 13 128GB - Blanco</h1>
                </div>
                <div class="py-4 border-b border-gray-200 flex items-center justify-between">
                    <p class="text-base leading-4 text-gray-800 dark:text-gray-300">Colours</p>
                    <div class="flex items-center justify-center">
                        <p class="text-sm leading-none text-gray-600 dark:text-gray-300">Smoke Blue with red accents</p>
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
                    <svg class="mr-3 text-white dark:text-gray-900" width="16" height="17" viewBox="0 0 16 17"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
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
        </script>

    </x-app-layout>


</body>

</html>
