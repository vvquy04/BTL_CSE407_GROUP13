<?php

namespace App\Strategies\Shipping;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Shipping;

class ExpressShippingStrategy implements IShippingStrategy
{
    public function processShipping(array $orderData, Request $request): array
    {
        try {
            // Validate shipping data
            if (!$this->validateShippingData($request)) {
                return [
                    'success' => false,
                    'message' => 'Dữ liệu vận chuyển không hợp lệ'
                ];
            }

            // Save shipping information using the correct table structure
            $shipping_id = DB::table('tbl_shipping')->insertGetId([
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_street' => $request->shipping_address_detail,
                'shipping_ward' => $this->getWardName($request->nameWards),
                'shipping_district' => $this->getDistrictName($request->nameProvince),
                'shipping_city' => $this->getCityName($request->nameCity),
                'shipping_note' => $request->shipping_note ?? '',
                'shipping_method' => $this->getShippingMethodCode(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return [
                'success' => true,
                'message' => 'Xử lý vận chuyển thành công',
                'shipping_id' => $shipping_id
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý vận chuyển: ' . $e->getMessage()
            ];
        }
    }

    public function getShippingMethodName(): string
    {
        return 'Giao hàng nhanh';
    }

    public function getShippingMethodCode(): int
    {
        return 2;
    }

    public function validateShippingData(Request $request): bool
    {
        if (
            !$request->shipping_name ||
            !$request->shipping_phone ||
            !$request->shipping_email ||
            !$request->shipping_address_detail
        ) {
            throw new \Exception('Vui lòng nhập đầy đủ thông tin vận chuyển');
        }
        return true;
    }

    private function calculateShippingFee(array $orderData): float
    {
        // Logic tính phí vận chuyển nhanh
        $base_fee = 20000; // Phí cơ bản cao hơn
        $weight_fee = isset($orderData['weight']) ? $orderData['weight'] * 3000 : 0; // 3000đ/kg
        $distance_fee = isset($orderData['distance']) ? $orderData['distance'] * 1500 : 0; // 1500đ/km
        $express_fee = 15000; // Phí giao hàng nhanh

        return $base_fee + $weight_fee + $distance_fee + $express_fee;
    }

    private function getWardName($xaid) {
        $ward = \App\Models\Wards::where('xaid', $xaid)->first();
        return $ward ? $ward->name_xaphuong : '';
    }

    private function getDistrictName($maqh) {
        $district = \App\Models\Province::where('maqh', $maqh)->first();
        return $district ? $district->name_quanhuyen : '';
    }

    private function getCityName($matp) {
        $city = \App\Models\City::where('matp', $matp)->first();
        return $city ? $city->name_city : '';
    }
} 