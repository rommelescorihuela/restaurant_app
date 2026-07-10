<?php

namespace App\Notifications;

use App\Models\Table;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ServiceAlert extends Notification
{
    use Queueable;

    public function __construct(
        public Table $table,
        public User $waiter,
        public string $type,
        public ?string $message = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'table_id' => $this->table->id,
            'table_number' => $this->table->number,
            'waiter_id' => $this->waiter->id,
            'waiter_name' => $this->waiter->name,
            'type' => $this->type,
            'message' => $this->message ?? match ($this->type) {
                'help_request' => "Mesa {$this->table->number} necesita ayuda",
                'table_abandoned' => "Mesa {$this->table->number} sin atender por mucho tiempo",
                default => "Alerta en mesa {$this->table->number}",
            },
        ];
    }
}
