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
    # Order Received
    

    Thank you for your order! Below is a summary of the items in your cart:

    {{-- @foreach ($cartItems as $item)
    **{{ $item['title'] }}**
    - Price: ${{ number_format($item['price'], 2) }}
    - Quantity: {{ $item['quantity'] }}
    @endforeach --}}

    Total: ${{ $orderDetails['total'] }}

    We will process your order shortly.

    Thank you for shopping with us!

    Thanks,{{ config('app.name') }}
@endcomponent

{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Received</title>
    <!-- Agrega la URL del archivo CSS compilado de Tailwind -->
    <style>
        div{
            background-color: #edf2f7;
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
    <div class="container mx-auto">
        <h1 class="text-green-500 text-3xl font-bold mb-4">Order Received</h1>

        <p class="text-lg">Thank you for your order! Below is a summary of the items in your cart:</p> --}}

        {{-- @foreach ($cartItems as $item)
        <p class="text-base"><strong>{{ $item['title'] }}</strong></p>
        <p class="text-base">- Price: ${{ number_format($item['price'], 2) }}</p>
        <p class="text-base">- Quantity: {{ $item['quantity'] }}</p>
        @endforeach --}}

        {{-- <p class="text-lg font-bold">Total: $688.75</p>

        <p class="text-lg">We will process your order shortly.</p>

        <p class="text-lg">Thank you for shopping with us!</p>

        <p class="text-lg">Thanks,TouchPhone</p>
    </div>
</body>
</html> --}}

