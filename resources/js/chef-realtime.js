// Chef Real-time Dashboard
class ChefRealtime {
    constructor() {
        this.echo = null;
        this.notificationSound = null;
        this.isNotificationPlaying = false;
        this.notificationDuration = 20000; // 20 seconds
        this.init();
    }

    init() {
        // Initialize notification sound
        this.initNotificationSound();
        
        // Initialize Echo (Laravel Broadcasting)
        this.initEcho();
        
        // Set up event listeners
        this.setupEventListeners();
        
        // Start auto-refresh for dashboard data
        this.startAutoRefresh();
        
        // Enable audio on first user interaction
        this.enableAudioOnInteraction();
    }

    initNotificationSound() {
        // Create a more persistent notification sound using Web Audio API
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            
            // Create a more attention-grabbing notification sound
            const createNotificationSound = () => {
                // Create multiple oscillators for a richer sound
                const oscillators = [];
                const gainNodes = [];
                
                // Create 3 oscillators for a chord-like sound
                for (let i = 0; i < 3; i++) {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    // Different frequencies for each oscillator
                    const frequencies = [800, 1000, 1200];
                    oscillator.frequency.setValueAtTime(frequencies[i], audioContext.currentTime);
                    
                    // Create a pulsing effect
                    gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                    gainNode.gain.linearRampToValueAtTime(0.2, audioContext.currentTime + 0.1);
                    gainNode.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.2);
                    gainNode.gain.linearRampToValueAtTime(0.2, audioContext.currentTime + 0.3);
                    gainNode.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.4);
                    gainNode.gain.linearRampToValueAtTime(0.2, audioContext.currentTime + 0.5);
                    gainNode.gain.linearRampToValueAtTime(0, audioContext.currentTime + 0.6);
                    
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.6);
                    
