<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

// Cart routes
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear.get');
Route::get('/cart/count', [App\Http\Controllers\CartController::class, 'getCount'])->name('cart.count');

// Promo code routes
Route::post('/cart/apply-promo', [App\Http\Controllers\CartController::class, 'applyPromo'])->name('cart.apply-promo');
Route::post('/cart/remove-promo', [App\Http\Controllers\CartController::class, 'removePromo'])->name('cart.remove-promo');

// Order routes
Route::get('/orders/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('orders.checkout');
Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/tracking', [App\Http\Controllers\OrderController::class, 'liveTracking'])->name('orders.tracking');
Route::get('/orders/{order}/delivery-locations', [App\Http\Controllers\OrderController::class, 'getDeliveryLocations'])->name('orders.delivery-locations');
Route::post('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
Route::get('/orders/{order}/status', [App\Http\Controllers\OrderController::class, 'checkStatus'])->name('orders.status');

// OTP Verification routes
Route::get('/verify-otp', [App\Http\Controllers\OtpVerificationController::class, 'showVerificationForm'])->name('otp.verify');
Route::post('/send-otp', [App\Http\Controllers\OtpVerificationController::class, 'sendOtp'])->name('otp.send');
Route::post('/verify-otp', [App\Http\Controllers\OtpVerificationController::class, 'verifyOtp'])->name('otp.verify.post');
Route::post('/resend-otp', [App\Http\Controllers\OtpVerificationController::class, 'resendOtp'])->name('otp.resend');

// Delivery routes
Route::middleware(['auth', 'check.role:delivery'])->group(function () {
    Route::get('/delivery/dashboard', [App\Http\Controllers\DeliveryController::class, 'dashboard'])->name('delivery.dashboard');
    Route::get('/delivery/orders/{order}', [App\Http\Controllers\DeliveryController::class, 'showOrder'])->name('delivery.orders.show');
    Route::get('/delivery/orders/{order}/navigation', [App\Http\Controllers\DeliveryController::class, 'routeNavigation'])->name('delivery.orders.navigation');
    Route::post('/delivery/orders/{order}/delivered', [App\Http\Controllers\DeliveryController::class, 'markDelivered'])->name('delivery.orders.delivered');
    Route::post('/delivery/orders/{order}/cancelled', [App\Http\Controllers\DeliveryController::class, 'markCancelled'])->name('delivery.orders.cancelled');
    Route::post('/delivery/orders/{order}/location', [App\Http\Controllers\DeliveryController::class, 'updateLocation'])->name('delivery.orders.location');
    Route::get('/delivery/history', [App\Http\Controllers\DeliveryController::class, 'orderHistory'])->name('delivery.history');
    Route::get('/delivery/orders/{order}/locations', [App\Http\Controllers\DeliveryController::class, 'getOrderLocation'])->name('delivery.orders.locations');
});

// Chef routes
Route::middleware(['auth', 'check.role:chef'])->group(function () {
    Route::get('/chef/dashboard', [App\Http\Controllers\ChefController::class, 'dashboard'])->name('chef.dashboard');
    Route::get('/chef/orders/{order}', [App\Http\Controllers\ChefController::class, 'showOrder'])->name('chef.orders.show');
    Route::post('/chef/orders/{order}/accept', [App\Http\Controllers\ChefController::class, 'acceptOrder'])->name('chef.orders.accept');
    Route::post('/chef/orders/{order}/status', [App\Http\Controllers\ChefController::class, 'updateOrderStatus'])->name('chef.orders.status');
    Route::post('/chef/orders/{order}/assign-delivery', [App\Http\Controllers\ChefController::class, 'assignDelivery'])->name('chef.orders.assign-delivery');
    Route::get('/chef/delivery-guys', [App\Http\Controllers\ChefController::class, 'getAvailableDeliveryGuys'])->name('chef.delivery-guys');
    Route::get('/chef/orders/history', [App\Http\Controllers\ChefController::class, 'orderHistory'])->name('chef.orders.history');
    Route::get('/chef/orders/{order}/print-bill', [App\Http\Controllers\ChefController::class, 'printBill'])->name('chef.orders.print-bill');
    
    // Real-time API endpoints
    Route::get('/chef/dashboard/check-new-orders', [App\Http\Controllers\ChefController::class, 'checkNewOrders'])->name('chef.check-new-orders');
    Route::get('/chef/dashboard/check-order-updates', [App\Http\Controllers\ChefController::class, 'checkOrderUpdates'])->name('chef.check-order-updates');
});

// Admin routes
Route::middleware(['auth', 'check.role:admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [App\Http\Controllers\AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Product Management
    Route::get('/admin/products', [App\Http\Controllers\AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/create', [App\Http\Controllers\AdminController::class, 'createProduct'])->name('admin.products.create');
    Route::post('/admin/products', [App\Http\Controllers\AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::get('/admin/products/{product}/edit', [App\Http\Controllers\AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::put('/admin/products/{product}', [App\Http\Controllers\AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/{product}', [App\Http\Controllers\AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    // Category Management
    Route::get('/admin/categories', [App\Http\Controllers\AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categories/create', [App\Http\Controllers\AdminController::class, 'createCategory'])->name('admin.categories.create');
    Route::post('/admin/categories', [App\Http\Controllers\AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}/edit', [App\Http\Controllers\AdminController::class, 'editCategory'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [App\Http\Controllers\AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [App\Http\Controllers\AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
    
    // Order Management
    Route::get('/admin/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/orders/{order}', [App\Http\Controllers\AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::post('/admin/orders/{order}/status', [App\Http\Controllers\AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    
    // Settings
    Route::get('/admin/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [App\Http\Controllers\AdminController::class, 'updateSettings'])->name('admin.settings.update');
    
    // Schedule Management
    Route::get('/admin/schedules', [App\Http\Controllers\AdminController::class, 'schedules'])->name('admin.schedules');
    Route::post('/admin/schedules', [App\Http\Controllers\AdminController::class, 'updateSchedule'])->name('admin.schedules.update');
    Route::post('/admin/schedules/override', [App\Http\Controllers\AdminController::class, 'createOverrideSchedule'])->name('admin.schedules.override');
    Route::post('/admin/schedules/{schedule}/update', [App\Http\Controllers\AdminController::class, 'updateSchedule'])->name('admin.schedules.update-override');
    Route::delete('/admin/schedules/{schedule}', [App\Http\Controllers\AdminController::class, 'deleteOverrideSchedule'])->name('admin.schedules.delete');

    // Promo Code Management
    Route::get('/admin/promo-codes', [App\Http\Controllers\AdminController::class, 'promoCodes'])->name('admin.promo-codes');
    Route::post('/admin/promo-codes', [App\Http\Controllers\AdminController::class, 'storePromoCode'])->name('admin.promo-codes.store');
    Route::put('/admin/promo-codes/{promoCode}', [App\Http\Controllers\AdminController::class, 'updatePromoCode'])->name('admin.promo-codes.update');
    Route::delete('/admin/promo-codes/{promoCode}', [App\Http\Controllers\AdminController::class, 'deletePromoCode'])->name('admin.promo-codes.delete');
    Route::post('/admin/promo-codes/{promoCode}/send-email', [App\Http\Controllers\AdminController::class, 'sendPromoCodeEmail'])->name('admin.promo-codes.send-email');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
