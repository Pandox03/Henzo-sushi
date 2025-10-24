// Real-time functionality for Henzo Sushi
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Configure Pusher
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Order status updates for customers
export function listenToOrderStatus(orderId, callback) {
    window.Echo.private(`order-status.${orderId}`)
        .listen('order.status.changed', (e) => {
            callback(e.order);
        });
}

// New orders for chefs
export function listenToNewOrders(callback) {
    window.Echo.channel('chef-orders')
        .listen('order.new', (e) => {
            callback(e.order);
        });
}

// Order status updates for chefs
export function listenToChefOrderUpdates(callback) {
    window.Echo.channel('chef-orders')
        .listen('order.status.changed', (e) => {
            callback(e.order);
        });
}

// Delivery location updates for customers
export function listenToDeliveryTracking(orderId, callback) {
    window.Echo.private(`order-tracking.${orderId}`)
        .listen('delivery.location.updated', (e) => {
            callback(e.deliveryLocation);
        });
}

// Admin dashboard updates
export function listenToAdminUpdates(callback) {
    window.Echo.channel('admin-orders')
        .listen('order.new', (e) => {
            callback('new_order', e.order);
        });
    
    window.Echo.channel('admin-delivery-tracking')
        .listen('delivery.location.updated', (e) => {
            callback('delivery_update', e.deliveryLocation);
        });
}

// Delivery guy location updates
export function listenToDeliveryUpdates(callback) {
    window.Echo.channel('delivery-orders')
        .listen('order.status.changed', (e) => {
            callback(e.order);
        });
}

export default {
    listenToOrderStatus,
    listenToNewOrders,
    listenToChefOrderUpdates,
    listenToDeliveryTracking,
    listenToAdminUpdates,
    listenToDeliveryUpdates
};

