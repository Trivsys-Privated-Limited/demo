<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\table;
use App\Models\item;
use App\Models\order;

class dashboardController extends Controller
{
   /* public function index() */

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'week');
        $totalUsers = User::count();
        $totalTables = table::count();
        $totalItems = item::count();
        $totalOrders = order::count();

       /* $analyticsLabels = [];
        $analyticsRevenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $analyticsLabels[] = Carbon::parse($date)->translatedFormat('M Y');
            $analyticsRevenue[] = (float) order::whereDate('created_at', $date)->sum('total');
        } */

        /* $analyticsLabels = [];
        $analyticsRevenue = [];

       for ($i = 11; $i >= 0; $i--) {

       $date = Carbon::now()->subMonths($i);

       $analyticsLabels[] = $date->format('M Y');

      $analyticsRevenue[] = (float) order::whereYear('created_at', $date->year)
        ->whereMonth('created_at', $date->month)
        ->sum('total');
      } */

        /*  Testing  Purpose start */

        $analyticsLabels = [];
$analyticsRevenue = [];

switch ($filter) {

    case 'today':

        $analyticsLabels[] = Carbon::today()->format('d M');

        $analyticsRevenue[] = order::whereDate('created_at', Carbon::today())
            ->sum('total');

        break;


    case 'week':

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $analyticsLabels[] = $date->format('d M');

            $analyticsRevenue[] = order::whereDate('created_at', $date)
                ->sum('total');
        }

        break;


    case 'month':

        for ($i = 29; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $analyticsLabels[] = $date->format('d M');

            $analyticsRevenue[] = order::whereDate('created_at', $date)
                ->sum('total');
        }

        break;


    case 'year':

        for ($i = 11; $i >= 0; $i--) {

            $date = Carbon::now()->subMonths($i);

            $analyticsLabels[] = $date->format('M');

            $analyticsRevenue[] = order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total');
        }

        break;


    case 'all':

        $firstOrder = order::orderBy('created_at')->first();

        if ($firstOrder) {

            $start = Carbon::parse($firstOrder->created_at)->startOfMonth();
            $end = Carbon::now()->startOfMonth();

            while ($start <= $end) {

                $analyticsLabels[] = $start->format('M Y');

                $analyticsRevenue[] = order::whereYear('created_at', $start->year)
                    ->whereMonth('created_at', $start->month)
                    ->sum('total');

                $start->addMonth();
            }
        }

        break;
}

        /* Testing Purpose End */


        $todayRevenue = (float) order::whereDate('created_at', Carbon::today())->sum('total');
        $thisMonthRevenue = (float) order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');
        $pendingOrders = order::where('status', 'pending')->count();

        $topItems = order::with('item')
            ->selectRaw('item_id, SUM(quantity) as total_qty')
            ->groupBy('item_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $topItemsLabels = $topItems->map(function ($itemData) {
            return $itemData->item->name ?? 'Deleted Item';
        })->toArray();

        $topItemsQty = $topItems->pluck('total_qty')->toArray();

        return view('backend.home', compact(
            'totalUsers',
            'totalTables',
            'totalItems',
            'totalOrders',
            'analyticsLabels',
            'analyticsRevenue',
            'todayRevenue',
            'thisMonthRevenue',
            'pendingOrders',
            'topItems',
            'topItemsLabels',
            'topItemsQty',
            'filter'
        ));
    }
}
