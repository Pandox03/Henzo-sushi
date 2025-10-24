<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store the intended URL for redirect after login
            session(['intended_url' => route('cart.index')]);
            return redirect()->route('login')->with('message', 'Please login to view your cart.');
        }

        $cart = session('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'total' => $details['quantity'] * $product->price
                ];
                $total += $details['quantity'] * $product->price;
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        $cart = session('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image
            ];
        }

        session(['cart' => $cart]);
        
        // Calculate total items in cart
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'success' => true, 
            'message' => 'Product added to cart',
            'cart_count' => $totalItems,
            'debug' => [
                'product_id' => $productId,
                'quantity_added' => $quantity,
                'total_items' => $totalItems,
                'cart_contents' => $cart
            ]
        ]);
    }

    public function update(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity;

        $cart = session('cart', []);
        
        if (isset($cart[$productId])) {
            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                $cart[$productId]['quantity'] = $quantity;
            }
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        $productId = $request->product_id;
        $cart = session('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
        }

        return response()->json(['success' => true]);
    }

    public function clear()
    {
        session(['cart' => []]);
        session()->forget('cart');
        return response()->json(['success' => true, 'message' => 'Cart cleared successfully']);
    }

    public function getCount()
    {
        $cart = session('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'cart_count' => $totalItems,
            'cart_contents' => $cart
        ]);
    }

    /**
     * Apply a promo code to the cart
     */
    public function applyPromo(Request $request)
    {
        $promoCode = $request->input('promo_code');
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You need to be logged in to use promo codes.'
            ], 401);
        }

        // Get user's completed orders count
        $orderCount = $user->orders()->where('status', 'delivered')->count();
        
        // Define promo codes with their conditions
        $validPromoCodes = [
            'WELCOME' => [
                'discount' => 15,
                'min_orders' => 0,
                'max_orders' => 0, // Only for first order
                'message' => 'Welcome discount applied! 15% off your first order.',
                'error' => 'Welcome promo code is only valid for first-time customers.'
            ],
            'SUSHI10' => [
                'discount' => 10,
                'min_orders' => 10,
                'max_orders' => 19, // Between 10 and 19 orders
                'message' => 'Loyalty discount applied! 10% off your order.',
                'error' => 'This promo code requires 10-19 completed orders. Your orders: ' . $orderCount
            ],
            'SUSHI20' => [
                'discount' => 20,
                'min_orders' => 20, // 20+ orders
                'max_orders' => PHP_INT_MAX,
                'message' => 'VIP discount applied! 20% off your order.',
                'error' => 'This promo code requires 20+ completed orders. Your orders: ' . $orderCount
            ]
        ];

        $promoCode = strtoupper(trim($promoCode));
        
        if (!array_key_exists($promoCode, $validPromoCodes)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid promo code. Please check and try again.'
            ], 422);
        }
        
        $promo = $validPromoCodes[$promoCode];
        
        // Check if user meets the order requirements
        if ($orderCount >= $promo['min_orders'] && $orderCount <= $promo['max_orders']) {
            // For WELCOME code, make sure it's the first order
            if ($promoCode === 'WELCOME' && $orderCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => $promo['error']
                ], 422);
            }
            
            // Check if promo code is already in session
            if (session('promo_code') === $promoCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'This promo code is already applied.'
                ], 422);
            }
            
            // Store the promo code and discount percentage in the session
            session([
                'promo_code' => $promoCode,
                'discount_percent' => $promo['discount']
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $promo['message'],
                'discount_percent' => $promo['discount']
            ]);
        }
        
        // User doesn't meet the order requirements
        return response()->json([
            'success' => false,
            'message' => $promo['error']
        ], 422);
    }
    
    /**
     * Remove the applied promo code
     */
    public function removePromo()
    {
        session()->forget(['promo_code', 'discount_percent']);
        
        return response()->json([
            'success' => true,
            'message' => 'Promo code removed successfully.'
        ]);
    }
}
