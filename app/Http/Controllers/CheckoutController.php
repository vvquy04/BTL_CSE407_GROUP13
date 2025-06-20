<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Gloudemans\Shoppingcart\Facades\Cart;// dùng để  lưu tạm các message sau khi thực hiện một công việc gì đó.
use App\Http\Requests; // dùng để lấy dữ liệu từ form
use Illuminate\Support\Facades\Redirect; // dùng để chuyển hướng
use App\Models\City;
use App\Models\Province;
use App\Models\Wards;
use App\Models\Feeship;
use App\Rules\Captcha;

use App\Models\Shipping;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Coupon;

// Import Strategy Pattern classes
use App\Strategies\Payment\PaymentContext;
use App\Strategies\Payment\CashOnDeliveryStrategy;
use App\Strategies\Payment\CreditCardPaymentStrategy;
use App\Strategies\Payment\IPaymentStrategy;
use Illuminate\Support\Facades\Validator;
use App\Strategies\Shipping\ShippingContext;
use App\Strategies\Shipping\StandardShippingStrategy;
use App\Strategies\Shipping\ExpressShippingStrategy;

// Import Discount Strategy Pattern classes
use App\Strategies\Discount\DiscountContext;
use App\Strategies\Discount\MembershipDiscountStrategy;
use App\Strategies\Discount\VolumeDiscountStrategy;

class CheckoutController extends Controller
{
        public function delete_fee_home() {
        if(Session::get('fee')) {
            Session::forget('fee');
        }
        return redirect()->back();
    }
    
    public function calculate_fee(Request $request) {
        $data = $request->all();
        $feeship = Feeship::where('fee_matp',$data['cityId'])
                         ->where('fee_maqh',$data['provinceId'])
                         ->where('fee_xaid',$data['wardId'])
                         ->first();

        $baseFee = $feeship ? $feeship->fee_feeship : 10000;
        
        // Áp dụng hệ số cho phương thức vận chuyển
        $shippingMethod = $data['shippingMethod'] ?? 1;
        $multiplier = $shippingMethod == 2 ? 2 : 1; // Giao hàng nhanh có phí gấp đôi
        
        $finalFee = $baseFee * $multiplier;

        Session::put('fee', $finalFee);
        Session::save();

        return response()->json([
            'success' => true,
            'fee' => $finalFee,
            'message' => 'Tính phí vận chuyển thành công!'
        ]);
    }

    public function getCurrentFee() {        $fee = Session::get('fee') ?? 10000;
        return response()->json($fee);
    }
    
