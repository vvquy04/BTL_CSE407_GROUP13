<?php

namespace App\Strategies\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CreditCardPaymentStrategy implements IPaymentStrategy
{
    /**
     * Xử lý thanh toán qua thẻ tín dụng
     */
    public function processPayment(array $orderData, Request $request): array
    {
        try {
            return [
                'success' => true,
                'message' => 'Đang xử lý thanh toán qua thẻ tín dụng...',
                'redirect' => 'payment_credit',
                'order_info' => $orderData
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý thanh toán thẻ tín dụng: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy tên phương thức thanh toán
     */
    public function getPaymentMethodName(): string
    {
        return 'Thanh toán qua thẻ tín dụng';
    }

    /**
     * Lấy mã phương thức thanh toán
     */
    public function getPaymentMethodCode(): int
    {
        return 3;
    }

    /**
     * Validate dữ liệu thanh toán
     */
    public function validatePaymentData(Request $request): bool
    {
        return true;
    }
}
