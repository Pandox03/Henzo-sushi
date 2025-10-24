<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-100">
        <!-- Header -->
        <div class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">🗺️ Delivery Navigation</h1>
                        <p class="text-gray-600">Order #{{ $order->order_number }} - {{ $order->user->name }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Destination</div>
                            <div class="text-lg font-semibold text-green-600">
                                {{ Str::limit($order->delivery_address, 30) }}
                            </div>
                        </div>
                        <a href="{{ route('delivery.orders.show', $order) }}" 
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
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Navigation Route</h2>
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
                                    <p class="text-red-700 mb-4">To enable route navigation, you need to configure a Google Maps API key.</p>
                                    
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
                        
                        <!-- Navigation Controls -->
                        <div class="mt-6 flex space-x-4">
                            <button id="start-navigation" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors">
                                🚀 Start Navigation
                            </button>
                            <button id="update-location" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors">
                                📍 Update My Location
                            </button>
                            <button id="mark-delivered" 
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition-colors">
                                ✅ Mark as Delivered
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Delivery Information -->
                <div class="space-y-6">
                    <!-- Customer Details -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Customer Details</h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">👤</span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $order->user->name }} {{ $order->user->last_name }}</p>
                                    <p class="text-sm text-gray-600">Customer</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">📞</span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $order->phone }}</p>
                                    <p class="text-sm text-gray-600">Contact Number</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <span class="text-gray-500">📍</span>
                                <div>
                                    <p class="font-medium text-gray-900">Delivery Address</p>
                                    <p class="text-sm text-gray-600">{{ $order->delivery_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Info -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Navigation Info</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Distance</span>
                                <span id="distance" class="font-semibold">Calculating...</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Estimated Time</span>
                                <span id="duration" class="font-semibold">Calculating...</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Current Speed</span>
                                <span id="speed" class="font-semibold">-- km/h</span>
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
                                    <span class="text-green-600">{{ $order->total_amount }} MAD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Updates -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Delivery Updates</h3>
                        <div id="delivery-updates" class="space-y-3 max-h-64 overflow-y-auto">
                            <div class="text-sm text-gray-500">Ready to start delivery...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Maps API with proper async loading -->
    <script>
        // Load Google Maps API asynchronously
        function loadGoogleMaps() {
            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config('maps.google_maps_api_key', 'YOUR_GOOGLE_MAPS_API_KEY') }}&libraries=geometry,places,marker&callback=initGoogleMaps';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }
        
        // Load when page is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', loadGoogleMaps);
        } else {
            loadGoogleMaps();
        }
    </script>
    
    <!-- Delivery Navigation JavaScript -->
    <script>
        class DeliveryNavigation {
            constructor() {
                this.map = null;
                this.directionsService = null;
                this.directionsRenderer = null;
                this.deliveryMarker = null;
                this.customerMarker = null;
                this.watchId = null;
                this.currentLocation = null;
                this.destination = {
                    lat: {{ $order->delivery_latitude ?? 0 }},
                    lng: {{ $order->delivery_longitude ?? 0 }}
                };
                this.orderId = {{ $order->id }};
                this.isNavigating = false;
                
                this.init();
            }

            init() {
                this.initMap();
                this.setupEventListeners();
                this.getCurrentLocation();
            }

            initMap() {
                // Initialize map
                this.map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 13,
                    center: this.destination.lat ? this.destination : { lat: 33.5731, lng: -7.5898 },
                    mapTypeId: 'roadmap'
                });

                this.directionsService = new google.maps.DirectionsService();
                this.directionsRenderer = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: '#10B981',
                        strokeWeight: 4,
                        strokeOpacity: 0.8
                    }
                });

                this.directionsRenderer.setMap(this.map);

                // Add customer marker using compatible approach
                if (this.destination.lat) {
                    this.customerMarker = this.createCompatibleMarker(
                        this.destination,
                        '🏠',
                        '#10B981',
                        'Delivery Address'
                    );
                }
            }

            getCurrentLocation() {
                // Check if we're in development mode (HTTP instead of HTTPS)
                if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                    this.addUpdate('⚠️ HTTPS required for location access. Using demo mode...');
                    // Use a mock location for demo purposes
                    this.currentLocation = {
                        lat: this.destination.lat + (Math.random() - 0.5) * 0.01,
                        lng: this.destination.lng + (Math.random() - 0.5) * 0.01
                    };
                    this.updateDeliveryMarker();
                    this.calculateRoute();
                    return;
                }

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.currentLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            this.updateDeliveryMarker();
                            this.calculateRoute();
                        },
                        (error) => {
                            console.error('Error getting location:', error);
                            this.addUpdate('Unable to get your current location. Using demo mode...');
                            // Use a mock location for demo purposes
                            this.currentLocation = {
                                lat: this.destination.lat + (Math.random() - 0.5) * 0.01,
                                lng: this.destination.lng + (Math.random() - 0.5) * 0.01
                            };
                            this.updateDeliveryMarker();
                            this.calculateRoute();
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 60000
                        }
                    );
                } else {
                    this.addUpdate('Geolocation is not supported by this browser. Using demo mode...');
                    // Use a mock location for demo purposes
                    this.currentLocation = {
                        lat: this.destination.lat + (Math.random() - 0.5) * 0.01,
                        lng: this.destination.lng + (Math.random() - 0.5) * 0.01
                    };
                    this.updateDeliveryMarker();
                    this.calculateRoute();
                }
            }

            updateDeliveryMarker() {
                if (!this.currentLocation) return;

                if (this.deliveryMarker) {
                    if (this.deliveryMarker.position) {
                        this.deliveryMarker.position = this.currentLocation;
                    } else {
                        this.deliveryMarker.setPosition(this.currentLocation);
                    }
                } else {
                    this.deliveryMarker = this.createCompatibleMarker(
                        this.currentLocation,
                        '🚚',
                        '#3B82F6',
                        'Your Location'
                    );
                }
            }

            calculateRoute() {
                if (!this.currentLocation || !this.destination.lat) return;

                const request = {
                    origin: this.currentLocation,
                    destination: this.destination,
                    travelMode: 'DRIVING',
                    avoidHighways: false,
                    avoidTolls: false
                };

                this.directionsService.route(request, (result, status) => {
                    if (status === 'OK') {
                        this.directionsRenderer.setDirections(result);
                        this.updateNavigationInfo(result);
                    }
                });
            }

            updateNavigationInfo(directionsResult) {
                const route = directionsResult.routes[0];
                const leg = route.legs[0];
                
                document.getElementById('distance').textContent = leg.distance.text;
                document.getElementById('duration').textContent = leg.duration.text;
            }

            startNavigation() {
                if (!this.currentLocation) {
                    alert('Please allow location access to start navigation.');
                    return;
                }

                this.isNavigating = true;
                this.addUpdate('Navigation started! Following route to customer...');
                
                // Start watching location
                this.watchId = navigator.geolocation.watchPosition(
                    (position) => {
                        this.currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        this.updateDeliveryMarker();
                        this.updateLocationOnServer();
                        this.updateSpeed(position.coords.speed);
                    },
                    (error) => {
                        console.error('Error watching location:', error);
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 1000
                    }
                );
            }

            updateLocationOnServer() {
                fetch(`/delivery/orders/${this.orderId}/location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: this.currentLocation.lat,
                        longitude: this.currentLocation.lng,
                        address: 'Location updated'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Location updated on server');
                    }
                })
                .catch(error => {
                    console.error('Error updating location:', error);
                });
            }

            updateSpeed(speed) {
                if (speed) {
                    document.getElementById('speed').textContent = Math.round(speed * 3.6) + ' km/h';
                }
            }

            markDelivered() {
                if (confirm('Are you sure you want to mark this order as delivered?')) {
                    fetch(`/delivery/orders/${this.orderId}/delivered`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.addUpdate('Order marked as delivered!');
                            alert('Order delivered successfully!');
                            window.location.href = '/delivery/dashboard';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                }
            }

            addUpdate(message) {
                const updatesContainer = document.getElementById('delivery-updates');
                const updateElement = document.createElement('div');
                updateElement.className = 'text-sm text-gray-600 border-l-2 border-green-500 pl-3 py-1';
                updateElement.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <span class="text-green-500">●</span>
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
                document.getElementById('start-navigation').addEventListener('click', () => {
                    this.startNavigation();
                });

                document.getElementById('update-location').addEventListener('click', () => {
                    this.getCurrentLocation();
                    this.addUpdate('Location updated manually');
                });

                document.getElementById('mark-delivered').addEventListener('click', () => {
                    this.markDelivered();
                });
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
                new DeliveryNavigation();
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
