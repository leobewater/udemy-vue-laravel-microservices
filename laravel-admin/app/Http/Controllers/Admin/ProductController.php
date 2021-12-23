<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductUpdatedEvent;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\ProductCreated;
use App\Jobs\ProductDeleted;
use App\Jobs\ProductUpdated;
use App\Models\Product;
use App\Services\UserService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController
{
    private $userService;

    public function __constructor(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $this->userService->allows('view', 'products');

        $products = Product::paginate();
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $this->userService->allows('view', 'products');

        return new ProductResource(Product::find($id));
    }

    public function store(ProductCreateRequest $request)
    {
        $this->userService->allows('edit', 'products');

        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        // dispatch event for refreshing the cache
        event(new ProductUpdatedEvent($product));

        // dispatch to queue and specify the queue name
        ProductCreated::dispatch($product->toArray())->onQueue('checkout_queue');

//        $file = $request->file('image');
//        $name = Str::random(10);
//        $url = Storage::putFileAs('images', $file, $name . '.'. $file->extension());
//
//        $product = Product::create([
//            'title' => $request->input('title'),
//            'description' => $request->input('description'),
//            'image' => env('APP_URL') . '/' . $url,
//            'price' => $request->input('price'),
//        ]);

        return response($product, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $this->userService->allows('edit', 'products');

        $product = Product::find($id);

        $product->update($request->only('title', 'description', 'image', 'price'));

        // dispatch event for refreshing the cache
        event(new ProductUpdatedEvent($product));

        ProductUpdated::dispatch($product->toArray())->onQueue('checkout_queue');

        return response($product, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $this->userService->allows('edit', 'products');

        Product::destroy($id);

        ProductDeleted::dispatch($id)->onQueue('checkout_queue');

        return response(null, Response::HTTP_NO_CONTENT);
    }

}
