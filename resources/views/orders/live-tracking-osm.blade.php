<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                Live Delivery Tracking - Order #{{ $order->id }}
            </h2>
            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Order
            </a>
        </div>
    </x-slot>

    <!-- CSRF Token for API calls -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Map Container - Takes 2 columns on large screens -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Live Tracking Map
                                </h3>
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-gray-600">Live</span>
                                </div>
                            </div>
                            <div id="map" class="w-full rounded-xl overflow-hidden border-2 border-gray-100 shadow-inner" style="height: 600px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Side Panel - Order Info & Updates -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Order Status Card -->
                    <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Order Status</h3>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="space-y-3">
                            @if($order->status === 'out_for_delivery')
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                    </svg>
                                    <span class="text-xl font-bold">Out for Delivery</span>
                                </div>
                            @elseif($order->status === 'delivered')
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xl font-bold">Delivered</span>
                                </div>
                            @else
                                <span class="text-xl font-bold">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                            @endif
                            <div class="pt-3 border-t border-purple-400">
                                <p class="text-sm opacity-90">Order Total</p>
                                <p class="text-2xl font-bold">${{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Info Card -->
                    @if($order->deliveryGuy)
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Your Delivery Driver
                        </h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($order->deliveryGuy->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $order->deliveryGuy->name }}</p>
                                <p class="text-sm text-gray-500">{{ $order->deliveryGuy->phone }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Live Updates Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            Live Updates
                        </h3>
                        <div id="tracking-updates" class="space-y-2 max-h-96 overflow-y-auto">
                            <div class="flex items-start space-x-2 p-3 bg-blue-50 rounded-lg">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0 animate-pulse"></div>
                                <div class="flex-1">
                                    <span class="text-sm text-blue-800">Initializing live tracking...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OpenStreetMap + Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    
    <!-- Live Tracking JavaScript with Modern Design -->
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
                // Initialize OpenStreetMap with modern styling
                const center = this.customerLocation.lat ? 
                    [this.customerLocation.lat, this.customerLocation.lng] : 
                    [33.5731, -7.5898]; // Default to Casablanca
                
                this.map = L.map('map', {
                    zoomControl: true,
                    scrollWheelZoom: true,
                    dragging: true,
                    touchZoom: true,
                    doubleClickZoom: true
                }).setView(center, 13);

                // Modern tile layer with better styling
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19,
                    className: 'map-tiles'
                }).addTo(this.map);

                // Add customer marker with modern design
                if (this.customerLocation.lat && this.customerLocation.lng) {
                    const customerIcon = L.divIcon({
                        className: 'custom-marker-wrapper',
                        html: `
                            <div class="marker-container pulse-marker">
                                <div class="marker-pin customer-pin">
                                    <svg class="marker-icon" fill="white" viewBox="0 0 24 24" stroke="none">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="marker-pulse"></div>
                            </div>
                        `,
                        iconSize: [40, 40],
                        iconAnchor: [20, 40]
                    });
                    
                    this.customerMarker = L.marker(
                        [this.customerLocation.lat, this.customerLocation.lng], 
                        { icon: customerIcon }
                    ).addTo(this.map);
                    
                    this.customerMarker.bindPopup(`
                        <div class="modern-popup">
                            <div class="popup-icon customer-icon">
                                <svg fill="white" viewBox="0 0 24 24" stroke="none">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                </svg>
                            </div>
                            <div class="popup-content">
                                <h4>Delivery Address</h4>
                                <p>Your order will arrive here</p>
                            </div>
                        </div>
                    `).openPopup();
                }
            }

            startTracking() {
                if (!this.deliveryGuyId) {
                    this.addUpdate('Waiting for delivery driver assignment...', 'info');
                    return;
                }

                this.isTracking = true;
                this.addUpdate('Live tracking started', 'success');
                
                // Poll for delivery location updates
                this.pollDeliveryLocation();
            }

            async pollDeliveryLocation() {
                if (!this.isTracking) return;

                try {
                    const response = await fetch(`/orders/{{ $order->id }}/delivery-locations`);
                    const data = await response.json();

                    if (data.success && data.locations && data.locations.length > 0) {
                        const latestLocation = data.locations[0];
                        console.log('Received location data:', latestLocation);
                        this.updateDeliveryLocation(latestLocation);
                    } else {
                        console.log('No locations available:', data);
                        this.addUpdate('Waiting for driver location...', 'info');
                    }
                } catch (error) {
                    console.error('Error fetching delivery location:', error);
                    this.addUpdate('Error fetching location data', 'error');
                }

                // Poll every 10 seconds
                setTimeout(() => this.pollDeliveryLocation(), 10000);
            }

            updateDeliveryLocation(location) {
                const lat = parseFloat(location.latitude || location.lat);
                const lng = parseFloat(location.longitude || location.lng);
                
                if (isNaN(lat) || isNaN(lng)) {
                    console.error('Invalid coordinates:', location);
                    this.addUpdate('Invalid driver location data', 'error');
                    return;
                }
                
                const newLocation = [lat, lng];
                
                // Remove old delivery marker
                if (this.deliveryMarker) {
                    this.map.removeLayer(this.deliveryMarker);
                }

                // Add new delivery marker with animated design
                const deliveryIcon = L.divIcon({
                    className: 'custom-marker-wrapper',
                    html: `
                        <div class="marker-container pulse-marker">
                            <div class="marker-pin delivery-pin">
                                <svg class="marker-icon" fill="white" stroke="white" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                </svg>
                            </div>
                            <div class="marker-pulse delivery-pulse"></div>
                        </div>
                    `,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40]
                });

                this.deliveryMarker = L.marker(newLocation, { 
                    icon: deliveryIcon 
                }).addTo(this.map);

                this.deliveryMarker.bindPopup(`
                    <div class="modern-popup">
                        <div class="popup-icon delivery-icon">
                            <svg fill="white" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                            </svg>
                        </div>
                        <div class="popup-content">
                            <h4>Delivery Driver</h4>
                            <p>On the way to you</p>
                        </div>
                    </div>
                `);

                // Update route
                if (this.customerLocation.lat && this.customerLocation.lng) {
                    this.updateRoute(newLocation, [this.customerLocation.lat, this.customerLocation.lng]);
                }

                this.addUpdate(`Driver location updated`, 'success');
            }

            updateRoute(start, end) {
                // Remove existing route
                if (this.routingControl) {
                    this.map.removeControl(this.routingControl);
                }

                // Add new route with custom styling
                this.routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(start[0], start[1]),
                        L.latLng(end[0], end[1])
                    ],
                    routeWhileDragging: false,
                    addWaypoints: false,
                    createMarker: function() { return null; },
                    lineOptions: {
                        styles: [{ 
                            color: '#8B5CF6', 
                            weight: 5, 
                            opacity: 0.8,
                            className: 'route-line'
                        }]
                    },
                    show: false
                }).addTo(this.map);

                // Fit map to show both locations
                const group = new L.featureGroup([this.customerMarker, this.deliveryMarker]);
                this.map.fitBounds(group.getBounds().pad(0.15));
            }

            addUpdate(message, type = 'info') {
                const updatesContainer = document.getElementById('tracking-updates');
                const updateElement = document.createElement('div');
                
                const colors = {
                    info: 'bg-blue-50 text-blue-800',
                    success: 'bg-green-50 text-green-800',
                    error: 'bg-red-50 text-red-800'
                };
                
                const dotColors = {
                    info: 'bg-blue-500',
                    success: 'bg-green-500',
                    error: 'bg-red-500'
                };
                
                updateElement.className = `flex items-start space-x-2 p-3 rounded-lg ${colors[type]} animate-slideIn`;
                updateElement.innerHTML = `
                    <div class="w-2 h-2 ${dotColors[type]} rounded-full mt-1.5 flex-shrink-0"></div>
                    <div class="flex-1">
                        <span class="text-xs font-medium">${new Date().toLocaleTimeString()}</span>
                        <p class="text-sm">${message}</p>
                    </div>
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
        /* Modern Map Styling */
        .custom-marker-wrapper {
            background: transparent !important;
            border: none !important;
        }
        
        .marker-container {
            position: relative;
            width: 40px;
            height: 40px;
        }
        
        .marker-pin {
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1);
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        .customer-pin {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .delivery-pin {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            animation: deliveryBounce 2s ease-in-out infinite;
        }
        
        .marker-icon {
            width: 20px;
            height: 20px;
            transform: rotate(45deg);
        }
        
        .marker-pulse {
            position: absolute;
            top: 0;
            left: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.4);
            animation: pulse 2s ease-out infinite;
            z-index: 1;
        }
        
        .delivery-pulse {
            background: rgba(139, 92, 246, 0.4);
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        @keyframes deliveryBounce {
            0%, 100% {
                transform: rotate(-45deg) translateY(0);
            }
            50% {
                transform: rotate(-45deg) translateY(-5px);
            }
        }
        
        /* Modern Popup Styling */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border: none;
            padding: 0;
        }
        
        .leaflet-popup-content {
            margin: 0;
            min-width: 200px;
        }
        
        .modern-popup {
            display: flex;
            align-items: center;
            padding: 12px;
        }
        
        .popup-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .popup-icon svg {
            width: 20px;
            height: 20px;
        }
        
        .customer-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .delivery-icon {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        }
        
        .popup-content h4 {
            font-weight: 600;
            font-size: 14px;
            color: #111827;
            margin: 0 0 4px 0;
        }
        
        .popup-content p {
            font-size: 12px;
            color: #6b7280;
            margin: 0;
        }
        
        /* Route Line Animation */
        .route-line {
            animation: dashAnimation 20s linear infinite;
        }
        
        @keyframes dashAnimation {
            to {
                stroke-dashoffset: -100;
            }
        }
        
        /* Update Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Routing Control Styling */
        .leaflet-routing-container {
            display: none;
        }
        
        /* Zoom Control Styling */
        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            border-radius: 8px !important;
            overflow: hidden;
        }
        
        .leaflet-control-zoom a {
            border: none !important;
            width: 36px !important;
            height: 36px !important;
            line-height: 36px !important;
            font-size: 20px !important;
        }
        
        .leaflet-control-zoom a:first-child {
            border-bottom: 1px solid #e5e7eb !important;
        }
    </style>
</x-app-layout>
