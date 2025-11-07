// Customer Real-time Order Tracking with SVG Icons
class CustomerRealtime {
    constructor() {
        this.echo = null;
        this.currentOrderId = null;
        this.isTracking = false;
        this.init();
    }

    init() {
        // Initialize Echo (Laravel Broadcasting)
        this.initEcho();
        
        // Set up event listeners
        this.setupEventListeners();
        
        // Start order tracking if on order page
        this.startOrderTracking();
    }

    initEcho() {
        console.log('Customer real-time tracking initialized');
    }

    setupEventListeners() {
        // Listen for page visibility changes
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pauseTracking();
            } else {
                this.resumeTracking();
            }
        });
    }

    startOrderTracking() {
        // Check if we're on an order page
        const orderId = this.getOrderIdFromUrl();
        if (orderId) {
            this.currentOrderId = orderId;
            this.isTracking = true;
            
            // Initialize the timeline with current order data
            this.initializeTimeline();
            
            this.startPolling();
            console.log('Started tracking order:', orderId);
        }
    }

    initializeTimeline() {
        // Get initial order data from the page
        const orderData = this.getOrderDataFromPage();
        if (orderData) {
            console.log('Initializing timeline with order data:', orderData);
            this.updateOrderDisplay(orderData);
        }
        
        // Force an immediate status check
        setTimeout(() => {
            this.checkOrderStatus();
        }, 1000);
    }

    getOrderDataFromPage() {
        // Get order data from server
        return window.orderData || {
            status: 'pending',
            order_number: 'Unknown',
            created_at: new Date().toISOString(),
            accepted_at: null,
            preparing_at: null,
            ready_at: null,
            out_for_delivery_at: null,
            delivered_at: null,
            delivery_guy: null
        };
    }

    getOrderIdFromUrl() {
        // Extract order ID from URL like /orders/123
        const path = window.location.pathname;
        const match = path.match(/\/orders\/(\d+)/);
        return match ? parseInt(match[1]) : null;
    }

    startPolling() {
        if (!this.isTracking) return;
        
        // Poll every 3 seconds for order updates
        setInterval(() => {
            if (this.isTracking && this.currentOrderId) {
                this.checkOrderStatus();
            }
        }, 3000);
    }

    async checkOrderStatus() {
        try {
            const response = await fetch(`/orders/${this.currentOrderId}/status`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                console.log('Order status check response:', data);
                
                if (data.hasUpdate && data.order) {
                    console.log('Order update detected:', data.order.status);
                    this.updateOrderDisplay(data.order);
                } else {
                    console.log('No order update detected');
                }
            } else {
                console.error('Failed to check order status:', response.status);
            }
        } catch (error) {
            console.error('Error checking order status:', error);
        }
    }

    updateOrderDisplay(order) {
        console.log('Order status updated:', order.status);
        
        // Update status display
        this.updateStatusDisplay(order);
        
        // Update progress timeline
        this.updateProgressTimeline(order);
        
        // Show notification
        this.showStatusNotification(order);
        
        // Update page title
        this.updatePageTitle(order);
    }

    updateStatusDisplay(order) {
        const statusElement = document.querySelector('.order-status');
        if (statusElement) {
            const statusConfig = this.getStatusConfig(order.status);
            
            // Animate status change
            statusElement.style.transform = 'scale(0.95)';
            statusElement.style.opacity = '0.7';
            
            setTimeout(() => {
                statusElement.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center ${statusConfig.bgColor}">
                            ${statusConfig.icon}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">${statusConfig.title}</h3>
                            <p class="text-gray-600">${statusConfig.description}</p>
                        </div>
                    </div>
                `;
                
                statusElement.style.transform = 'scale(1)';
                statusElement.style.opacity = '1';
            }, 200);
        }
    }

    updateProgressTimeline(order) {
        const timeline = document.querySelector('.order-timeline');
        if (timeline) {
            const steps = this.getTimelineSteps(order);
            console.log('Updating timeline with steps:', steps);
            
            // Clear existing timeline
            timeline.innerHTML = '';
            
            // Create timeline steps
            steps.forEach((step, index) => {
                const stepElement = document.createElement('div');
                stepElement.className = `timeline-step ${step.completed ? 'completed' : step.active ? 'active' : ''}`;
                stepElement.innerHTML = `
                    <div class="timeline-marker">
                        <div class="marker-circle">
                            ${step.icon}
                        </div>
                        <div class="marker-line"></div>
                    </div>
                    <div class="timeline-content">
                        <h4 class="font-bold text-base">
                            ${step.title}
                        </h4>
                        <p class="text-sm text-gray-600 mt-1">${step.description}</p>
                        ${step.time ? `<p class="text-xs text-gray-500 mt-2 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${step.time}
                        </p>` : ''}
                    </div>
                `;
                
                timeline.appendChild(stepElement);
                
                // Animate step appearance with stagger effect
                setTimeout(() => {
                    stepElement.classList.add(step.completed ? 'completed' : step.active ? 'active' : '');
                }, index * 150);
            });
        } else {
            console.error('Timeline element not found');
        }
    }

    getTimelineSteps(order) {
        const steps = [
            {
                title: 'Order Placed',
                description: 'Your order has been received',
                completed: true,
                active: false,
                icon: this.getSvgIcon('check-circle', 'marker-icon'),
                time: this.formatTime(order.created_at)
            },
            {
                title: 'Order Accepted',
                description: 'Chef has accepted your order',
                completed: order.status !== 'pending',
                active: order.status === 'accepted',
                icon: this.getSvgIcon(order.status === 'accepted' ? 'chef' : 'circle', 'marker-icon'),
                time: order.accepted_at ? this.formatTime(order.accepted_at) : null
            },
            {
                title: 'Preparing',
                description: 'Your order is being prepared',
                completed: ['preparing', 'ready', 'out_for_delivery', 'delivered'].includes(order.status),
                active: order.status === 'preparing',
                icon: this.getSvgIcon(order.status === 'preparing' ? 'cooking' : 'circle', 'marker-icon'),
                time: order.preparing_at ? this.formatTime(order.preparing_at) : null
            },
            {
                title: 'Ready',
                description: 'Your order is ready for delivery',
                completed: ['ready', 'out_for_delivery', 'delivered'].includes(order.status),
                active: order.status === 'ready',
                icon: this.getSvgIcon(order.status === 'ready' ? 'ready' : 'circle', 'marker-icon'),
                time: order.ready_at ? this.formatTime(order.ready_at) : null
            },
            {
                title: 'Out for Delivery',
                description: order.delivery_guy ? `Being delivered by ${order.delivery_guy.name}` : 'Your order is out for delivery',
                completed: order.status === 'delivered',
                active: order.status === 'out_for_delivery',
                icon: this.getSvgIcon(order.status === 'out_for_delivery' ? 'truck' : 'circle', 'marker-icon'),
                time: order.out_for_delivery_at ? this.formatTime(order.out_for_delivery_at) : null
            },
            {
                title: 'Delivered',
                description: 'Your order has been delivered',
                completed: order.status === 'delivered',
                active: false,
                icon: this.getSvgIcon(order.status === 'delivered' ? 'delivered' : 'circle', 'marker-icon'),
                time: order.delivered_at ? this.formatTime(order.delivered_at) : null
            }
        ];
        
        return steps;
    }

    getSvgIcon(type, className = '') {
        const icons = {
            'check-circle': `<svg class="${className}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`,
            'circle': `<svg class="${className}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
            </svg>`,
            'chef': `<svg class="${className}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`,
            'cooking': `<svg class="${className} spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>`,
            'ready': `<svg class="${className}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>`,
            'truck': `<svg class="${className}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
            </svg>`,
            'delivered': `<svg class="${className}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`,
            'clock': `<svg class="${className}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`
        };
        
        return icons[type] || icons['circle'];
    }

    getStatusConfig(status) {
        const configs = {
            'pending': {
                icon: this.getSvgIcon('clock', 'w-8 h-8 text-white'),
                title: 'Order Pending',
                description: 'Your order is waiting to be accepted by a chef',
                bgColor: 'bg-gradient-to-br from-yellow-400 to-yellow-500'
            },
            'accepted': {
                icon: this.getSvgIcon('chef', 'w-8 h-8 text-white'),
                title: 'Order Accepted',
                description: 'A chef has accepted your order and will start preparing it',
                bgColor: 'bg-gradient-to-br from-blue-400 to-blue-500'
            },
            'preparing': {
                icon: this.getSvgIcon('cooking', 'w-8 h-8 text-white'),
                title: 'Preparing',
                description: 'Your order is being prepared by our chef',
                bgColor: 'bg-gradient-to-br from-orange-400 to-orange-500'
            },
            'ready': {
                icon: this.getSvgIcon('ready', 'w-8 h-8 text-white'),
                title: 'Ready',
                description: 'Your order is ready and waiting for delivery',
                bgColor: 'bg-gradient-to-br from-green-400 to-green-500'
            },
            'out_for_delivery': {
                icon: this.getSvgIcon('truck', 'w-8 h-8 text-white'),
                title: 'Out for Delivery',
                description: 'Your order is on its way to you',
                bgColor: 'bg-gradient-to-br from-purple-400 to-purple-500'
            },
            'delivered': {
                icon: this.getSvgIcon('delivered', 'w-8 h-8 text-white'),
                title: 'Delivered',
                description: 'Your order has been delivered successfully',
                bgColor: 'bg-gradient-to-br from-green-500 to-green-600'
            },
            'cancelled': {
                icon: `<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>`,
                title: 'Cancelled',
                description: 'Your order has been cancelled',
                bgColor: 'bg-gradient-to-br from-red-400 to-red-500'
            }
        };
        
        return configs[status] || configs['pending'];
    }

    showStatusNotification(order) {
        const config = this.getStatusConfig(order.status);
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg border-l-4 border-blue-500 transform transition-all duration-300 ease-in-out translate-x-full';
        
        notification.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center ${config.bgColor}">
                            ${config.icon}
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">${config.title}</p>
                        <p class="mt-1 text-sm text-gray-500">${config.description}</p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
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

    updatePageTitle(order) {
        const config = this.getStatusConfig(order.status);
        document.title = `${config.title} - Order #${order.order_number}`;
    }

    formatTime(timestamp) {
        if (!timestamp) return null;
        const date = new Date(timestamp);
        return date.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
    }

    pauseTracking() {
        this.isTracking = false;
    }

    resumeTracking() {
        this.isTracking = true;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize on order pages
    if (window.location.pathname.includes('/orders/')) {
        new CustomerRealtime();
    }
});
