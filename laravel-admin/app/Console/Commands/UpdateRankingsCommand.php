<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class UpdateRankingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rankings';

    protected $description = "(Custom) - Send orders' influencer revenue to redis";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::where('is_influencer', 1)->get();

        $users->each(function(User $user) {
            $orders = Order::where('user_id', $user->id)->where('complete', 1)->get();

            // get total revenue from influencer's orders
            $revenue = $orders->sum(function(Order $order){
                return $order->influencer_total;
            });

            // add influencer name and revenue to redis
            Redis::zadd('rankings', $revenue, $user->fullName());
        });
    }
}
