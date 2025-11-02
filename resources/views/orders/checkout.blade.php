@php
    use App\Models\Schedule;
    use Carbon\Carbon;
    $isOpen = Schedule::isOpenNow();
    $nextOpening = Schedule::getNextOpeningTime();
@endphp

<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
                <p class="mt-2 text-gray-600">Complete your order details</p>
            </div>

            <!-- Closed Notice -->
            @if(!$isOpen)
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <span class="text-2xl">🔴</span>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-red-800">We're Currently Closed</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>We're sorry, but we cannot accept orders while we're closed.</p>
                                @if($nextOpening)
                                    <p class="mt-1"><strong>We will reopen on {{ $nextOpening->format('F d, Y') }} at {{ $nextOpening->format('g:i A') }}.</strong></p>
                                @else
                                    <p class="mt-1">Please check back later.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4 py-3 border-b border-gray-200">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="text-2xl">🍣</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item['product']->name }}</h3>
                                <p class="text-sm text-gray-500">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${{ number_format($item['total'], 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span>Subtotal</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        @php
                            $deliveredCount = Auth::check() ? \App\Models\Order::where('user_id', Auth::id())->where('status', \App\Models\Order::STATUS_DELIVERED)->count() : 0;
                            $discountPercent = $deliveredCount === 0 ? 15 : ((($deliveredCount + 1) % 10) === 1 ? 20 : 0);
                            $discountAmount = round($total * ($discountPercent/100), 2);
                            $totalAfter = max(0, $total - $discountAmount);
                        @endphp
                        @if($discountPercent > 0)
                            <div class="flex justify-between text-sm text-green-700 mt-1">
                                <span>Discount ({{ $discountPercent }}% {{ $deliveredCount === 0 ? 'first order' : 'loyalty' }})</span>
                                <span>- ${{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-lg font-semibold mt-2">
                            <span>Total:</span>
                            <span class="text-yellow-600">${{ number_format($totalAfter, 2) }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Free delivery included</p>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Delivery Information</h2>
                    
                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Delivery Address -->
                        <div>
                            <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Delivery Address *
                            </label>
                            <div class="space-y-3">
                                <!-- Address Input -->
                                <textarea 
                                    id="delivery_address" 
                                    name="delivery_address" 
                                    rows="3" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                    placeholder="Enter your full delivery address..."
                                    required
                                >{{ old('delivery_address') }}</textarea>
                                
                                <!-- Location Buttons -->
                                <div class="flex space-x-2">
                                    <button type="button" id="use-current-location" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md text-sm font-medium transition-colors">
                                        📍 Use Current Location
                                    </button>
                                    <button type="button" id="pick-on-map" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md text-sm font-medium transition-colors">
                                        🗺️ Pick on Map
                                    </button>
                                </div>
                                
                                <!-- Hidden coordinates -->
                                <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                
                                <!-- Map Container -->
                                <div id="map-container" class="hidden">
                                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-300">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-sm font-medium text-gray-700">📍 Select Delivery Location</h4>
                                            <button type="button" id="close-map" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
                                        </div>
                                        <div id="map" class="w-full h-48 border border-gray-300 rounded-md"></div>
                                        <div class="flex items-center justify-between mt-2">
                                            <p class="text-xs text-gray-500">Click on the map to select your location</p>
                                            <div class="flex space-x-2 map-controls">
                                                <button type="button" id="zoom-in" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">+</button>
                                                <button type="button" id="zoom-out" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">-</button>
                                                <button type="button" id="center-map" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded text-xs">📍</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('delivery_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number * (Moroccan)
                            </label>
                            <input 
                                type="tel" 
                                id="phone" 
                                name="phone" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="06xxxxxxxx or +212xxxxxxxxx"
                                value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                required
                            >
                            <p class="mt-1 text-sm text-gray-500">Enter a valid Moroccan phone number (e.g., 06xxxxxxxx, +212xxxxxxxxx)</p>
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Special Instructions -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Instructions (Optional)
                            </label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="Any special instructions for your order..."
                            >{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            @if($isOpen)
                                <button 
                                    type="submit" 
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center"
                                >
                                    <span class="mr-2">🍣</span>
                                    Confirm My Order
                                </button>
                            @else
                                <button 
                                    type="button" 
                                    disabled
                                    class="w-full bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg cursor-not-allowed flex items-center justify-center opacity-60"
                                >
                                    <span class="mr-2">🔴</span>
                                    Restaurant is Closed
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Back to Cart -->
            <div class="mt-8 text-center">
                <a href="{{ route('cart.index') }}" class="text-yellow-600 hover:text-yellow-700 font-medium">
                    ← Back to Cart
                </a>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <style>
        .custom-marker {
            background: transparent !important;
            border: none !important;
        }
        
        #map {
            cursor: crosshair;
        }
        
        #map-container {
            transition: all 0.3s ease;
        }
        
        .map-controls button {
            transition: all 0.2s ease;
        }
        
        .map-controls button:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
    </style>
    
    <script>
        let map;
        let marker;
        let currentLocation = null;

        document.addEventListener('DOMContentLoaded', function() {
            const useCurrentLocationBtn = document.getElementById('use-current-location');
            const pickOnMapBtn = document.getElementById('pick-on-map');
            const mapContainer = document.getElementById('map-container');
            const closeMapBtn = document.getElementById('close-map');
            const zoomInBtn = document.getElementById('zoom-in');
            const zoomOutBtn = document.getElementById('zoom-out');
            const centerMapBtn = document.getElementById('center-map');
            const latitudeInput = document.getElementById('latitude');
            const longitudeInput = document.getElementById('longitude');
            const addressInput = document.getElementById('delivery_address');

            // Use Current Location
            useCurrentLocationBtn.addEventListener('click', function() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Store current location
                            currentLocation = { lat: lat, lng: lng };
                            
                            // Set coordinates
                            latitudeInput.value = lat;
                            longitudeInput.value = lng;
                            
                            // Reverse geocoding to get address
                            reverseGeocode(lat, lng, addressInput);
                            
                            // Show success message
                            showLocationMessage('Current location detected successfully!', 'success');
                        },
                        function(error) {
                            showLocationMessage('Unable to get your location. Please try again or use the map.', 'error');
                        }
                    );
                } else {
                    showLocationMessage('Geolocation is not supported by this browser.', 'error');
                }
            });

            // Pick on Map
            pickOnMapBtn.addEventListener('click', function() {
                mapContainer.classList.remove('hidden');
                
                if (!map) {
                    // Try to get user's location first, fallback to Casablanca
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const lat = position.coords.latitude;
                                const lng = position.coords.longitude;
                                currentLocation = { lat: lat, lng: lng };
                                
                                // Initialize map centered on user's location
                                map = L.map('map').setView([lat, lng], 12);
                                initializeMapFeatures();
                            },
                            function(error) {
                                // Fallback to Casablanca if location fails
                                map = L.map('map').setView([33.5731, -7.5898], 10);
                                initializeMapFeatures();
                            }
                        );
                    } else {
                        // Fallback to Casablanca if geolocation not supported
                        map = L.map('map').setView([33.5731, -7.5898], 10);
                        initializeMapFeatures();
                    }
                }
            });

            // Initialize map features
            function initializeMapFeatures() {
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                
                // Add click event to map
                map.on('click', function(e) {
                    const lat = e.latlng.lat;
                    const lng = e.latlng.lng;
                    
                    // Debug: Show exact coordinates
                    console.log('Clicked coordinates:', lat, lng);
                    
                    // Set coordinates with higher precision
                    latitudeInput.value = lat.toFixed(6);
                    longitudeInput.value = lng.toFixed(6);
                    
                    // Add/update marker at exact click location
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker([lat, lng], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: '<div style="background-color: #3B82F6; color: white; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">📍</div>',
                            iconSize: [25, 25],
                            iconAnchor: [12, 12]
                        })
                    }).addTo(map);
                    
                    // Show coordinates in message
                    showLocationMessage(`Location selected: ${lat.toFixed(4)}, ${lng.toFixed(4)}`, 'success');
                    
                    // Show coordinates in address field temporarily
                    addressInput.value = `Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    
                    // Reverse geocoding to get address (will replace coordinates)
                    reverseGeocode(lat, lng, addressInput);
                });
            }

            // Close Map
            closeMapBtn.addEventListener('click', function() {
                mapContainer.classList.add('hidden');
            });

            // Zoom In
            zoomInBtn.addEventListener('click', function() {
                if (map) {
                    map.zoomIn();
                }
            });

            // Zoom Out
            zoomOutBtn.addEventListener('click', function() {
                if (map) {
                    map.zoomOut();
                }
            });

            // Center Map
            centerMapBtn.addEventListener('click', function() {
                if (map) {
                    if (currentLocation) {
                        map.setView([currentLocation.lat, currentLocation.lng], 15);
                    } else {
                        // Center on Casablanca
                        map.setView([33.5731, -7.5898], 10);
                    }
                }
            });

            // Reverse geocoding function
            function reverseGeocode(lat, lng, addressInput) {
                // Use higher zoom level for more accurate results
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&zoom=18&extratags=1`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Geocoding response:', data);
                        if (data.address) {
                            // Create a balanced address - not too short, not too detailed
                            const address = data.address;
                            let cleanAddress = '';
                            
                            // Build address with good balance including numbers
                            if (address.road || address.street) {
                                let streetName = address.road || address.street;
                                
                                // Add house number if available
                                if (address.house_number) {
                                    streetName += ', ' + address.house_number;
                                }
                                
                                // Add apartment number if available
                                if (address.apartment || address.flat) {
                                    streetName += ', Apt ' + (address.apartment || address.flat);
                                }
                                
                                cleanAddress += streetName + ', ';
                            }
                            
                            if (address.suburb || address.neighbourhood) {
                                cleanAddress += (address.suburb || address.neighbourhood) + ', ';
                            }
                            if (address.quarter || address.district) {
                                cleanAddress += (address.quarter || address.district) + ', ';
                            }
                            if (address.city || address.town) {
                                cleanAddress += (address.city || address.town);
                            } else if (address.county) {
                                cleanAddress += address.county;
                            }
                            
                            // Fallback to display_name if our custom format is empty
                            if (!cleanAddress.trim()) {
                                cleanAddress = data.display_name;
                            }
                            
                            // Clean up the address (remove extra commas and spaces)
                            cleanAddress = cleanAddress.replace(/,\s*,/g, ',').replace(/,\s*$/, '').trim();
                            
                            addressInput.value = cleanAddress;
                        } else if (data.display_name) {
                            // Fallback to display_name but clean it up for good balance
                            let fallbackAddress = data.display_name;
                            
                            // Remove administrative divisions and country for cleaner display
                            fallbackAddress = fallbackAddress.replace(/,?\s*\d{5}\s*,?/, ''); // Remove postal code
                            fallbackAddress = fallbackAddress.replace(/,?\s*Préfecture[^,]*\s*,?/gi, ''); // Remove "Préfecture" parts
                            fallbackAddress = fallbackAddress.replace(/,?\s*Pachalik[^,]*\s*,?/gi, ''); // Remove "Pachalik" parts
                            fallbackAddress = fallbackAddress.replace(/,?\s*Arrondissement[^,]*\s*,?/gi, ''); // Remove "Arrondissement" parts
                            fallbackAddress = fallbackAddress.replace(/,?\s*Maroc\s*,?$/, ''); // Remove "Maroc" at the end
                            fallbackAddress = fallbackAddress.replace(/,?\s*Morocco\s*,?$/, ''); // Remove "Morocco" at the end
                            
                            // Try to extract and format house/apartment numbers better
                            // Look for patterns like "123, Street Name" and format them
                            fallbackAddress = fallbackAddress.replace(/(\d+)\s*,?\s*([^,]+(?:Boulevard|Rue|Avenue|Street|Road))/gi, '$2, $1');
                            
                            // Clean up extra commas and spaces
                            fallbackAddress = fallbackAddress.replace(/,\s*,/g, ',').replace(/,\s*$/, '').trim();
                            
                            addressInput.value = fallbackAddress;
                        }
                    })
                    .catch(error => {
                        console.error('Reverse geocoding error:', error);
                        // Set a simple fallback with coordinates
                        addressInput.value = `Location: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                        showLocationMessage('Could not get address details, using coordinates', 'warning');
                    });
            }

            // Show location message
            function showLocationMessage(message, type) {
                const messageDiv = document.createElement('div');
                let bgColor, textColor;
                
                switch(type) {
                    case 'success':
                        bgColor = 'bg-green-100';
                        textColor = 'text-green-800';
                        break;
                    case 'warning':
                        bgColor = 'bg-yellow-100';
                        textColor = 'text-yellow-800';
                        break;
                    case 'error':
                    default:
                        bgColor = 'bg-red-100';
                        textColor = 'text-red-800';
                        break;
                }
                
                messageDiv.className = `mt-2 p-3 rounded-md text-sm ${bgColor} ${textColor}`;
                messageDiv.textContent = message;
                
                // Remove existing messages
                const existingMessages = document.querySelectorAll('.location-message');
                existingMessages.forEach(msg => msg.remove());
                
                messageDiv.classList.add('location-message');
                addressInput.parentNode.appendChild(messageDiv);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    messageDiv.remove();
                }, 5000);
            }
        });
    </script>
</x-app-layout>
