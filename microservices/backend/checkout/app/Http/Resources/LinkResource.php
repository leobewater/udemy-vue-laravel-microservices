<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Microservices\UserService;

class LinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'user' => (new UserService())->get($this->user_id), // new UserResource($this->user),
            'products' => ProductResource::collection($this->products)
        ];
    }
}
