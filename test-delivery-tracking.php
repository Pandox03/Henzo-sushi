<?php
/**
 * Delivery Tracking Test Script
 * This script simulates a delivery driver moving and updating their location
 * to test if the live tracking system is working properly
 */

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;
use App\Models\DeliveryLocation;
use App\Models\User;

echo "🍣 Henzo Sushi - Delivery Tracking Test Script\n";
echo "==============================================\n\n";

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
echo "   Delivery Address: {$order->delivery_address}\n";
echo "   Delivery Guy ID: {$order->delivery_guy_id}\n\n";

// Get delivery guy
$deliveryGuy = User::find($order->delivery_guy_id);
if (!$deliveryGuy) {
    echo "❌ Delivery guy not found\n";
    exit(1);
}

echo "✅ Delivery Guy: {$deliveryGuy->name}\n\n";

// Starting location (Casablanca center)
$startLat = 33.5731;
$startLng = -7.5898;

// Customer location
$customerLat = $order->delivery_latitude ?: 33.5731;
$customerLng = $order->delivery_longitude ?: -7.5898;

echo "📍 Starting Location: {$startLat}, {$startLng}\n";
echo "🏠 Customer Location: {$customerLat}, {$customerLng}\n\n";

// Calculate distance and direction
$distance = calculateDistance($startLat, $startLng, $customerLat, $customerLng);
echo "📏 Distance to customer: " . round($distance, 2) . " km\n\n";

// Simulate movement
$currentLat = $startLat;
$currentLng = $startLng;
$step = 0;
$totalSteps = 20; // Number of location updates

echo "🚚 Starting delivery simulation...\n";
echo "   Updating location every 5 seconds...\n";
echo "   Watch the customer tracking page to see live updates!\n\n";

while ($step < $totalSteps) {
    $step++;
    
    // Move towards customer (simple linear interpolation)
    $progress = $step / $totalSteps;
    $currentLat = $startLat + ($customerLat - $startLat) * $progress;
    $currentLng = $startLng + ($customerLng - $startLng) * $progress;
    
    // Add some random variation to make it more realistic
    $currentLat += (rand(-100, 100) / 100000); // Small random variation
    $currentLng += (rand(-100, 100) / 100000);
    
    // Update delivery guy's current location in database
    $deliveryGuy->update([
        'current_latitude' => $currentLat,
        'current_longitude' => $currentLng,
        'last_location_update' => now()
    ]);
    
    // Create delivery location record
    DeliveryLocation::create([
        'order_id' => $order->id,
        'delivery_guy_id' => $deliveryGuy->id,
        'latitude' => $currentLat,
        'longitude' => $currentLng,
        'address' => "Test location update #{$step}",
        'recorded_at' => now()
    ]);
    
    // Calculate remaining distance
    $remainingDistance = calculateDistance($currentLat, $currentLng, $customerLat, $customerLng);
    
    echo "📍 Step {$step}/{$totalSteps}: {$currentLat}, {$currentLng} (Distance: " . round($remainingDistance, 2) . " km)\n";
    
    // Wait 5 seconds before next update
    sleep(5);
}

echo "\n✅ Delivery simulation completed!\n";
echo "   Final location: {$currentLat}, {$currentLng}\n";
echo "   Final distance: " . round($remainingDistance, 2) . " km\n\n";

echo "🎯 Test Results:\n";
echo "   - Check the customer tracking page: /orders/{$order->id}/tracking\n";
echo "   - You should see the driver moving on the map\n";
echo "   - The route should update in real-time\n\n";

echo "🔧 To stop the simulation, press Ctrl+C\n";
echo "   To run again, execute: php test-delivery-tracking.php\n\n";

/**
 * Calculate distance between two coordinates in kilometers
 */
function calculateDistance($lat1, $lng1, $lat2, $lng2) {
    $earthRadius = 6371; // Earth's radius in kilometers
    
    $dLat = deg2rad($lat2 - $lat1);
    $dLng = deg2rad($lng2 - $lng1);
    
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng/2) * sin($dLng/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    
    return $earthRadius * $c;
}
?>