    public function get_delivery_home(Request $request) {
        try {
            $data = $request->all();
            $output = '';
            
            // Validate input
            if (!isset($data['action']) || !isset($data['ma_id'])) {
                return response("<option value='0'>Dữ liệu không hợp lệ</option>")
                       ->header('Content-Type', 'text/html; charset=UTF-8');
            }

            $action = $data['action'];
            $ma_id = $data['ma_id'];
            
            if($action === 'nameCity') {
                // Load provinces for selected city
                $selectProvince = Province::where('matp', $ma_id)
                                         ->orderBy('maqh', 'ASC')
                                         ->get();
                
                $output .= "<option value='0'>---Chọn quận huyện---</option>";
                
                if($selectProvince->count() > 0) {
                    foreach($selectProvince as $qh) {
                        $output .= "<option value='".$qh->maqh."'>".$qh->name_quanhuyen."</option>";
                    }
                } else {
                    $output .= "<option value='0'>Không có dữ liệu quận huyện</option>";
                }
                
            } elseif($action === 'nameProvince') {
                // Load wards for selected province
                $selectWards = Wards::where('maqh', $ma_id)
                                   ->orderBy('xaid', 'ASC')
                                   ->get();
                
                $output .= "<option value='0'>---Chọn xã phường---</option>";
                
                if($selectWards->count() > 0) {
                    foreach($selectWards as $xp) {
                        $output .= "<option value='".$xp->xaid."'>".$xp->name_xaphuong."</option>";
                    }
                } else {
                    $output .= "<option value='0'>Không có dữ liệu xã phường</option>";
                }
                
            } elseif($action === 'nameWards') {
                // Khi chọn xã phường, trả về option đã chọn
                $ward = Wards::where('xaid', $ma_id)->first();
                if ($ward) {
                    $output .= "<option value='".$ward->xaid."' selected>".$ward->name_xaphuong."</option>";
                } else {
                    $output .= "<option value='0'>Không tìm thấy xã phường</option>";
                }
            } else {
                $output = "<option value='0'>Action không hợp lệ: " . $action . "</option>";
            }
            
            return response($output)->header('Content-Type', 'text/html; charset=UTF-8');
            
        } catch (\Exception $e) {
            return response("<option value='0'>Lỗi tải dữ liệu: " . $e->getMessage() . "</option>")
                   ->header('Content-Type', 'text/html; charset=UTF-8');
        }
    }
    public function AuthLogin() {
        if(Session::get('admin_id') != null) {
            return Redirect::to('admin.dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }
    public function login_checkout(Request $request)  {
        $meta_title = "Đăng nhập hoặc đăng ký tài khoản";
        $meta_desc = "Đăng nhập hoặc đăng ký tài khoản của shop";
        $meta_keywords = "đăng nhập xwatch247, xwatch247 login";
        $meta_canonical = $request->url();
        $image_og = "";

        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $branch_product = DB::table('tbl_branch_product')->where('branch_status','1')->orderby('branch_id','desc')->get();
        return view('pages.checkout.login_checkout')->with('category_product',$cate_product)->with('branch_product',$branch_product)
        ->with('meta_title',$meta_title)
        ->with('meta_desc',$meta_desc)
        ->with('meta_keywords',$meta_keywords)
        ->with('meta_canonical',$meta_canonical)
        ->with('image_og',$image_og);
        
    }
    public function add_customer(Request $request) {
        $data = array();
        $data['customer_name'] = $request->customer_name;
        $data['customer_email'] = $request->customer_email;
        $data['customer_phone'] = $request->customer_phone;
        $data['customer_password'] = md5($request->customer_password);

        $validated = $request->validate([
            'customer_name' => 'required|min:5',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|numeric|min:9',
            'customer_password' => 'required|min:6',
            'g-recaptcha-response'=>new Captcha(),
        ]);

        $customer_id = DB::table('tbl_customers')->insertGetId($data);
        $customer_name = $request->customer_name;

        Session::put('customer_id',$customer_id);
        Session::put('customer_name',$customer_name);
        
        return Redirect::to('/checkout');
    }    public function checkout(Request $request) {
        $meta_title = "Thông tin giao hàng và thanh toán";
        $meta_desc = "Trang nhập thông tin giao hàng và chọn phương thức thanh toán";
        $meta_keywords = "giao hàng xwatch247, xwatch247 checkout, thanh toán";
        $meta_canonical = $request->url();
        $image_og = "";
        $city = City::orderBy('matp')->get();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $branch_product = DB::table('tbl_branch_product')->where('branch_status','1')->orderby('branch_id','desc')->get();
        
        return view('pages.checkout.checkout_unified')->with('category_product',$cate_product)->with('branch_product',$branch_product)
        ->with('meta_title',$meta_title)
        ->with('meta_desc',$meta_desc)
        ->with('meta_keywords',$meta_keywords)
        ->with('meta_canonical',$meta_canonical)
        ->with('image_og',$image_og)->with('cityData',$city);
    }    public function save_checkout_customer(Request $request) {
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;

        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_address'] = $request->shipping_address;
        $data['shipping_note'] = $request->shipping_note;

        $shipping_id = DB::table('tbl_shipping')->insertGetId($data);
        Session::put('shipping_id',$shipping_id);

        return Redirect::to('/checkout');
    }
    
    /**
     * Xác nhận đặt hàng
     */
    public function confirmOrder(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'shipping_name' => 'required|string|max:255',
                'shipping_email' => 'required|email',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'payment_select' => 'required|in:2,3',
                'shipping_select' => 'required|in:1,2'
            ]);

            $cart = Session::get('cart');
            if (!$cart || empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng trống'
                ]);
            }

            // Tính tổng đơn hàng
            $subtotal = 0;
            foreach($cart as $item) {
                $subtotal += $item['product_price'] * $item['product_qty'];
            }

            // Tính phí vận chuyển
            $shippingFee = $request->input('fee_shipping', 10000);
            
            // Tính discount từ strategy pattern (chỉ VIP và Volume)
            $discountAmount = 0;
            $selectedDiscountType = $request->input('selected_discount_type');
            
            // Debug: Log selected_discount_type từ request
            // Log::info('=== DEBUG SELECTED DISCOUNT TYPE ===');
            // Log::info('selected_discount_type from request: ' . ($selectedDiscountType ?? 'null'));
            // Log::info('=====================================');
            
