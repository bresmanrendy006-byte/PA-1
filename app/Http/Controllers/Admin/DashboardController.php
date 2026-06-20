<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;  
use App\Models\Order; 

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Mengambil total menu
        $totalMenu = Menu::count();

        // 2. Mengambil pesanan hari ini
        $todayOrders = Order::whereDate('created_at', now())->count();

        // 3. Mengambil total pendapatan (asumsi status 'confirmed' atau 'selesai')
        $totalRevenue = Order::where('status', 'confirmed')->sum('total_price');

        // 4. Mengambil 5 pesanan terbaru (ini yang menyebabkan error tadi)
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Kirim semua variabel ke view
        return view('admin.dashboard', compact(
            'totalMenu', 
            'todayOrders', 
            'totalRevenue', 
            'recentOrders'
        ));
    }
}