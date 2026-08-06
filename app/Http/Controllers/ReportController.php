<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\table;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Role based redirection
        if ($user->isSuperAdmin()) {
            return $this->superAdminReport($request);
        } elseif ($user->isRestaurantAdmin()) {
            return $this->restaurantAdminReport($request, $user->id);
        }

        abort(403, 'Unauthorized action.');
    }

    // ==========================================
    // 1. SUPER ADMIN LOGIC
    // ==========================================
    private function superAdminReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        $todayRevenue = Subscription::whereDate('created_at', Carbon::today())->sum('amount');
        $thisMonthRevenue = Subscription::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->sum('amount');

        $rangeRevenue = Subscription::whereDate('created_at', '>=', $startDate)
                                    ->whereDate('created_at', '<=', $endDate)
                                    ->sum('amount');

        $activeSubscriptionsCount = Subscription::where('status', 'active')
                                                ->where('end_date', '>=', Carbon::today())
                                                ->count();

        $subscriptions = Subscription::with('user') 
                            ->whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate)
                            ->orderByDesc('created_at')
                            ->get();

        return view('backend.reports', compact(
            'todayRevenue', 'thisMonthRevenue', 'rangeRevenue', 
            'activeSubscriptionsCount', 'subscriptions', 
            'startDate', 'endDate'
        ));
    }

    // ==========================================
    // 2. RESTAURANT ADMIN LOGIC (View Page)
    // ==========================================
    private function restaurantAdminReport(Request $request, $userId)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $selectedTable = $request->input('table_id', null);

        $todayTotal = order::where('user_id', $userId)->whereDate('created_at', Carbon::today())->sum('total');
        $todayOrders = order::where('user_id', $userId)->whereDate('created_at', Carbon::today())->distinct('order_number')->count();

        $thisMonthTotal = order::where('user_id', $userId)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total');
        $thisMonthOrders = order::where('user_id', $userId)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->distinct('order_number')->count();

        $allTables = table::where('user_id', $userId)->get();

        $tables = table::where('user_id', $userId)->when($selectedTable, function ($q, $val) {
            $q->where('id', $val);
        })->get();
        
        $tableReports = [];
        $totalRangeOrders = 0;
        $totalRangeRevenue = 0;

        foreach ($tables as $table) {
            $dailyRevenue = order::where('user_id', $userId)->where('table_id', $table->id)->whereDate('created_at', Carbon::today())->sum('total');
            $dailyOrdersCount = order::where('user_id', $userId)->where('table_id', $table->id)->whereDate('created_at', Carbon::today())->distinct('order_number')->count();

            $monthlyRevenue = order::where('user_id', $userId)->where('table_id', $table->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total');
            $monthlyOrdersCount = order::where('user_id', $userId)->where('table_id', $table->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->distinct('order_number')->count();

            $rangeRevenue = order::where('user_id', $userId)->where('table_id', $table->id)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->sum('total');
            $rangeOrdersCount = order::where('user_id', $userId)->where('table_id', $table->id)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->distinct('order_number')->count();

            $totalRangeOrders += $rangeOrdersCount;
            $totalRangeRevenue += $rangeRevenue;

            $tableReports[] = (object) [
                'table_number' => $table->table_number,
                'daily_orders' => $dailyOrdersCount,
                'daily_revenue' => $dailyRevenue,
                'monthly_orders' => $monthlyOrdersCount,
                'monthly_revenue' => $monthlyRevenue,
                'range_orders' => $rangeOrdersCount,
                'range_revenue' => $rangeRevenue,
            ];
        }

        return view('backend.reports', compact(
            'todayTotal', 'todayOrders', 
            'thisMonthTotal', 'thisMonthOrders', 
            'tableReports', 
            'startDate', 'endDate',
            'allTables', 'selectedTable',
            'totalRangeOrders', 'totalRangeRevenue'
        ));
    }

    // ==========================================
    // 3. PDF DOWNLOAD LOGIC
    // ==========================================
  /*  public function download(Request $request)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $user = auth()->user();
        
        if($user->isRestaurantAdmin()) {
            return $this->downloadRestaurantPDF($request, $user->id);
        }

        abort(403, 'PDF download for this role is not available.');
    } */

    private function downloadRestaurantPDF(Request $request, $userId)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        $selectedTable = $request->input('table_id', null);

        // Fetch same data as index view, but strictly for PDF
        $todayTotal = order::where('user_id', $userId)->whereDate('created_at', Carbon::today())->sum('total');
        $todayOrders = order::where('user_id', $userId)->whereDate('created_at', Carbon::today())->distinct('order_number')->count();

        $thisMonthTotal = order::where('user_id', $userId)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total');
        $thisMonthOrders = order::where('user_id', $userId)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->distinct('order_number')->count();

        $tables = table::where('user_id', $userId)->when($selectedTable, function ($q, $val) {
            $q->where('id', $val);
        })->get();
        
        $tableReports = [];
        $totalRangeOrders = 0;
        $totalRangeRevenue = 0;

        foreach ($tables as $table) {
            $dailyRevenue = order::where('user_id', $userId)->where('table_id', $table->id)->whereDate('created_at', Carbon::today())->sum('total');
            $dailyOrdersCount = order::where('user_id', $userId)->where('table_id', $table->id)->whereDate('created_at', Carbon::today())->distinct('order_number')->count();

            $monthlyRevenue = order::where('user_id', $userId)->where('table_id', $table->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total');
            $monthlyOrdersCount = order::where('user_id', $userId)->where('table_id', $table->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->distinct('order_number')->count();

            $rangeRevenue = order::where('user_id', $userId)->where('table_id', $table->id)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->sum('total');
            $rangeOrdersCount = order::where('user_id', $userId)->where('table_id', $table->id)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->distinct('order_number')->count();

            $totalRangeOrders += $rangeOrdersCount;
            $totalRangeRevenue += $rangeRevenue;

            $tableReports[] = (object) [
                'table_number' => $table->table_number,
                'daily_orders' => $dailyOrdersCount,
                'daily_revenue' => $dailyRevenue,
                'monthly_orders' => $monthlyOrdersCount,
                'monthly_revenue' => $monthlyRevenue,
                'range_orders' => $rangeOrdersCount,
                'range_revenue' => $rangeRevenue,
            ];
        }

        $data = compact(
            'todayTotal', 'todayOrders', 
            'thisMonthTotal', 'thisMonthOrders', 
            'tableReports', 
            'startDate', 'endDate',
            'totalRangeOrders', 'totalRangeRevenue'
        );

        $pdf = PDF::loadView('backend.reports_pdf', $data);
        return $pdf->download('Sales_Report_'.$startDate.'_to_'.$endDate.'.pdf');
    } 

        public function download(Request $request)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        $user = auth()->user();
        
        // Super Admin ke liye condition add ki hai
        if ($user->isSuperAdmin()) {
            return $this->downloadSuperAdminPDF($request);
        } elseif ($user->isRestaurantAdmin()) {
            return $this->downloadRestaurantPDF($request, $user->id);
        }

        abort(403, 'PDF download for this role is not available.');
    }

    // Super Admin ki PDF generate karne ka function
    private function downloadSuperAdminPDF(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        $todayRevenue = Subscription::whereDate('created_at', Carbon::today())->sum('amount');
        $thisMonthRevenue = Subscription::whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->sum('amount');

        $rangeRevenue = Subscription::whereDate('created_at', '>=', $startDate)
                                    ->whereDate('created_at', '<=', $endDate)
                                    ->sum('amount');

        $activeSubscriptionsCount = Subscription::where('status', 'active')
                                                ->where('end_date', '>=', Carbon::today())
                                                ->count();

        $subscriptions = Subscription::with('user')
                            ->whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate)
                            ->orderByDesc('created_at')
                            ->get();

        $data = compact(
            'todayRevenue', 'thisMonthRevenue', 'rangeRevenue',
            'activeSubscriptionsCount', 'subscriptions',
            'startDate', 'endDate'
        );

        // Yeh Super Admin ki nayi PDF view load karega
        $pdf = PDF::loadView('backend.super_admin_reports_pdf', $data);
        return $pdf->download('Subscriptions_Report_'.$startDate.'_to_'.$endDate.'.pdf');
    }
}