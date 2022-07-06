<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderProduct extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "productName" => $this->product->name,
            "description" => $this->product->description,
            "quantity" => $this->quantity,
            "price" => $this->price,
            "totalAmount" => $this->amount,
        ];
    }
}
