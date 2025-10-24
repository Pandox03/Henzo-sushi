<?php
/**
 * Check Orders Status
 * This script shows the status of orders and helps you find orders for testing
 */

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\User;

echo "🍣 Henzo Sushi - Order Status Checker\n";
echo "====================================\n\n";

// Get all orders with their status
$orders = Order::with(['user', 'deliveryGuy', 'chef'])->orderBy('created_at', 'desc')->get();

if ($orders->isEmpty()) {
    echo "❌ No orders found in the system\n";
    exit(1);
}

echo "📋 Order Status Summary:\n";
echo "========================\n\n";

$statusCounts = [];
foreach ($orders as $order) {
    $status = $order->status;
    $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
}

foreach ($statusCounts as $status => $count) {
    $emoji = match($status) {
        'pending' => '⏳',
        'preparing' => '👨‍🍳',
        'ready' => '✅',
        'out_for_delivery' => '🚚',
        'delivered' => '🎉',
        'cancelled' => '❌',
        default => '📋'
    };
    
    echo "{$emoji} {$status}: {$count} orders\n";
}

echo "\n📊 Detailed Order List:\n";
echo "=======================\n\n";

foreach ($orders as $order) {
    $statusEmoji = match($order->status) {
        'pending' => '⏳',
        'preparing' => '👨‍🍳',
        'ready' => '✅',
        'out_for_delivery' => '🚚',
        'delivered' => '🎉',
        'cancelled' => '❌',
        default => '📋'
    };
    
    echo "Order #{$order->id} {$statusEmoji} {$order->status}\n";
    echo "   Customer: {$order->user->name}\n";
    echo "   Total: $" . number_format($order->total_amount, 2) . "\n";
    echo "   Created: {$order->created_at->format('Y-m-d H:i:s')}\n";
    
    if ($order->chef) {
        echo "   Chef: {$order->chef->name}\n";
    }
    
    if ($order->deliveryGuy) {
        echo "   Delivery: {$order->deliveryGuy->name}\n";
    }
    
    if ($order->status === 'out_for_delivery') {
        echo "   🎯 READY FOR TRACKING TEST!\n";
        echo "   📱 Customer tracking: http://127.0.0.1:8000/orders/{$order->id}/tracking\n";
        echo "   🚚 Delivery navigation: http://127.0.0.1:8000/delivery/orders/{$order->id}/navigation\n";
    }
    
    echo "\n";
}

// Check if we have any orders ready for testing
$trackingReadyOrders = Order::where('status', 'out_for_delivery')
    ->whereNotNull('delivery_guy_id')
    ->count();

if ($trackingReadyOrders > 0) {
    echo "✅ Found {$trackingReadyOrders} orders ready for tracking testing!\n\n";
    echo "🚀 To test tracking, run:\n";
    echo "   php add-test-locations.php\n";
    echo "   OR\n";
    echo "   php test-delivery-tracking.php\n\n";
} else {
    echo "⚠️  No orders ready for tracking testing.\n";
    echo "   You need orders with status 'out_for_delivery' and assigned delivery drivers.\n\n";
    
    echo "💡 To prepare an order for testing:\n";
    echo "   1. Create an order as a customer\n";
    echo "   2. Assign a chef to prepare it\n";
    echo "   3. Mark it as 'ready'\n";
    echo "   4. Assign a delivery driver\n";
    echo "   5. Mark it as 'out_for_delivery'\n\n";
}
?>

