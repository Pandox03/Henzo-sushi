<?php
/**
 * Add Test Delivery Locations
 * This script adds some test delivery locations to an order for testing
 */

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\DeliveryLocation;
use App\Models\User;

echo "🍣 Henzo Sushi - Add Test Delivery Locations\n";
echo "===========================================\n\n";

// Get an order that's out for delivery
$order = Order::where('status', 'out_for_delivery')
    ->whereNotNull('delivery_guy_id')
    ->first();

if (!$order) {
    echo "❌ No orders found with status 'out_for_delivery'\n";
    echo "   Please assign a delivery driver to an order first.\n\n";
    exit(1);
}

echo "✅ Found order #{$order->id}\n";
echo "   Customer: {$order->user->name}\n";
echo "   Delivery Address: {$order->delivery_address}\n\n";

// Get delivery guy
$deliveryGuy = User::find($order->delivery_guy_id);
if (!$deliveryGuy) {
    echo "❌ Delivery guy not found\n";
    exit(1);
}

echo "✅ Delivery Guy: {$deliveryGuy->name}\n\n";

// Customer location
$customerLat = $order->delivery_latitude ?: 33.5731;
$customerLng = $order->delivery_longitude ?: -7.5898;

// Test locations (simulating driver movement)
$testLocations = [
    ['lat' => 33.5731, 'lng' => -7.5898, 'address' => 'Starting location - Restaurant'],
    ['lat' => 33.5740, 'lng' => -7.5900, 'address' => 'On the road - Boulevard Mohammed V'],
    ['lat' => 33.5750, 'lng' => -7.5905, 'address' => 'Approaching customer area'],
    ['lat' => 33.5755, 'lng' => -7.5910, 'address' => 'Near customer location'],
    ['lat' => $customerLat, 'lng' => $customerLng, 'address' => 'Arrived at customer location']
];

echo "📍 Adding test locations...\n\n";

foreach ($testLocations as $index => $location) {
    // Update delivery guy's current location
    $deliveryGuy->update([
        'current_latitude' => $location['lat'],
        'current_longitude' => $location['lng'],
        'last_location_update' => now()->subMinutes(count($testLocations) - $index)
    ]);
    
    // Create delivery location record
    DeliveryLocation::create([
        'order_id' => $order->id,
        'delivery_guy_id' => $deliveryGuy->id,
        'latitude' => $location['lat'],
        'longitude' => $location['lng'],
        'address' => $location['address'],
        'recorded_at' => now()->subMinutes(count($testLocations) - $index)
    ]);
    
    echo "✅ Added location " . ($index + 1) . ": {$location['lat']}, {$location['lng']}\n";
    echo "   Address: {$location['address']}\n\n";
}

echo "🎯 Test locations added successfully!\n\n";
echo "📱 Now check the customer tracking page:\n";
echo "   http://127.0.0.1:8000/orders/{$order->id}/tracking\n\n";
echo "   You should see:\n";
echo "   - Driver marker on the map\n";
echo "   - Route from driver to customer\n";
echo "   - Live updates in the tracking log\n\n";
?>

