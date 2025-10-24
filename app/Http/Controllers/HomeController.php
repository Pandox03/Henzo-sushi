<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->where('is_active', true)->orderBy('sort_order')->get();
        $featuredProducts = Product::where('is_available', true)->inRandomOrder()->limit(6)->get();
        
        return view('home', compact('categories', 'featuredProducts'));
    }
}
