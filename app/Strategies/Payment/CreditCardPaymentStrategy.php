<?php

namespace App\Strategies\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CreditCardPaymentStrategy implements IPaymentStrategy
{
    /**
     * Xử lý thanh toán qua thẻ tín dụng
     * 
     * @param array $orderData
     * @param Request $request
     * @return array
     */
    public function processPayment(array $orderData, Request $request): array
    {
        try {
            // Validate required data with detailed error messages
            $missingFields = [];
            if (!isset($orderData['customer_id'])) {
                $missingFields[] = 'customer_id';
            }
            if (!isset($orderData['shipping_id'])) {
                $missingFields[] = 'shipping_id';
            }
            if (!isset($orderData['order_total'])) {
                $missingFields[] = 'order_total';
            }
            
            if (!empty($missingFields)) {
                return [
                    'success' => false,
                    'message' => 'Thiếu thông tin đơn hàng cần thiết: ' . implode(', ', $missingFields)
                ];
            }

            // Tạo payment record trước
            $payment_id = DB::table('tbl_payment')->insertGetId([
                'payment_method' => 'Credit Card',
                'payment_status' => 'Chờ thanh toán',
                'payment_amount' => $orderData['order_total'] ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Tạo đơn hàng trong database với payment_id
            $order_id = DB::table('tbl_order')->insertGetId([
                'customer_id' => $orderData['customer_id'] ?? 0,
                'shipping_id' => $orderData['shipping_id'] ?? null,
                'payment_id' => $payment_id,
                'order_total' => $orderData['order_total'] ?? 0,
                'order_status' => 'Chờ thanh toán',
                'order_code' => 'ORD' . time() . rand(100, 999),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Lưu order details
            $cart = Session::get('cart');
            if ($cart) {
                foreach ($cart as $item) {
                    DB::table('tbl_order_details')->insert([
                        'order_id' => $order_id,
                        'product_id' => $item['product_id'] ?? 0,
                        'product_name' => $item['product_name'] ?? '',
                        'product_price' => $item['product_price'] ?? 0,
                        'product_sales_quanlity' => $item['product_qty'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
            
            return [
                'success' => true,
                'message' => 'Đang xử lý thanh toán qua thẻ tín dụng...',
                'order_id' => $order_id,
                'redirect_url' => route('payment-credit')
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
     * 
     * @return string
     */
    public function getPaymentMethodName(): string
    {
        return 'Thanh toán qua thẻ tín dụng';
    }

    /**
     * Lấy mã phương thức thanh toán
     * 
     * @return int
     */
    public function getPaymentMethodCode(): int
    {
        return 3;
    }

    /**
     * Validate dữ liệu thanh toán
     * 
     * @param Request $request
     * @return bool
     */
    public function validatePaymentData(Request $request): bool
    {
        // Với Credit Card, không cần validate ở đây
        // Validation sẽ được thực hiện ở trang riêng
        return true;
    }
}
