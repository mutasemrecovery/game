<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Order;
use App\Notifications\UnreturnedOrderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NotifyUnreturnedOrders extends Command
{
    protected $signature   = 'orders:notify-unreturned';
    protected $description = 'Send hourly reminders for executed orders whose characters have not been returned yet (runs from 8 AM)';

    public function handle(): void
    {
        if (Carbon::now()->hour < 8) {
            return;
        }

        // Orders that were executed and whose date was yesterday or earlier (should have been returned by now)
        $orders = Order::with('user')
            ->where('order_status', 6)
            ->whereDate('date', '<=', Carbon::yesterday()->toDateString())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No unreturned orders found.');
            return;
        }

        $admins = Admin::all();

        foreach ($orders as $order) {
            $alreadyNotified = DB::table('notifications')
                ->where('notifiable_type', Admin::class)
                ->where('type', UnreturnedOrderNotification::class)
                ->whereNull('read_at')
                ->where('created_at', '>=', Carbon::now()->subMinutes(50))
                ->whereRaw("JSON_EXTRACT(data, '$.order_id') = ?", [$order->id])
                ->exists();

            if ($alreadyNotified) {
                continue;
            }

            foreach ($admins as $admin) {
                $admin->notify(new UnreturnedOrderNotification($order));
            }
        }

        $this->info("Processed {$orders->count()} unreturned order(s).");
    }
}
