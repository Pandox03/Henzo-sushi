<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\OrderStatusChanged;

class ChefController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.role:chef');
    }

    public function dashboard()
    {
        // Get awaiting orders (pending status)
        $awaitingOrders = Order::where('status', 'pending')
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get chef's order history
        $chefOrders = Order::where('chef_id', Auth::id())
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('chef.dashboard', compact('awaitingOrders', 'chefOrders'));
    }

    public function acceptOrder(Order $order)
    {
        // Check if order is still pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'This order is no longer available.');
        }

        // Assign order to chef
        $order->update([
            'chef_id' => Auth::id(),
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        // Broadcast order status change
        broadcast(new OrderStatusChanged($order));

        return redirect()->back()->with('success', 'Order accepted successfully!');
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:preparing,ready'
        ]);

        // Check if chef owns this order
        if ($order->chef_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to update this order.');
        }

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'preparing') {
            $updateData['preparing_at'] = now();
        } elseif ($request->status === 'ready') {
            $updateData['ready_at'] = now();
        }

        $order->update($updateData);

        // Broadcast order status change
        broadcast(new OrderStatusChanged($order));

        $statusMessage = $request->status === 'preparing' ? 'Order marked as preparing!' : 'Order marked as ready!';
        return redirect()->back()->with('success', $statusMessage);
    }

    public function assignDelivery(Request $request, Order $order)
    {
        try {
            \Log::info('Assign delivery request:', [
                'order_id' => $order->id,
                'delivery_guy_id' => $request->delivery_guy_id,
                'all_data' => $request->all(),
                'chef_id' => Auth::id()
            ]);

            $request->validate([
                'delivery_guy_id' => 'required|exists:users,id'
            ]);

            // Check if chef owns this order
            if ($order->chef_id !== Auth::id()) {
                \Log::warning('Unauthorized delivery assignment attempt', [
                    'order_id' => $order->id,
                    'chef_id' => Auth::id(),
                    'order_chef_id' => $order->chef_id
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to assign delivery for this order.'
                ], 403);
            }

            // Check if order is ready
            if ($order->status !== 'ready') {
                \Log::warning('Order not ready for delivery assignment', [
                    'order_id' => $order->id,
                    'status' => $order->status
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Order must be ready before assigning delivery.'
                ], 400);
            }

            // Check if delivery guy has delivery role
            $deliveryGuy = User::find($request->delivery_guy_id);
            if (!$deliveryGuy || !$deliveryGuy->hasRole('delivery')) {
                \Log::warning('Invalid delivery guy selected', [
                    'delivery_guy_id' => $request->delivery_guy_id,
                    'user_exists' => $deliveryGuy ? true : false,
                    'has_delivery_role' => $deliveryGuy ? $deliveryGuy->hasRole('delivery') : false
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Selected user is not a delivery person.'
                ], 400);
            }

            $order->update([
                'delivery_guy_id' => $request->delivery_guy_id,
                'status' => 'out_for_delivery',
                'out_for_delivery_at' => now()
            ]);

            // Broadcast order status change
            broadcast(new OrderStatusChanged($order));

            \Log::info('Delivery assignment successful', [
                'order_id' => $order->id,
                'delivery_guy_id' => $request->delivery_guy_id,
                'delivery_guy_name' => $deliveryGuy->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order assigned to delivery person successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error assigning delivery', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error assigning delivery: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAvailableDeliveryGuys()
    {
        try {
            $deliveryGuys = User::role('delivery')
                ->where('is_active', true)
                ->select('id', 'name', 'phone')
                ->get();

            \Log::info('Delivery guys found:', $deliveryGuys->toArray());

            return response()->json($deliveryGuys);
        } catch (\Exception $e) {
            \Log::error('Error fetching delivery guys: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch delivery guys'], 500);
        }
    }

    public function orderHistory()
    {
        $orders = Order::where('chef_id', Auth::id())
            ->with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('chef.order-history', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        // Check if chef owns this order or if it's awaiting assignment
        if ($order->chef_id !== Auth::id() && $order->status !== 'pending') {
            abort(403, 'You are not authorized to view this order.');
        }

        $order->load(['user', 'orderItems.product', 'chef', 'deliveryGuy']);

        return view('chef.order-details', compact('order'));
    }

    public function printBill(Order $order)
    {
        // Check if chef owns this order
        if ($order->chef_id !== Auth::id()) {
            abort(403, 'You are not authorized to print this bill.');
        }

        return view('chef.print-bill', compact('order'));
    }

    public function checkNewOrders()
    {
        // Get the last order ID from session or use 0
        $lastOrderId = session('last_chef_order_id', 0);
        
        \Log::info('Checking for new orders', [
            'chef_id' => Auth::id(),
            'last_order_id' => $lastOrderId,
            'time' => now()->toDateTimeString()
        ]);
        
        // Get new pending orders created in the last 2 minutes
        $recentOrders = Order::where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(2))
            ->where('id', '>', $lastOrderId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        \Log::info('Found orders', [
            'count' => $recentOrders->count(),
            'orders' => $recentOrders->pluck('id')->toArray()
        ]);
        
        if ($recentOrders->count() > 0) {
            // Update the last order ID to the highest ID we've seen
            $highestId = $recentOrders->max('id');
            session(['last_chef_order_id' => $highestId]);
            
            \Log::info('Returning new orders', [
                'highest_id' => $highestId,
                'latest_order' => $recentOrders->first()->id
            ]);
            
            return response()->json([
                'hasNewOrders' => true,
                'latestOrder' => $recentOrders->first(),
                'newOrdersCount' => $recentOrders->count()
            ]);
        }
        
        return response()->json(['hasNewOrders' => false]);
    }

    public function checkOrderUpdates()
    {
        // Get orders assigned to this chef
        $chefOrders = Order::where('chef_id', Auth::id())
            ->whereIn('status', ['accepted', 'preparing', 'ready', 'out_for_delivery'])
            ->with('user', 'deliveryGuy')
            ->get();
        
        // For now, we'll just return that there are no updates
        // In a real implementation, you'd track what changed
        return response()->json(['hasUpdates' => false, 'updatedOrders' => []]);
    }

}