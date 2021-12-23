<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController
{
    public function show($code)
    {
        $link = Link::whereCode($code)->first();

        return new LinkResource($link);
    }
}