            if ($selectedDiscountType && $selectedDiscountType !== 'none') {
                $tempOrder = new \stdClass();
                $tempOrder->total_amount = $subtotal;
                $tempOrder->user = null;
                $customerId = Session::get('customer_id');
                if ($customerId) {
                    $tempOrder->user = DB::table('tbl_customers')->where('customer_id', $customerId)->first();
                }
                $tempOrder->order_details = collect();
                foreach($cart as $item) {
                    for ($i = 0; $i < $item['product_qty']; $i++) {
                        $detail = new \stdClass();
                        $detail->quantity = 1;
                        $tempOrder->order_details->push($detail);
                    }
                }
                // Chọn strategy phù hợp
                $discountContext = new \App\Strategies\Discount\DiscountContext();
                if ($selectedDiscountType === 'membership') {
                    $discountContext->setDiscountStrategy(new \App\Strategies\Discount\MembershipDiscountStrategy());
                } elseif ($selectedDiscountType === 'volume') {
                    $discountContext->setDiscountStrategy(new \App\Strategies\Discount\VolumeDiscountStrategy());
                }
                $discountResult = $discountContext->calculateDiscount($tempOrder);
                $discountAmount = $discountResult['amount'] ?? 0;
            }
            
            // Tính coupon discount riêng biệt (nếu có)
            $couponDiscount = 0;
            if (Session::has('coupon')) {
                $coupon = Session::get('coupon');
                foreach($coupon as $key => $val) {
                    if($val['coupon_condition'] == 1) {
                        $couponDiscount = ($subtotal * $val['coupon_number']) / 100;
                    } else {
                        $couponDiscount = $val['coupon_number'];
                    }
                }
            }
            
            // Tổng discount = Strategy discount + Coupon discount
            $totalDiscount = $discountAmount + $couponDiscount;
            
            // Tính tổng cuối
            $total = $subtotal - $totalDiscount + $shippingFee;

            // Debug: Log các giá trị tính toán
            // Log::info('=== DEBUG ORDER CALCULATION ===');
            // Log::info('Subtotal: ' . $subtotal);
            // Log::info('Strategy Discount: ' . $discountAmount);
            // Log::info('Coupon Discount: ' . $couponDiscount);
            // Log::info('Total Discount: ' . $totalDiscount);
            // Log::info('Shipping Fee: ' . $shippingFee);
            // Log::info('Final Total: ' . $total);
            // Log::info('Selected Discount Type: ' . ($selectedDiscountType ?? 'none'));
            // Log::info('================================');

            // --- XỬ LÝ SHIPPING STRATEGY ---
            $shippingContext = new \App\Strategies\Shipping\ShippingContext();
            if ($request->shipping_select == 1) {
                $shippingContext->setShippingStrategy(new \App\Strategies\Shipping\StandardShippingStrategy());
            } elseif ($request->shipping_select == 2) {
                $shippingContext->setShippingStrategy(new \App\Strategies\Shipping\ExpressShippingStrategy());
            }
            
            $orderData = [
                'customer_id' => Session::get('customer_id') ?? 0,
                'order_total' => $total,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_note' => $request->shipping_note ?? '',
                'shipping_method' => $request->shipping_select,
                'nameWards' => $request->nameWards,
                'nameProvince' => $request->nameProvince,
                'nameCity' => $request->nameCity
            ];
            
            // Debug: Log session and order data
            // Log::info('Session customer_id: ' . (Session::get('customer_id') ?? 'null'));
            // Log::info('Order data before shipping:', $orderData);
            
