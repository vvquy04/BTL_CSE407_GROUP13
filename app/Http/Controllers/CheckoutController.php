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

// Import Strategy Pattern classes
use App\Strategies\Payment\PaymentContext;
use App\Strategies\Payment\CashOnDeliveryStrategy;
use App\Strategies\Payment\CreditCardPaymentStrategy;
use App\Strategies\Payment\IPaymentStrategy;
use Illuminate\Support\Facades\Validator;
use App\Strategies\Shipping\ShippingContext;
use App\Strategies\Shipping\StandardShippingStrategy;
use App\Strategies\Shipping\ExpressShippingStrategy;

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
    
    public function confirmOrder(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'shipping_name' => 'required|string|max:255',
                'shipping_email' => 'required|email|max:255',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address_detail' => 'required|string|max:500',
                'payment_select' => 'required|integer|in:2,3',
                'shipping_select' => 'required|integer|in:1,2'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Thông tin không hợp lệ: ' . implode(', ', $validator->errors()->all())
                    ]);
                } else {
                    Session::put('message', 'Thông tin không hợp lệ: ' . implode(', ', $validator->errors()->all()));
                    return Redirect::to('/payment');
                }
            }

            // Kiểm tra giỏ hàng
            $cart = Session::get('cart');
            if (!$cart || empty($cart)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Giỏ hàng trống!'
                    ]);
                } else {
                    Session::put('message', 'Giỏ hàng trống!');
                    return Redirect::to('/gio-hang');
                }
            }

            // Lưu thông tin shipping
            $shipping_data = [
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_street' => $request->shipping_address_detail,
                'shipping_ward' => $request->nameWards ? $this->getWardName($request->nameWards) : '',
                'shipping_district' => $request->nameProvince ? $this->getDistrictName($request->nameProvince) : '',
                'shipping_city' => $request->nameCity ? $this->getCityName($request->nameCity) : '',
                'shipping_note' => $request->shipping_note ?? '',
                'shipping_method' => $request->shipping_select,
                'created_at' => now(),
                'updated_at' => now()
            ];
            $shipping_id = DB::table('tbl_shipping')->insertGetId($shipping_data);
            Session::put('shipping_id', $shipping_id);
            
            // Xử lý vận chuyển theo Strategy Pattern
            $shipping_method = $request->shipping_select;
            $shipping_context = new ShippingContext();
            
            switch($shipping_method) {
                case 1:
                    $shipping_strategy = new StandardShippingStrategy();
                    break;
                case 2:
                    $shipping_strategy = new ExpressShippingStrategy();
                    break;
                default:
                    $error_message = 'Phương thức vận chuyển không hợp lệ!';
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $error_message
                        ]);
                    } else {
                        Session::put('message', $error_message);
                        return Redirect::to('/payment');
                    }
            }

            $shipping_context->setShippingStrategy($shipping_strategy);
            
            // Xử lý thanh toán theo Strategy Pattern
            $payment_method = $request->payment_select;
            $payment_method_name = '';
            $context = new \App\Strategies\Payment\PaymentContext();
            
            switch($payment_method) {
                case 2:
                    $strategy = new \App\Strategies\Payment\CashOnDeliveryStrategy();
                    $payment_method_name = 'Thanh toán khi nhận hàng (COD)';
                    break;
                case 3:
                    $strategy = new \App\Strategies\Payment\CreditCardPaymentStrategy();
                    $payment_method_name = 'Thanh toán qua thẻ tín dụng';
                    break;
                default:
                    $error_message = 'Phương thức thanh toán không hợp lệ!';
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $error_message
                        ]);
                    } else {
                        Session::put('message', $error_message);
                        return Redirect::to('/payment');
                    }
            }

            $context->setPaymentStrategy($strategy);

            // Tính toán tổng tiền
            $total = 0;
            foreach($cart as $item) {
                $total += $item['product_price'] * $item['product_qty'];
            }

            // Áp dụng coupon
            if(Session::get('coupon')) {
                foreach(Session::get('coupon') as $coupon) {
                    if($coupon['coupon_condition'] == 1) {
                        $total = $total - ($total * $coupon['coupon_number'] / 100);
                    } else {
                        $total = $total - $coupon['coupon_number'];
                    }
                }
            }

            // Thêm phí vận chuyển
            if(Session::get('fee')) {
                $total += Session::get('fee');
            }
            
            // Dữ liệu đơn hàng
            $order_data = [
                'customer_id' => Session::get('customer_id'),
                'shipping_id' => $shipping_id,
                'order_total' => $total,
                'order_status' => 'Đang chờ xử lý',
                'shipping_method' => $shipping_method,
                'ward_name' => $shipping_data['shipping_ward'],
                'district_name' => $shipping_data['shipping_district'],
                'city_name' => $shipping_data['shipping_city'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Thực hiện vận chuyển theo Strategy Pattern
            $shipping_result = $shipping_context->executeShipping($order_data, $request);
            if (!$shipping_result['success']) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $shipping_result['message']
                    ]);
                } else {
                    Session::put('message', $shipping_result['message']);
                    return Redirect::to('/payment');
                }
            }

            // Thực hiện thanh toán theo Strategy Pattern
            $result = $context->executePayment($order_data, $request);

            if($result['success']) {
                // Tạo thông tin đơn hàng để lưu vào session
                $order_info = [
                    'order_code' => 'ORD' . time() . rand(100, 999),
                    'order_total' => $total,
                    'shipping_fee' => Session::get('fee') ?? 0,
                    'payment_method' => $payment_method_name,
                    'shipping_method' => $shipping_context->getShippingMethodName(),
                    'shipping_name' => $request->shipping_name,
                    'shipping_phone' => $request->shipping_phone,
                    'shipping_address_detail' => $request->shipping_address_detail,
                    'shipping_email' => $request->shipping_email,
                    'ward_name' => $shipping_data['shipping_ward'],
                    'district_name' => $shipping_data['shipping_district'],
                    'city_name' => $shipping_data['shipping_city']
                ];

                // Lưu thông tin đơn hàng vào session để các trang thanh toán sử dụng
                Session::put('order_info', $order_info);

                // Lưu thông tin thanh toán
                $payment_data = [
                    'payment_method' => $payment_method_name,
                    'payment_status' => 'Đang chờ xử lý',
                    'payment_amount' => $total,
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

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đặt hàng thành công với phương thức: ' . $payment_method_name,
                        'order_info' => [
                            'payment_method' => $payment_method_name,
                            'shipping_method' => $shipping_context->getShippingMethodName(),
                            'order_total' => number_format($total, 0, ',', '.'),
                            'shipping_fee' => number_format(Session::get('fee') ?? 0, 0, ',', '.'),
                        ],
                        'redirect_url' => $payment_method == 2 ? route('payment-cod') : route('payment-credit')
                    ]);
                } else {
                    switch($payment_method) {
                        case 2:
                            return redirect()->route('payment-cod');
                        case 3:
                            return redirect()->route('payment-credit');
                        default:
                            Session::put('message', 'Phương thức thanh toán không được hỗ trợ!');
                            return Redirect::to('/payment');
                    }
                }
            } else {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message']
                    ]);
                } else {
                    Session::put('message', $result['message']);
                    return Redirect::to('/payment');
                }
            }

        } catch (\Exception $e) {
            $error_message = 'Có lỗi xảy ra: ' . $e->getMessage();
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $error_message
                ]);
            } else {
                Session::put('message', $error_message);
                return Redirect::to('/payment');
            }
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
                'shipping_name' => $order_info['shipping_name'] ?? '',
                'shipping_phone' => $order_info['shipping_phone'] ?? '',
                'shipping_address' => $order_info['shipping_address'] ?? '',
                'shipping_email' => $order_info['shipping_email'] ?? '',
                'order_total' => $order_info['order_total'] ?? 0,
                'payment_method' => 'Thanh toán qua thẻ tín dụng',
                'transaction_id' => 'TXN' . time() . rand(100, 999)
            ];

            Session::put('order_info', $success_info);

            // Clear các session không cần thiết
            Session::forget(['cart', 'coupon', 'fee']);
            
            // Chuyển hướng đến trang thành công
            return redirect()->route('order-success')
                ->with('success', 'Thanh toán thành công! Cảm ơn bạn đã mua hàng.');
            
        } catch (\Exception $e) {
            Log::error('Credit card payment error: ' . $e->getMessage());
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
}