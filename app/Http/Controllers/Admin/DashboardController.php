<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', ['stats' => ['products' => Product::count(), 'categories' => Category::count(), 'new_inquiries' => Inquiry::where('status', 'new')->count()], 'inquiries' => Inquiry::with('product')->latest()->limit(6)->get()]);
    }
}
