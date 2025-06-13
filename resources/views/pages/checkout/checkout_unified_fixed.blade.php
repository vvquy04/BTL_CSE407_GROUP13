@extends('layout')
@section("title","Trang thanh toán")
@section("content")
<section id="cart_items">
    <div class="breadcrumbs">
        <ol class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li class="active">Thanh toán</li>
        </ol>
    </div><!--/breadcrums-->
    
    <div class="register-req">
        <p>Vui lòng nhập thông tin giao hàng và chọn phương thức thanh toán</p>
    </div><!--/register-req-->

    @if(session()->has('message'))
    <div class="alert alert-danger">
        {{ session()->get('message') }}
    </div>
    @elseif(session()->has('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div>
    @endif

    <form id="checkout-form">
        {{ csrf_field() }}
        
        <div class="shopper-informations">
            <div class="row">
                <!-- PHẦN 1: THÔNG TIN GIAO HÀNG -->
                <div class="col-sm-6 clearfix">
                    <div class="bill-to">
                        <p><i class="fa fa-truck"></i> Thông tin giao hàng</p>
                        <div class="form-one">
                            <input type="text" name="shipping_name" class="shipping_name form-control" placeholder="Tên người nhận *" required style="margin-bottom: 10px;">
                            <input type="email" name="shipping_email" class="shipping_email form-control" placeholder="Địa chỉ email *" required style="margin-bottom: 10px;">
                            <input type="text" name="shipping_phone" class="shipping_phone form-control" placeholder="Số điện thoại *" required style="margin-bottom: 10px;">
                            
                            <!-- Địa chỉ chi tiết -->
                            <input type="text" name="shipping_address_detail" class="shipping_address_detail form-control" placeholder="Địa chỉ chi tiết (số nhà, ngõ, đường) *" required style="margin-bottom: 10px;">
                            
                            <!-- Chọn địa điểm -->
                            <div class="row" style="margin: 10px 0;">
                                <div class="col-md-12">
                                    <select class="form-control choose city" name="nameCity" id="nameCity" required style="margin-bottom: 10px;">
                                        <option value="0">Chọn tỉnh thành phố</option>
                                        @foreach($cityData as $key => $ci) 
                                            <option value="{{ $ci->matp }}">{{ $ci->name_city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin: 10px 0;">
                                <div class="col-md-6">
                                    <select class="form-control choose province" name="nameProvince" id="nameProvince" required style="margin-bottom: 10px;">
                                        <option value="0">Chọn quận huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-control choose ward" name="nameWards" id="nameWards" required style="margin-bottom: 10px;">
                                        <option value="0">Chọn xã phường</option>
                                    </select>
                                </div>
                            </div>
                            
                            <textarea name="shipping_note" class="shipping_note form-control" placeholder="Ghi chú đơn hàng của bạn" rows="3" style="margin-bottom: 10px;"></textarea>
                            
                            <input type="hidden" name="fee_shipping" class="fee_shipping" value="0">
                            @if(Session::get('coupon'))
                                @foreach(Session::get('coupon') as $key => $val)
                                    <input type="hidden" name="coupon_value" class="coupon_value" value="{{$val['coupon_code']}}">
                                @endforeach
                            @else
                                <input type="hidden" name="coupon_value" class="coupon_value" value="0">
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- PHẦN 2: THÔNG TIN ĐƠN HÀNG VÀ THANH TOÁN -->
                <div class="col-sm-6 clearfix">
                    <div class="order-message">
                        <p><i class="fa fa-shopping-cart"></i> Thông tin đơn hàng</p>
                        
                        <!-- Hiển thị giỏ hàng -->
                        <div class="table-responsive cart_info" style="margin-bottom: 20px;">
                            <?php $totalcartPrice = 0; ?>
                            
                            <table class="table table-condensed">
                                <thead>
                                    <tr class="cart_menu">
                                        <td class="description">Sản phẩm</td>
                                        <td class="price">Giá</td>
                                        <td class="quantity">SL</td>
                                        <td class="total">Thành tiền</td>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(Session::get('cart'))
                                    @foreach(Session::get('cart') as $key => $cart)
                                    <tr>
                                        <td class="cart_description">
                                            <h4><a href="{{URL::to('/chi-tiet-san-pham/'.$cart['product_id'])}}">{{$cart['product_name']}}</a></h4>
                                            <p>Mã: {{$cart['product_id']}}</p>
                                        </td>
                                        <td class="cart_price">
                                            <p>{{number_format($cart['product_price'],0,',','.')}} đ</p>
                                        </td>
                                        <td class="cart_quantity">
                                            <p>{{$cart['product_qty']}}</p>
                                        </td>
                                        <td class="cart_total">
                                            <p class="cart_total_price">
                                                {{number_format($cart['product_price'] * $cart['product_qty'],0,',','.')}} đ
                                            </p>
                                            <?php
                                            $totalcartPrice += $cart['product_price'] * $cart['product_qty'];
                                            ?>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center">Giỏ hàng trống</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Tổng tiền và coupon -->
                        <div class="order-summary" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                            <div class="row">
                                <div class="col-md-6">Tổng tiền sản phẩm:</div>
                                <div class="col-md-6 text-right"><strong id="cart_subtotal">{{number_format($totalcartPrice,0,',','.')}} đ</strong></div>
                            </div>
                            
                            @if(Session::get('coupon'))
                                @foreach(Session::get('coupon') as $key => $val)
                                <div class="row">
                                    <div class="col-md-6">
                                        Mã giảm giá ({{$val['coupon_code']}}):
                                        @if($val['coupon_condition']==1)
                                            {{$val['coupon_number']}}%
                                        @else
                                            {{number_format($val['coupon_number'],0,',','.')}} đ
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-right text-success">
                                        @if($val['coupon_condition']==1)
                                            -{{number_format($totalcartPrice*$val['coupon_number']/100,0,',','.')}} đ
                                            <?php $totalcartPrice = $totalcartPrice - ($totalcartPrice*$val['coupon_number']/100); ?>
                                        @else
                                            -{{number_format($val['coupon_number'],0,',','.')}} đ
                                            <?php $totalcartPrice = $totalcartPrice - $val['coupon_number']; ?>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @endif

                            <div class="row">
                                <div class="col-md-6">Phí vận chuyển:</div>
                                <div class="col-md-6 text-right"><span class="order_fee">10,000 đ</span></div>
                            </div>
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6"><strong>Tổng thanh toán:</strong></div>
                                <div class="col-md-6 text-right"><strong class="order_total text-primary" style="font-size: 18px;">{{number_format($totalcartPrice + 10000,0,',','.')}} đ</strong></div>
                            </div>
                        </div>

                        <!-- Chọn phương thức thanh toán -->
                        <div class="payment-options" style="margin-top: 20px;">
                            <h4><i class="fa fa-credit-card"></i> Chọn phương thức thanh toán</h4>
                            <div class="payment-method" style="border: 1px solid #ddd; border-radius: 5px; margin: 10px 0;">
                                <label style="display: block; padding: 15px; cursor: pointer; margin: 0;">
                                    <input type="radio" name="payment_select" value="2" checked style="margin-right: 10px;">
                                    <i class="fa fa-money" style="color: #28a745;"></i>
                                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                                    <p style="margin: 5px 0 0 25px; color: #666; font-size: 13px;">Thanh toán bằng tiền mặt khi nhận hàng. Đơn giản, an toàn.</p>
                                </label>
                            </div>
                            <div class="payment-method" style="border: 1px solid #ddd; border-radius: 5px; margin: 10px 0;">
                                <label style="display: block; padding: 15px; cursor: pointer; margin: 0;">
                                    <input type="radio" name="payment_select" value="3" style="margin-right: 10px;">
                                    <i class="fa fa-credit-card" style="color: #007bff;"></i>
                                    <strong>Thanh toán qua thẻ tín dụng</strong>
                                    <p style="margin: 5px 0 0 25px; color: #666; font-size: 13px;">Thanh toán trực tuyến qua thẻ Visa, MasterCard. Bảo mật cao.</p>
                                </label>
                            </div>
                        </div>

                        <!-- Nút xác nhận -->
                        <div class="payment-button" style="margin-top: 20px;">
                            <button type="button" class="btn btn-primary btn-lg btn-block" id="confirm-order-btn" style="padding: 15px; font-size: 16px;">
                                <i class="fa fa-check-circle"></i> Xác nhận đặt hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div style="clear:both"></div>
</section> <!--/#cart_items-->

<script type="text/javascript">
$(document).ready(function(){
    console.log('Checkout page loaded');
    
    // Xử lý chọn địa chỉ
    $('.choose').on('change', function(){
        var action = $(this).attr('id');
        var ma_id = $(this).val();
        var _token = $('input[name="_token"]').val();
        var result = '';
        
        console.log('Address change:', action, ma_id);
        
        if(action == 'nameCity'){
            result = 'nameProvince';
            $('#nameProvince').html('<option value="0">Chọn quận huyện</option>');
            $('#nameWards').html('<option value="0">Chọn xã phường</option>');
        } else if(action == 'nameProvince') {
            result = 'nameWards';
            $('#nameWards').html('<option value="0">Chọn xã phường</option>');
        }
        
        if(ma_id != '0' && result != '') {
            $.ajax({
                url: '{{ URL::to("/get-delivery-home") }}',
                method: 'POST',
                data: { action: action, ma_id: ma_id, _token: _token },
                success: function (data) {
                    $('#' + result).html(data);
                    console.log('Address loaded successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Address loading error:', error);
                    alert('Có lỗi khi tải dữ liệu địa chỉ!');
                }
            });
        }
    });

    // Tính phí vận chuyển
    $('.choose').on('change', function(){
        calculate_delivery();
    });

    function calculate_delivery(){
        var cityId = $('#nameCity').val();
        var provinceId = $('#nameProvince').val(); 
        var wardId = $('#nameWards').val();
        var _token = $('input[name="_token"]').val();
        
        console.log('Calculating delivery fee:', cityId, provinceId, wardId);
        
        if(cityId != '0' && provinceId != '0' && wardId != '0'){
            $.ajax({
                url: '{{ URL::to("/calculate-fee") }}',
                method: 'POST',
                data: { cityId: cityId, provinceId: provinceId, wardId: wardId, _token: _token },
                success: function(data){
                    console.log('Fee calculation result:', data);
                    if(data.success) {
                        $('.order_fee').text(formatNumber(data.fee) + ' đ');
                        $('.fee_shipping').val(data.fee);
                        updateTotal();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Fee calculation error:', error);
                }
            });
        }
    }

    function updateTotal(){
        var cartTotal = {{ $totalcartPrice }};
        var fee = parseInt($('.fee_shipping').val()) || 0;
        var total = cartTotal + fee;
        $('.order_total').text(formatNumber(total) + ' đ');
        console.log('Total updated:', total);
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Xử lý thay đổi phương thức thanh toán
    $('input[name="payment_select"]').on('change', function(){
        var method = $(this).val();
        console.log('Payment method changed:', method);
        
        if(method == '2') {
            console.log('COD selected');
        } else if(method == '3') {
            console.log('Credit card selected');
        }
    });

    // Xử lý xác nhận đơn hàng
    $('#confirm-order-btn').click(function(e){
        e.preventDefault();
        console.log('Confirm order button clicked');
        
        var shipping_name = $('.shipping_name').val().trim();
        var shipping_email = $('.shipping_email').val().trim();
        var shipping_phone = $('.shipping_phone').val().trim(); 
        var shipping_address_detail = $('.shipping_address_detail').val().trim();
        var shipping_note = $('.shipping_note').val().trim();
        var payment_select = $('input[name="payment_select"]:checked').val();
        var nameCity = $('#nameCity').val();
        var nameProvince = $('#nameProvince').val();
        var nameWards = $('#nameWards').val();
        var _token = $('input[name="_token"]').val();

        console.log('Form data:', {
            shipping_name, shipping_email, shipping_phone, 
            shipping_address_detail, payment_select,
            nameCity, nameProvince, nameWards
        });

        // Validation
        if(!shipping_name || !shipping_email || !shipping_phone || !shipping_address_detail || nameCity == '0' || nameProvince == '0' || nameWards == '0'){
            alert('Vui lòng điền đầy đủ thông tin giao hàng!');
            return;
        }

        // Validate email
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!emailRegex.test(shipping_email)) {
            alert('Vui lòng nhập email hợp lệ!');
            $('.shipping_email').focus();
            return;
        }

        // Validate phone
        var phoneRegex = /^[0-9]{10,11}$/;
        if(!phoneRegex.test(shipping_phone.replace(/\s+/g, ''))) {
            alert('Vui lòng nhập số điện thoại hợp lệ (10-11 số)!');
            $('.shipping_phone').focus();
            return;
        }

        if(!payment_select) {
            alert('Vui lòng chọn phương thức thanh toán!');
            return;
        }

        // Tạo địa chỉ đầy đủ
        var cityName = $('#nameCity option:selected').text();
        var provinceName = $('#nameProvince option:selected').text();
        var wardName = $('#nameWards option:selected').text();
        var shipping_address = shipping_address_detail + ', ' + wardName + ', ' + provinceName + ', ' + cityName;

        var formData = {
            shipping_name: shipping_name,
            shipping_email: shipping_email,
            shipping_phone: shipping_phone,
            shipping_address: shipping_address,
            shipping_note: shipping_note,
            payment_select: payment_select,
            _token: _token
        };

        console.log('Sending order data:', formData);

        // Hiển thị loading
        $('#confirm-order-btn').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);

        $.ajax({
            url: '{{ URL::to("/confirm-order") }}',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response){
                console.log('Order response:', response);
                
                if(response.success){
                    alert('🎉 ' + response.message);
                    if(response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        window.location.href = '{{ URL::to("/order-success") }}';
                    }
                } else {
                    alert('❌ Lỗi: ' + response.message);
                    $('#confirm-order-btn').html('<i class="fa fa-check-circle"></i> Xác nhận đặt hàng').prop('disabled', false);
                }
            },
            error: function(xhr, status, error){
                console.error('Order error:', xhr.responseText);
                var errorMessage = 'Có lỗi xảy ra. Vui lòng thử lại!';
                
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if(xhr.responseText) {
                    try {
                        var errorData = JSON.parse(xhr.responseText);
                        if(errorData.message) {
                            errorMessage = errorData.message;
                        }
                    } catch(e) {
                        console.error('Error parsing response:', e);
                    }
                }
                
                alert('❌ ' + errorMessage);
                $('#confirm-order-btn').html('<i class="fa fa-check-circle"></i> Xác nhận đặt hàng').prop('disabled', false);
            }
        });
    });
});
</script>

<style>
.payment-method:hover {
    border-color: #007bff !important;
    background-color: #f8f9fa;
}

.payment-method input:checked + i {
    color: #007bff !important;
}

.order-summary {
    font-size: 14px;
}

.order-summary .row {
    margin-bottom: 5px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.alert {
    margin-bottom: 20px;
}
</style>

@endsection
