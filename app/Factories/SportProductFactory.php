<?php

namespace App\Factories;

use App\Models\SportProduct;
use App\Interfaces\IProduct;

class SportProductFactory extends ProductFactory
{
    public function createProduct(array $data): IProduct
    {
        return new SportProduct($data);
    }
} 