<?php

namespace App\Strategies\Discount;

class DiscountContext
{
    /** @var IDiscountStrategy */
    private $discountStrategy;

    /**
     * Đặt strategy giảm giá hiện tại
     */
    public function setDiscountStrategy(IDiscountStrategy $discountStrategy)
    {
        $this->discountStrategy = $discountStrategy;
    }

    /**
     * Tính discount bằng strategy hiện tại
     */
    public function calculateDiscount($order): array
    {
        if (!$this->discountStrategy) {
            throw new \Exception('No discount strategy set!');
        }
        return $this->discountStrategy->processDiscount($order);
    }
} 