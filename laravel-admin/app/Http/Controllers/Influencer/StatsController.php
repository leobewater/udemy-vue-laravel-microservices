<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Order;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class StatsController
{
    private $userService;

    public function __constructor(UserService $userService)
    {
        $this->userService = $userService;
    }

    // get influencer's code stats and group revenue by influencer's code
    public function index(Request $request)
    {
        //$user = $request->user();
        $user = $this->userService->getUser();

        $links = Link::where('user_id', $user->id)->get();

        return $links->map(function (Link $link) {
            $orders = Order::where('code', $link->code)->where('complete', 1)->get();
            return [
                'code' => $link->code,
                'count' => $orders->count(),
                'revenue' => $orders->sum(function(Order $order){
                    return $order->influencer_total;
                })
            ];
        });
    }

    public function rankings()
    {
        // sort our ranking DESC withscores
        return Redis::zrevrange('rankings', 0, -1, true);
    }
}
