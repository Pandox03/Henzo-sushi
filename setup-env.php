<?php
/**
 * Simple setup script to configure environment variables
 * Run: php setup-env.php
 */

echo "🗺️ Google Maps API Setup\n";
echo "========================\n\n";

// Check if .env exists
if (!file_exists('.env')) {
    echo "❌ .env file not found!\n";
    echo "Please create a .env file first by running: cp .env.example .env\n";
    echo "Then run: php artisan key:generate\n";
    exit(1);
}

// Read current .env
$envContent = file_get_contents('.env');

// Check if GOOGLE_MAPS_API_KEY is already set
if (strpos($envContent, 'GOOGLE_MAPS_API_KEY=') !== false) {
    echo "✅ GOOGLE_MAPS_API_KEY is already configured in .env\n";
    
    // Extract current value
    preg_match('/GOOGLE_MAPS_API_KEY=(.+)/', $envContent, $matches);
    $currentKey = trim($matches[1] ?? '');
    
    if ($currentKey && $currentKey !== 'YOUR_GOOGLE_MAPS_API_KEY_HERE') {
        echo "Current key: " . substr($currentKey, 0, 10) . "...\n";
        echo "✅ Google Maps should work!\n";
    } else {
        echo "❌ Please set a valid Google Maps API key in .env\n";
        echo "Add this line to your .env file:\n";
        echo "GOOGLE_MAPS_API_KEY=your_actual_api_key_here\n";
    }
} else {
    echo "❌ GOOGLE_MAPS_API_KEY not found in .env\n";
    echo "Add this line to your .env file:\n";
    echo "GOOGLE_MAPS_API_KEY=your_actual_api_key_here\n";
}

echo "\n📋 Quick Setup Steps:\n";
echo "1. Get API key from: https://console.cloud.google.com/\n";
echo "2. Enable: Maps JavaScript API, Directions API, Geocoding API\n";
echo "3. Add to .env: GOOGLE_MAPS_API_KEY=your_key_here\n";
echo "4. Run: php artisan config:clear\n";
echo "5. Refresh your browser\n\n";

echo "🎯 After setup, you'll have:\n";
echo "✅ Live delivery tracking for customers\n";
echo "✅ Route navigation for delivery guys\n";
echo "✅ Real-time GPS tracking\n";
echo "✅ Google Maps integration\n\n";
?>



