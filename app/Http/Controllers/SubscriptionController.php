<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Show all subscriptions for a specific restaurant user
     */
    public function index($userId)
    {
        $restaurant    = User::where('role', 'restaurant')->findOrFail($userId);
        $subscriptions = Subscription::where('user_id', $userId)
            ->orderByDesc('start_date')
            ->get();

        // Update expired subscriptions automatically
        $this->autoExpire($userId);

        // Re-fetch after update
        $subscriptions = Subscription::where('user_id', $userId)
            ->orderByDesc('start_date')
            ->get();

        $activeSubscription = $subscriptions->where('status', 'active')
            ->where('end_date', '>=', Carbon::today())
            ->first();

        return view('backend.subscription.index', compact('restaurant', 'subscriptions', 'activeSubscription'));
    }

    /**
     * Show create form
     */
    public function create($userId)
    {
        $restaurant = User::where('role', 'restaurant')->findOrFail($userId);
        return view('backend.subscription.create', compact('restaurant'));
    }

    /**
     * Store a new subscription
     */
    public function store(Request $request, $userId)
    {
        $request->validate([
            'start_date' => 'required|date',
            'months'     => 'required|integer|min:1|max:60',
            'amount'     => 'required|numeric|min:0',
            'notes'      => 'nullable|string|max:500',
        ]);

        $restaurant = User::where('role', 'restaurant')->findOrFail($userId);

        $startDate = Carbon::parse($request->start_date);
        $months = (int) $request->months;
        $endDate   = $startDate->copy()->addMonths($months)->subDay();
        // Create subscription
        Subscription::create([
            'user_id'    => $userId,
            'admin_id'   => auth()->id(),
            'start_date' => $startDate->toDateString(),
            'end_date'   => $endDate->toDateString(),
            'months'     => $months,
            'amount'     => $request->amount,
            'notes'      => $request->notes,
            'status'     => 'active',
        ]);

        // Activate the user automatically
        $restaurant->update(['status' => 'active']);

        return redirect()
            ->route('subscriptions.index', $userId)
            ->with('success', "Subscription added successfully! {$restaurant->bussiness_name} is now active until {$endDate->format('d M Y')}.");
    }

    /**
     * Delete a subscription
     */
    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $userId = $subscription->user_id;
        $subscription->delete();

        // If no more active subscriptions, deactivate user
        $hasActive = Subscription::where('user_id', $userId)
            ->where('status', 'active')
            ->where('end_date', '>=', Carbon::today())
            ->exists();

        if (!$hasActive) {
            User::find($userId)?->update(['status' => 'inactive']);
        }

        return redirect()
            ->route('subscriptions.index', $userId)
            ->with('success', 'Subscription deleted successfully.');
    }

    /**
     * Auto-expire subscriptions that are past their end date
     */
    public static function autoExpire($userId = null)
    {
        $query = Subscription::where('status', 'active')
            ->where('end_date', '<', Carbon::today());

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $expired = $query->get();

        foreach ($expired as $sub) {
            $sub->update(['status' => 'expired']);

            // Check if user has any other active subscription
            $hasActive = Subscription::where('user_id', $sub->user_id)
                ->where('status', 'active')
                ->where('end_date', '>=', Carbon::today())
                ->exists();

            if (!$hasActive) {
                User::find($sub->user_id)?->update(['status' => 'inactive']);
            }
        }
    }
}
