<!-- resources/views/cart/index.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>

<body>
    <h1>Cart Products</h1>

    @if ($products->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <ul>
            @foreach ($products as $product)
                @php
                    // Find the cart item corresponding to this product
                    $cartItem = collect($carts)->firstWhere('id', $product->id);
                @endphp
                <li>
                    <p><strong>Product ID:</strong> {{ $product->id }}</p>
                    <p><strong>Title:</strong> {{ $product->title }}</p>
                    <p><strong>Price:</strong> {{ $product->price }}</p>
                    <p><strong>Quantity:</strong> {{ $cartItem['quantity'] ?? 0 }}</p>
                    <p><strong>Subtotal:</strong> {{ $product->price * ($cartItem['quantity'] ?? 0) }}</p>
                </li>
                <form method="POST" action="{{ route('sessions.create', $product->id) }}">
                    @csrf
                    <button class="px-4 py-2 mt-1 text-gray-800 bg-white border rounded">
                        Add To Cart
                    </button>
                </form>
                <form method="POST" action="{{ route('sessions.destroy', $product->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 mt-1 text-gray-800 bg-white border rounded">
                        Remove To Cart
                    </button>
                </form>
            @endforeach
        </ul>

        <h2>Total Price: {{ $totalPrice }}</h2>
    @endif

    <a href="{{ route('home') }}">Back</a>
</body>

</html>
