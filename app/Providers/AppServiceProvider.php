<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\ContactMessage;
use App\Models\order; // Model check karlein small 'o' hy ya capital 'O'

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Isko khali chordein
    }

    public function boot(): void
    {
        // YAHAN PAR APNA CODE LAZMI CHECK KAREIN
        View::composer('*', function ($view) {
            
            $navMessages = collect();
            $unreadMessagesCount = 0;
            $pendingOrdersCount = 0;
            $totalNotifications = 0;

            if (auth()->check()) {
                $userId = auth()->id();
                $userRole = auth()->user()->role;

                try {
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
                    // Fail-safe
                }
            }

            // Variables ko share karna
            $view->with([
                'navMessages'         => $navMessages,
                'unreadMessagesCount' => $unreadMessagesCount,
                'pendingOrdersCount'  => $pendingOrdersCount,
                'totalNotifications'  => $totalNotifications
            ]);
        });
    }
}