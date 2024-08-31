<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $carts = $request->session()->get('carts', []);

        $products = Product::all();
        return view('home', ['products' => $products]);
    }


    public function showCarts(Request $request)
    {
        // Retrieve the cart from the session
        $carts = $request->session()->get('carts', []);

        // Extract product IDs from the cart
        $productIds = array_column($carts, 'id');

        // Query the database for products with these IDs
        $products = Product::whereIn('id', $productIds)->get();

        // Calculate the total price
        $totalPrice = 0;
        foreach ($products as $product) {
            // Find the cart item corresponding to this product
            $cartItem = collect($carts)->firstWhere('id', $product->id);
            // Calculate the total price for this product and add to the total
            $totalPrice += $product->price * ($cartItem['quantity'] ?? 0);
        }

        // Pass products, carts, and total price data to the view
        return view('carts', compact('products', 'carts', 'totalPrice'));
    }


    public function createSession(Request $request, Product $product)
    {
        $carts = $request->session()->get('carts', []);

        // Flag to check if the product was added
        $productExists = false;

        // Use reference (&) to modify the original array
        foreach ($carts as &$cart) {
            if ($cart['id'] === $product->id) {
                $cart['quantity']++; // Increment the quantity by 1
                $productExists = true;
                break; // Exit the loop once the product is found and updated
            }
        }

        // Add the product with quantity 1 if it does not already exist
        if (!$productExists) {
            $carts[] = ['id' => $product->id, 'quantity' => 1];
        }

        // Save the updated carts back to the session
        $request->session()->put('carts', $carts);

        return redirect()->back();
    }

    public function destroySession(Request $request, Product $product)
    {
        // Retrieve the current cart from the session
        $carts = $request->session()->get('carts', []);

        foreach ($carts as $key => &$cart) {
            if ($cart['id'] === $product->id) {
                // If quantity equals to one, remove the product from the cart
                if ($cart['quantity'] === 1) {
                    unset($carts[$key]);
                    break;
                } else {
                    // Decrease the quantity by 1
                    $cart['quantity']--;
                    break;
                }
            }
        }

        // Update the session with the modified cart
        $request->session()->put('carts', $carts);

        return redirect()->back();
    }
}
