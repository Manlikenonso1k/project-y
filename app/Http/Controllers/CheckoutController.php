<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessCheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function show(): View|RedirectResponse
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
        } else {
            $cart = Cart::where('session_id', session()->getId())->first();
        }

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $items = $cart->items()->with('product')->get();
        $total = $cart->getTotal();

        return view('checkout', compact('cart', 'items', 'total'));
    }

    public function process(ProcessCheckoutRequest $request): RedirectResponse
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
        } else {
            $cart = Cart::where('session_id', session()->getId())->first();
        }

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        $subtotal = $cart->getTotal();
        $tax = $subtotal * 0.1; // 10% tax
        $shipping = 10; // Fixed shipping
        $total = $subtotal + $tax + $shipping;

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . time(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'status' => 'pending',
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
            'notes' => $request->notes ?? '',
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->price * $item->quantity,
            ]);
        }

        $cart->items()->delete();
        session()->put('last_order_id', $order->id);

        return redirect()->route('order.success', $order)->with('success', 'Order placed successfully!');
    }

    public function success(Order $order): View
    {
        $isOwner = Auth::check() && $order->user_id === Auth::id();
        $isGuestOrderInSession = ! Auth::check() && (int) session('last_order_id') === $order->id;

        abort_unless($isOwner || $isGuestOrderInSession, 403);

        return view('order-success', compact('order'));
    }
}
