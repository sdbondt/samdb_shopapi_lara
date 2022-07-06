<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    // create order
    public function store() {
        $order = Order::create([
            'user_id' => request()->user()->id
        ]);
        $totalAmount = 0;
        if(count(request()->user()->products) == 0) {
            return ['msg' => 'No products in cart.'];
        } else {
            foreach(request()->user()->products as $p) {
                global $totalAmount;
                $product = Product::find($p->pivot->product_id);
                $amount = $p->pivot->quantity * $product->price;
                $totalAmount += $amount;
                OrderItem::create([
                    'user_id' => request()->user()->id,
                    'product_id' => $p->pivot->product_id,
                    'order_id' => $order->id,
                    'quantity' => $p->pivot->quantity,
                    'price' => $product->price,
                    'amount' => $amount
                ]);
                request()->user()->products()->detach($p->id);
            }
            $order->update(['amount' => $totalAmount]);
            return ['msg' => new OrderResource($order)];
        }
    }

    public function index() {
        return [
            'orders' => new OrderCollection(request()->user()->orders)
        ];
    }

    public function show(Order $order) {
        return [
            'order' => new OrderResource($order)
        ];
    }
}
