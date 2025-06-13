<?php

namespace App\Strategies\Shipping;

use Illuminate\Http\Request;

class ShippingContext
{
    private IShippingStrategy $shippingStrategy;

    public function setShippingStrategy(IShippingStrategy $strategy): void
    {
        $this->shippingStrategy = $strategy;
    }

    public function executeShipping(array $orderData, Request $request): array
    {
        return $this->shippingStrategy->processShipping($orderData, $request);
    }

    public function getShippingMethodName(): string
    {
        return $this->shippingStrategy->getShippingMethodName();
    }

    public function getShippingMethodCode(): int
    {
        return $this->shippingStrategy->getShippingMethodCode();
    }
} 