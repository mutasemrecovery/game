<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UnreturnedOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type'          => 'unreturned_order',
            'order_id'      => $this->order->id,
            'order_number'  => $this->order->number,
            'customer_name' => $this->order->user->name ?? '-',
            'message'       => 'تذكير: طلب #' . $this->order->number . ' — لم يتم إرجاع الشخصيات بعد',
        ];
    }
}
