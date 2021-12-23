<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use App\Jobs\LinkCreated;
use App\Models\Link;
use App\Models\LinkProduct;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController
{
    private $userService;

    public function __constructor(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(Request $request)
    {
        $user = $this->userService->getUser();

        $link = Link::create([
            'user_id' => $user->id,
            'code' => Str::random(6)
        ]);

        $linkProducts = [];

        foreach($request->input('products') as $product_id) {
           $linkProduct = LinkProduct::create([
                'link_id' => $link->id,
                'product_id' => $product_id
            ]);

            $linkProducts[] = $linkProduct->toArray();
        }

        LinkCreated::dispatch($link, $linkProducts)->onQueue('checkout_queue');

        return new LinkResource($link);
    }
}
