<?php

namespace App\Strategies\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Gloudemans\Shoppingcart\Facades\Cart;

class PaymentContext
{
    private IPaymentStrategy $paymentStrategy;

    /**
     * Thiết lập chiến lược thanh toán
     * 
     * @param IPaymentStrategy $strategy
     */
    public function setPaymentStrategy(IPaymentStrategy $strategy): void
    {
        $this->paymentStrategy = $strategy;
    }

    /**
     * Thực hiện thanh toán
     * 
     * @param array $orderData
     * @param Request $request
     * @return array
     */
    public function executePayment(array $orderData, Request $request): array
    {
        if (!isset($this->paymentStrategy)) {
            throw new \Exception('Payment strategy not set');
        }

        // Validate dữ liệu thanh toán
        if (!$this->paymentStrategy->validatePaymentData($request)) {
            return [
                'success' => false,
                'message' => 'Dữ liệu thanh toán không hợp lệ'
            ];
        }

        // Xử lý thanh toán theo strategy trước
        $paymentResult = $this->paymentStrategy->processPayment($orderData, $request);

        // Xóa session giỏ hàng sau khi tạo đơn hàng thành công
        if ($paymentResult['success']) {
            Session::forget(['cart', 'coupon', 'fee']);
        }

        return $paymentResult;
    }

    /**
     * Tạo order
     * 
     * @param array $orderData
     * @return int
     */
    private function createOrder(array $orderData): int
    {
        $data = [
            'customer_id' => $orderData['customer_id'],
            'shipping_id' => $orderData['shipping_id'],
            'payment_id' => $orderData['payment_id'],
            'order_total' => $orderData['order_total'],
            'order_status' => 'Đang chờ xử lý',
            'order_code' => 'ORD' . time() . rand(100, 999),
            'created_at' => now(),
            'updated_at' => now()
        ];

        return DB::table('tbl_order')->insertGetId($data);
    }

    /**
     * Tạo order details
     * 
     * @param int $orderId
     */
    private function createOrderDetails(int $orderId): void
    {
        // Sử dụng Session cart thay vì Laravel Shopping Cart
        $cart = Session::get('cart');
        
        if ($cart && count($cart) > 0) {
            foreach ($cart as $item) {
                $data = [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_price' => $item['product_price'],
                    'product_sales_quanlity' => $item['product_qty'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                DB::table('tbl_order_details')->insert($data);
            }
        }
    }

    /**
     * Lấy danh sách các phương thức thanh toán có sẵn
     * 
     * @return array
     */
    public static function getAvailablePaymentMethods(): array
    {
        return [
            2 => 'Thanh toán khi nhận hàng (COD)',
            3 => 'Thẻ tín dụng'
        ];
    }

    /**
     * Tạo strategy dựa trên payment method code
     * 
     * @param int $paymentMethod
     * @return IPaymentStrategy
     */
    public static function createStrategy(int $paymentMethod): IPaymentStrategy
    {
        switch ($paymentMethod) {
            case 2:
                return new CashOnDeliveryStrategy();
            case 3:
                return new CreditCardPaymentStrategy();
            default:
                throw new \InvalidArgumentException('Phương thức thanh toán không được hỗ trợ');
        }
    }
}
