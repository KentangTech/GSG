<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\Notification as ModelsNotification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ActivityNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;

        $notification = ModelsNotification::create([
            'title' => $data['title'],
            'type' => $data['type'] ?? 'Info',
            'icon' => $data['icon'] ?? 'fa-bell',
            'bg' => $data['bg'] ?? 'primary',
            'user_id' => $data['user_id'] ?? Auth::id(),
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('activity');
    }

    public function broadcastAs()
    {
        return 'activity.created';
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}
