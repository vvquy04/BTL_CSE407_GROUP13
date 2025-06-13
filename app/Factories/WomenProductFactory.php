<?php

namespace App\Factories;

use App\Models\WomenProduct;
use App\Interfaces\IProduct;

class WomenProductFactory extends ProductFactory
{
    public function createProduct(array $data): IProduct
    {
        return new WomenProduct($data);
    }
} 