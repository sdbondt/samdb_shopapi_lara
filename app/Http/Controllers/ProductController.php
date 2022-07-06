<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function store() {
        $this->authorize('create', Product::class);
        $attr = request()->validate([
            'name' => ['required', 'max:255', Rule::unique('products', 'name')],
            'price' => ['required', 'numeric'],
            'description' => 'required'
        ]);

        $attr['admin_id'] = request()->user()->id;
        $attr['slug'] = Str::slug($attr['name']);

        $product = Product::create($attr);
        return [
            'product' => new ProductResource($product)
        ];

    }

    public function update(Product $product) {
        $this->authorize('update', [Product::class, $product]);
        $attr = request()->validate([
            'name' => ['sometimes', 'required', Rule::unique('products', 'name')->ignore($product->id)],
            'price' => ['sometimes', 'required', 'numeric'],
            'description' => ['sometimes', 'required']
        ]);

        if($attr['name'] ?? false) {
            $attr['slug'] = Str::slug($attr['name']);
        }
        $product->update($attr);
        return [
            'product' => new ProductResource($product)
        ];
    }

    public function destroy(Product $product) {
        $this->authorize('delete', [Product::class, $product]);
        $product->delete();
        return [
            'msg' => 'Product deleted'
        ];
    }

    public function index() {
        $products = Product::latest()->filter(request(['q', 'price', 'operator']))->paginate(10);
        return [
            'products' => new ProductCollection($products)
        ];
    }

    public function show(Product $product) {
        return [
            'product' => new ProductResource($product)
        ];
    }

    
}
