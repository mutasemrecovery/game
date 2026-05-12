<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CharacterConflictNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Order $upcomingOrder,
        public array $conflictingProducts
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $names = implode('، ', $this->conflictingProducts);

        return [
            'type'          => 'character_conflict',
            'order_id'      => $this->upcomingOrder->id,
            'order_number'  => $this->upcomingOrder->number,
            'message'       => 'تنبيه: طلب #' . $this->upcomingOrder->number . ' غداً يحتوي على شخصيات لم تُرجَع بعد: ' . $names,
        ];
    }
}
