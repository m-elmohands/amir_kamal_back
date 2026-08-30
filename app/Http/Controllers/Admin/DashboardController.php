<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $inquiryCounts = Inquiry::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $won = (int) ($inquiryCounts['won'] ?? 0);
        $lost = (int) ($inquiryCounts['lost'] ?? 0);

        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'open_inquiries' => Inquiry::open()->count(),
                'quotes_sent' => (int) ($inquiryCounts['quoted'] ?? 0),
                'conversion_rate' => ($won + $lost) > 0 ? round(($won / ($won + $lost)) * 100, 1) : 0,
            ],
            'attention' => [
                'low_stock' => Product::where('availability_status', 'low_stock')->count(),
                'out_of_stock' => Product::where('availability_status', 'out_of_stock')->count(),
                'follow_ups_due' => Inquiry::open()->whereNotNull('next_follow_up_at')->where('next_follow_up_at', '<=', now())->count(),
            ],
            'inquiries' => Inquiry::with(['product.translations', 'assignee'])->latest()->limit(6)->get(),
        ]);
    }
}
