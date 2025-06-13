<?php

namespace App\Factories;

use App\Models\MenProduct;
use App\Interfaces\IProduct;

class MenProductFactory extends ProductFactory
{
    public function createProduct(array $data): IProduct
    {
        return new MenProduct($data);
    }
} 