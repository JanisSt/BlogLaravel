<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends Controller
{
    public function show(Product $product)
    {
        $product->load('deliveries');

        return view('products.show', [
            'product' => $product
        ]);
    }
}
