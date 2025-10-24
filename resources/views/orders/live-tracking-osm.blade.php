<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🍣 Live Delivery Tracking - Order #{{ $order->id }}
        </h2>
    </x-slot>

    <!-- CSRF Token for API calls -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Order Status Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Order Status</h3>
                            <p class="text-sm text-gray-600">
                                @if($order->status === 'out_for_delivery')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        🚚 Out for Delivery
                                    </span>
                                @elseif($order->status === 'delivered')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        ✅ Delivered
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        📋 {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Order Total</p>
                            <p class="text-lg font-semibold">${{ number_format($order->total, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">📍 Live Delivery Tracking</h3>
                    <div id="map" style="height: 500px; width: 100%; border-radius: 8px;"></div>
                </div>
            </div>

            <!-- Tracking Updates -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">📱 Live Updates</h3>
                    <div id="tracking-updates" class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                            <span class="text-sm text-blue-800">Initializing live tracking...</span>
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
    
    <!-- Live Tracking JavaScript with OpenStreetMap -->
    <script>
        class LiveDeliveryTracking {
            constructor() {
                this.map = null;
                this.routingControl = null;
                this.customerMarker = null;
                this.deliveryMarker = null;
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
                // Initialize OpenStreetMap centered on customer location
                const center = this.customerLocation.lat ? [this.customerLocation.lat, this.customerLocation.lng] : [33.5731, -7.5898]; // Default to Casablanca
                
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

            startTracking() {
                if (!this.deliveryGuyId) {
                    this.addUpdate('No delivery driver assigned yet');
                    return;
                }

                this.isTracking = true;
                this.addUpdate('Starting live tracking...');
                
                // Poll for delivery location updates
                this.pollDeliveryLocation();
            }

            async pollDeliveryLocation() {
                if (!this.isTracking) return;

                try {
                    const response = await fetch(`/orders/{{ $order->id }}/delivery-locations`);
                    const data = await response.json();

                    if (data.success && data.locations && data.locations.length > 0) {
                        // Get the most recent location
                        const latestLocation = data.locations[0];
                        console.log('Received location data:', latestLocation);
                        this.updateDeliveryLocation(latestLocation);
                    } else {
                        console.log('No locations available:', data);
                        this.addUpdate('Waiting for delivery driver location...');
                    }
                } catch (error) {
                    console.error('Error fetching delivery location:', error);
                    this.addUpdate('Error fetching location data');
                }

                // Poll every 10 seconds
                setTimeout(() => this.pollDeliveryLocation(), 10000);
            }

            updateDeliveryLocation(location) {
                // Handle different property names from database
                const lat = parseFloat(location.latitude || location.lat);
                const lng = parseFloat(location.longitude || location.lng);
                
                // Validate coordinates
                if (isNaN(lat) || isNaN(lng)) {
                    console.error('Invalid coordinates:', location);
                    this.addUpdate('Invalid driver location data received');
                    return;
                }
                
                const newLocation = [lat, lng];
                
                // Remove old delivery marker
                if (this.deliveryMarker) {
                    this.map.removeLayer(this.deliveryMarker);
                }

                // Add new delivery marker
                this.deliveryMarker = L.marker(newLocation, {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="background-color: #3B82F6; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">🚚</div>',
                        iconSize: [30, 30],
                        iconAnchor: [15, 15]
                    })
                }).addTo(this.map);

                this.deliveryMarker.bindPopup('Delivery Driver').openPopup();

                // Update route if both locations exist
                if (this.customerLocation.lat && this.customerLocation.lng) {
                    this.updateRoute(newLocation, [this.customerLocation.lat, this.customerLocation.lng]);
                }

                // Update tracking info
                this.addUpdate(`Driver location updated: ${lat.toFixed(4)}, ${lng.toFixed(4)}`);
            }

            updateRoute(start, end) {
                // Remove existing route
                if (this.routingControl) {
                    this.map.removeControl(this.routingControl);
                }

                // Add new route
                this.routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(start[0], start[1]),
                        L.latLng(end[0], end[1])
                    ],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    createMarker: function() { return null; }, // Don't create default markers
                    lineOptions: {
                        styles: [{ color: '#3B82F6', weight: 4, opacity: 0.8 }]
                    }
                }).addTo(this.map);

                // Fit map to show both locations
                const group = new L.featureGroup([this.customerMarker, this.deliveryMarker]);
                this.map.fitBounds(group.getBounds().pad(0.1));
            }

            addUpdate(message) {
                const updatesContainer = document.getElementById('tracking-updates');
                const updateElement = document.createElement('div');
                updateElement.className = 'flex items-center space-x-3 p-3 bg-green-50 rounded-lg';
                updateElement.innerHTML = `
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-green-800">${new Date().toLocaleTimeString()} - ${message}</span>
                `;
                
                updatesContainer.insertBefore(updateElement, updatesContainer.firstChild);
                
                // Keep only last 10 updates
                while (updatesContainer.children.length > 10) {
                    updatesContainer.removeChild(updatesContainer.lastChild);
                }
            }

            setupEventListeners() {
                // Add any additional event listeners here
            }
        }

        // Initialize tracking when page loads
        document.addEventListener('DOMContentLoaded', function() {
            new LiveDeliveryTracking();
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
