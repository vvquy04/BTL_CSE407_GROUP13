<?php

namespace App\Strategies\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CreditCardPaymentStrategy implements IPaymentStrategy
{
    /**
     * Xử lý thanh toán qua thẻ tín dụng
     */
    public function processPayment(array $orderData, Request $request): array
    {
        try {
            // Kiểm tra thông tin thẻ cơ bản
            if (!$request->card_number || !$request->card_holder || !$request->expiry_date || !$request->cvv) {
                return [
                    'success' => false,
                    'message' => 'Vui lòng nhập đầy đủ thông tin thẻ tín dụng'
                ];
            }

            // Kiểm tra tổng tiền đơn hàng
            if (!isset($orderData['total'])) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin tổng tiền đơn hàng'
                ];
            }

            // Simulate credit card payment processing
            sleep(2); // Simulate processing time

            // Kiểm tra thẻ hợp lệ (demo)
            $card_number = $request->card_number;
            $last_digit = substr($card_number, -1);
            
            // Demo: Chỉ chấp nhận thẻ có số cuối là số chẵn
            if ($last_digit % 2 !== 0) {
                return [
                    'success' => false,
                    'message' => 'Thẻ không hợp lệ hoặc không đủ số dư. Vui lòng thử lại với thẻ khác.'
                ];
            }

            // Lưu thông tin thanh toán
            $payment_data = [
                'payment_method' => 'Credit Card',
                'payment_status' => 'Đã thanh toán',
                'payment_amount' => $orderData['total'],
                'payment_details' => json_encode([
                    'card_last4' => substr($card_number, -4),
                    'card_holder' => $request->card_holder,
                    'expiry_date' => $request->expiry_date
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ];

            $payment_id = DB::table('tbl_payment')->insertGetId($payment_data);

            // Cập nhật trạng thái đơn hàng
            if (isset($orderData['order_id'])) {
                DB::table('tbl_order')
                    ->where('order_id', $orderData['order_id'])
                    ->update([
                        'payment_id' => $payment_id,
                        'order_status' => 'Đã thanh toán',
                        'updated_at' => now()
                    ]);
            }

            // Lưu thông tin đơn hàng vào session
            $order_info = [
                'order_id' => $orderData['order_id'] ?? null,
                'order_code' => DB::table('tbl_order')->where('order_id', $orderData['order_id'])->value('order_code'),
                'order_total' => $orderData['total'],
                'payment_method' => 'Thanh toán qua thẻ tín dụng',
                'transaction_id' => 'TXN' . time() . rand(100, 999)
            ];
            Session::put('order_info', $order_info);

            return [
                'success' => true,
                'message' => 'Thanh toán thành công!',
                'payment_id' => $payment_id,
                'order_info' => $order_info
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý thanh toán: ' . $e->getMessage()
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
        // Chỉ kiểm tra các trường bắt buộc
        if (!$request->card_number || !$request->card_holder || !$request->expiry_date || !$request->cvv) {
            throw new \Exception('Vui lòng nhập đầy đủ thông tin thẻ tín dụng');
        }
        return true;
    }
}
