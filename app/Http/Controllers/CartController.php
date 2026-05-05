<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class CartController extends Controller
{
    protected function getCart(): Cart
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
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

    public function add(AddToCartRequest $request): RedirectResponse
    {
        $cart = $this->getCart();
        $product = Product::findOrFail($request->product_id);
        $price = $product->price;
        $variantId = null;

        if ($product->is_variable) {
            $variant = null;

            if ($request->filled('variant_id')) {
                $variant = $product->variants()->find($request->variant_id);
            }

            if (! $variant) {
                $variant = $product->variants()->orderBy('id')->first();
            }

            if ($variant) {
                $price = $variant->price;
                $variantId = $variant->id;
            }
        }

        $existingItemQuery = $cart->items()->where('product_id', $product->id);

        if ($variantId !== null && Schema::hasColumn('cart_items', 'variant_id')) {
            $existingItemQuery->where('variant_id', $variantId);
        }

        $existingItem = $existingItemQuery->first();

        if ($existingItem instanceof CartItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity
            ]);
        } else {
            $cartItemData = [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $price,
            ];

            if ($variantId !== null && Schema::hasColumn('cart_items', 'variant_id')) {
                $cartItemData['variant_id'] = $variantId;
            }

            CartItem::create($cartItemData);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->cart_id === $this->getCart()->id, 403);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Cart updated!');
    }

    public function remove(CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->cart_id === $this->getCart()->id, 403);

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
