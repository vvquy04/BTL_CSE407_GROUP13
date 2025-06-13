<?php

namespace App\Strategies\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

class CashOnDeliveryStrategy implements IPaymentStrategy
{
    /**
     * Xử lý thanh toán khi nhận hàng
     * 
     * @param array $orderData
     * @param Request $request
     * @return array
     */    public function processPayment(array $orderData, Request $request): array
    {
        try {
            // Không xóa session ở đây vì cần dữ liệu cho view
            // Session sẽ được xóa sau khi hoàn tất
            
            return [
                'success' => true,
                'message' => 'Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.',
                'redirect' => 'payment_cod',
                'order_info' => $orderData
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý đơn hàng: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy tên phương thức thanh toán
     * 
     * @return string
     */
    public function getPaymentMethodName(): string
    {
        return 'Thanh toán khi nhận hàng';
    }

    /**
     * Lấy mã phương thức thanh toán
     * 
     * @return int
     */
    public function getPaymentMethodCode(): int
    {
        return 2;
    }

    /**
     * Validate dữ liệu thanh toán
     * 
     * @param Request $request
     * @return bool
     */
    public function validatePaymentData(Request $request): bool
    {
        // Với thanh toán khi nhận hàng, không cần validate thêm gì
        return true;
    }
}
