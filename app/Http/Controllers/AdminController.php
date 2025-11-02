<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\DeliveryLocation;
use App\Models\Schedule;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
use App\Jobs\SendPromoCodeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PromoCodeMail;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:admin']);
    }

    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'preparing_orders' => Order::where('status', 'preparing')->count(),
            'out_for_delivery' => Order::where('status', 'out_for_delivery')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
            'total_customers' => User::role('customer')->count(),
            'total_chefs' => User::role('chef')->count(),
            'total_delivery_guys' => User::role('delivery')->count(),
            'total_products' => Product::count(),
        ];

        // Recent orders
        $recentOrders = Order::with(['user', 'chef', 'deliveryGuy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Revenue by day (last 7 days)
        $revenueByDay = Order::where('status', 'delivered')
            ->where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top customers (by delivered revenue)
        $topCustomers = Order::select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('COUNT(orders.id) as total_orders')
            )
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.status', 'delivered')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'ordersByStatus', 'revenueByDay', 'topCustomers'));
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::query()->withCount(['orders as total_orders']);

        // Filters: role (Spatie), search (name/email/phone)
        if ($request->filled('role')) {
            $query->role($request->string('role'));
        }

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:customer,chef,delivery,admin',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        // assign role via Spatie
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:customer,chef,delivery,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        // sync role via Spatie
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    // Product Management
    public function products(Request $request)
    {
        $query = Product::query();

        if ($request->filled('q')) {
            $search = $request->string('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('is_available', true);
            } elseif ($request->availability === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(['id','name']);
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'preparation_time' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'has_discount' => 'nullable',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0.01',
            'discount_expires_at' => 'nullable|date',
        ]);

        $productData = $request->only([
            'name', 'description', 'price', 'category_id', 'preparation_time', 'is_available',
            'has_discount', 'discount_type', 'discount_value', 'discount_expires_at'
        ]);

        // Handle checkbox
        $productData['has_discount'] = $request->has('has_discount') ? true : false;
        
        // Clear discount fields if discount is disabled
        if (!$productData['has_discount']) {
            $productData['discount_type'] = null;
            $productData['discount_value'] = null;
            $productData['discount_expires_at'] = null;
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $productData['image'] = $imagePath;
        }

        Product::create($productData);

        return redirect()->route('admin.products')->with('success', 'Product created successfully!');
    }

    public function editProduct(Product $product)
    {
        $categories = \App\Models\Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get(['id','name']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'has_discount' => 'nullable',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0.01',
            'discount_expires_at' => 'nullable|date',
        ]);

        $productData = $request->only([
            'name', 'description', 'price', 'category_id', 'is_available',
            'has_discount', 'discount_type', 'discount_value', 'discount_expires_at'
        ]);

        // Handle checkbox
        $productData['has_discount'] = $request->has('has_discount') ? true : false;
        
        // Clear discount fields if discount is disabled
        if (!$productData['has_discount']) {
            $productData['discount_type'] = null;
            $productData['discount_value'] = null;
            $productData['discount_expires_at'] = null;
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $productData['image'] = $imagePath;
        }

        $product->update($productData);

        return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }

    // Category Management
    public function categories(Request $request)
    {
        $query = \App\Models\Category::query();
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where('name', 'like', "%{$q}%");
        }
        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->only(['name','description','is_active','sort_order']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        \App\Models\Category::create($data);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    }

    public function editCategory(\App\Models\Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, \App\Models\Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->only(['name','description','is_active','sort_order']);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
    }

    public function deleteCategory(\App\Models\Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully!');
    }

    // Order Management
    public function orders(Request $request)
    {
        $query = Order::query()->with(['user', 'chef', 'deliveryGuy', 'orderItems.product']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }

        if ($request->filled('customer')) {
            $customer = $request->string('customer');
            $query->whereHas('user', function ($q) use ($customer) {
                $q->where('name', 'like', "%{$customer}%")
                  ->orWhere('email', 'like', "%{$customer}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['user', 'chef', 'deliveryGuy', 'orderItems.product', 'deliveryLocations']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,out_for_delivery,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    // System Settings
    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        // Update system settings here
        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    /**
     * Display schedule management page
     */
    public function schedules()
    {
        $schedules = Schedule::where('is_override', false)
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();
        
        $overrideSchedules = Schedule::where('is_override', true)
            ->whereNotNull('override_date')
            ->orderBy('override_date', 'desc')
            ->get();
        
        return view('admin.schedules', compact('schedules', 'overrideSchedules'));
    }

    /**
     * Update default schedule for a day of week
     */
    public function updateSchedule(Request $request, Schedule $schedule = null)
    {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'is_closed' => 'nullable|boolean',
            'lunch_start' => 'nullable|date_format:H:i',
            'lunch_end' => 'nullable|date_format:H:i',
            'dinner_start' => 'nullable|date_format:H:i',
            'dinner_end' => 'nullable|date_format:H:i',
        ]);

        if ($schedule && $schedule->is_override) {
            // Update override schedule
            $schedule->update([
                'is_closed' => $request->has('is_closed'),
                'lunch_start' => $request->lunch_start ? Carbon::parse($request->lunch_start)->format('H:i:s') : null,
                'lunch_end' => $request->lunch_end ? Carbon::parse($request->lunch_end)->format('H:i:s') : null,
                'dinner_start' => $request->dinner_start ? Carbon::parse($request->dinner_start)->format('H:i:s') : null,
                'dinner_end' => $request->dinner_end ? Carbon::parse($request->dinner_end)->format('H:i:s') : null,
                'notes' => $request->notes,
            ]);

            return redirect()->back()->with('success', 'Override schedule updated successfully!');
        }

        // Update or create default schedule
        Schedule::updateOrCreate(
            ['day_of_week' => $request->day_of_week, 'is_override' => false],
            [
                'is_closed' => $request->has('is_closed'),
                'lunch_start' => $request->lunch_start ? Carbon::parse($request->lunch_start)->format('H:i:s') : null,
                'lunch_end' => $request->lunch_end ? Carbon::parse($request->lunch_end)->format('H:i:s') : null,
                'dinner_start' => $request->dinner_start ? Carbon::parse($request->dinner_start)->format('H:i:s') : null,
                'dinner_end' => $request->dinner_end ? Carbon::parse($request->dinner_end)->format('H:i:s') : null,
            ]
        );

        return redirect()->back()->with('success', 'Schedule updated successfully!');
    }

    /**
     * Create override schedule for a specific date
     */
    public function createOverrideSchedule(Request $request)
    {
        $request->validate([
            'override_date' => 'required|date',
            'is_closed' => 'nullable|boolean',
            'lunch_start' => 'nullable|date_format:H:i',
            'lunch_end' => 'nullable|date_format:H:i',
            'dinner_start' => 'nullable|date_format:H:i',
            'dinner_end' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $date = Carbon::parse($request->override_date);
        $dayOfWeek = strtolower($date->format('l'));

        Schedule::updateOrCreate(
            [
                'day_of_week' => $dayOfWeek,
                'override_date' => $date->format('Y-m-d'),
                'is_override' => true
            ],
            [
                'is_closed' => $request->has('is_closed'),
                'lunch_start' => $request->lunch_start ? Carbon::parse($request->lunch_start)->format('H:i:s') : null,
                'lunch_end' => $request->lunch_end ? Carbon::parse($request->lunch_end)->format('H:i:s') : null,
                'dinner_start' => $request->dinner_start ? Carbon::parse($request->dinner_start)->format('H:i:s') : null,
                'dinner_end' => $request->dinner_end ? Carbon::parse($request->dinner_end)->format('H:i:s') : null,
                'notes' => $request->notes,
            ]
        );

        return redirect()->back()->with('success', 'Override schedule created successfully!');
    }

    /**
     * Delete override schedule
     */
    public function deleteOverrideSchedule(Schedule $schedule)
    {
        if (!$schedule->is_override) {
            return redirect()->back()->with('error', 'Cannot delete default schedule!');
        }

        $schedule->delete();
        return redirect()->back()->with('success', 'Override schedule deleted successfully!');
    }

    /**
     * Display promo codes management page
     */
    public function promoCodes()
    {
        $promoCodes = PromoCode::withCount('usages')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $products = Product::where('is_available', true)->orderBy('name')->get();
        
        // Prepare promo codes data for JavaScript
        $promoCodesJson = $promoCodes->map(function($pc) {
            return [
                'id' => $pc->id,
                'code' => $pc->code,
                'name' => $pc->name,
                'description' => $pc->description,
                'discount_type' => $pc->discount_type,
                'discount_value' => $pc->discount_value,
                'minimum_order_amount' => $pc->minimum_order_amount,
                'usage_limit_per_user' => $pc->usage_limit_per_user,
                'total_usage_limit' => $pc->total_usage_limit,
                'valid_for_days' => $pc->valid_for_days,
                'is_active' => $pc->is_active,
                'send_email_to_users' => $pc->send_email_to_users,
                'applicable_products' => $pc->applicable_products,
            ];
        })->values()->toArray();
        
        return view('admin.promo-codes', compact('promoCodes', 'products', 'promoCodesJson'));
    }

    /**
     * Store a new promo code
     */
    public function storePromoCode(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|max:50|unique:promo_codes,code|regex:/^[A-Z0-9-_]+$/i',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'discount_type' => 'required|in:percentage,fixed',
                'discount_value' => 'required|numeric|min:0.01',
                'minimum_order_amount' => 'nullable|numeric|min:0',
                'usage_limit_per_user' => 'required|integer|min:1',
                'total_usage_limit' => 'nullable|integer|min:1',
                'valid_for_days' => 'nullable|integer|min:1',
                'is_active' => 'nullable',
                'send_email_to_users' => 'nullable',
                'applicable_products' => 'nullable|array',
                'applicable_products.*' => 'exists:products,id',
            ]);

            $sendEmailToUsers = $request->has('send_email_to_users') && $request->send_email_to_users == '1';
            
            \Log::info('Promo code creation', [
                'send_email_checkbox_present' => $request->has('send_email_to_users'),
                'send_email_value' => $request->send_email_to_users ?? 'not set',
                'send_email_checked' => $sendEmailToUsers,
            ]);

            $data = [
                'code' => strtoupper(trim($request->code)),
                'name' => $request->name,
                'description' => $request->description,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'minimum_order_amount' => $request->minimum_order_amount ?: null,
                'usage_limit_per_user' => $request->usage_limit_per_user,
                'total_usage_limit' => $request->total_usage_limit ?: null,
                'valid_for_days' => $request->valid_for_days ?: null,
                'is_active' => $request->has('is_active') ? true : false,
                'send_email_to_users' => $sendEmailToUsers,
            ];

            // Calculate expires_at if valid_for_days is set
            if ($request->valid_for_days) {
                $data['expires_at'] = Carbon::now()->addDays($request->valid_for_days);
            }

            // Handle applicable products
            if ($request->has('applicable_products') && !empty($request->applicable_products)) {
                $data['applicable_products'] = $request->applicable_products;
            } else {
                $data['applicable_products'] = null; // All products
            }

            $promoCode = PromoCode::create($data);

            // Dispatch email jobs to queue if requested
            if ($sendEmailToUsers) {
                \Log::info('Queueing promo code emails for all users', [
                    'promo_code_id' => $promoCode->id,
                    'promo_code' => $promoCode->code,
                ]);
                
                $users = User::whereHas('roles', function($query) {
                    $query->where('name', 'customer');
                })->whereNotNull('email_verified_at')->get();

                \Log::info('Found ' . $users->count() . ' customers to queue emails for');

                $queued = 0;
                foreach ($users as $index => $user) {
                    // Dispatch job with delay to respect rate limits (1 second between each email)
                    SendPromoCodeEmail::dispatch($promoCode, $user)
                        ->delay(now()->addSeconds($index));
                    $queued++;
                }

                $promoCode->update(['emailed_at' => now()]);
                
                $message = "Promo code created successfully! {$queued} emails queued for delivery.";
                
                \Log::info('Promo code emails queued', [
                    'queued' => $queued,
                    'promo_code_id' => $promoCode->id,
                ]);
                
                return redirect()->route('admin.promo-codes')->with('success', $message);
            }

            return redirect()->route('admin.promo-codes')->with('success', 'Promo code created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            \Log::error('Failed to create promo code: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create promo code: ' . $e->getMessage());
        }
    }

    /**
     * Update a promo code
     */
    public function updatePromoCode(Request $request, PromoCode $promoCode)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code,' . $promoCode->id . '|regex:/^[A-Z0-9-_]+$/',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'minimum_order_amount' => 'nullable|numeric|min:0',
            'usage_limit_per_user' => 'required|integer|min:1',
            'total_usage_limit' => 'nullable|integer|min:1',
            'valid_for_days' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'send_email_to_users' => 'nullable|boolean',
            'applicable_products' => 'nullable|array',
            'applicable_products.*' => 'exists:products,id',
        ]);

        $data = $request->only([
            'code', 'name', 'description', 'discount_type', 'discount_value',
            'minimum_order_amount', 'usage_limit_per_user', 'total_usage_limit',
            'is_active', 'send_email_to_users'
        ]);

        // Convert code to uppercase
        $data['code'] = strtoupper($data['code']);

        // Handle valid_for_days and expires_at
        if ($request->valid_for_days) {
            // If valid_for_days changed or expires_at is null, recalculate
            if ($promoCode->valid_for_days != $request->valid_for_days || !$promoCode->expires_at) {
                $data['expires_at'] = Carbon::now()->addDays($request->valid_for_days);
            }
            $data['valid_for_days'] = $request->valid_for_days;
        } else {
            $data['valid_for_days'] = null;
            $data['expires_at'] = null;
        }

        // Handle applicable products
        if ($request->has('applicable_products')) {
            $data['applicable_products'] = $request->applicable_products;
        } else {
            $data['applicable_products'] = null; // All products
        }

        $data['is_active'] = $request->has('is_active');
        
        // Only send email if it hasn't been sent before and checkbox is checked
        if ($request->has('send_email_to_users') && !$promoCode->emailed_at) {
            $users = User::whereHas('roles', function($query) {
                $query->where('name', 'customer');
            })->get();

            foreach ($users as $user) {
                try {
                    Mail::to($user->email)->send(new PromoCodeMail($promoCode, $user));
                } catch (\Exception $e) {
                    \Log::error('Failed to send promo code email to ' . $user->email . ': ' . $e->getMessage());
                }
            }

            $data['emailed_at'] = now();
        }

        $promoCode->update($data);

        return redirect()->route('admin.promo-codes')->with('success', 'Promo code updated successfully!');
    }

    /**
     * Delete a promo code
     */
    public function deletePromoCode(PromoCode $promoCode)
    {
        $promoCode->delete();
        return redirect()->route('admin.promo-codes')->with('success', 'Promo code deleted successfully!');
    }

    /**
     * Manually send promo code email to all users
     */
    public function sendPromoCodeEmail(PromoCode $promoCode)
    {
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->whereNotNull('email_verified_at')->get();

        $queued = 0;
        foreach ($users as $index => $user) {
            // Dispatch job with delay to respect rate limits (1 second between each email)
            SendPromoCodeEmail::dispatch($promoCode, $user)
                ->delay(now()->addSeconds($index));
            $queued++;
        }

        $promoCode->update(['emailed_at' => now()]);

        $message = "{$queued} emails queued for delivery.";

        \Log::info('Manually queued promo code emails', [
            'queued' => $queued,
            'promo_code_id' => $promoCode->id,
        ]);

        return redirect()->back()->with('success', $message);
    }
}

