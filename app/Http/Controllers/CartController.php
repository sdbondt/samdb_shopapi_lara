<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartCollection;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart(Product $product) {
        request()->validate([
            'quantity' => ['sometimes', 'numeric', 'min:-100', 'max:100']
        ]);
        $quantity = request('quantity') ? request('quantity') : 1;
        $cartItemExists = request()->user()->products()->where('product_id', $product->id)->exists();

        if($cartItemExists) {
            foreach(request()->user()->products as $p) {
                if($p->id == $product->id) {
                    //increment met pos of neg getal
                    //indien increment met neg getal: totaal > 0 = gewoon updaten, anders pivot->quantity 1 zetten door te verminderen met zichzelf en + 1
                    $p->pivot->increment('quantity',  $p->pivot->quantity + $quantity >= 1 ? $quantity : -$p->pivot->quantity + 1);
                }
            }
            return ['msg' => 'Cart Item got updated.'];
        } else {
            request()->user()->products()->attach($product->id, ['quantity' => $quantity]);
            return [
                'msg' => 'Product got added to cart.',
            ];
        }
    }

    public function updateCartItem(Product $product) {
        request()->validate([
            'quantity' => ['sometimes', 'numeric', 'min:-100', 'max:100']
        ]);
        $quantity = request('quantity') ? request('quantity') : 1;
        foreach(request()->user()->products as $p) {
            if($p->id == $product->id) {
                $p->pivot->increment('quantity', $p->pivot->quantity + $quantity >= 1 ? $quantity : -$p->pivot->quantity + 1);
            }
        }
        return ['msg' => 'Cart Item got updated.'];
        // request()->user()->products()->updateExistingPivot($product->id, ['quantity' => $quantity]);
        // return [
        //     'msg' => 'Product in cart got updated.'
        // ];
    }

    public function removeFromCart(Product $product) {
        request()->user()->products()->detach($product->id);
        return [
            'msg' => 'Product got removed from cart.'
        ];
    }

    public function removeAllFromCart() {
        $ids = request()->user()->products->pluck('id');
        request()->user()->products()->detach($ids);
        return [
            'msg' => 'Cart cleared.'
        ];
    }

    public function index() {
        return [
            'cart' => new CartCollection(request()->user()->products)
        ];
    }
}
