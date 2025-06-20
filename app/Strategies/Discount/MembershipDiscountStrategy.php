<?php

namespace App\Strategies\Discount;

use App\Models\Order;

class MembershipDiscountStrategy implements IDiscountStrategy
{
    private array $membershipRates;

    /**
     * @param array $membershipRates Mảng tỷ lệ giảm theo level ['gold' => 0.05, 'silver' => 0.03, 'bronze' => 0.01]
     */
    public function __construct(array $membershipRates = [])
    {
        $this->membershipRates = $membershipRates ?: [
            'gold' => 0.05,    // VIP Gold: giảm 5%
            'silver' => 0.03,  // VIP Silver: giảm 3%
            'bronze' => 0.01   // VIP Bronze: giảm 1%
        ];
    }

    /**
     * Kiểm tra và tính toán discount
     */
    public function processDiscount($order): array
    {
        // Kiểm tra điều kiện áp dụng
        if (!$order->user) {
            return [
                'applicable' => false,
                'amount' => 0,
                'description' => 'Không đủ điều kiện giảm giá thành viên',
                'type' => 'membership'
            ];
        }

        $level = $order->user->membership_level ?? 'none';
        $rate = $this->membershipRates[$level] ?? 0;

        if ($rate <= 0) {
            return [
                'applicable' => false,
                'amount' => 0,
                'description' => 'Không có hạng thành viên hợp lệ',
                'type' => 'membership'
            ];
        }

        // Tính toán discount
        $discountAmount = $order->total_amount * $rate;
        $maxDiscount = $this->getMaxDiscount($level);
        
        if ($maxDiscount > 0) {
            $discountAmount = min($discountAmount, $maxDiscount);
        }

        $levelNames = ['gold' => 'VIP Gold', 'silver' => 'VIP Silver', 'bronze' => 'VIP Bronze'];
        $levelName = $levelNames[$level] ?? ucfirst($level);
        $ratePercent = $rate * 100;

        return [
            'applicable' => true,
            'amount' => round($discountAmount, 0),
            'description' => "{$levelName} giảm {$ratePercent}%",
            'type' => 'membership'
        ];
    }

    /**
     * Lấy giới hạn giảm giá tối đa
     */
    private function getMaxDiscount(string $level): float
    {
        $maxDiscounts = [
            'gold' => 1000000,   // VIP Gold: tối đa 1 triệu
            'silver' => 500000,  // VIP Silver: tối đa 500k
            'bronze' => 200000   // VIP Bronze: tối đa 200k
        ];

        return $maxDiscounts[$level] ?? 0;
    }

    /**
     * Mô tả loại giảm giá
     */
    public function getDiscountDescription(): string
    {
        return "Giảm giá thành viên VIP";
    }

    /**
     * Lấy loại giảm giá
     */
    public function getDiscountType(): string
    {
        return 'membership';
    }

    /**
     * Lấy cấu hình tỷ lệ thành viên
     */
    public function getMembershipRates(): array
    {
        return $this->membershipRates;
    }

    /**
     * Cập nhật cấu hình tỷ lệ thành viên
     */
    public function setMembershipRates(array $rates): void
    {
        $this->membershipRates = $rates;
    }
}
