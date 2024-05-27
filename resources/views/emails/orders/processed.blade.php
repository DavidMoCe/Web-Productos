{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}

{{-- @component('mail::message')
    # Order Received
    

    Thank you for your order! Below is a summary of the items in your cart:

    @foreach ($orderDetails['productos'] as $item)
    **{{ $item['titulo_producto'] }}**
    - Estado: {{$item['estado_producto']}}
    - Precio: {{ $item['precio_producto']}}
    - Cantidad: {{ $item['cantidad_producto'] }}
    @endforeach

    Total: {{ $orderDetails['total'] }}

    We will process your order shortly.

    Thank you for shopping with us!

    Thanks,{{ config('app.name') }}
@endcomponent --}}

<!DOCTYPE html>
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
            <p class="text-lg">Thanks,TouchPhone</p>
        </div>
    </div>
</body>
</html>

