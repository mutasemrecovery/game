<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Order;
use App\Notifications\UnexecutedOrderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyUnexecutedOrders extends Command
{
    protected $signature   = 'orders:notify-unexecuted';
    protected $description = 'Send hourly reminders for orders that have not been executed yet (runs from 8 AM)';

    public function handle(): void
    {
        if (Carbon::now()->hour < 8) {
            return;
        }

        // Orders whose date has passed but are still not executed, cancelled, or returned
        $orders = Order::with('user')
            ->whereDate('date', '<=', Carbon::today()->toDateString())
            ->whereNotIn('order_status', [3, 6, 7])
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No unexecuted orders found.');
            return;
        }

        $admins = Admin::all();

        foreach ($orders as $order) {
            // Skip if an unread notification for this order was sent in the last 50 minutes
            $alreadyNotified = \DB::table('notifications')
                ->where('notifiable_type', Admin::class)
                ->where('type', UnexecutedOrderNotification::class)
                ->whereNull('read_at')
                ->where('created_at', '>=', Carbon::now()->subMinutes(50))
                ->whereRaw("JSON_EXTRACT(data, '$.order_id') = ?", [$order->id])
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            foreach ($admins as $admin) {
                $admin->notify(new UnexecutedOrderNotification($order));
            }
        }

        $this->info("Processed {$orders->count()} unexecuted order(s).");
    }
}
