<?php

namespace App\Factories;

use App\Models\SmartProduct;
use App\Interfaces\IProduct;

class SmartProductFactory extends ProductFactory
{
    public function createProduct(array $data): IProduct
    {
        return new SmartProduct($data);
    }
} 