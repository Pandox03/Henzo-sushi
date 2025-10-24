// Chef Real-time Dashboard - Fixed Audio Version
class ChefRealtime {
    constructor() {
        this.echo = null;
        this.notificationSound = null;
        this.fallbackAudio = null;
        this.isNotificationPlaying = false;
        this.notificationDuration = 10000; // 10 seconds
        this.audioEnabled = false;
        this.soundType = 'notification'; // Only notification sound
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
        // Create HTML5 Audio fallback for better browser compatibility
        try {
            // Create a simple beep sound using data URL
            this.fallbackAudio = new Audio();
            this.fallbackAudio.volume = 0.7;
            
            // Create a simple beep sound
            this.createBeepSound();
            
            console.log('Audio initialized successfully');
        } catch (error) {
            console.warn('Could not initialize audio:', error);
        }
    }

    createBeepSound() {
        // Create the notification sound only
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const sampleRate = audioContext.sampleRate;
            const duration = 1.0; // Shorter duration - 1.0 seconds
            const numSamples = sampleRate * duration;
            const buffer = audioContext.createBuffer(1, numSamples, sampleRate);
            const data = buffer.getChannelData(0);
            
            // Create the notification sound with longer duration
            this.createNotificationSound(data, sampleRate, duration);
            
            // Convert to WAV data URL
            const wavData = this.bufferToWav(buffer);
            this.fallbackAudio.src = wavData;
            
            console.log('Notification sound created (1.0s duration)');
            
        } catch (error) {
            console.warn('Could not create notification sound:', error);
        }
    }

    createNotificationSound(data, sampleRate, duration) {
        // Enhanced notification sound with longer duration
        for (let i = 0; i < data.length; i++) {
            const t = i / sampleRate;
            let sample = 0;
            
            // Ascending notes with more complexity
            sample += Math.sin(2 * Math.PI * 440 * t) * 0.3; // A4
            sample += Math.sin(2 * Math.PI * 554.37 * t) * 0.3; // C#5
            sample += Math.sin(2 * Math.PI * 659.25 * t) * 0.4; // E5
            
            // Add some harmonics for richness
            sample += Math.sin(2 * Math.PI * 880 * t) * 0.1; // A5 (octave)
            sample += Math.sin(2 * Math.PI * 1108.74 * t) * 0.1; // C#6 (octave)
            
            // Apply envelope for natural decay (slower decay for longer sound)
            const envelope = Math.exp(-t * 1.5) * (1 - Math.exp(-t * 8));
            sample *= envelope;
            
            // Add a slight vibrato for warmth
            const vibrato = 1 + 0.03 * Math.sin(2 * Math.PI * 4 * t);
            sample *= vibrato;
            
            data[i] = sample;
        }
    }

    bufferToWav(buffer) {
        const length = buffer.length;
        const arrayBuffer = new ArrayBuffer(44 + length * 2);
        const view = new DataView(arrayBuffer);
        
        // WAV header
        const writeString = (offset, string) => {
            for (let i = 0; i < string.length; i++) {
                view.setUint8(offset + i, string.charCodeAt(i));
            }
        };
        
        writeString(0, 'RIFF');
        view.setUint32(4, 36 + length * 2, true);
        writeString(8, 'WAVE');
        writeString(12, 'fmt ');
        view.setUint32(16, 16, true);
        view.setUint16(20, 1, true);
        view.setUint16(22, 1, true);
        view.setUint32(24, 44100, true);
        view.setUint32(28, 88200, true);
        view.setUint16(32, 2, true);
        view.setUint16(34, 16, true);
        writeString(36, 'data');
        view.setUint32(40, length * 2, true);
        
        // Convert float samples to 16-bit PCM
        const channelData = buffer.getChannelData(0);
        let offset = 44;
        for (let i = 0; i < length; i++) {
            const sample = Math.max(-1, Math.min(1, channelData[i]));
            view.setInt16(offset, sample < 0 ? sample * 0x8000 : sample * 0x7FFF, true);
            offset += 2;
        }
        
        const blob = new Blob([arrayBuffer], { type: 'audio/wav' });
        return URL.createObjectURL(blob);
    }

    enableAudioOnInteraction() {
        // Enable audio on first user interaction
        const enableAudio = () => {
            if (!this.audioEnabled) {
                this.audioEnabled = true;
                console.log('Audio enabled by user interaction');
                
                // Test audio
                this.testAudio();
            }
        };
        
        // Listen for any user interaction
        document.addEventListener('click', enableAudio, { once: true });
        document.addEventListener('keydown', enableAudio, { once: true });
        document.addEventListener('touchstart', enableAudio, { once: true });
    }

    testAudio() {
        if (this.fallbackAudio) {
            this.fallbackAudio.play().catch(error => {
                console.warn('Audio test failed:', error);
            });
        }
    }

    initEcho() {
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
        // Poll every 5 seconds for new orders
        setInterval(() => {
            this.checkForNewOrders();
        }, 10000);
    }

    listenForOrderStatusChanges() {
        // Poll every 3 seconds for order updates
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
                
                if (data.hasNewOrders && data.latestOrder) {
                    console.log('New orders found:', data.newOrdersCount);
                    // Only handle new order if we haven't seen this order before
                    if (this.isNewOrder(data.latestOrder)) {
                        this.handleNewOrder(data.latestOrder);
                    }
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

    isNewOrder(order) {
        // Check if we've already processed this order
        const processedOrders = this.processedOrders || new Set();
        
        if (processedOrders.has(order.id)) {
            console.log('Order already processed:', order.id);
            return false;
        }
        
        // Mark this order as processed
        processedOrders.add(order.id);
        this.processedOrders = processedOrders;
        
        console.log('New order detected:', order.id);
        return true;
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
        
        // Update the awaiting orders section instead of reloading
        console.log('New order received, updating UI...');
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
        if (!this.audioEnabled) {
            console.log('Audio not enabled yet, trying to enable...');
            this.audioEnabled = true;
        }
        
        try {
            console.log('Playing 10 notification sounds...');
            
            // Use the existing fallback audio and play it multiple times
            if (this.fallbackAudio) {
                // Play immediately
                this.fallbackAudio.currentTime = 0;
                this.fallbackAudio.play().catch(error => {
                    console.warn('Audio play failed:', error);
                });
                
                // Play 9 more times with 0.5 second intervals
                for (let i = 1; i < 10; i++) {
                    setTimeout(() => {
                        this.fallbackAudio.currentTime = 0;
                        this.fallbackAudio.play().catch(error => {
                            console.warn('Audio play failed:', error);
                        });
                        console.log(`Playing sound ${i + 1}/10`);
                    }, i * 500); // 500ms, 1000ms, 1500ms, 2000ms, 2500ms, 3000ms, 3500ms, 4000ms, 4500ms
                }
            } else {
                console.warn('No fallback audio available');
            }
            
        } catch (error) {
            console.warn('Could not play notification sound:', error);
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
        }
    }

    startAutoRefresh() {
        // Auto-refresh disabled to prevent interrupting sounds
        console.log('Auto-refresh disabled to prevent sound interruption');
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
