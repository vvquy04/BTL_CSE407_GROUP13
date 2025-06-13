<?php

namespace App\Strategies\Payment;

use Illuminate\Http\Request;

interface IPaymentStrategy
{
    /**
     * Xử lý thanh toán
     * 
     * @param array $orderData
     * @param Request $request
     * @return array
     */
    public function processPayment(array $orderData, Request $request): array;

    /**
     * Lấy tên phương thức thanh toán
     * 
     * @return string
     */
    public function getPaymentMethodName(): string;

    /**
     * Lấy mã phương thức thanh toán
     * 
     * @return int
     */
    public function getPaymentMethodCode(): int;

    /**
     * Validate dữ liệu thanh toán
     * 
     * @param Request $request
     * @return bool
     */
    public function validatePaymentData(Request $request): bool;
}
