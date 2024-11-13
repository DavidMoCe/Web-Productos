{{-- <x-mail::message>
    # Introduction

    The body of your message.

    <x-mail::button :url="''">
    Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message> --}}

    @component('mail::message')
        # Pedido Realizado
        

        {{-- Thank you for your order! Below is a summary of the items in your cart: --}}
        A continuación se muestra un resumen del pedido:

        @foreach ($orderDetails['products'] as $item)
        @php
            switch ($item["state"]) {
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
                default:
                    $estado="";
                    break;
            }
        @endphp

        **{{ $item['name'] }} {{ $item['capacity']}} {{ $item['color'] }}**
        - Estado: {{$estado}}
        - Precio: {{ $item['price']}}
        - Cantidad: {{ $item['quantity'] }}
        @endforeach

        Total: {{ number_format($item['price'], 2, ',', '.') . ' €' }}

        {{-- We will process your order shortly. --}}
        Procesaremos su pedido en breve.

        {{-- Thank you for shopping with us! --}}
        ¡Gracias por comprar con nosotros!

        {{-- Thanks,{{ config('app.name') }} --}}
        {{-- Gracias,{{ config('app.name') }} --}}
    @endcomponent

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Received</title>
    <!-- Agrega la URL del archivo CSS compilado de Tailwind -->
    <style>
        .fondo{
            background-color: #bbcdde;
        }

        .fondo-div{
            background-color: #ffffff;
            margin: 0 auto;
        }

        .text-green-500 {
            color: #38a169;
        }

        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }
    </style>
</head>
<body>
    <div class="fondo container mx-auto">
        <div id="contenido" class="fondo-div">
            <h1 class="text-green-500 text-3xl font-bold mb-4">Order Received</h1>
            <p class="text-lg">Thank you for your order! Below is a summary of the items in your cart:</p>
            @foreach ($orderDetails['productos'] as $item)
            <p class="text-base"><strong>{{ $item['titulo_producto'] }}</strong></p>
            <p class="text-base">- Precio: {{ $item['precio_producto']}}</p>
            <p class="text-base">- Estado: {{$item['estado_producto']}}</p>
            <p class="text-base">- Cantidad: {{ $item['cantidad_producto'] }}</p>
            @endforeach
            <p class="text-lg font-bold">Total: {{ $orderDetails['total'] }}</p>
            <p class="text-lg">We will process your order shortly.</p>
            <p class="text-lg">Thank you for shopping with us!</p>
            <p class="text-lg">Thanks,Titulo proyecto</p>
        </div>
    </div>
</body>
</html> --}}

