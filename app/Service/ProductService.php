<?php

namespace App\Service;

use App\Models\Product;

class ProductService
{

    public function storeProducts($data)
    {
        $products = json_decode(file_get_contents($data['products-file']), true);

        foreach ($products as $product) {
            Product::updateOrCreate($product);
        }
    }
}