            try {
                $shippingResult = $shippingContext->executeShipping($orderData, $request);
                if (!$shippingResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $shippingResult['message'] ?? 'Lỗi vận chuyển'
                    ]);
                }
                $shipping_id = $shippingResult['shipping_id'] ?? null;
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi xử lý vận chuyển: ' . $e->getMessage()
                ]);
            }

            // --- XỬ LÝ PAYMENT STRATEGY ---
            $paymentContext = new \App\Strategies\Payment\PaymentContext();
            if ($request->payment_select == 2) {
                $paymentContext->setPaymentStrategy(new \App\Strategies\Payment\CashOnDeliveryStrategy());
            } elseif ($request->payment_select == 3) {
                $paymentContext->setPaymentStrategy(new \App\Strategies\Payment\CreditCardPaymentStrategy());
            }
            
            $orderData['shipping_id'] = $shipping_id;
            
            // Debug: Log the order data being passed to payment strategy
            // Log::info('Order data for payment strategy:', $orderData);
            
            try {
                $paymentResult = $paymentContext->executePayment($orderData, $request);
                if (!$paymentResult['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => $paymentResult['message'] ?? 'Lỗi thanh toán'
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi xử lý thanh toán: ' . $e->getMessage()
                ]);
            }

            // Lưu thông tin đơn hàng vào session để hiển thị trên trang thanh toán thành công
            $order_info = [
                'order_id' => $paymentResult['order_id'] ?? null,
                'order_code' => 'ORD' . time() . rand(100, 999),
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_email' => $request->shipping_email,
                'shipping_address_detail' => $request->shipping_address,
                'shipping_note' => $request->shipping_note ?? '',
                'order_total' => $total,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => $totalDiscount,
                'strategy_discount' => $discountAmount,
                'coupon_discount' => $couponDiscount,
                'selected_discount_type' => $selectedDiscountType,
                'discount_description' => $this->getDiscountDescription($selectedDiscountType, $discountAmount, $couponDiscount),
                'payment_method' => $request->payment_select == 2 ? 'Thanh toán khi nhận hàng (COD)' : 'Thanh toán qua thẻ tín dụng',
                'shipping_method' => $request->shipping_select == 1 ? 'Giao hàng tiêu chuẩn' : 'Giao hàng nhanh',
                'ward_name' => $this->getWardName($request->nameWards),
                'district_name' => $this->getDistrictName($request->nameProvince),
                'city_name' => $this->getCityName($request->nameCity),
                'transaction_id' => 'TXN' . time() . rand(100, 999)
            ];

            Session::put('order_info', $order_info);

            // Debug: Log order_info được lưu vào session
            // Log::info('=== DEBUG ORDER_INFO SAVED ===');
            // Log::info('order_total: ' . $order_info['order_total']);
            // Log::info('subtotal: ' . $order_info['subtotal']);
            // Log::info('discount_amount: ' . $order_info['discount_amount']);
            // Log::info('shipping_fee: ' . $order_info['shipping_fee']);
            // Log::info('discount_description: ' . $order_info['discount_description']);
            // Log::info('==============================');

            // Xác định redirect URL dựa trên phương thức thanh toán
            $redirect_url = '';
            if ($request->payment_select == 2) {
                // COD - chuyển đến trang payment_cod
                $redirect_url = route('payment-cod');
            } elseif ($request->payment_select == 3) {
                // Credit Card - chuyển đến trang payment_credit
                $redirect_url = route('payment-credit');
            } else {
                // Fallback
                $redirect_url = url('/order-success');
            }

            // Xóa giỏ hàng và coupon nếu thành công
            Session::forget('cart');
            Session::forget('coupon');

            return response()->json([
                'success' => true,
                'message' => $paymentResult['message'] ?? 'Đặt hàng thành công!',
                'order_id' => $paymentResult['order_id'] ?? null,
                'redirect_url' => $redirect_url
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
    }

    public function logout_checkout() {
        Session::put('shipping_id',null);
        Session::put('customer_id',null);
        Session::put('customer_name',null);
        return Redirect::to('/login-checkout');
    }
    public function login_customer(Request $request) {
        $email = $request->email_account;
        $password = md5($request->password_account);

        $result = DB::table('tbl_customers')->where('customer_email',$email)->where('customer_password',$password)->first();
      
        
        if($result) {
            Session::put('customer_id',$result->customer_id);
            Session::put('customer_name',$result->customer_name);
            return Redirect::to('/checkout');
        } else {            Session::put('message','Mật khẩu hoặc tài khoản không đúng, vui lòng nhập lại!');
            return Redirect::to('/login-checkout');
        }
    }    /**
     * Hiển thị trang thanh toán COD
     */
    public function showPaymentCOD() {
        $order_info = Session::get('order_info');
        if (!$order_info) {
            Session::put('message', 'Vui lòng thực hiện đặt hàng trước khi thanh toán.');
            return redirect()->to('/gio-hang');
        }

        // Debug: Log order_info được đọc từ session
        // Log::info('=== DEBUG SHOW PAYMENT COD ===');
        // Log::info('order_total: ' . ($order_info['order_total'] ?? 'null'));
        // Log::info('subtotal: ' . ($order_info['subtotal'] ?? 'null'));
        // Log::info('discount_amount: ' . ($order_info['discount_amount'] ?? 'null'));
        // Log::info('shipping_fee: ' . ($order_info['shipping_fee'] ?? 'null'));
        // Log::info('discount_description: ' . ($order_info['discount_description'] ?? 'null'));
        // Log::info('==============================');

        $meta_title = "Thanh toán khi nhận hàng (COD)";
        $meta_desc = "Hoàn tất đơn hàng với phương thức thanh toán khi nhận hàng";
        $meta_keywords = "thanh toán COD, thanh toán khi nhận hàng, xwatch247";
        $meta_canonical = request()->url();
        $image_og = "";

        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $branch_product = DB::table('tbl_branch_product')->where('branch_status','1')->orderby('branch_id','desc')->get();

        return view('pages.checkout.payment_cod', compact('order_info'))
            ->with('category_product', $cate_product)
            ->with('branch_product', $branch_product)
            ->with('meta_title', $meta_title)
            ->with('meta_desc', $meta_desc)
            ->with('meta_keywords', $meta_keywords)
            ->with('meta_canonical', $meta_canonical)
            ->with('image_og', $image_og);
    }    /**
     * Hiển thị trang thanh toán thẻ tín dụng
     */
    public function showPaymentCredit() {
        $order_info = Session::get('order_info');
        if (!$order_info) {
            Session::put('message', 'Vui lòng thực hiện đặt hàng trước khi thanh toán.');
            return redirect()->to('/gio-hang');
        }

        // Debug: Log order_info được đọc từ session
        // Log::info('=== DEBUG SHOW PAYMENT CREDIT ===');
        // Log::info('order_total: ' . ($order_info['order_total'] ?? 'null'));
        // Log::info('subtotal: ' . ($order_info['subtotal'] ?? 'null'));
        // Log::info('discount_amount: ' . ($order_info['discount_amount'] ?? 'null'));
        // Log::info('shipping_fee: ' . ($order_info['shipping_fee'] ?? 'null'));
        // Log::info('discount_description: ' . ($order_info['discount_description'] ?? 'null'));
        // Log::info('================================');

        $meta_title = "Thanh toán qua thẻ tín dụng";
        $meta_desc = "Hoàn tất đơn hàng với phương thức thanh toán qua thẻ tín dụng";
        $meta_keywords = "thanh toán thẻ tín dụng, thanh toán online, xwatch247";
        $meta_canonical = request()->url();
        $image_og = "";

        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $branch_product = DB::table('tbl_branch_product')->where('branch_status','1')->orderby('branch_id','desc')->get();

        return view('pages.checkout.payment_credit', compact('order_info'))
            ->with('category_product', $cate_product)
            ->with('branch_product', $branch_product)
            ->with('meta_title', $meta_title)
            ->with('meta_desc', $meta_desc)
            ->with('meta_keywords', $meta_keywords)
            ->with('meta_canonical', $meta_canonical)
            ->with('image_og', $image_og);
    }

    /**
     * Xử lý thanh toán thẻ tín dụng
     */
    public function processCreditPayment(Request $request) {
        try {
            // Validate dữ liệu thẻ tín dụng
            $validator = Validator::make($request->all(), [
                'card_number' => 'required|string|min:15|max:19',
                'card_holder' => 'required|string|max:255',
                'expiry_date' => 'required|string|size:5|regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/',
                'cvv' => 'required|string|min:3|max:4'
            ], [
                'card_number.required' => 'Vui lòng nhập số thẻ',
                'card_number.min' => 'Số thẻ không hợp lệ',
                'card_number.max' => 'Số thẻ không hợp lệ',
                'card_holder.required' => 'Vui lòng nhập tên chủ thẻ',
                'expiry_date.required' => 'Vui lòng nhập ngày hết hạn',
                'expiry_date.regex' => 'Định dạng ngày hết hạn không hợp lệ (MM/YY)',
                'cvv.required' => 'Vui lòng nhập mã CVV',
                'cvv.min' => 'Mã CVV không hợp lệ',
                'cvv.max' => 'Mã CVV không hợp lệ'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Kiểm tra thông tin đơn hàng
            $order_info = Session::get('order_info');
            if (!$order_info) {
                return redirect()->route('cart')->with('error', 'Không tìm thấy thông tin đơn hàng');
            }

            // Simulate credit card payment processing
            sleep(2); // Simulate processing time

            // Kiểm tra thẻ hợp lệ (demo)
            $card_number = $request->card_number;
            $last_digit = substr($card_number, -1);
            
            // Demo: Chỉ chấp nhận thẻ có số cuối là số chẵn
            if ($last_digit % 2 !== 0) {
                return back()->with('error', 'Thẻ không hợp lệ hoặc không đủ số dư. Vui lòng thử lại với thẻ khác.');
            }

            // Lưu thông tin thanh toán
            $payment_data = [
                'payment_method' => 'Credit Card',
                'payment_status' => 'Đã thanh toán',
                'payment_amount' => $order_info['order_total'],
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
            if (isset($order_info['order_id'])) {
                DB::table('tbl_order')
                    ->where('order_id', $order_info['order_id'])
                    ->update([
                        'payment_id' => $payment_id,
                        'order_status' => 'Đã thanh toán',
                        'updated_at' => now()
                    ]);
            }

            // Lưu thông tin đơn hàng vào session để hiển thị trên trang thành công
            $success_info = [
                'order_id' => $order_info['order_id'] ?? null,
                'order_code' => $order_info['order_code'] ?? 'ORD' . time() . rand(100, 999),
                'shipping_name' => $order_info['shipping_name'] ?? '',
                'shipping_phone' => $order_info['shipping_phone'] ?? '',
                'shipping_email' => $order_info['shipping_email'] ?? '',
                'shipping_address_detail' => $order_info['shipping_address_detail'] ?? '',
                'shipping_note' => $order_info['shipping_note'] ?? '',
                'order_total' => $order_info['order_total'] ?? 0,
                'subtotal' => $order_info['subtotal'] ?? 0,
                'shipping_fee' => $order_info['shipping_fee'] ?? 0,
                'discount_amount' => $order_info['discount_amount'] ?? 0,
                'strategy_discount' => $order_info['strategy_discount'] ?? 0,
                'coupon_discount' => $order_info['coupon_discount'] ?? 0,
                'selected_discount_type' => $order_info['selected_discount_type'] ?? null,
                'discount_description' => $order_info['discount_description'] ?? '',
                'payment_method' => 'Thanh toán qua thẻ tín dụng',
                'shipping_method' => $order_info['shipping_method'] ?? 'Giao hàng tiêu chuẩn',
                'ward_name' => $order_info['ward_name'] ?? '',
                'district_name' => $order_info['district_name'] ?? '',
                'city_name' => $order_info['city_name'] ?? '',
                'transaction_id' => 'TXN' . time() . rand(100, 999)
            ];

            Session::put('order_info', $success_info);

            // Clear các session không cần thiết
            Session::forget(['cart', 'coupon', 'fee']);
            
            // Chuyển hướng đến trang thành công
            return redirect()->route('order-success')
                ->with('success', 'Thanh toán thành công! Cảm ơn bạn đã mua hàng.');
            
        } catch (\Exception $e) {
            // Log::error('Credit card payment error: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi xử lý thanh toán. Vui lòng thử lại sau.');
        }
    }

    /**
     * Hiển thị trang thanh toán thành công
     */
    public function showPaymentSuccess() {
        $order_info = Session::get('order_info');
        $payment_info = Session::get('payment_info');

        if (!$order_info || !$payment_info) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin đơn hàng');
        }

        return view('pages.checkout.payment_success', compact('order_info', 'payment_info'));
    }

    /**
     * Lấy tên phương thức thanh toán
     */
    private function getPaymentMethodName($paymentMethod) {
        switch($paymentMethod) {
            case 2:
                return 'Thanh toán khi nhận hàng (COD)';
            case 3:
                return 'Thanh toán qua thẻ tín dụng';
            default:
                return 'Không xác định';
        }
    }

    /**
     * Quản lý đơn hàng (Admin)
     */
    public function manage_order() {
        $this->AuthLogin();
        $all_order = DB::table('tbl_order')->join('tbl_customers','tbl_order.customer_id','=','tbl_customers.customer_id')
        ->select('tbl_order.*','tbl_customers.customer_name')
        ->orderby('tbl_order.order_id','desc')->get();

        return view('admin.manage_order')->with('all_order',$all_order);
    }

    /**
     * Xem chi tiết đơn hàng (Admin)
     */
    public function view_order_detail($order_id) {
        $this->AuthLogin();
        $order_by_id = DB::table('tbl_order')
            ->join('tbl_customers', 'tbl_customers.customer_id', '=', 'tbl_order.customer_id')
            ->join('tbl_shipping', 'tbl_shipping.shipping_id', '=', 'tbl_order.shipping_id')
            ->join('tbl_order_details', 'tbl_order_details.order_id', '=', 'tbl_order.order_id')
            ->join('tbl_payment', 'tbl_payment.payment_id', '=', 'tbl_order.payment_id')
            ->where('tbl_order.order_id', $order_id)
            ->select('tbl_order.*', 'tbl_customers.*', 'tbl_shipping.*', 'tbl_order_details.*', 'tbl_payment.payment_method')
            ->first();
        
        $products = DB::table('tbl_order_details')
            ->where('tbl_order_details.order_id', $order_id)
            ->get();
            
        return view('admin.view_order')
            ->with('order_by_id', $order_by_id)
            ->with('order_list', $products);
    }

    /**
     * Xóa đơn hàng (Admin)
     */
    public function delete_order($order_id) {
        $this->AuthLogin();
        DB::table('tbl_order_details')->where('order_id',$order_id)->delete();
        DB::table('tbl_order')->where('order_id',$order_id)->delete();
        Session::put('message','Xóa đơn hàng thành công');
        return Redirect::to('/manage-order');
    }
    public function checkout_view(Request $request) {
        $meta_title = "Trang thanh toán - Thông tin giao hàng";
        $meta_desc = "Nhập thông tin giao hàng và chọn phương thức thanh toán";
        $meta_keywords = "checkout, thanh toán, giao hàng";
        $meta_canonical = $request->url();
        $image_og = "";
        
        $city = City::orderBy('matp')->get();
        $cate_product = DB::table('tbl_category_product')->where('category_status','1')->orderby('category_id','desc')->get();
        $branch_product = DB::table('tbl_branch_product')->where('branch_status','1')->orderby('branch_id','desc')->get();
        
        return view('pages.checkout.view_checkout')
            ->with('category_product',$cate_product)
            ->with('branch_product',$branch_product)
            ->with('meta_title',$meta_title)
            ->with('meta_desc',$meta_desc)
            ->with('meta_keywords',$meta_keywords)
            ->with('meta_canonical',$meta_canonical)
            ->with('image_og',$image_og)
            ->with('cityData',$city);
    }

    // Helper functions to get location names by id
    private function getCityName($matp) {
        $city = \App\Models\City::where('matp', $matp)->first();
        return $city ? $city->name_city : '';
    }
    private function getDistrictName($maqh) {
        $district = \App\Models\Province::where('maqh', $maqh)->first();
        return $district ? $district->name_quanhuyen : '';
    }
    private function getWardName($xaid) {
        $ward = \App\Models\Wards::where('xaid', $xaid)->first();
        return $ward ? $ward->name_xaphuong : '';
    }

    /**
     * 🎯 Tính toán giảm giá sử dụng Discount Strategy Pattern
     * @param float $orderAmount Số tiền đơn hàng
     * @param int $totalQuantity Tổng số lượng sản phẩm
     * @param int|null $customerId ID khách hàng
     * @param string|null $selectedDiscountType Loại giảm giá được chọn
     * @return array Kết quả giảm giá
     */
    private function calculateOrderDiscount(float $orderAmount, int $totalQuantity, ?int $customerId, ?string $selectedDiscountType = null): array
    {
        // Tạo đối tượng Order giả để test các strategy
        $tempOrder = new \stdClass();
        $tempOrder->total_amount = $orderAmount;
        $tempOrder->user = null;
        
        // Lấy thông tin khách hàng nếu có
        if ($customerId) {
            $tempOrder->user = DB::table('tbl_customers')->where('customer_id', $customerId)->first();
        }
        
        // 🎯 FAKE MEMBERSHIP LEVEL FOR TESTING
        // Nếu không có user hoặc user chưa có membership_level, fake dựa trên order amount
        if (!$tempOrder->user) {
            $tempOrder->user = new \stdClass();
            $tempOrder->user->customer_id = 0;
        }
        
        if (!isset($tempOrder->user->membership_level) || !$tempOrder->user->membership_level) {
            // Fake membership level dựa trên order amount để demo
            if ($orderAmount >= 3000000) {
                $tempOrder->user->membership_level = 'gold';    // VIP Gold cho đơn >= 3M
            } elseif ($orderAmount >= 2000000) {
                $tempOrder->user->membership_level = 'silver';  // VIP Silver cho đơn >= 2M  
            } elseif ($orderAmount >= 1000000) {
                $tempOrder->user->membership_level = 'bronze';  // VIP Bronze cho đơn >= 1M
            }
            // Đơn < 1M = guest (không set membership_level)
        }
        
        // Tạo order_details giả để tính số lượng
        $tempOrder->order_details = collect();
        for ($i = 0; $i < $totalQuantity; $i++) {
            $detail = new \stdClass();
            $detail->quantity = 1;
            $tempOrder->order_details->push($detail);
        }

        // Chọn các strategy áp dụng
        $strategies = $this->selectApplicableStrategies($tempOrder);

        // Kiểm tra có chọn discount type không
        if (empty($strategies)) {
            return [
                'originalAmount' => $orderAmount,
                'discountAmount' => 0,
                'finalAmount' => $orderAmount,
                'description' => 'Không có giảm giá áp dụng'
            ];
        }

        // Nếu không chọn discount type, trả về không giảm giá
        if (!$selectedDiscountType) {
            return [
                'originalAmount' => $orderAmount,
                'discountAmount' => 0,
                'finalAmount' => $orderAmount,
                'description' => 'Chưa chọn loại giảm giá'
            ];
        }

        // Sử dụng DiscountContext mới
        $discountContext = new DiscountContext();
        if ($selectedDiscountType === 'membership') {
            $discountContext->setDiscountStrategy(new \App\Strategies\Discount\MembershipDiscountStrategy());
        } elseif ($selectedDiscountType === 'volume') {
            $discountContext->setDiscountStrategy(new \App\Strategies\Discount\VolumeDiscountStrategy());
        } else {
            return [
                'originalAmount' => $orderAmount,
                'discountAmount' => 0,
                'finalAmount' => $orderAmount,
                'description' => 'Không có giảm giá áp dụng'
            ];
        }
        $result = $discountContext->calculateDiscount($tempOrder);

        return $result['selected'];
    }

    /**
     * Chọn các strategy phù hợp dựa trên đơn hàng
     * Chỉ trả về các strategy tự động: VIP và Volume
     */
    private function selectApplicableStrategies($order)
    {
        $strategies = [];

        // 1. Membership Discount Strategy (VIP)
        $membershipStrategy = new MembershipDiscountStrategy();
        $membershipResult = $membershipStrategy->processDiscount($order);
        if ($membershipResult['applicable']) {
                $strategies[] = $membershipStrategy;
        }
        
        // 2. Volume Discount Strategy
        $volumeStrategy = new VolumeDiscountStrategy();
        $volumeResult = $volumeStrategy->processDiscount($order);
        if ($volumeResult['applicable']) {
                $strategies[] = $volumeStrategy;
        }

        return $strategies;
    }

    /**
     * API để kiểm tra discount có thể áp dụng (cho frontend)
     * Trả về các strategy tự động: VIP và Volume
     */
    public function checkAvailableDiscounts(Request $request)
    {
        try {
            $cart = Session::get('cart');
            if (!$cart || empty($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng trống',
                    'discounts' => []
                ]);
            }

            // Tính tổng đơn hàng
            $subtotal = 0;
            $totalQuantity = 0;
            foreach($cart as $item) {
                $subtotal += $item['product_price'] * $item['product_qty'];
                $totalQuantity += $item['product_qty'];
            }

            // Tạo order giả để test
            $tempOrder = new \stdClass();
            $tempOrder->total_amount = $subtotal;
            $tempOrder->user = null;
            
            // Lấy thông tin khách hàng nếu có
            $customerId = Session::get('customer_id');
            if ($customerId) {
                $tempOrder->user = DB::table('tbl_customers')->where('customer_id', $customerId)->first();
            }
            
            // Fake membership level for testing
            if (!$tempOrder->user) {
                $tempOrder->user = new \stdClass();
                $tempOrder->user->customer_id = 0;
            }
            
            if (!isset($tempOrder->user->membership_level) || !$tempOrder->user->membership_level) {
                if ($subtotal >= 3000000) {
                    $tempOrder->user->membership_level = 'gold';
                } elseif ($subtotal >= 2000000) {
                    $tempOrder->user->membership_level = 'silver';
                } elseif ($subtotal >= 1000000) {
                    $tempOrder->user->membership_level = 'bronze';
                }
            }
            
            // Tạo order_details giả
            $tempOrder->order_details = collect();
            for ($i = 0; $i < $totalQuantity; $i++) {
                $detail = new \stdClass();
                $detail->quantity = 1;
                $tempOrder->order_details->push($detail);
            }

            // Lấy các strategy có sẵn (chỉ strategy tự động)
            $strategies = $this->selectApplicableStrategies($tempOrder);
            
            // Lặp qua từng strategy để trả về danh sách các discount khả dụng
            $availableDiscounts = [];
            foreach ([
                new \App\Strategies\Discount\MembershipDiscountStrategy(),
                new \App\Strategies\Discount\VolumeDiscountStrategy()
            ] as $discountStrategy) {
                $discountContext = new \App\Strategies\Discount\DiscountContext();
                $discountContext->setDiscountStrategy($discountStrategy);
                $availableDiscounts[] = $discountContext->calculateDiscount($tempOrder);
            }

            return response()->json([
                'success' => true,
                'subtotal' => $subtotal,
                'discounts' => $availableDiscounts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage(),
                'discounts' => []
            ]);
        }
    }

    /**
     * Tạo thông tin shipping
     */
    private function createShipping(Request $request)
    {
        $shipping = new Shipping();
        $shipping->shipping_name = $request->shipping_name;
        $shipping->shipping_email = $request->shipping_email;
        $shipping->shipping_phone = $request->shipping_phone;
        $shipping->shipping_address = $request->shipping_address;
        $shipping->shipping_note = $request->shipping_note ?? '';
        $shipping->shipping_method = $request->shipping_select;
        $shipping->save();
        
        return $shipping->shipping_id;
    }
    
    /**
     * Tạo thông tin payment
     */
    private function createPayment(Request $request)
    {
        $paymentData = [
            'payment_method' => $request->payment_select == 2 ? 'COD' : 'Credit Card',
            'payment_status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        return DB::table('tbl_payment')->insertGetId($paymentData);
    }

    /**
     * Tạo thông tin mô tả discount
     */
    private function getDiscountDescription($selectedDiscountType, $discountAmount, $couponDiscount) {
        $descriptions = [];
        
        if ($selectedDiscountType === 'membership' && $discountAmount > 0) {
            $descriptions[] = 'VIP Discount: -' . number_format($discountAmount, 0, ',', '.') . 'đ';
        } elseif ($selectedDiscountType === 'volume' && $discountAmount > 0) {
            $descriptions[] = 'Volume Discount: -' . number_format($discountAmount, 0, ',', '.') . 'đ';
        }
        
        if ($couponDiscount > 0) {
            $descriptions[] = 'Coupon: -' . number_format($couponDiscount, 0, ',', '.') . 'đ';
        }
        
        return implode(', ', $descriptions);
    }
}