<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
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
            'type'          => 'new_order',
            'order_id'      => $this->order->id,
            'order_number'  => $this->order->number,
            'customer_name' => $this->order->user->name ?? '-',
            'message'       => 'حجز جديد #' . $this->order->number . ' من ' . ($this->order->user->name ?? '-'),
        ];
    }
}
