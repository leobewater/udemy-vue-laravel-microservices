<?php

namespace App\Console\Commands;

use App\Jobs\AdminAdded;
use App\Jobs\OrderCompleted;
use App\Jobs\ProductCreated;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Console\Command;

class FireEventCommand extends Command
{
    protected $signature = 'fire';

    protected $description = 'Command description';

    public function handle()
    {
        // dispatch job with a existing order for testing
//        $order = Order::find(43);
//        $data = $order->toArray();
//        $data['admin_total'] = $order->admin_total;
//        $data['influencer_total'] = $order->influencer_total;
//        OrderCompleted::dispatch($data);

        // send event to rabbitmq
        //AdminAdded::dispatch('a@a.com');

        $product = Product::find(1);
        ProductCreated::dispatch($product->toArray())->onQueue('checkout_queue');
    }
}
