<x-main-layout>
    <section class="container">
        <div class="flex items-center justify-between">
            <div>
                <h1>Products</h1>
            </div>
            <div>
                <a href="{{ route('carts.index') }}">
                    Cart 8
                </a>
            </div>
        </div>

        @if ($products)
            <div
                class="grid items-center w-full px-2 py-10 mx-auto space-y-4 max-w-7xl md:grid-cols-2 md:gap-6 md:space-y-0 lg:grid-cols-4">
                @foreach ($products as $product)
                    <div class="relative aspect-[16/9] p-1 border  w-auto rounded-md md:aspect-auto md:h-[400px]">
                        <img src="{{ Storage::url($product->image) }}" alt="AirMax Pro"
                            class="z-0 object-cover w-full h-full rounded-md" />
                        <div class="absolute inset-0 rounded-md bg-gradient-to-t from-gray-900 to-transparent"></div>
                        <div class="absolute text-left bottom-4 left-4">
                            <h1 class="text-lg font-semibold text-white"> {{ $product->title }}</h1>
                            <p class="mt-2 text-sm text-gray-300">
                                ₹ {{ $product->price }}
                            </p>
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
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-red-500">No Products</div>
        @endif

    </section>
</x-main-layout>
