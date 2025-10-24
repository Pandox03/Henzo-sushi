<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🚚 Delivery Navigation - Order #{{ $order->id }}
        </h2>
    </x-slot>

    <!-- CSRF Token for API calls -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Delivery Details</h3>
                            <p class="text-sm text-gray-600">
                                <strong>Customer:</strong> {{ $order->user->name }}<br>
                                <strong>Address:</strong> {{ $order->delivery_address }}<br>
                                <strong>Phone:</strong> {{ $order->phone }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Order Total</p>
                            <p class="text-lg font-semibold">${{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">🗺️ Route Navigation</h3>
                    <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">🎯 Navigation Controls</h3>
                    <div class="flex space-x-4">
                        <button id="start-delivery" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            🚀 Start Delivery (Use My Location)
                        </button>
                        <button id="start-navigation" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            🗺️ Manual Navigation
                        </button>
                        <button id="center-map" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            📍 Center on Me
                        </button>
                        <button id="toggle-tracking" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            📡 Toggle Tracking
                        </button>
                    </div>
                    <div id="navigation-status" class="mt-4 p-3 bg-gray-100 rounded-lg">
                        <p class="text-sm text-gray-600">Ready to start navigation...</p>
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
    
    <!-- Delivery Navigation JavaScript with OpenStreetMap -->
    <script>
        class DeliveryNavigation {
            constructor() {
                this.map = null;
                this.routingControl = null;
                this.customerMarker = null;
                this.driverMarker = null;
                this.currentLocation = null;
                this.customerLocation = {
                    lat: {{ $order->delivery_latitude ?? 0 }},
                    lng: {{ $order->delivery_longitude ?? 0 }}
                };
                this.isTracking = false;
                this.watchId = null;
                
                this.init();
            }

            init() {
                this.initMap();
                this.setupEventListeners();
                this.updateStatus('Map initialized. Getting your location...');
                
                // Automatically get driver's location when page loads
                this.getCurrentLocation().then(location => {
                    if (location) {
                        this.currentLocation = location;
                        this.updateStatus('✅ Your location detected! Click "Start Delivery" to begin with your real GPS location.');
                    } else {
                        this.updateStatus('⚠️ Could not get your location. You can use manual navigation or try again.');
                    }
                });
            }

            initMap() {
                // Initialize OpenStreetMap
                const center = this.customerLocation.lat ? [this.customerLocation.lat, this.customerLocation.lng] : [33.5731, -7.5898];
                
                this.map = L.map('map').setView(center, 13);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(this.map);

                // Add customer marker
                if (this.customerLocation.lat && this.customerLocation.lng) {
                    this.customerMarker = L.marker([this.customerLocation.lat, this.customerLocation.lng], {
                        icon: L.divIcon({
                            className: 'custom-marker',
                            html: '<div style="background-color: #10B981; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">🏠</div>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 15]
                        })
                    }).addTo(this.map);
                    
                    this.customerMarker.bindPopup('Delivery Address').openPopup();
                }
            }

            setupEventListeners() {
                document.getElementById('start-delivery').addEventListener('click', () => {
                    this.startDelivery();
                });

                document.getElementById('start-navigation').addEventListener('click', () => {
                    this.startNavigation();
                });

                document.getElementById('center-map').addEventListener('click', () => {
                    this.centerOnCurrentLocation();
                });

                document.getElementById('toggle-tracking').addEventListener('click', () => {
                    this.toggleTracking();
                });
            }

            startDelivery() {
                this.updateStatus('🚀 Starting delivery... Getting your location...');
                
                this.getCurrentLocation().then(location => {
                    if (location) {
                        this.currentLocation = location;
                        this.updateDriverLocation();
                        this.updateRoute();
                        this.startTracking();
                        this.updateStatus('✅ Delivery started! Using your real GPS location. Live tracking active.');
                        
                        // Disable start delivery button and enable others
                        document.getElementById('start-delivery').disabled = true;
                        document.getElementById('start-delivery').textContent = '✅ Delivery Active';
                        document.getElementById('start-delivery').className = 'bg-gray-500 text-white font-bold py-2 px-4 rounded cursor-not-allowed';
                    } else {
                        this.updateStatus('❌ Could not get your location. Please try manual navigation.');
                    }
                });
            }

            startNavigation() {
                if (!this.customerLocation.lat || !this.customerLocation.lng) {
                    this.updateStatus('❌ Customer location not available');
                    return;
                }

                this.getCurrentLocation().then(location => {
                    if (location) {
                        this.currentLocation = location;
                        this.updateRoute();
                        this.updateStatus('✅ Navigation started! Follow the blue route.');
                    } else {
                        this.updateStatus('❌ Could not get your current location');
                    }
                });
            }

            updateRoute() {
                if (!this.currentLocation || !this.customerLocation.lat) return;

                // Remove existing route
                if (this.routingControl) {
                    this.map.removeControl(this.routingControl);
                }

                // Add new route
                this.routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(this.currentLocation.lat, this.currentLocation.lng),
                        L.latLng(this.customerLocation.lat, this.customerLocation.lng)
                    ],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    createMarker: function() { return null; },
                    lineOptions: {
                        styles: [{ color: '#3B82F6', weight: 4, opacity: 0.8 }]
                    }
                }).addTo(this.map);

                // Add driver marker
                if (this.driverMarker) {
                    this.map.removeLayer(this.driverMarker);
                }

                this.driverMarker = L.marker([this.currentLocation.lat, this.currentLocation.lng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="background-color: #3B82F6; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">🚚</div>',
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    })
                }).addTo(this.map);

                this.driverMarker.bindPopup('Your Location').openPopup();

                // Fit map to show both locations
                const group = new L.featureGroup([this.customerMarker, this.driverMarker]);
                this.map.fitBounds(group.getBounds().pad(0.1));
            }

            getCurrentLocation() {
                return new Promise((resolve) => {
                    if (!navigator.geolocation) {
                        this.updateStatus('❌ Geolocation not supported by this browser');
                        resolve(null);
                        return;
                    }

                    // Check if we're on HTTP (not HTTPS) for demo purposes
                    if (location.protocol === 'http:' && !location.hostname.includes('localhost')) {
                        this.updateStatus('⚠️ Using demo location (HTTP detected)');
                        resolve({
                            lat: 33.5731 + (Math.random() - 0.5) * 0.01,
                            lng: -7.5898 + (Math.random() - 0.5) * 0.01
                        });
                        return;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const location = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            this.updateStatus('✅ Location obtained successfully');
                            resolve(location);
                        },
                        (error) => {
                            console.error('Geolocation error:', error);
                            this.updateStatus('❌ Could not get location: ' + error.message);
                            
                            // Use demo location as fallback
                            this.updateStatus('⚠️ Using demo location');
                            resolve({
                                lat: 33.5731 + (Math.random() - 0.5) * 0.01,
                                lng: -7.5898 + (Math.random() - 0.5) * 0.01
                            });
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 60000
                        }
                    );
                });
            }

            centerOnCurrentLocation() {
                if (this.currentLocation) {
                    this.map.setView([this.currentLocation.lat, this.currentLocation.lng], 15);
                    this.updateStatus('📍 Map centered on your location');
                } else {
                    this.getCurrentLocation().then(location => {
                        if (location) {
                            this.currentLocation = location;
                            this.map.setView([location.lat, location.lng], 15);
                            this.updateStatus('📍 Map centered on your location');
                        }
                    });
                }
            }

            toggleTracking() {
                if (this.isTracking) {
                    this.stopTracking();
                } else {
                    this.startTracking();
                }
            }

            startTracking() {
                this.isTracking = true;
                this.updateStatus('📡 Live tracking started');
                
                // Update location every 10 seconds
                this.watchId = setInterval(() => {
                    this.getCurrentLocation().then(location => {
                        if (location) {
                            this.currentLocation = location;
                            this.updateDriverLocation();
                        }
                    });
                }, 10000);

                document.getElementById('toggle-tracking').textContent = '📡 Stop Tracking';
            }

            stopTracking() {
                this.isTracking = false;
                if (this.watchId) {
                    clearInterval(this.watchId);
                    this.watchId = null;
                }
                this.updateStatus('📡 Live tracking stopped');
                document.getElementById('toggle-tracking').textContent = '📡 Start Tracking';
            }

            updateDriverLocation() {
                if (!this.currentLocation || !this.driverMarker) return;

                // Update driver marker position
                this.driverMarker.setLatLng([this.currentLocation.lat, this.currentLocation.lng]);
                
                // Update route if navigation is active
                if (this.routingControl) {
                    this.updateRoute();
                }

                // Save location to database
                this.saveLocationToDatabase(this.currentLocation.lat, this.currentLocation.lng);

                this.updateStatus(`📍 Location updated: ${this.currentLocation.lat.toFixed(4)}, ${this.currentLocation.lng.toFixed(4)}`);
            }

            saveLocationToDatabase(lat, lng) {
                // Send location to server
                fetch(`/delivery/orders/{{ $order->id }}/location`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: lat,
                        longitude: lng,
                        address: 'Live tracking location'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Location saved to database');
                    } else {
                        console.error('Failed to save location:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saving location:', error);
                });
            }

            updateStatus(message) {
                const statusElement = document.getElementById('navigation-status');
                statusElement.innerHTML = `<p class="text-sm text-gray-600">${new Date().toLocaleTimeString()} - ${message}</p>`;
            }
        }

        // Initialize navigation when page loads
        document.addEventListener('DOMContentLoaded', function() {
            new DeliveryNavigation();
        });
    </script>

    <style>
        .custom-marker {
            background: transparent !important;
            border: none !important;
        }
        
        .leaflet-routing-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</x-app-layout>
