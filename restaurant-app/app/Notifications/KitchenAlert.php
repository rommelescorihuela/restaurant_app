<?php

namespace App\Notifications;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KitchenAlert extends Notification
{
    use Queueable;

    public function __construct(
        public string $type,
        public string $message,
        public ?OrderItem $item = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'order_item_id' => $this->item?->id,
            'dish_name' => $this->item?->dish?->name,
            'table_number' => $this->item?->order?->table?->number,
        ];
    }
}
