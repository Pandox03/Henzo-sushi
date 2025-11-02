<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
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
                // Use discounted price if discount is valid
                $itemPrice = $product->isDiscountValid() ? $product->discounted_price : $product->price;
                $itemTotal = $details['quantity'] * $itemPrice;
                
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'total' => $itemTotal,
                    'original_price' => $product->price,
                    'discounted_price' => $product->isDiscountValid() ? $product->discounted_price : null,
                    'discount_amount' => $product->isDiscountValid() ? $product->discount_amount : 0,
                ];
                $total += $itemTotal;
            }
        }

        // Get schedule status
        $isOpen = Schedule::isOpenNow();
        $nextOpening = Schedule::getNextOpeningTime();

        return view('cart.index', compact('cartItems', 'total', 'isOpen', 'nextOpening'));
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
        
        // Use discounted price if discount is valid
        $itemPrice = $product->isDiscountValid() ? $product->discounted_price : $product->price;
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            // Update price in case discount changed
            $cart[$productId]['price'] = $itemPrice;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $itemPrice,
                'original_price' => $product->price,
                'discounted_price' => $product->isDiscountValid() ? $product->discounted_price : null,
                'has_discount' => $product->isDiscountValid(),
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
        $promoCodeString = strtoupper(trim($request->input('promo_code')));
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'You need to be logged in to use promo codes.'
            ], 401);
        }

        // Find promo code in database
        $promoCode = PromoCode::where('code', $promoCodeString)->first();
        
        if (!$promoCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid promo code.'
            ], 400);
        }

        // Check if promo code is valid
        if (!$promoCode->isValid()) {
            if (!$promoCode->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This promo code is not active.'
                ], 400);
            }
            
            if ($promoCode->expires_at && $promoCode->expires_at->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This promo code has expired.'
                ], 400);
            }

            if ($promoCode->total_usage_limit) {
                $currentUsage = $promoCode->usages()->count();
                if ($currentUsage >= $promoCode->total_usage_limit) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This promo code has reached its usage limit.'
                    ], 400);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'This promo code is not valid.'
            ], 400);
        }

        // Get cart total
        $cart = session('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                // Use discounted price if discount is valid
                $itemPrice = $product->isDiscountValid() ? $product->discounted_price : $product->price;
                $itemTotal = $details['quantity'] * $itemPrice;
                
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $details['quantity'],
                    'total' => $itemTotal,
                ];
                $total += $itemTotal;
            }
        }

        // Check if user can use this promo code
        if (!$promoCode->canBeUsedByUser($user->id, $total)) {
            $userUsageCount = $promoCode->usages()->where('user_id', $user->id)->count();
            
            if ($promoCode->minimum_order_amount && $total < $promoCode->minimum_order_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum order amount of ' . number_format($promoCode->minimum_order_amount, 2) . ' MAD required.'
                ], 400);
            }

            if ($userUsageCount >= $promoCode->usage_limit_per_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have reached the maximum usage limit for this promo code.'
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'You cannot use this promo code.'
            ], 400);
        }

        // Calculate discount
        $discountAmount = $promoCode->calculateDiscount($total, $cartItems);
        
        // Store promo code in session
        session([
            'promo_code' => $promoCode->code,
            'promo_code_id' => $promoCode->id,
            'discount_amount' => $discountAmount,
            'discount_percent' => $promoCode->discount_type === 'percentage' ? $promoCode->discount_value : null
        ]);

        $message = 'Promo code applied successfully! ';
        if ($promoCode->discount_type === 'percentage') {
            $message .= $promoCode->discount_value . '% off';
        } else {
            $message .= number_format($promoCode->discount_value, 2) . ' MAD off';
        }
        $message .= ' applied.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'discount_amount' => $discountAmount,
            'discount_percent' => $promoCode->discount_type === 'percentage' ? $promoCode->discount_value : null
        ]);
    }
    
    /**
     * Remove the applied promo code
     */
    public function removePromo()
    {
        session()->forget(['promo_code', 'promo_code_id', 'discount_amount', 'discount_percent']);
        
        return response()->json([
            'success' => true,
            'message' => 'Promo code removed successfully.'
        ]);
    }
}
