// Map tracking functionality for delivery
import L from 'leaflet';

export class DeliveryTracker {
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.map = null;
        this.deliveryMarker = null;
        this.customerMarker = null;
        this.route = null;
        this.options = {
            center: [0, 0],
            zoom: 13,
            ...options
        };
    }

    init() {
        // Initialize the map
        this.map = L.map(this.containerId).setView(this.options.center, this.options.zoom);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(this.map);

        return this;
    }

    setCustomerLocation(lat, lng, address = '') {
        if (this.customerMarker) {
            this.map.removeLayer(this.customerMarker);
        }

        this.customerMarker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'customer-marker',
                html: '<div class="customer-pin">🏠</div>',
                iconSize: [30, 30]
            })
        }).addTo(this.map);

        if (address) {
            this.customerMarker.bindPopup(`<b>Delivery Address:</b><br>${address}`).openPopup();
        }

        return this;
    }

    setDeliveryLocation(lat, lng, name = 'Delivery Guy') {
        if (this.deliveryMarker) {
            this.map.removeLayer(this.deliveryMarker);
        }

        this.deliveryMarker = L.marker([lat, lng], {
            icon: L.divIcon({
                className: 'delivery-marker',
                html: '<div class="delivery-pin">🚗</div>',
                iconSize: [30, 30]
            })
        }).addTo(this.map);

        this.deliveryMarker.bindPopup(`<b>${name}</b><br>Current Location`);

        return this;
    }

    updateDeliveryLocation(lat, lng) {
        if (this.deliveryMarker) {
            this.deliveryMarker.setLatLng([lat, lng]);
        } else {
            this.setDeliveryLocation(lat, lng);
        }

        // Center map on delivery location
        this.map.setView([lat, lng], this.map.getZoom());
    }

    drawRoute(deliveryLat, deliveryLng, customerLat, customerLng) {
        if (this.route) {
            this.map.removeLayer(this.route);
        }

        // Simple straight line route (in production, use a routing service)
        this.route = L.polyline([
            [deliveryLat, deliveryLng],
            [customerLat, customerLng]
        ], {
            color: 'blue',
            weight: 3,
            opacity: 0.7
        }).addTo(this.map);

        // Fit map to show both markers
        const group = new L.featureGroup([this.deliveryMarker, this.customerMarker]);
        this.map.fitBounds(group.getBounds().pad(0.1));
    }

    getDistance(lat1, lng1, lat2, lng2) {
        const R = 6371; // Earth's radius in kilometers
        const dLat = this.deg2rad(lat2 - lat1);
        const dLng = this.deg2rad(lng2 - lng1);
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) * 
            Math.sin(dLng/2) * Math.sin(dLng/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        const distance = R * c;
        return distance;
    }

    deg2rad(deg) {
        return deg * (Math.PI/180);
    }

    destroy() {
        if (this.map) {
            this.map.remove();
        }
    }
}

// CSS for custom markers
const markerStyles = `
    .customer-marker, .delivery-marker {
        background: transparent;
        border: none;
    }
    
    .customer-pin, .delivery-pin {
        font-size: 20px;
        text-align: center;
        line-height: 30px;
    }
`;

// Add styles to document
if (typeof document !== 'undefined') {
    const style = document.createElement('style');
    style.textContent = markerStyles;
    document.head.appendChild(style);
}

export default DeliveryTracker;

