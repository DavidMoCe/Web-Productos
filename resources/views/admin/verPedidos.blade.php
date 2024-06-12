<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
        
        <!-- <title>Shop Homepage - Start Bootstrap Template</title> -->
        <title>TouchPhone</title>
        <!-- Favicon-->
        <!-- <link rel="icon" type="image/x-icon" href="assets/favicon.ico" /> -->

        <script src="https://cdn.tailwindcss.com"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <x-app-layout>
            <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Pedidos '.$estado) }}
                </h2>
            </x-slot>
           
            <div class="mx-auto max-w-2xl px-4 py-10 md:px-6 md:!py-16 lg:max-w-7xl lg:px-8">
                <div class="bg-white overflow-hidden hover:shadow-md rounded-lg p-5 sm:w-full sm:h-full m-auto">                      
                    <div class="container md:pt-4 md:pb-8 md:px-5 mx-auto">
                        <div class="w-full overflow-x-auto">
                            <div class="pb-8 pt-3">
                                <a href={{ route('verTodosPedidos', ['vista' => 'Recibidos']) }} class="btn btn-default border border-black p-3">Todos los pedidos</a>
                                <a href={{ route('verTodosPedidos', ['vista' => 'Pendientes']) }} class="btn btn-default border border-black p-3">Pedidos pendientes</a>
                                <a href={{ route('verTodosPedidos', ['vista' => 'Aceptados']) }} class="btn btn-default border border-black p-3">Pedidos Aceptados</a>
                                <a href={{ route('verTodosPedidos', ['vista' => 'Procesados']) }} class="btn btn-default border border-black p-3">Pedidos procesados</a>
                                <a href={{ route('verTodosPedidos', ['vista' => 'Rechazados']) }} class="btn btn-default border border-black p-3">Pedidos rechazados</a>
                            </div>
                            @if ($pedidos->isEmpty())
                                <p>No hay pedidos en este momento</p>
                            @else
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg mx-1">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <!-- Cabecera de la tabla -->
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Detalles del pedido
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Detalles del envío
                                            </th>
                                            <th scope="col"  class="px-6 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Detalles de pago
                                            </th>
                                            <th scope="col"  class="px-6 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                                Estado pedido
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- Cuerpo de la tabla -->
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        @foreach ($pedidos as $pedido)
                                            @foreach ($pedido->productos as $producto)
                                                @php
                                                    switch ($producto->estado) {
                                                        case 'STA':
                                                        case 'COR':
                                                            $estado="Correcto";
                                                            break;
                                                        case 'BUE':
                                                        case 'MBU':
                                                            $estado="Muy bueno";
                                                            break;
                                                        case 'IMP':
                                                            $estado="Excelente";
                                                            break;
                                                        default:
                                                            $estado="";
                                                            break;
                                                    }
                                                @endphp
                                                <tr data-pedido-id="{{ $pedido->id }}">
                                                    <td class="py-3 whitespace-nowrap text-center cursor-pointer openModalBtn" onclick="obtenerDatos({{ $pedido->id }})">
                                                        <div class="text-left pl-6 flex flex-col">
                                                            <span class="underline text-md">{{ $pedido->id }}</span>
                                                            <span class="text-sm">{{ $pedido->created_at->format('d/m/Y, H:i')}}</span>
                                                            <span class="text-sm font-bold">{{ $pedido->usuario->name}} {{ $pedido->usuario->lastname}}</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 whitespace-nowrap text-center">
                                                        <div class="text-left pl-6 flex flex-col">
                                                            <span class="text-sm">SKU</span>
                                                            <span class="text-sm">{{ $producto->nombre." ". $producto->capacidad." - ".$producto->color." - ".($producto->libre ? 'Libre ':'').($producto->bateria ? $producto->bateria:'')." ".$estado}}</span>
                                                            <span class="text-sm pt-1">Cantidad</span>
                                                            <span class="text-sm">{{ $producto->pivot->unidades }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 whitespace-nowrap text-center">
                                                        <div class="text-left pl-6 flex flex-col">
                                                            <span class="text-sm text-slate-600">Precio total (IVA incluido)</span>
                                                            <span class="text-sm font-bold">{{ number_format($producto->precioD, 2, ',', '.') . ' €' }}</span>
                                                            <span class="text-sm">{{ $pedido->metodoPago }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 whitespace-nowrap text-center">
                                                        <select class="form-select" onchange="actualizarEstadoPedido({{ $pedido->id }}, this.value)">
                                                            <option value="pendiente" {{ $pedido->enviado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                            <option value="aceptado" {{ $pedido->enviado == 'aceptado' ? 'selected' : '' }}>Aceptado</option>
                                                            <option value="procesado" {{ $pedido->enviado == 'procesado' ? 'selected' : '' }}>Procesado</option>
                                                            <option value="rechazado" {{ $pedido->enviado == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                                            
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-end opacity-0 pointer-events-none transition-opacity duration-500">
                                <div id="modalContentContainer" class="bg-gray-100 w-2/3 h-full p-4 shadow-lg transform translate-x-full transition-transform duration-500">
                                    <button id="closeModalBtn" class="btn btn-default border border-black p-3">Cerrar</button>
                                    <h2 class="text-xl font-bold mb-4">Datos del usuario</h2>

                                    <div class="bg-white overflow-hidden hover:shadow-md sm:w-full rounded-lg p-5 m-auto mb-6">                      
                                        <div class="container md:p-1 mx-auto relative">
                                            <div class="w-full overflow-x-auto">
                                                <h2 class="text-xl font-bold mb-4">Estado del pedido</h2>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-row gap-6 mb-6">
                                        <div class="bg-white overflow-hidden hover:shadow-md sm:w-full rounded-lg p-5 m-auto">                      
                                            <div class="container md:p-1 mx-auto relative">
                                                <div class="w-full overflow-x-auto">
                                                    <h2 class="text-xl font-bold mb-4">Detalles del pedido</h2>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-white overflow-hidden hover:shadow-md sm:w-full rounded-lg p-5 m-auto">                      
                                            <div class="container md:p-1 mx-auto relative">
                                                <div class="w-full overflow-x-auto">
                                                    <h2 class="text-xl font-bold mb-4">Detalles del envío</h2>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white overflow-hidden hover:shadow-md sm:w-full rounded-lg p-5 m-auto">                      
                                        <div class="container md:p-1 mx-auto relative">
                                            <div class="w-full overflow-x-auto">
                                                <h2 class="text-xl font-bold mb-4">Líneas de pedido</h2>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-row gap-6 mt-6">
                                        <div class="bg-white overflow-hidden hover:shadow-md sm:w-full rounded-lg p-5 m-auto">                      
                                            <div class="container md:p-1 mx-auto relative">
                                                <div class="w-full overflow-x-auto">
                                                    <h2 class="text-xl font-bold mb-4">Dirección de envío</h2>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-white overflow-hidden hover:shadow-md sm:w-full rounded-lg p-5 m-auto">                      
                                            <div class="container md:p-1 mx-auto relative">
                                                <div class="w-full overflow-x-auto">
                                                    <h2 class="text-xl font-bold mb-4">Dirección de facturación</h2>

                                                    <div class="mb-2">
                                                        <span>
                                                            {{  $pedido->usuario->name }}
                                                            {{  $pedido->usuario->lastname }}
                                                        </span>
                                                    </div>
                                                    <div class="">
                                                        <span>
                                                            {{ $pedido->direccionFacturacion->empresa }}
                                                        </span>
                                                    </div>
                                                    <div class="">
                                                        <span>
                                                            {{ $pedido->direccionFacturacion->direccion_1 }}
                                                        </span>
                                                    </div>
                                                    <div class="">
                                                        <span>
                                                            {{ old('address_2', isset($direccion_2) ? $direccion_2 : '') }}
                                                        </span>
                                                        <input type="text" id="address_2" name="address_2" value="{{ old('address_2', isset($direccion_2) ? $direccion_2 : '') }}" hidden readonly>
                                                    </div>
                                                    <div class="">
                                                        <span>
                                                            {{ old('postal_code', isset($codigo_postal) ? $codigo_postal : '') }}
                                                            {{ old('city', isset($ciudad) ? $ciudad : '') }}Málaga
                                                        </span>
                                                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', isset($codigo_postal) ? $codigo_postal : '') }}" hidden readonly>
                                                        <input type="text" id="city" name="city" value="{{ old('city', isset($ciudad) ? $ciudad : '') }}" hidden readonly>
                                                    </div>
                                                    <div class="">
                                                        <span>
                                                            {{ old('country', isset($pais) ?  $pais : '') }}
                                                        </span>
                                                        <input type="text" id="country" name="country"value="{{ old('country', isset($pais) ?  $pais : '') }}" hidden readonly>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span>
                                                            {{ old('phone', isset($telefono) ? $telefono : '') }}
                                                        </span>
                                                        <input type="text" id="phone" name="phone" value="{{ old('phone', isset($telefono) ? $telefono : '') }}" hidden readonly>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    
                                    
                                    <p id='nombreCliente' class="p-2">Nombre</p>
                                    <p id='apellidoCliente' class="p-2">Apellido</p>
                                    <p id='emailCliente' class="p-2">Email</p>
                                    <p id='telefonoCliente' class="p-2">Telefono</p>
                                    
                                    <p id='E_pais' class="p-2">País envío</p>
                                    <p id='E_direccion_1' class="p-2">Dirección 1 envío</p>
                                    <p id='E_direccion_2' class="p-2">Dirección 2 envío</p>
                                    <p id='E_ciudad' class="p-2">Ciudad envío</p>
                                    <p id='E_codigoPostal' class="p-2">CP envío</p>
                                    <p id='E_empresa' class="p-2">Empresa envío</p>
                                    <p id='E_telefono' class="p-2">Teléfono envío</p>
                                    
                                    <p id='F_pais' class="p-2">País facturación</p>
                                    <p id='F_direccion_1' class="p-2">Dirección 1 facturación</p>
                                    <p id='F_direccion_2' class="p-2">Dirección 2 facturación</p>
                                    <p id='F_ciudad' class="p-2">Ciudad facturación</p>
                                    <p id='F_codigoPostal' class="p-2">CP facturación</p>
                                    <p id='F_empresa' class="p-2">Empresa facturación</p>
                                    <p id='F_nif_dni' class="p-2">NIF/DNI facturación</p>
                                </div>
                            </div>
                        
                            <script>
                                function actualizarEstadoPedido(pedidoId, nuevoEstado) {
                                    // Envía los datos al controlador mediante AJAX para actualizar el estado del pedido
                                    $.ajax({
                                        type: 'POST',
                                        url: '{{ route('actualizarEstadoPedido') }}',
                                        data: {
                                            pedido_id: pedidoId,
                                            nuevo_estado: nuevoEstado,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {
                                            // Maneja la respuesta del servidor si es necesario
                                            //console.log(response);
                                        },
                                        error: function(xhr, status, error) {
                                            // Maneja los errores si es necesario
                                            console.error(xhr.responseText);
                                        }
                                    });

                                }

                                const modal = document.getElementById('modal');
                                const modalContentContainer = document.getElementById('modalContentContainer');
                                const closeModalBtn = document.getElementById('closeModalBtn');

                                function obtenerDatos(pedidoId) {
                                    // Envía los datos al controlador mediante AJAX para obtener los datos del cliente
                                    $.ajax({
                                        type: 'POST',
                                        url: '{{ route('obtenerDatosCliente') }}',
                                        data: {
                                            pedido_id: pedidoId,
                                            _token: '{{ csrf_token() }}'
                                        },
                                        success: function(response) {

                                            //obtenemos los datos de la peticion
                                            var nombre = response.nombre;
                                            var apellido = response.apellido;
                                            var email = response.email;
                                            var telefono = response.telefono;
                                            var E_pais = response.E_pais;
                                            var E_direccion_1 = response.E_direccion_1;
                                            var E_direccion_2 = response.E_direccion_2;
                                            var E_ciudad = response.E_ciudad;
                                            var E_codigo_postal = response.E_codigo_postal;
                                            var E_empresa = response.E_empresa;
                                            var E_telefono = response.E_telefono;
                                            var F_pais = response.F_pais;
                                            var F_direccion_1 = response.F_direccion_1;
                                            var F_direccion_2 = response.F_direccion_2;
                                            var F_ciudad = response.F_ciudad;
                                            var F_codigo_postal = response.F_codigo_postal;
                                            var F_empresa = response.F_empresa;
                                            var F_nif_dni = response.F_nif_dni;

                                            // Maneja la respuesta del servidor si es necesario
                                            console.log(response);
                                            $("#nombreCliente").text(nombre);
                                            $("#apellidoCliente").text(apellido);
                                            $("#emailCliente").text(email);
                                            $("#telefonoCliente").text(telefono);
                                            
                                            $("#E_pais").text(E_pais);
                                            $("#E_direccion_1").text(E_direccion_1);
                                            $("#E_direccion_2").text(E_direccion_2);
                                            $("#E_ciudad").text(E_ciudad);
                                            $("#E_codigoPostal").text(E_codigo_postal);
                                            $("#E_empresa").text(E_empresa);
                                            $("#E_telefono").text(E_telefono);
                                            
                                            $("#F_pais").text(F_pais);
                                            $("#F_direccion_1").text(F_direccion_1);
                                            $("#F_direccion_2").text(F_direccion_2);
                                            $("#F_ciudad").text(F_ciudad);
                                            $("#F_codigoPostal").text(F_codigo_postal);
                                            $("#F_empresa").text(F_empresa);
                                            $("#F_nif_dni").text(F_nif_dni);
                                            
                                        },
                                        error: function(xhr, status, error) {
                                            // Maneja los errores si es necesario
                                            console.error(xhr.responseText);
                                        }
                                    });

                                    modal.classList.remove('opacity-0', 'pointer-events-none');
                                    modalContentContainer.classList.remove('translate-x-full');
                                }

                                // Manejar el cierre de la ventana emergente
                                function closeModal() {
                                    modal.classList.add('opacity-0', 'pointer-events-none');
                                    modalContentContainer.classList.add('translate-x-full');
                                }
                                closeModalBtn.addEventListener('click', closeModal);
                                // Cerrar el modal cuando se hace clic en el fondo
                                modal.addEventListener('click', closeModal);
                                // Evitar el cierre del modal al hacer clic en el contenido del modal
                                modalContentContainer.addEventListener('click', (event) => {
                                    event.stopPropagation();
                                });
                            </script>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-app-layout>
    </body>
</html>