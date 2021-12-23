<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Services\UserService;
//use App\Models\User;
//use Illuminate\Contracts\Queue\ShouldQueue;
//use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class UpdateRankingsListener
{
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;

        $revenue = $order->influencer_total;

        $userService = new UserService();
        //$user = User::find($order->user_id);
        $user = $userService->get($order->user_id);

        Redis::zincrby('rankings', $revenue, $user->fullName());
    }
}
