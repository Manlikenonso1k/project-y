<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    protected function getCart(): Cart
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        } else {
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }
        
        return $cart;
    }

    public function index(): View
    {
        $cart = $this->getCart();
        $items = $cart->items()->with('product')->get();
        $total = $cart->getTotal();

        return view('cart', compact('cart', 'items', 'total'));
    }

    public function add(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cart = $this->getCart();
        $product = Product::findOrFail($request->product_id);

        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove(CartItem $cartItem): RedirectResponse
    {
        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    public function clear(): RedirectResponse
    {
        $cart = $this->getCart();
        $cart->items()->delete();

        return redirect()->back()->with('success', 'Cart cleared!');
    }
}
