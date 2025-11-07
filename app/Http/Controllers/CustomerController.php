<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:customer']);
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get all available products
        $products = Product::with('category')
            ->where('is_available', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get all active categories
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Get cart items from session
        $cart = Session::get('cart', []);
        $cartItems = [];
        $cartTotal = 0;
        
        foreach ($cart as $productId => $details) {
            $product = Product::find($productId);
            if ($product && isset($details['quantity'])) {
                $quantity = $details['quantity'];
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                $cartTotal += $product->price * $quantity;
            }
        }
        
        // Get user statistics
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'total_expenses' => Order::where('user_id', $user->id)
                ->where('status', 'delivered')
                ->sum('total_amount')
        ];
        
        return view('customer.dashboard', compact('products', 'categories', 'cartItems', 'cartTotal', 'stats'));
    }

    public function liked()
    {
        // TODO: Implement liked products functionality
        return view('customer.liked');
    }

    public function feedback()
    {
        // TODO: Implement feedback functionality
        return view('customer.feedback');
    }

    public function messages()
    {
        // TODO: Implement messages functionality
        return view('customer.messages');
    }

    public function customization()
    {
        // TODO: Implement customization functionality
        return view('customer.customization');
    }
}

