<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index()
    {

        // total menu
        $totalMenu = Menu::count();

        // pesanan hari ini
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();

        return view('admin.dashboard', compact(
            'totalMenu',
            'todayOrders'
        ));

    }

}