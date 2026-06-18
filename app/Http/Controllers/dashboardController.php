<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\table;
use App\Models\item;
use App\Models\order;

class dashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTables = table::count();
        $totalItems = item::count();
        $totalOrders = order::count();

        return view('backend.home', compact('totalUsers', 'totalTables', 'totalItems', 'totalOrders'));
    }
}
