<?php

namespace App\Strategies\Discount;

class VolumeDiscountStrategy implements IDiscountStrategy
{
    private array $volumeTiers;
    private int $minimumQuantity;

    /**
     * @param array $volumeTiers Mảng các mức giảm giá theo số lượng
     * [
     *   ['min' => 3, 'max' => 5, 'rate' => 0.03],
     *   ['min' => 6, 'max' => 10, 'rate' => 0.05],
     *   ['min' => 11, 'max' => 999, 'rate' => 0.08]
     * ]
     * @param int $minimumQuantity Số lượng tối thiểu để áp dụng
     */
    public function __construct(array $volumeTiers = [], int $minimumQuantity = 3)
    {
        $this->volumeTiers = $volumeTiers ?: [
            ['min' => 3, 'max' => 5, 'rate' => 0.03, 'name' => 'Mua 3-5 sản phẩm'],
            ['min' => 6, 'max' => 10, 'rate' => 0.05, 'name' => 'Mua 6-10 sản phẩm'],
            ['min' => 11, 'max' => 999, 'rate' => 0.08, 'name' => 'Mua trên 11 sản phẩm']
        ];
        $this->minimumQuantity = $minimumQuantity;
    }

    /**
     * Kiểm tra và tính toán discount
     */
    public function processDiscount($order): array
    {
        // Kiểm tra điều kiện áp dụng
        if (!$order->order_details || $order->order_details->isEmpty()) {
            return [
                'applicable' => false,
                'amount' => 0,
                'description' => 'Không có sản phẩm trong đơn hàng',
                'type' => 'volume'
            ];
        }

        $totalQuantity = $order->order_details->sum('quantity');
        
        if ($totalQuantity < $this->minimumQuantity) {
            return [
                'applicable' => false,
                'amount' => 0,
                'description' => "Không đủ điều kiện giảm giá mua nhiều (tối thiểu {$this->minimumQuantity} sản phẩm)",
                'type' => 'volume'
            ];
        }

        // Tìm mức giảm giá phù hợp
        $applicableTier = $this->findApplicableTier($totalQuantity);
        
        if (!$applicableTier) {
            return [
                'applicable' => false,
                'amount' => 0,
                'description' => 'Không tìm thấy mức giảm giá phù hợp',
                'type' => 'volume'
            ];
        }

        // Tính toán discount
        $discountAmount = $order->total_amount * $applicableTier['rate'];
        $maxDiscount = $this->getMaxDiscount($applicableTier['rate']);
        
        if ($maxDiscount > 0) {
            $discountAmount = min($discountAmount, $maxDiscount);
        }

        $rate = $applicableTier['rate'] * 100;
        $tierName = $applicableTier['name'] ?? "Mua {$totalQuantity} sản phẩm";

        return [
            'applicable' => true,
            'amount' => round($discountAmount, 0),
            'description' => "{$tierName} giảm {$rate}%",
            'type' => 'volume'
        ];
    }

    /**
     * Tìm mức giảm giá phù hợp với số lượng
     */
    private function findApplicableTier(int $quantity): ?array
    {
        foreach ($this->volumeTiers as $tier) {
            if ($quantity >= $tier['min'] && $quantity <= $tier['max']) {
                return $tier;
            }
        }
        
        return null;
    }

    /**
     * Lấy giới hạn giảm giá tối đa
     */
    private function getMaxDiscount(float $rate): float
    {
        $maxDiscounts = [
            0.03 => 300000,  // Mức 3%: tối đa 300k
            0.05 => 800000,  // Mức 5%: tối đa 800k
            0.08 => 1500000  // Mức 8%: tối đa 1.5M
        ];

        return $maxDiscounts[$rate] ?? 0;
    }
}
