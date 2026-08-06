<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Subscription;
use App\Models\User;
use App\Models\table;
use App\Models\item;
use App\Models\order;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $filter = $request->get('filter', 'week');

        // SUPER ADMIN - sees all restaurants
        if ($user->isSuperAdmin()) {
            return $this->superAdminDashboard($filter);
        }

        // RESTAURANT ADMIN - sees only their restaurant
        if ($user->isRestaurantAdmin()) {
            return $this->restaurantAdminDashboard($filter, $user->id);
        }

        // RESTAURANT USER - sees limited data
        return $this->restaurantUserDashboard($filter, $user->id);
    }

    /**
     * Super Admin Dashboard - All restaurants data
     */
  /*  private function superAdminDashboard($filter)
    {
        $totalUsers       = User::where('role', 'restaurant_admin')->count();
        $totalStaff       = User::where('role', 'restaurant_user')->count();
        $totalOrders      = order::count();
        $totalRevenue     = (float) order::sum('total');
        $todayRevenue     = (float) order::whereDate('created_at', Carbon::today())->sum('total');
        $thisMonthRevenue = (float) order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');
        $pendingOrders    = order::where('status', 'pending')->count();

        // All restaurants with stats
        $restaurants = User::where('role', 'restaurant_admin')
            ->with('subscriptions', 'activeSubscription')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($restaurant) {
                $tables     = table::where('user_id', $restaurant->id)->count();
                $items      = item::where('user_id', $restaurant->id)->count();
                $totalOrders = order::where('user_id', $restaurant->id)->count();
                $revenue    = (float) order::where('user_id', $restaurant->id)->sum('total');
                $todayOrders = order::where('user_id', $restaurant->id)
                    ->whereDate('created_at', Carbon::today())
                    ->count();

                $restaurant->stats = [
                    'tables'       => $tables,
                    'items'        => $items,
                    'total_orders' => $totalOrders,
                    'revenue'      => $revenue,
                    'today_orders' => $todayOrders,
                ];
                return $restaurant;
            });

        // Revenue chart for all restaurants
        $analyticsLabels = [];
        $analyticsRevenue = [];
        $this->getChartData($filter, null, $analyticsLabels, $analyticsRevenue);

        return view('backend.home', compact(
            'totalUsers',
            'totalOrders',
            'totalRevenue',
            'todayRevenue',
            'thisMonthRevenue',
            'pendingOrders',
            'restaurants',
            'analyticsLabels',
            'analyticsRevenue',
            'filter'
        ));
    } */

        /**
     * Super Admin Dashboard - System and Subscription Data
     */
    private function superAdminDashboard($filter)
    {
        // SaaS Metrics
        $totalUsers          = User::where('role', 'restaurant_admin')->count();
        $inactiveRestaurants = User::where('role', 'restaurant_admin')->where('status', 'inactive')->count();
        
        $activeSubscriptions = Subscription::where('status', 'active')
                                           ->where('end_date', '>=', Carbon::today())
                                           ->count();

        // Revenue Metrics (From Subscriptions, not Food Orders)
        $totalRevenue     = (float) Subscription::sum('amount');
        $todayRevenue     = (float) Subscription::whereDate('created_at', Carbon::today())->sum('amount');
        $thisMonthRevenue = (float) Subscription::whereMonth('created_at', Carbon::now()->month)
                                                ->whereYear('created_at', Carbon::now()->year)
                                                ->sum('amount');

        // All restaurants with stats (for the table below the cards)
        $restaurants = User::where('role', 'restaurant_admin')
            ->with('subscriptions')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($restaurant) {
                $tables      = table::where('user_id', $restaurant->id)->count();
                $items       = item::where('user_id', $restaurant->id)->count();
                $totalOrders = order::where('user_id', $restaurant->id)->count();
                $revenue     = (float) order::where('user_id', $restaurant->id)->sum('total');
                $todayOrders = order::where('user_id', $restaurant->id)
                                    ->whereDate('created_at', Carbon::today())
                                    ->count();

                $restaurant->stats = [
                    'tables'       => $tables,
                    'items'        => $items,
                    'total_orders' => $totalOrders,
                    'revenue'      => $revenue,
                    'today_orders' => $todayOrders,
                ];
                return $restaurant;
            });

        // Revenue chart specifically for Subscription Revenue
        $analyticsLabels = [];
        $analyticsRevenue = [];
        $this->getSuperAdminChartData($filter, $analyticsLabels, $analyticsRevenue);

        return view('backend.home', compact(
            'totalUsers',
            'inactiveRestaurants',
            'activeSubscriptions',
            'totalRevenue',
            'todayRevenue',
            'thisMonthRevenue',
            'restaurants',
            'analyticsLabels',
            'analyticsRevenue',
            'filter'
        ));
    }

    /**
     * Restaurant Admin Dashboard - Own restaurant only
     */
    private function restaurantAdminDashboard($filter, $userId)
    {
        $totalOrders      = order::where('user_id', $userId)->count();
        $totalItems       = item::where('user_id', $userId)->count();
        $totalTables      = table::where('user_id', $userId)->count();
        $totalRevenue     = (float) order::where('user_id', $userId)->sum('total');
        $todayRevenue     = (float) order::where('user_id', $userId)->whereDate('created_at', Carbon::today())->sum('total');
        $thisMonthRevenue = (float) order::where('user_id', $userId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');
        $pendingOrders    = order::where('user_id', $userId)->where('status', 'pending')->count();

        // Staff members under this restaurant
        $staffMembers = User::where('parent_id', $userId)
            ->where('role', 'restaurant_user')
            ->count();

        // Chart data
        $analyticsLabels = [];
        $analyticsRevenue = [];
        $this->getChartData($filter, $userId, $analyticsLabels, $analyticsRevenue);

        // Top items
        $topItems = order::with('item')
            ->where('user_id', $userId)
            ->selectRaw('item_id, SUM(quantity) as total_qty')
            ->groupBy('item_id')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $topItemsLabels = $topItems->map(fn($item) => $item->item->name ?? 'Deleted Item')->toArray();
        $topItemsQty = $topItems->pluck('total_qty')->toArray();

        return view('backend.home', compact(
            'totalOrders',
            'totalItems',
            'totalTables',
            'totalRevenue',
            'todayRevenue',
            'thisMonthRevenue',
            'pendingOrders',
            'staffMembers',
            'analyticsLabels',
            'analyticsRevenue',
            'topItems',
            'topItemsLabels',
            'topItemsQty',
            'filter'
        ));
    }

    /**
     * Restaurant User Dashboard - Limited view
     */
    private function restaurantUserDashboard($filter, $userId)
    {
        $totalOrders      = order::where('user_id', $userId)->count();
        $todayRevenue     = (float) order::where('user_id', $userId)->whereDate('created_at', Carbon::today())->sum('total');
        $thisMonthRevenue = (float) order::where('user_id', $userId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');
        $pendingOrders    = order::where('user_id', $userId)->where('status', 'pending')->count();

        // Chart data
        $analyticsLabels = [];
        $analyticsRevenue = [];
        $this->getChartData($filter, $userId, $analyticsLabels, $analyticsRevenue);

        return view('backend.home', compact(
            'totalOrders',
            'todayRevenue',
            'thisMonthRevenue',
            'pendingOrders',
            'analyticsLabels',
            'analyticsRevenue',
            'filter'
        ));
    }

    /**
     * Helper to generate chart data
     */
    private function getChartData($filter, $userId, &$labels, &$revenue)
    {
        $query = order::query();
        if ($userId) {
            $query->where('user_id', $userId);
        }

        switch ($filter) {
            case 'today':
                $labels[]  = Carbon::today()->format('d M');
                $revenue[] = $query->whereDate('created_at', Carbon::today())->sum('total');
                break;

            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[]  = $date->format('d M');
                    $revenue[] = $query->clone()->whereDate('created_at', $date)->sum('total');
                }
                break;

            case 'month':
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[]  = $date->format('d M');
                    $revenue[] = $query->clone()->whereDate('created_at', $date)->sum('total');
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[]  = $date->format('M');
                    $revenue[] = $query->clone()
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('total');
                }
                break;

            case 'all':
                $firstOrder = $query->clone()->orderBy('created_at')->first();
                if ($firstOrder) {
                    $start = Carbon::parse($firstOrder->created_at)->startOfMonth();
                    $end   = Carbon::now()->startOfMonth();
                    while ($start <= $end) {
                        $labels[]  = $start->format('M Y');
                        $revenue[] = $query->clone()
                            ->whereYear('created_at', $start->year)
                            ->whereMonth('created_at', $start->month)
                            ->sum('total');
                        $start->addMonth();
                    }
                }
                break;
        }
    }

    /**
     * Helper to generate chart data for Super Admin (Subscriptions)
     */
    private function getSuperAdminChartData($filter, &$labels, &$revenue)
    {
        $query = Subscription::query();

        switch ($filter) {
            case 'today':
                $labels[]  = Carbon::today()->format('d M');
                $revenue[] = $query->whereDate('created_at', Carbon::today())->sum('amount');
                break;

            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[]  = $date->format('d M');
                    $revenue[] = $query->clone()->whereDate('created_at', $date)->sum('amount');
                }
                break;

            case 'month':
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[]  = $date->format('d M');
                    $revenue[] = $query->clone()->whereDate('created_at', $date)->sum('amount');
                }
                break;

            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[]  = $date->format('M');
                    $revenue[] = $query->clone()
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('amount');
                }
                break;

            case 'all':
                $firstOrder = $query->clone()->orderBy('created_at')->first();
                if ($firstOrder) {
                    $start = Carbon::parse($firstOrder->created_at)->startOfMonth();
                    $end   = Carbon::now()->startOfMonth();
                    while ($start <= $end) {
                        $labels[]  = $start->format('M Y');
                        $revenue[] = $query->clone()
                            ->whereYear('created_at', $start->year)
                            ->whereMonth('created_at', $start->month)
                            ->sum('amount');
                        $start->addMonth();
                    }
                }
                break;
        }
    }
}
