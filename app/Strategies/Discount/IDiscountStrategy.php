<?php

namespace App\Strategies\Discount;

interface IDiscountStrategy
{
    /**
     * Kiểm tra và tính toán discount
     * @param object $order Đơn hàng cần kiểm tra
     * @return array ['applicable' => bool, 'amount' => float, 'description' => string, 'type' => string]
     */
    public function processDiscount($order): array;
}
