<?php

namespace App\Strategies\Shipping;

use Illuminate\Http\Request;

interface IShippingStrategy
{
    /**
     * Xử lý vận chuyển
     * @param array $orderData Thông tin đơn hàng
     * @param Request $request Request từ form
     * @return array Kết quả xử lý
     */
    public function processShipping(array $orderData, Request $request): array;

    /**
     * Lấy tên phương thức vận chuyển
     * @return string
     */
    public function getShippingMethodName(): string;

    /**
     * Lấy mã phương thức vận chuyển
     * @return int
     */
    public function getShippingMethodCode(): int;

    /**
     * Validate dữ liệu vận chuyển
     * @param Request $request
     * @return bool
     */
    public function validateShippingData(Request $request): bool;
} 