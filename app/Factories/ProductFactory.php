<?php

namespace App\Factories;

use App\Interfaces\IProduct;

abstract class ProductFactory
{
    abstract public function createProduct(array $data): IProduct;

    public function create(array $data): IProduct
    {
        return $this->createProduct($data);
    }
} 