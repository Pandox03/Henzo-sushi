// Customer Real-time Order Tracking
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
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center ${statusConfig.bgColor}">
                            <span class="text-white text-lg">${statusConfig.icon}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">${statusConfig.title}</h3>
                            <p class="text-sm text-gray-600">${statusConfig.description}</p>
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
                        <div class="marker-circle ${step.completed ? 'bg-green-500' : step.active ? 'bg-blue-500' : 'bg-gray-300'}">
                            ${step.completed ? '✓' : step.active ? '⏳' : '○'}
                        </div>
                        <div class="marker-line ${index === steps.length - 1 ? 'hidden' : ''}"></div>
                    </div>
                    <div class="timeline-content">
                        <h4 class="font-semibold ${step.completed ? 'text-green-700' : step.active ? 'text-blue-700' : 'text-gray-500'}">
                            ${step.title}
                        </h4>
                        <p class="text-sm text-gray-600">${step.description}</p>
                        ${step.time ? `<p class="text-xs text-gray-500 mt-1">${step.time}</p>` : ''}
                    </div>
                `;
                
                timeline.appendChild(stepElement);
                
                // Animate step appearance
                setTimeout(() => {
                    stepElement.style.opacity = '0';
                    stepElement.style.transform = 'translateX(-20px)';
                    stepElement.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        stepElement.style.opacity = '1';
                        stepElement.style.transform = 'translateX(0)';
                    }, 100);
                }, index * 100);
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
                time: this.formatTime(order.created_at)
            },
            {
                title: 'Order Accepted',
                description: 'Chef has accepted your order',
                completed: order.status !== 'pending',
                active: order.status === 'accepted',
                time: order.accepted_at ? this.formatTime(order.accepted_at) : null
            },
            {
                title: 'Preparing',
                description: 'Your order is being prepared',
                completed: ['preparing', 'ready', 'out_for_delivery', 'delivered'].includes(order.status),
                active: order.status === 'preparing',
                time: order.preparing_at ? this.formatTime(order.preparing_at) : null
            },
            {
                title: 'Ready',
                description: 'Your order is ready for delivery',
                completed: ['ready', 'out_for_delivery', 'delivered'].includes(order.status),
                active: order.status === 'ready',
                time: order.ready_at ? this.formatTime(order.ready_at) : null
            },
            {
                title: 'Out for Delivery',
                description: order.delivery_guy ? `Being delivered by ${order.delivery_guy.name}` : 'Your order is out for delivery',
                completed: order.status === 'delivered',
                active: order.status === 'out_for_delivery',
                time: order.out_for_delivery_at ? this.formatTime(order.out_for_delivery_at) : null
            },
            {
                title: 'Delivered',
                description: 'Your order has been delivered',
                completed: order.status === 'delivered',
                active: false,
                time: order.delivered_at ? this.formatTime(order.delivered_at) : null
            }
        ];
        
        return steps;
    }

    getStatusConfig(status) {
        const configs = {
            'pending': {
                icon: '⏳',
                title: 'Order Pending',
                description: 'Your order is waiting to be accepted by a chef',
                bgColor: 'bg-yellow-500'
            },
            'accepted': {
                icon: '👨‍🍳',
                title: 'Order Accepted',
                description: 'A chef has accepted your order and will start preparing it',
                bgColor: 'bg-blue-500'
            },
            'preparing': {
                icon: '🍳',
                title: 'Preparing',
                description: 'Your order is being prepared by our chef',
                bgColor: 'bg-orange-500'
            },
            'ready': {
                icon: '✅',
                title: 'Ready',
                description: 'Your order is ready and waiting for delivery',
                bgColor: 'bg-green-500'
            },
            'out_for_delivery': {
                icon: '🚚',
                title: 'Out for Delivery',
                description: 'Your order is on its way to you',
                bgColor: 'bg-purple-500'
            },
            'delivered': {
                icon: '🎉',
                title: 'Delivered',
                description: 'Your order has been delivered successfully',
                bgColor: 'bg-green-600'
            },
            'cancelled': {
                icon: '❌',
                title: 'Cancelled',
                description: 'Your order has been cancelled',
                bgColor: 'bg-red-500'
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
                        <div class="w-8 h-8 rounded-full flex items-center justify-center ${config.bgColor}">
                            <span class="text-white text-lg">${config.icon}</span>
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900">${config.title}</p>
                        <p class="mt-1 text-sm text-gray-500">${config.description}</p>
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

    updatePageTitle(order) {
        const config = this.getStatusConfig(order.status);
        document.title = `${config.icon} ${config.title} - Order #${order.order_number}`;
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
