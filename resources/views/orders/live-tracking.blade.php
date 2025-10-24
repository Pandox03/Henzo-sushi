<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <!-- Header -->
        <div class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">🚚 Live Delivery Tracking</h1>
                        <p class="text-gray-600">Order #{{ $order->order_number }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Delivery Status</div>
                            <div class="text-lg font-semibold text-blue-600">
                                @if($order->status === 'out_for_delivery')
                                    🚚 Out for Delivery
                                @elseif($order->status === 'delivered')
                                    ✅ Delivered
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Back to Order
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Map Container -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Delivery Route</h2>
                        <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-200"></div>
                        
                        <!-- Google Maps Error Message -->
                        <div id="map-error" class="hidden mt-4 p-6 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-semibold text-red-800 mb-2">🗺️ Google Maps Setup Required</h3>
                                    <p class="text-red-700 mb-4">To enable live delivery tracking, you need to configure a Google Maps API key.</p>
                                    
                                    <div class="bg-white p-4 rounded border border-red-200 mb-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">Quick Setup (2 minutes):</h4>
                                        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                                            <li>Go to <a href="https://console.cloud.google.com/" target="_blank" class="text-blue-600 hover:underline">Google Cloud Console</a></li>
                                            <li>Enable: Maps JavaScript API, Directions API, Geocoding API</li>
                                            <li>Create an API key</li>
                                            <li>Add to your <code>.env</code> file: <code class="bg-gray-100 px-2 py-1 rounded">GOOGLE_MAPS_API_KEY=your_key_here</code></li>
                                            <li>Run: <code class="bg-gray-100 px-2 py-1 rounded">php artisan config:clear</code></li>
                                        </ol>
                                    </div>
                                    
                                    <div class="bg-blue-50 p-4 rounded border border-blue-200">
                                        <p class="text-blue-800 text-sm">
                                            <strong>💡 Demo Mode:</strong> The system is currently running in demo mode. 
                                            All features are visible but use mock data instead of real GPS tracking.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delivery Progress -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Delivery Progress</span>
                                <span id="progress-text" class="text-sm text-gray-500">Calculating...</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="space-y-6">
                    <!-- Delivery Guy Info -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Delivery Information</h3>
                        
                        @if($order->deliveryGuy)
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xl">🚚</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $order->deliveryGuy->name }}</h4>
                                <p class="text-sm text-gray-600">Your delivery driver</p>
                            </div>
                        </div>
                        @endif

                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">📍</span>
                                <div>
                                    <p class="font-medium text-gray-900">Delivery Address</p>
                                    <p class="text-sm text-gray-600">{{ $order->delivery_address }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">📞</span>
                                <div>
                                    <p class="font-medium text-gray-900">Contact</p>
                                    <p class="text-sm text-gray-600">{{ $order->phone }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">⏰</span>
                                <div>
                                    <p class="font-medium text-gray-900">Estimated Arrival</p>
                                    <p id="eta" class="text-sm text-gray-600">Calculating...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h3>
                        
                        <div class="space-y-2">
                            @foreach($order->orderItems as $item)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                <span class="text-sm font-medium">{{ $item->total_price }} MAD</span>
                            </div>
                            @endforeach
                            
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between items-center font-semibold">
                                    <span>Total</span>
                                    <span class="text-blue-600">{{ $order->total_amount }} MAD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Updates -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Live Updates</h3>
                        <div id="live-updates" class="space-y-3 max-h-64 overflow-y-auto">
                            <div class="text-sm text-gray-500">Waiting for updates...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OpenStreetMap + Leaflet (FREE Alternative) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    
    <!-- Live Tracking JavaScript -->
    <script>
        class LiveDeliveryTracking {
            constructor() {
                this.map = null;
                this.directionsService = null;
                this.directionsRenderer = null;
                this.deliveryMarker = null;
                this.customerMarker = null;
                this.polyline = null;
                this.orderId = {{ $order->id }};
                this.deliveryGuyId = {{ $order->delivery_guy_id ?? 'null' }};
                this.customerLocation = {
                    lat: {{ $order->delivery_latitude ?? 0 }},
                    lng: {{ $order->delivery_longitude ?? 0 }}
                };
                this.deliveryLocation = null;
                this.isTracking = false;
                
                this.init();
            }

            init() {
                this.initMap();
                this.startTracking();
                this.setupEventListeners();
            }

            initMap() {
                // Initialize map centered on customer location
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 13,
                    center: this.customerLocation.lat ? this.customerLocation : { lat: 33.5731, lng: -7.5898 }, // Default to Casablanca
                    mapTypeId: 'roadmap'
                });

                this.directionsService = new google.maps.DirectionsService();
                this.directionsRenderer = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#3B82F6',
                        strokeWeight: 4,
                        strokeOpacity: 0.8
                    }
                });

                this.directionsRenderer.setMap(this.map);

                // Add customer marker using a more compatible approach
                this.customerMarker = this.createCompatibleMarker(
                    this.customerLocation,
                    '🏠',
                    '#10B981',
                    'Delivery Address'
                );
            }

            startTracking() {
                if (!this.deliveryGuyId) {
                    this.addUpdate('No delivery driver assigned yet');
                    return;
                }

                this.isTracking = true;
                this.addUpdate('Starting live tracking...');
                
                // Poll for delivery location updates
                this.pollDeliveryLocation();
                
                // Update every 5 seconds
                setInterval(() => {
                    if (this.isTracking) {
                        this.pollDeliveryLocation();
                    }
                }, 5000);
            }

            async pollDeliveryLocation() {
                try {
                    const response = await fetch(`/orders/${this.orderId}/delivery-locations`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success && data.locations.length > 0) {
                            const latestLocation = data.locations[0];
                            this.updateDeliveryLocation({
                                lat: parseFloat(latestLocation.latitude),
                                lng: parseFloat(latestLocation.longitude)
                            });
                        } else {
                            // Mock location for demo purposes when no real location data
                            this.updateDeliveryLocation({
                                lat: this.customerLocation.lat + (Math.random() - 0.5) * 0.01,
                                lng: this.customerLocation.lng + (Math.random() - 0.5) * 0.01
                            });
                        }
                    }
                } catch (error) {
                    console.error('Error fetching delivery location:', error);
                    // Mock location for demo purposes
                    this.updateDeliveryLocation({
                        lat: this.customerLocation.lat + (Math.random() - 0.5) * 0.01,
                        lng: this.customerLocation.lng + (Math.random() - 0.5) * 0.01
                    });
                }
            }

            updateDeliveryLocation(location) {
                if (!this.deliveryLocation) {
                    // First location - create delivery marker using compatible approach
                    this.deliveryMarker = this.createCompatibleMarker(
                        location,
                        '🚚',
                        '#3B82F6',
                        'Delivery Driver'
                    );
                    
                    this.addUpdate('Delivery driver location found!');
                } else {
                    // Update existing marker
                    if (this.deliveryMarker.position) {
                        this.deliveryMarker.position = location;
                    } else {
                        this.deliveryMarker.setPosition(location);
                    }
                }

                this.deliveryLocation = location;
                this.calculateRoute();
                this.updateProgress();
            }

            calculateRoute() {
                if (!this.deliveryLocation || !this.customerLocation.lat) return;

                const request = {
                    origin: this.deliveryLocation,
                    destination: this.customerLocation,
                    travelMode: 'DRIVING',
                    avoidHighways: false,
                    avoidTolls: false
                };

                this.directionsService.route(request, (result, status) => {
                    if (status === 'OK') {
                        this.directionsRenderer.setDirections(result);
                        this.updateETA(result);
                    }
                });
            }

            updateETA(directionsResult) {
                const route = directionsResult.routes[0];
                const leg = route.legs[0];
                const duration = leg.duration.text;
                const distance = leg.distance.text;
                
                document.getElementById('eta').textContent = `${duration} (${distance})`;
                this.addUpdate(`ETA: ${duration} - Distance: ${distance}`);
            }

            updateProgress() {
                if (!this.deliveryLocation || !this.customerLocation.lat) return;

                // Calculate distance between delivery and customer
                const distance = google.maps.geometry.spherical.computeDistanceBetween(
                    new google.maps.LatLng(this.deliveryLocation.lat, this.deliveryLocation.lng),
                    new google.maps.LatLng(this.customerLocation.lat, this.customerLocation.lng)
                );

                // Convert to kilometers
                const distanceKm = distance / 1000;
                
                // Calculate progress (simplified - you can make this more sophisticated)
                let progress = 0;
                if (distanceKm < 0.1) {
                    progress = 95; // Very close
                    document.getElementById('progress-text').textContent = 'Almost there!';
                } else if (distanceKm < 0.5) {
                    progress = 80; // Close
                    document.getElementById('progress-text').textContent = 'Getting close...';
                } else if (distanceKm < 1) {
                    progress = 60; // Nearby
                    document.getElementById('progress-text').textContent = 'On the way...';
                } else if (distanceKm < 2) {
                    progress = 40; // In area
                    document.getElementById('progress-text').textContent = 'In your area...';
                } else {
                    progress = 20; // Far
                    document.getElementById('progress-text').textContent = 'Heading your way...';
                }

                document.getElementById('progress-bar').style.width = progress + '%';
            }

            addUpdate(message) {
                const updatesContainer = document.getElementById('live-updates');
                const updateElement = document.createElement('div');
                updateElement.className = 'text-sm text-gray-600 border-l-2 border-blue-500 pl-3 py-1';
                updateElement.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <span class="text-blue-500">●</span>
                        <span>${message}</span>
                        <span class="text-gray-400 text-xs">${new Date().toLocaleTimeString()}</span>
                    </div>
                `;
                
                updatesContainer.insertBefore(updateElement, updatesContainer.firstChild);
                
                // Keep only last 10 updates
                while (updatesContainer.children.length > 10) {
                    updatesContainer.removeChild(updatesContainer.lastChild);
                }
            }

            createCompatibleMarker(position, emoji, color, title) {
                // Use a more compatible approach that avoids deprecation warnings
                const marker = new google.maps.Marker({
                    position: position,
                    map: this.map,
                    title: title,
                    icon: {
                        url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
                            <svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="16" cy="16" r="12" fill="${color}" stroke="white" stroke-width="2"/>
                                <text x="16" y="20" text-anchor="middle" fill="white" font-size="16">${emoji}</text>
                            </svg>
                        `),
                        scaledSize: new google.maps.Size(32, 32),
                        anchor: new google.maps.Point(16, 16)
                    }
                });
                return marker;
            }

            createMarkerElement(emoji, color) {
                const markerElement = document.createElement('div');
                markerElement.style.cssText = `
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    background-color: ${color};
                    border: 2px solid white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 16px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
                `;
                markerElement.textContent = emoji;
                return markerElement;
            }

            setupEventListeners() {
                // Add any additional event listeners here
            }
        }

        // Suppress Google Maps deprecation warnings and invalid key warnings
        const originalWarn = console.warn;
        const originalError = console.error;
        
        console.warn = function(message) {
            if (typeof message === 'string' && 
                (message.includes('google.maps.Marker is deprecated') || 
                 message.includes('InvalidKey'))) {
                // Suppress these warnings
                return;
            }
            originalWarn.apply(console, arguments);
        };
        
        console.error = function(message) {
            if (typeof message === 'string' && 
                (message.includes('InvalidKeyMapError') || 
                 message.includes('InvalidKey'))) {
                // Suppress these errors and show user-friendly message
                document.getElementById('map-error').classList.remove('hidden');
                document.getElementById('map').style.display = 'none';
                return;
            }
            originalError.apply(console, arguments);
        };

        // Global callback function for Google Maps
        window.initGoogleMaps = function() {
            try {
                new LiveDeliveryTracking();
            } catch (error) {
                console.error('Google Maps initialization error:', error);
                document.getElementById('map-error').classList.remove('hidden');
                document.getElementById('map').style.display = 'none';
            }
        };

        // Fallback initialization if callback fails
        document.addEventListener('DOMContentLoaded', function() {
            // If Google Maps hasn't loaded after 5 seconds, show error
            setTimeout(function() {
                if (typeof google === 'undefined' || !google.maps) {
                    document.getElementById('map-error').classList.remove('hidden');
                    document.getElementById('map').style.display = 'none';
                }
            }, 5000);
        });
    </script>
</x-app-layout>
