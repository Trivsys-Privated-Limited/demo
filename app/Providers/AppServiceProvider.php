<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\ContactMessage;
use App\Models\order; // Aapke OrderController ke mutabiq small 'o'

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Har blade view ke sath navbar data share karne ke liye
        View::composer('*', function ($view) {
            
            // Default values
            $navMessages = collect();
            $unreadMessagesCount = 0;
            $pendingOrdersCount = 0;
            $totalNotifications = 0;

            if (auth()->check()) {
                $userId = auth()->id();
                $userRole = auth()->user()->role;

                try {
                    // Safety check 1: Agar contact_messages table majood hai
                    if (Schema::hasTable('contact_messages')) {
                        $navMessages = ContactMessage::where('receiver_id', $userId)
                            ->where('is_read', 0)
                            ->with('sender')
                            ->orderByDesc('created_at')
                            ->take(3)
                            ->get();

                        $unreadMessagesCount = ContactMessage::where('receiver_id', $userId)
                            ->where('is_read', 0)
                            ->count();
                    }

                    // Safety check 2: Agar orders table majood hai
                    if (Schema::hasTable('orders')) {
                        if ($userRole === 'super_admin') {
                            $pendingOrdersCount = order::where('status', 'pending')->count();
                        } 
                        elseif (in_array($userRole, ['restaurant_admin', 'restaurant_user'])) {
                            $pendingOrdersCount = order::where('user_id', $userId)
                                ->where('status', 'pending')
                                ->count();
                        }
                    }

                    $totalNotifications = $pendingOrdersCount;

                } catch (\Exception $e) {
                    // Agar database ka koi masla aaye toh layout crash hone se bach jayega
                }
            }

            // Views ko variables pass karein
            $view->with([
                'navMessages'         => $navMessages,
                'unreadMessagesCount' => $unreadMessagesCount,
                'pendingOrdersCount'  => $pendingOrdersCount,
                'totalNotifications'  => $totalNotifications
            ]);
        });
    }
}