<?php

namespace App\Http\Controllers;

use App\Models\order;
use App\Models\table;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Custom dates for the custom filter at the bottom
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        // Optional table filter
        $selectedTable = $request->input('table_id', null);

        // Global Totals
        // Today
        $todayTotal = order::whereDate('created_at', Carbon::today())->sum('total');
        $todayOrders = order::whereDate('created_at', Carbon::today())->distinct('order_number')->count();

        // This Month
        $thisMonthTotal = order::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->sum('total');
        $thisMonthOrders = order::whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year)
                                 ->distinct('order_number')->count();

        // Table wise calculations for Daily vs Monthly
        // Keep full list for the select dropdown
        $allTables = table::all();

        // Apply optional table filter when computing reports
        $tables = table::when($selectedTable, function ($q, $val) {
            $q->where('id', $val);
        })->get();
        $tableReports = [];
        $totalRangeOrders = 0;
        $totalRangeRevenue = 0;

        foreach ($tables as $table) {
            // Daily
            $dailyRevenue = order::where('table_id', $table->id)
                                ->whereDate('created_at', Carbon::today())
                                ->sum('total');
            $dailyOrdersCount = order::where('table_id', $table->id)
                                    ->whereDate('created_at', Carbon::today())
                                    ->distinct('order_number')->count();

            // Monthly
            $monthlyRevenue = order::where('table_id', $table->id)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->sum('total');
            $monthlyOrdersCount = order::where('table_id', $table->id)
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->distinct('order_number')->count();

            // Custom Range (if they use the form)
            $rangeRevenue = order::where('table_id', $table->id)
                                    ->whereDate('created_at', '>=', $startDate)
                                    ->whereDate('created_at', '<=', $endDate)
                                    ->sum('total');
            $rangeOrdersCount = order::where('table_id', $table->id)
                                        ->whereDate('created_at', '>=', $startDate)
                                        ->whereDate('created_at', '<=', $endDate)
                                        ->distinct('order_number')->count();

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

    public function download(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        $selectedTable = $request->input('table_id', null);

        // Global Totals
        $todayTotal = order::whereDate('created_at', Carbon::today())->sum('total');
        $todayOrders = order::whereDate('created_at', Carbon::today())->distinct('order_number')->count();

        $thisMonthTotal = order::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->sum('total');
        $thisMonthOrders = order::whereMonth('created_at', Carbon::now()->month)
                                 ->whereYear('created_at', Carbon::now()->year)
                                 ->distinct('order_number')->count();

        // Table wise calculations for Daily vs Monthly
        $tables = table::when($selectedTable, function ($q, $val) {
            $q->where('id', $val);
        })->get();
        $tableReports = [];
        $totalRangeOrders = 0;
        $totalRangeRevenue = 0;

        foreach ($tables as $table) {
            $dailyRevenue = order::where('table_id', $table->id)->whereDate('created_at', Carbon::today())->sum('total');
            $dailyOrdersCount = order::where('table_id', $table->id)->whereDate('created_at', Carbon::today())->distinct('order_number')->count();

            $monthlyRevenue = order::where('table_id', $table->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->sum('total');
            $monthlyOrdersCount = order::where('table_id', $table->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->distinct('order_number')->count();

            $rangeRevenue = order::where('table_id', $table->id)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->sum('total');
            $rangeOrdersCount = order::where('table_id', $table->id)->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->distinct('order_number')->count();

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

        $pdf = PDF::loadView('backend.reports_pdf', compact(
            'todayTotal', 'todayOrders', 
            'thisMonthTotal', 'thisMonthOrders', 
            'tableReports', 
            'startDate', 'endDate',
            'totalRangeOrders', 'totalRangeRevenue'
        ));

        return $pdf->download("Sales_Statement_{$startDate}_to_{$endDate}.pdf");
    }
}
