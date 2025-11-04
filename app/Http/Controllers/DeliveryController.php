<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DeliveryLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:delivery']);
    }

    public function dashboard()
    {
        $deliveryGuy = Auth::user();
        
        // Get orders assigned to this delivery guy
        $assignedOrders = Order::where('delivery_guy_id', $deliveryGuy->id)
            ->whereIn('status', ['out_for_delivery', 'delivered'])
            ->with(['user', 'orderItems.product', 'chef'])
            ->orderBy('out_for_delivery_at', 'desc')
            ->get();

        // Get today's statistics
        $today = now()->startOfDay();
        $tomorrow = now()->addDay()->startOfDay();
        
        $todayStats = [
            'total_orders' => Order::where('delivery_guy_id', $deliveryGuy->id)
                ->whereBetween('out_for_delivery_at', [$today, $tomorrow])
                ->count(),
            'delivered_orders' => Order::where('delivery_guy_id', $deliveryGuy->id)
                ->where('status', 'delivered')
                ->whereBetween('delivered_at', [$today, $tomorrow])
                ->count(),
            'pending_orders' => Order::where('delivery_guy_id', $deliveryGuy->id)
                ->where('status', 'out_for_delivery')
                ->whereBetween('out_for_delivery_at', [$today, $tomorrow])
                ->count(),
        ];

        // Calculate today's earnings (assuming 10% of order value as delivery fee)
        $earnings = Order::where('delivery_guy_id', $deliveryGuy->id)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [$today, $tomorrow])
            ->sum('total_amount') * 0.1;

        return view('delivery.dashboard', compact('assignedOrders', 'todayStats', 'earnings'));
    }

    public function showOrder(Order $order)
    {
        // Check if this order is assigned to the current delivery guy
        if ($order->delivery_guy_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this order.');
        }

        $order->load(['user', 'orderItems.product', 'chef', 'deliveryLocations' => function($query) {
            $query->orderBy('recorded_at', 'desc');
        }]);

        return view('delivery.order-details', compact('order'));
    }

    public function routeNavigation(Order $order)
    {
        // Check if this order is assigned to the current delivery guy
        if ($order->delivery_guy_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this order.');
        }

        // Only allow navigation for orders that are out for delivery
        if ($order->status !== 'out_for_delivery') {
            abort(403, 'This order is not available for navigation.');
        }

        $order->load(['user', 'orderItems.product', 'chef']);

        return view('delivery.route-navigation-osm', compact('order'));
    }

    public function markDelivered(Order $order)
    {
        // Check if this order is assigned to the current delivery guy
        if ($order->delivery_guy_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this order.');
        }

        // Check if order is in the correct status
        if ($order->status !== 'out_for_delivery') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not in the correct status for delivery.'
            ], 400);
        }

        // Update order status
        $order->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);

        // Record final delivery location
        DeliveryLocation::create([
            'order_id' => $order->id,
            'delivery_guy_id' => Auth::id(),
            'latitude' => $order->delivery_latitude,
            'longitude' => $order->delivery_longitude,
            'address' => $order->delivery_address,
            'recorded_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as delivered successfully!'
        ]);
    }

    public function markCancelled(Order $order)
    {
        // Check if this order is assigned to the current delivery guy
        if ($order->delivery_guy_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this order.');
        }

        // Check if order is in the correct status
        if ($order->status !== 'out_for_delivery') {
            return response()->json([
                'success' => false,
                'message' => 'Order is not in the correct status for cancellation.'
            ], 400);
        }

        // Update order status
        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as cancelled successfully!'
        ]);
    }

    public function updateLocation(Request $request, Order $order)
    {
        // Check if this order is assigned to the current delivery guy
        if ($order->delivery_guy_id !== Auth::id()) {
            abort(403, 'You are not authorized to update this order.');
        }

        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500'
        ]);

        // Update delivery guy's current location
        Auth::user()->update([
            'current_latitude' => $request->latitude,
            'current_longitude' => $request->longitude,
            'last_location_update' => now()
        ]);

        // Record delivery location
        DeliveryLocation::create([
            'order_id' => $order->id,
            'delivery_guy_id' => Auth::id(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address ?: 'Location updated',
            'recorded_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully!'
        ]);
    }

    public function orderHistory()
    {
        $deliveryGuy = Auth::user();
        
        $orders = Order::where('delivery_guy_id', $deliveryGuy->id)
            ->with(['user', 'orderItems.product'])
            ->orderBy('out_for_delivery_at', 'desc')
            ->paginate(15);

        return view('delivery.order-history', compact('orders'));
    }

    public function getOrderLocation(Order $order)
    {
        // Check if this order is assigned to the current delivery guy
        if ($order->delivery_guy_id !== Auth::id()) {
            abort(403, 'You are not authorized to view this order.');
        }

        $locations = $order->deliveryLocations()
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'locations' => $locations
        ]);
    }
}