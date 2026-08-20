<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GiftCardPaymentController;

// Home Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/bestseller', [HomeController::class, 'bestSeller'])->name('bestseller');

// Product Routes
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{category:slug}', [ProductController::class, 'byCategory'])->name('category.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->middleware('throttle:cart')->name('cart.add');
Route::post('/cart/{cartItem}/update', [CartController::class, 'update'])->middleware('throttle:cart')->name('cart.update');
Route::delete('/cart/{cartItem}/remove', [CartController::class, 'remove'])->middleware('throttle:cart')->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->middleware('throttle:cart')->name('cart.clear');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->middleware('throttle:checkout')->name('checkout.process');
Route::get('/order/{order}/success', [CheckoutController::class, 'success'])->name('order.success');

// Bitcoin Payment Route
Route::get('/create-payment', [PaymentController::class, 'createPayment'])->middleware('throttle:payment')->name('payment.create');
Route::get('/order/{order}/payment', [PaymentController::class, 'showOrderPayment'])->middleware('throttle:payment')->name('payment.order');

// Gift Card Payment Routes
Route::get('/order/{order}/gift-card-payment', [GiftCardPaymentController::class, 'show'])->name('gift-card-payment.show');
Route::post('/order/{order}/gift-card-payment', [GiftCardPaymentController::class, 'submit'])->middleware('throttle:payment')->name('gift-card-payment.submit');
