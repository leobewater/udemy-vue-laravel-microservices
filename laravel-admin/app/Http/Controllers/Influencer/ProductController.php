<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Cache;
use Illuminate\Http\Request;
use Str;

class ProductController
{
    public function index(Request $request)
    {
        $products = Cache::remember('products', 60 * 30, function () use ($request) {
            sleep(2);
            return Product::all();
        });

        // search within the cached query (faster)
        if ($s = $request->input('s')) {
            $products = $products->filter(function (Product $product) use ($s) {
                return Str::contains($product->title, $s) || Str::contains($product->description, $s);
            });
        }

        return ProductResource::collection($products);


        // not working for search
//        return Cache::remember('products', 60*30, function () use ($request) {
//            sleep(2);
//            $query = Product::query();
//
//            if ($s = $request->input('s')) {
//                $query->whereRaw("title LIKE '%$s%'")
//                    ->orWhereRaw("description LIKE '%$s%'");;
//            }
//
//            return ProductResource::collection($query->get());
//        });

    }
}
