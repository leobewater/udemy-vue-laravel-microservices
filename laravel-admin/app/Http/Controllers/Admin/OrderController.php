<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\UserService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OrderController
{
    private $userService;

    public function __constructor(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $this->userService->allows('view', 'orders');

        $orders = Order::paginate();
        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        $this->userService->allows('view', 'orders');

        return new OrderResource(Order::find($id));
    }

    public function export()
    {
        $this->userService->allows('view', 'orders');

        $headers =[
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=orders.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() {
            $orders = Order::all();
            $file = fopen('php://output', 'w');

            // header row
            fputcsv($file, ['ID', 'Name', 'Email', 'Product Title', 'Price', 'Quantity']);

            // body
            foreach($orders as $order) {
                fputcsv($file, [$order->id, $order->name, $order->email, '', '', '']);
                foreach($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
