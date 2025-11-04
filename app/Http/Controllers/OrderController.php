<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Rules\MoroccanPhone;
use App\Events\NewOrderReceived;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function checkout()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to place an order.');
        }

        // Check if restaurant is open
        if (!Schedule::isOpenNow()) {
            $nextOpening = Schedule::getNextOpeningTime();
            $message = 'We are currently closed. ';
            if ($nextOpening) {
                $message .= 'We will reopen on ' . $nextOpening->format('F d, Y') . ' at ' . $nextOpening->format('g:i A') . '.';
            } else {
                $message .= 'Please check back later.';
            }
            return redirect()->route('cart.index')->with('error', $message);
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

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

        return view('orders.checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to place an order.');
        }

        // Check if restaurant is open
        if (!Schedule::isOpenNow()) {
            $nextOpening = Schedule::getNextOpeningTime();
            $message = 'We are currently closed. ';
            if ($nextOpening) {
                $message .= 'We will reopen on ' . $nextOpening->format('F d, Y') . ' at ' . $nextOpening->format('g:i A') . '.';
            } else {
                $message .= 'Please check back later.';
            }
            return redirect()->route('cart.index')->with('error', $message);
        }

        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'phone' => ['required', 'string', 'max:20', new MoroccanPhone],
            'notes' => 'nullable|string|max:1000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate total
        $total = 0;
        $cartItems = [];
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

        $user = Auth::user();
        $discountPercent = 0;
        $discountAmount = 0;
        $promoCode = null;
        $promoCodeId = session('promo_code_id');

        // Check if promo code is applied
        if ($promoCodeId) {
            $promoCode = PromoCode::find($promoCodeId);
            if ($promoCode && $promoCode->isValid() && $promoCode->canBeUsedByUser($user->id, $total)) {
                // Use promo code discount
                $discountAmount = session('discount_amount', 0);
                if ($promoCode->discount_type === 'percentage') {
                    $discountPercent = $promoCode->discount_value;
                }
            } else {
                // Invalid promo code, remove from session
                session()->forget(['promo_code', 'promo_code_id', 'discount_amount', 'discount_percent']);
            }
        }

        // Fallback to loyalty discounts if no valid promo code
        if (!$promoCode || $discountAmount == 0) {
            $userDeliveredOrdersCount = Order::where('user_id', $user->id)
                ->where('status', Order::STATUS_DELIVERED)
                ->count();

            if ($userDeliveredOrdersCount === 0) {
                // First order
                $discountPercent = 15;
            } elseif ((($userDeliveredOrdersCount + 1) % 10) === 1) {
                // 11th, 21st, 31st, ... orders
                $discountPercent = 20;
            }

            $discountAmount = round($total * ($discountPercent / 100), 2);
        }

        $totalAfterDiscount = max(0, $total - $discountAmount);

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => Auth::id(),
            'status' => 'pending',
            'total_amount' => $totalAfterDiscount,
            'delivery_fee' => 0, // Free delivery for now
            'delivery_address' => $request->delivery_address,
            'delivery_latitude' => $request->latitude,
            'delivery_longitude' => $request->longitude,
            'phone' => $request->phone,
            'notes' => $request->notes,
            'discount_percent' => $discountPercent,
            'discount_amount' => $discountAmount,
        ]);

        // Track promo code usage if applicable
        if ($promoCode && $discountAmount > 0) {
            PromoCodeUsage::create([
                'promo_code_id' => $promoCode->id,
                'user_id' => $user->id,
                'order_id' => $order->id,
                'discount_amount' => $discountAmount,
            ]);
            
            // Clear promo code from session after use
            session()->forget(['promo_code', 'promo_code_id', 'discount_amount', 'discount_percent']);
        }

        // Create order items
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $details['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $details['quantity'] * $product->price,
                ]);
            }
        }

        // Clear cart
        session(['cart' => []]);

        // Broadcast new order event to chefs
        broadcast(new NewOrderReceived($order));

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Load relationships
        $order->load(['orderItems.product', 'chef', 'deliveryGuy']);

        return view('orders.show-new', compact('order'));
    }

    public function liveTracking(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow tracking for orders that are out for delivery or delivered
        if (!in_array($order->status, ['out_for_delivery', 'delivered'])) {
            abort(403, 'This order is not available for live tracking.');
        }

        // Load relationships
        $order->load(['orderItems.product', 'chef', 'deliveryGuy']);
        
        return view('orders.live-tracking-osm', compact('order'));
    }

    public function getDeliveryLocations(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow tracking for orders that are out for delivery or delivered
        if (!in_array($order->status, ['out_for_delivery', 'delivered'])) {
            return response()->json([
                'success' => false,
                'message' => 'This order is not available for tracking.',
                'locations' => []
            ]);
        }

        $locations = $order->deliveryLocations()
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'locations' => $locations
        ]);
    }

    public function index()
    {
        $query = Auth::user()
            ->orders()
            ->with('orderItems.product');

        if (request()->filled('status')) {
            $query->where('status', request()->string('status'));
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('orders.index', compact('orders'));
    }

    public function cancel(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.show', $order)->with('success', 'Order cancelled successfully.');
    }

    public function checkStatus(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Get the last status update time from session
        $lastUpdate = session("order_{$order->id}_last_update", $order->created_at);
        
        // Check if order has been updated since last check
        $hasUpdate = $order->updated_at > $lastUpdate;
        
        if ($hasUpdate) {
            // Update the last update time
            session(["order_{$order->id}_last_update" => $order->updated_at]);
            
            // Load relationships
            $order->load(['chef', 'deliveryGuy']);
            
            // Log the update for debugging
            \Log::info("Order {$order->id} status update detected", [
                'status' => $order->status,
                'updated_at' => $order->updated_at,
                'last_update' => $lastUpdate
            ]);
        }

        return response()->json([
            'hasUpdate' => $hasUpdate,
            'order' => $hasUpdate ? $order : null,
            'debug' => [
                'current_status' => $order->status,
                'updated_at' => $order->updated_at,
                'last_update' => $lastUpdate,
                'has_update' => $hasUpdate
            ]
        ]);
    }
}