<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Notifications\CharacterConflictNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyCharacterConflicts extends Command
{
    protected $signature   = 'orders:notify-conflicts';
    protected $description = 'Send conflict warnings at 6 PM for tomorrow\'s orders containing unreturned characters';

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $tomorrowOrders = Order::with(['orderProducts.product'])
            ->whereDate('date', $tomorrow)
            ->whereNotIn('order_status', [3, 7])
            ->get();

        if ($tomorrowOrders->isEmpty()) {
            $this->info('No upcoming orders tomorrow.');
            return;
        }

        $admins  = Admin::all();
        $notified = 0;

        foreach ($tomorrowOrders as $order) {
            $productIds = $order->orderProducts->pluck('product_id')->toArray();

            // Find products that are in other active (not cancelled/returned) orders with date <= today
            $conflictProductIds = OrderProduct::whereIn('product_id', $productIds)
                ->where('order_id', '!=', $order->id)
                ->whereHas('order', function ($q) {
                    $q->whereNotIn('order_status', [3, 7])
                      ->whereDate('date', '<=', Carbon::today()->toDateString());
                })
                ->pluck('product_id')
                ->unique()
                ->toArray();

            if (empty($conflictProductIds)) {
                continue;
            }

            $conflictNames = $order->orderProducts
                ->whereIn('product_id', $conflictProductIds)
                ->pluck('product.name_ar')
                ->filter()
                ->values()
                ->toArray();

            foreach ($admins as $admin) {
                $admin->notify(new CharacterConflictNotification($order, $conflictNames));
            }

            $notified++;
        }

        $this->info("Conflict notifications sent for {$notified} order(s).");
    }
}
