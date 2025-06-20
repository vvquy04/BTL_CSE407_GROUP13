<?php

namespace App\Strategies\Shipping;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Shipping;

class StandardShippingStrategy implements IShippingStrategy
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
        return 'Giao hàng tiêu chuẩn';
    }

    public function getShippingMethodCode(): int
    {
        return 1;
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
        // Logic tính phí vận chuyển tiêu chuẩn
        $base_fee = 10000; // Phí cơ bản
        $weight_fee = isset($orderData['weight']) ? $orderData['weight'] * 2000 : 0; // 2000đ/kg
        $distance_fee = isset($orderData['distance']) ? $orderData['distance'] * 1000 : 0; // 1000đ/km

        return $base_fee + $weight_fee + $distance_fee;
    }

    private function getWardName($xaid)
    {
        $ward = \App\Models\Wards::where('xaid', $xaid)->first();
        return $ward ? $ward->name_xaphuong : '';
    }

    private function getDistrictName($maqh)
    {
        $district = \App\Models\Province::where('maqh', $maqh)->first();
        return $district ? $district->name_quanhuyen : '';
    }

    private function getCityName($matp)
    {
        $city = \App\Models\City::where('matp', $matp)->first();
        return $city ? $city->name_city : '';
    }
} 