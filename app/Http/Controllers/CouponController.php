<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Redirect; // dùng để chuyển hướng
use Illuminate\Support\Facades\Session;// dùng để  lưu tạm các message sau khi thực hiện một công việc gì đó.
use App\Http\Requests; // dùng để lấy dữ liệu từ form
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function AuthLogin() {
        if(Session::get('admin_id') != null) {
            return Redirect::to('admin.dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }
    
    public function unset_coupon() {
        $coupon = Session::get('coupon');
        if($coupon) {
            Session::forget('coupon');
            return redirect()->back()->with('message','Xóa mã giảm giá thành công');
        }
    }
    
    public function add_coupon() {
        $this->AuthLogin();
        return view('admin.coupon.add_coupon');
    }
    
    public function save_coupon(Request $request) {
        $data = $request->all();
        $coupon = new Coupon();
        $coupon->coupon_name = $data['coupon_name'];
        $coupon->coupon_code = $data['coupon_code'];
        $coupon->coupon_time = $data['coupon_time'];
        $coupon->coupon_condition = $data['coupon_condition'];
        $coupon->coupon_number = $data['coupon_number'];
        $coupon->save();
        Session::put('message','Thêm thành công mã giảm giá');
        return view('admin.coupon.add_coupon');
    }
    
    public function all_coupon() {
        $this->AuthLogin();
        $coupon = Coupon::orderBy('coupon_id','DESC')->get();
        return view('admin.coupon.all_coupon')->with(compact('coupon',$coupon));
    }
    
    public function delete_coupon($coupon_id) {
        $coupon = Coupon::find($coupon_id)->first();
        $coupon->delete();
        Session::put('message','Xóa mã giảm giá thành công');
        return Redirect::to('/all-coupon');
    }
    
    /**
     * 🎫 Áp dụng mã giảm giá
     */
    public function applyCouponCode(Request $request)
    {
        $couponCode = $request->input('coupon_code');
        
        if (!$couponCode) {
            return response()->json(['error' => 'Vui lòng nhập mã giảm giá!']);
        }
        
        // Kiểm tra coupon
        $coupon = Coupon::where('coupon_code', $couponCode)->first();
        
        if (!$coupon) {
            return response()->json(['error' => 'Mã giảm giá không tồn tại!']);
        }
        
        if ($coupon->coupon_time <= 0) {
            return response()->json(['error' => 'Mã giảm giá đã hết lượt sử dụng!']);
        }
        
        // Lưu coupon vào session
        Session::put('coupon', [
            'coupon_code' => $coupon->coupon_code,
            'coupon_condition' => $coupon->coupon_condition,
            'coupon_number' => $coupon->coupon_number
        ]);
        
        // Giảm số lượt sử dụng
        $coupon->coupon_time = $coupon->coupon_time - 1;
        $coupon->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!'
        ]);
    }
    
    /**
     * 🎫 Bỏ mã giảm giá
     */
    public function removeCouponCode(Request $request)
    {
        // Tăng lại số lượt sử dụng
        $currentCoupon = Session::get('coupon');
        if ($currentCoupon) {
            $coupon = Coupon::where('coupon_code', $currentCoupon['coupon_code'])->first();
            if ($coupon) {
                $coupon->coupon_time = $coupon->coupon_time + 1;
                $coupon->save();
            }
        }
        
        // Xóa coupon khỏi session
        Session::forget('coupon');
        
        return response()->json([
            'success' => true,
            'message' => 'Đã bỏ mã giảm giá!'
        ]);
    }
}