                    oscillators.push(oscillator);
                    gainNodes.push(gainNode);
                }
            };
            
            this.notificationSound = createNotificationSound;
        } catch (error) {
            console.warn('Could not initialize notification sound:', error);
        }
    }

    initEcho() {
        // For now, we'll use polling since Pusher isn't configured
        // In production, you would use:
        // import Echo from 'laravel-echo';
        // window.Pusher = require('pusher-js');
        // window.Echo = new Echo({
        //     broadcaster: 'pusher',
        //     key: process.env.MIX_PUSHER_APP_KEY,
        //     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        //     forceTLS: true
        // });
        
        console.log('Real-time notifications initialized (polling mode)');
    }

    setupEventListeners() {
        // Listen for new order notifications
        this.listenForNewOrders();
        
        // Listen for order status changes
        this.listenForOrderStatusChanges();
        
        // Listen for page visibility changes
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pauseNotifications();
            } else {
                this.resumeNotifications();
            }
        });
    }

    listenForNewOrders() {
        // In production with Pusher:
        // window.Echo.channel('chef-orders')
        //     .listen('NewOrderReceived', (e) => {
        //         this.handleNewOrder(e.order);
        //     });
        
        // For now, we'll use polling every 5 seconds
        setInterval(() => {
            this.checkForNewOrders();
        }, 5000);
    }

    listenForOrderStatusChanges() {
        // In production with Pusher:
        // window.Echo.private('order.' + orderId)
        //     .listen('OrderStatusChanged', (e) => {
        //         this.handleOrderStatusChange(e.order);
        //     });
        
        // For now, we'll use polling
        setInterval(() => {
            this.checkForOrderUpdates();
        }, 3000);
    }

    async checkForNewOrders() {
        try {
            console.log('Checking for new orders...');
            const response = await fetch('/chef/dashboard/check-new-orders', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('Response status:', response.status);
            
            if (response.ok) {
                const data = await response.json();
                console.log('Response data:', data);
                
                if (data.hasNewOrders) {
                    console.log('New orders found:', data.newOrdersCount);
                    this.handleNewOrder(data.latestOrder);
                } else {
                    console.log('No new orders');
                }
            } else {
                console.error('Response not OK:', response.status, response.statusText);
            }
        } catch (error) {
            console.error('Error checking for new orders:', error);
        }
    }

    async checkForOrderUpdates() {
        try {
            const response = await fetch('/chef/dashboard/check-order-updates', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.hasUpdates) {
                    this.handleOrderUpdates(data.updatedOrders);
                }
            }
        } catch (error) {
            console.error('Error checking for order updates:', error);
        }
    }

    handleNewOrder(order) {
        console.log('New order received:', order);
        
        // Play notification sound
        this.playNotificationSound();
        
        // Show visual notification
        this.showNotification('New Order Received!', `Order #${order.order_number} - ${order.user.name}`, 'success');
        
        // Update the awaiting orders section
        this.updateAwaitingOrders(order);
        
        // Show browser notification if permission granted
        this.showBrowserNotification('New Order!', `Order #${order.order_number} from ${order.user.name}`);
        
        // Update page title to indicate new order
        this.updatePageTitle('🔔 New Order!');
        
        // Force a page reload to ensure the order appears
        console.log('Reloading page to show new order...');
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }

    handleOrderStatusChange(order) {
        console.log('Order status changed:', order);
        
        // Update the order in the UI
        this.updateOrderInUI(order);
        
        // Show notification for status changes
        const statusMessages = {
            'accepted': 'Order Accepted',
            'preparing': 'Order Preparing',
            'ready': 'Order Ready',
            'out_for_delivery': 'Order Out for Delivery',
            'delivered': 'Order Delivered',
            'cancelled': 'Order Cancelled'
        };
        
        const message = statusMessages[order.status] || 'Order Status Updated';
        this.showNotification(message, `Order #${order.order_number}`, 'info');
    }

    handleOrderUpdates(orders) {
        orders.forEach(order => {
            this.updateOrderInUI(order);
        });
    }

    playNotificationSound() {
        if (this.isNotificationPlaying) return;
        
        try {
            this.isNotificationPlaying = true;
            
            // Play the notification sound multiple times for better attention
            if (this.notificationSound) {
                // Play immediately
                this.notificationSound();
                
                // Play again after 1 second
                setTimeout(() => {
                    if (this.isNotificationPlaying) {
                        this.notificationSound();
                    }
                }, 1000);
                
                // Play again after 2 seconds
                setTimeout(() => {
                    if (this.isNotificationPlaying) {
                        this.notificationSound();
                    }
                }, 2000);
                
                // Play again after 3 seconds
                setTimeout(() => {
                    if (this.isNotificationPlaying) {
                        this.notificationSound();
                    }
                }, 3000);
            }
            
            // Stop playing after duration
            setTimeout(() => {
                this.isNotificationPlaying = false;
            }, this.notificationDuration);
            
        } catch (error) {
            console.warn('Could not play notification sound:', error);
            this.isNotificationPlaying = false;
        }
    }

    showNotification(title, message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg border-l-4 ${
            type === 'success' ? 'border-green-500' : 
            type === 'error' ? 'border-red-500' : 
            'border-blue-500'
        } transform transition-all duration-300 ease-in-out translate-x-full`;
        
        notification.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center ${
                            type === 'success' ? 'bg-green-100' : 
                            type === 'error' ? 'bg-red-100' : 
                            'bg-blue-100'
                        }">
                            <svg class="w-5 h-5 ${
                                type === 'success' ? 'text-green-600' : 
                                type === 'error' ? 'text-red-600' : 
                                'text-blue-600'
                            }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">${title}</p>
                        <p class="mt-1 text-sm text-gray-500">${message}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    showBrowserNotification(title, message) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification(title, {
                body: message,
                icon: '/favicon.ico',
                badge: '/favicon.ico'
            });
        }
    }

    updateAwaitingOrders(order) {
        // Find the awaiting orders section and add the new order
        const awaitingSection = document.querySelector('#awaiting-orders');
        if (awaitingSection) {
            // Create order card
            const orderCard = this.createOrderCard(order);
            
            // Add to the top of awaiting orders
            const ordersContainer = awaitingSection.querySelector('.grid');
            if (ordersContainer) {
                ordersContainer.insertBefore(orderCard, ordersContainer.firstChild);
            }
        }
    }

    createOrderCard(order) {
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500';
        card.innerHTML = `
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Order #${order.order_number}</h3>
                    <p class="text-sm text-gray-600">${order.user.name}</p>
                    <p class="text-sm text-gray-500">${order.phone}</p>
                </div>
                <span class="px-2 py-1 text-xs font-semibold text-orange-800 bg-orange-100 rounded-full">
                    ${order.status.toUpperCase()}
                </span>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700">${order.delivery_address}</p>
                <p class="text-lg font-bold text-gray-900">${order.total_amount} MAD</p>
            </div>
            <div class="flex space-x-2">
                <a href="/chef/orders/${order.id}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                    View Details
                </a>
                <form action="/chef/orders/${order.id}/accept" method="POST" class="inline">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                        Accept Order
                    </button>
                </form>
            </div>
        `;
        return card;
    }

    updateOrderInUI(order) {
        // Find and update the order in the UI
        const orderElement = document.querySelector(`[data-order-id="${order.id}"]`);
        if (orderElement) {
            // Update status
            const statusElement = orderElement.querySelector('.status-badge');
            if (statusElement) {
                statusElement.textContent = order.status.toUpperCase();
                statusElement.className = `px-2 py-1 text-xs font-semibold rounded-full ${
                    order.status === 'pending' ? 'text-orange-800 bg-orange-100' :
                    order.status === 'accepted' ? 'text-blue-800 bg-blue-100' :
                    order.status === 'preparing' ? 'text-yellow-800 bg-yellow-100' :
                    order.status === 'ready' ? 'text-green-800 bg-green-100' :
                    order.status === 'out_for_delivery' ? 'text-purple-800 bg-purple-100' :
                    order.status === 'delivered' ? 'text-gray-800 bg-gray-100' :
                    'text-red-800 bg-red-100'
                }`;
            }
            
            // Update other order details as needed
            // Add more specific updates based on what changed
        }
    }

    startAutoRefresh() {
        // Refresh the entire dashboard every 30 seconds
        setInterval(() => {
            if (!document.hidden) {
                this.refreshDashboard();
            }
        }, 30000);
    }

    async refreshDashboard() {
        try {
            // Reload the page to get fresh data
            window.location.reload();
        } catch (error) {
            console.error('Error refreshing dashboard:', error);
        }
    }

    pauseNotifications() {
        this.isNotificationPlaying = false;
    }

    resumeNotifications() {
        // Resume normal operation
        this.resetPageTitle();
    }

    updatePageTitle(newTitle) {
        // Store original title if not already stored
        if (!this.originalTitle) {
            this.originalTitle = document.title;
        }
        
        // Update title with new order indicator
        document.title = newTitle + ' - ' + this.originalTitle;
        
        // Also update favicon to show notification
        this.updateFavicon('🔔');
    }

    resetPageTitle() {
        if (this.originalTitle) {
            document.title = this.originalTitle;
            this.updateFavicon('🍣');
        }
    }

    updateFavicon(emoji) {
        // Create a canvas to draw the emoji as favicon
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = 32;
        canvas.height = 32;
        
        // Set background
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, 32, 32);
        
        // Draw emoji
        ctx.font = '20px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(emoji, 16, 16);
        
        // Convert to data URL and set as favicon
        const dataURL = canvas.toDataURL('image/png');
        const link = document.querySelector("link[rel*='icon']") || document.createElement('link');
        link.type = 'image/x-icon';
        link.rel = 'shortcut icon';
        link.href = dataURL;
        document.getElementsByTagName('head')[0].appendChild(link);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on chef dashboard pages
    if (window.location.pathname.includes('/chef/')) {
        new ChefRealtime();
        
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }
});
