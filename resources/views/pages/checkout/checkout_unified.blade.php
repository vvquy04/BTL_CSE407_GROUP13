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

                        <!-- Chọn phương thức vận chuyển -->
                        <div class="payment-options" style="margin-top: 20px;">
                            <h4><i class="fa fa-truck"></i> Chọn phương thức vận chuyển</h4>
                            <div class="shipping-method" style="border: 1px solid #ddd; border-radius: 5px; margin: 10px 0;">
                                <label style="display: block; padding: 15px; cursor: pointer; margin: 0;">
                                    <input type="radio" name="shipping_select" value="1" checked style="margin-right: 10px;">
                                    <i class="fa fa-truck" style="color: #28a745;"></i>
                                    <strong>Giao hàng tiêu chuẩn</strong>
                                    <p style="margin: 5px 0 0 25px; color: #666; font-size: 13px;">
                                        Giao hàng trong 3 ngày làm việc. Phí vận chuyển từ 10,000đ
                                    </p>
                                </label>
                            </div>
                            <div class="shipping-method" style="border: 1px solid #ddd; border-radius: 5px; margin: 10px 0;">
                                <label style="display: block; padding: 15px; cursor: pointer; margin: 0;">
                                    <input type="radio" name="shipping_select" value="2" style="margin-right: 10px;">
                                    <i class="fa fa-bolt" style="color: #ffc107;"></i>
                                    <strong>Giao hàng nhanh</strong>
                                    <p style="margin: 5px 0 0 25px; color: #666; font-size: 13px;">
                                        Giao hàng trong 1 ngày làm việc. Phí vận chuyển từ 20,000đ
                                    </p>
                                </label>
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
                                
                                <!-- Credit Card Form -->
                                <div id="credit-card-form" style="display: none; padding: 15px; background: #f8f9fa; border-top: 1px solid #ddd;">
                                    <div class="form-group">
                                        <label for="card_number">Số thẻ</label>
                                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                                    </div>
                                    <div class="form-group">
                                        <label for="card_holder">Tên chủ thẻ</label>
                                        <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="NGUYEN VAN A">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expiry_date">Ngày hết hạn</label>
                                                <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cvv">Mã CVV</label>
                                                <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123" maxlength="4">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="alert alert-info" style="margin-top: 10px;">
                                        <i class="fa fa-lock"></i> Thông tin thẻ của bạn được bảo mật và mã hóa
                                    </div>
                                </div>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    // Kiểm tra nếu có lỗi jQuery
    if(typeof $ === 'undefined') {
        alert('Trang web có lỗi tải jQuery. Vui lòng refresh trang!');
        return;
    }
    
    // Initialize form state
    $('.error-field').removeClass('error-field');
    $('#confirm-order-btn').prop('disabled', false);
    
    // Add real-time validation feedback
    $('.shipping_name, .shipping_email, .shipping_phone, .shipping_address_detail').on('blur', function() {
        var $this = $(this);
        var value = $this.val().trim();
        
        if(!value) {
            $this.addClass('error-field');
        } else {
            $this.removeClass('error-field');
            
            // Specific validation for email
            if($this.hasClass('shipping_email')) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if(!emailRegex.test(value)) {
                    $this.addClass('error-field');
                } else {
                    $this.removeClass('error-field');
                }
            }
            
            // Specific validation for phone
            if($this.hasClass('shipping_phone')) {
                var phoneRegex = /^[0-9]{10,11}$/;
                var cleanPhone = value.replace(/[\s\-\(\)]/g, '');
                if(!phoneRegex.test(cleanPhone)) {
                    $this.addClass('error-field');
                } else {
                    $this.removeClass('error-field');
                }
            }
        }
    });
    
    // Real-time validation for select fields
    $('#nameCity, #nameProvince, #nameWards').on('change', function() {
        var $this = $(this);
        if($this.val() === '0') {
            $this.addClass('error-field');
        } else {
            $this.removeClass('error-field');
        }
    });
    
    // Real-time validation for payment method
    $('input[name="payment_select"]').on('change', function() {
        $('.payment-options').removeClass('error-field');
    });    // Xử lý chọn địa chỉ
    $(document).on('change', '.choose', function(){
        var action = $(this).attr('id');
        var ma_id = $(this).val();
        var _token = $('input[name="_token"]').val();
        var result = '';
        
        console.log('Address selection:', { action: action, ma_id: ma_id });
        
        // Kiểm tra action hợp lệ
        if (action !== 'nameCity' && action !== 'nameProvince' && action !== 'nameWards') {
            console.error('Invalid action:', action);
            return;
        }

        // Xác định dropdown cần cập nhật
        if (action === 'nameCity') {
            result = 'nameProvince';
            $('#nameProvince').html('<option value="0">Chọn quận huyện</option>');
            $('#nameWards').html('<option value="0">Chọn xã phường</option>');
        } else if (action === 'nameProvince') {
            result = 'nameWards';
            $('#nameWards').html('<option value="0">Chọn xã phường</option>');
        } else if (action === 'nameWards') {
            result = 'nameWards';
        }

        // Chỉ gửi request khi có ma_id hợp lệ
        if (ma_id && ma_id !== '0') {
            console.log('Sending AJAX request:', { action: action, ma_id: ma_id });
            
            $.ajax({
                url: '{{ URL::to("/get-delivery-home") }}',
                method: 'POST',
                data: { 
                    action: action, 
                    ma_id: ma_id, 
                    _token: _token 
                },
                beforeSend: function() {
                    if (action !== 'nameWards') {
                        $('#' + result).prop('disabled', true).html('<option value="0">Đang tải...</option>');
                    }
                },
                success: function (data) {
                    console.log('AJAX response:', data);
                    $('#' + result).prop('disabled', false).html(data);
                    // Tính lại phí vận chuyển sau khi chọn địa chỉ
                    calculate_delivery();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    if (action !== 'nameWards') {
                        $('#' + result).prop('disabled', false).html('<option value="0">Lỗi tải dữ liệu</option>');
                    }
                }
            });
        } else {
            // Reset các dropdown phụ thuộc
            if(action === 'nameCity') {
                $('#nameProvince').prop('disabled', false).html('<option value="0">Chọn quận huyện</option>');
                $('#nameWards').prop('disabled', false).html('<option value="0">Chọn xã phường</option>');
            } else if(action === 'nameProvince') {
                $('#nameWards').prop('disabled', false).html('<option value="0">Chọn xã phường</option>');
            }
            // Reset phí vận chuyển
            $('.order_fee').text('10,000 đ');
            $('.fee_shipping').val('10000');
            updateTotal();
        }
    });

    // Tính phí vận chuyển
    function calculate_delivery(){
        var cityId = $('#nameCity').val();
        var provinceId = $('#nameProvince').val(); 
        var wardId = $('#nameWards').val();
        var shippingMethod = $('input[name="shipping_select"]:checked').val();
        var _token = $('input[name="_token"]').val();
        
        console.log('Calculating delivery fee:', { cityId, provinceId, wardId, shippingMethod });
        
        if(cityId != '0' && provinceId != '0' && wardId != '0'){
            $.ajax({
                url: '{{ URL::to("/calculate-fee") }}',
                method: 'POST',
                data: { 
                    cityId: cityId, 
                    provinceId: provinceId, 
                    wardId: wardId,
                    shippingMethod: shippingMethod,
                    _token: _token 
                },
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

    function updateTotal() {
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
    $(document).on('change', 'input[name="payment_select"]', function(){
        var method = $(this).val();
        console.log('💳 Payment method changed:', method);
        
        // Ẩn/hiện form thẻ tín dụng
        if(method == '3') {
            $('#credit-card-form').slideDown();
        } else {
            $('#credit-card-form').slideUp();
        }
    });

    // Format số thẻ
    $('#card_number').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        var formattedValue = '';
        for(var i = 0; i < value.length; i++) {
            if(i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        $(this).val(formattedValue);
    });

    // Format ngày hết hạn
    $('#expiry_date').on('input', function() {
        var value = $(this).val().replace(/\D/g, '');
        if(value.length >= 2) {
            value = value.substring(0,2) + '/' + value.substring(2);
        }
        $(this).val(value);
    });

    // Chỉ cho phép nhập số cho CVV
    $('#cvv').on('input', function() {
        $(this).val($(this).val().replace(/\D/g, ''));
    });

    // Xử lý thay đổi phương thức vận chuyển
    $(document).on('change', 'input[name="shipping_select"]', function(){
        var method = $(this).val();
        console.log('🚚 Shipping method changed:', method);
        
        // Tính lại phí vận chuyển
        calculate_delivery();
    });

    // Xử lý xác nhận đơn hàng
    $(document).on('click', '#confirm-order-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        
        // Kiểm tra button có bị disabled không
        if($(this).prop('disabled')) {
            return false;
        }
        
        // Collect form data with better error handling
        var shipping_name = $('.shipping_name').val() ? $('.shipping_name').val().trim() : '';
        var shipping_email = $('.shipping_email').val() ? $('.shipping_email').val().trim() : '';
        var shipping_phone = $('.shipping_phone').val() ? $('.shipping_phone').val().trim() : '';
        var shipping_address_detail = $('.shipping_address_detail').val() ? $('.shipping_address_detail').val().trim() : '';
        var shipping_note = $('.shipping_note').val() ? $('.shipping_note').val().trim() : '';
        var payment_select = $('input[name="payment_select"]:checked').val();
        var nameCity = $('#nameCity').val();
        var nameProvince = $('#nameProvince').val();
        var nameWards = $('#nameWards').val();
        var _token = $('input[name="_token"]').val();

        // Enhanced Validation with specific field focus
        var validation_errors = [];
        
        if(!shipping_name) {
            validation_errors.push('Tên người nhận không được để trống');
            $('.shipping_name').addClass('error-field');
        } else {
            $('.shipping_name').removeClass('error-field');
        }
        
        if(!shipping_email) {
            validation_errors.push('Email không được để trống');
            $('.shipping_email').addClass('error-field');
        } else {
            $('.shipping_email').removeClass('error-field');
        }
        
        if(!shipping_phone) {
            validation_errors.push('Số điện thoại không được để trống');
            $('.shipping_phone').addClass('error-field');
        } else {
            $('.shipping_phone').removeClass('error-field');
        }
        
        if(!shipping_address_detail) {
            validation_errors.push('Địa chỉ chi tiết không được để trống');
            $('.shipping_address_detail').addClass('error-field');
        } else {
            $('.shipping_address_detail').removeClass('error-field');
        }
        
        if(!nameCity || nameCity == '0') {
            validation_errors.push('Vui lòng chọn tỉnh/thành phố');
            $('#nameCity').addClass('error-field');
        } else {
            $('#nameCity').removeClass('error-field');
        }
        
        if(!nameProvince || nameProvince == '0') {
            validation_errors.push('Vui lòng chọn quận/huyện');
            $('#nameProvince').addClass('error-field');
        } else {
            $('#nameProvince').removeClass('error-field');
        }
        
        if(!nameWards || nameWards == '0') {
            validation_errors.push('Vui lòng chọn xã/phường');
            $('#nameWards').addClass('error-field');
        } else {
            $('#nameWards').removeClass('error-field');
        }

        // Validate email format
        if(shipping_email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!emailRegex.test(shipping_email)) {
                validation_errors.push('Email không đúng định dạng');
                $('.shipping_email').addClass('error-field');
            }
        }

        // Validate phone format  
        if(shipping_phone) {
            var phoneRegex = /^[0-9]{10,11}$/;
            var cleanPhone = shipping_phone.replace(/[\s\-\(\)]/g, '');
            if(!phoneRegex.test(cleanPhone)) {
                validation_errors.push('Số điện thoại phải có 10-11 chữ số');
                $('.shipping_phone').addClass('error-field');
            }
        }

        // Validate payment method
        if(!payment_select) {
            validation_errors.push('Vui lòng chọn phương thức thanh toán');
            $('.payment-options').addClass('error-field');
        } else {
            $('.payment-options').removeClass('error-field');
        }

        // Show validation errors
        if(validation_errors.length > 0) {
            alert('Vui lòng kiểm tra lại thông tin:\n\n• ' + validation_errors.join('\n• '));
            
            // Focus on first error field
            var firstErrorField = $('.error-field').first();
            if(firstErrorField.length > 0) {
                firstErrorField.focus();
            }
            return false;
        }

        // Tạo địa chỉ đầy đủ
        var cityName = $('#nameCity option:selected').text();
        var provinceName = $('#nameProvince option:selected').text();
        var wardName = $('#nameWards option:selected').text();
        var shipping_address = shipping_address_detail + ', ' + wardName + ', ' + provinceName + ', ' + cityName;

        // Debug thông tin địa chỉ trước khi gửi
        console.log('Address values:', {
            nameWards: $('#nameWards').val(),
            nameProvince: $('#nameProvince').val(),
            nameCity: $('#nameCity').val()
        });

        var formData = {
            shipping_name: shipping_name,
            shipping_email: shipping_email,
            shipping_phone: shipping_phone,
            shipping_address: shipping_address,
            shipping_address_detail: shipping_address_detail,
            shipping_note: shipping_note,
            payment_select: payment_select,
            shipping_select: $('input[name="shipping_select"]:checked').val(),
            nameWards: $('#nameWards').val(),
            nameProvince: $('#nameProvince').val(),
            nameCity: $('#nameCity').val(),
            _token: _token
        };

        // Thêm thông tin thẻ tín dụng nếu chọn phương thức thanh toán thẻ
        if(payment_select == '3') {
            var card_number = $('#card_number').val().replace(/\s/g, '');
            var card_holder = $('#card_holder').val();
            var expiry_date = $('#expiry_date').val();
            var cvv = $('#cvv').val();

            // Validate thông tin thẻ
            if(!card_number || !card_holder || !expiry_date || !cvv) {
                alert('Vui lòng nhập đầy đủ thông tin thẻ tín dụng');
                return false;
            }

            formData.card_number = card_number;
            formData.card_holder = card_holder;
            formData.expiry_date = expiry_date;
            formData.cvv = cvv;
        }

        console.log('🚀 Sending order data:', formData);

        // Hiển thị loading state
        var originalText = $('#confirm-order-btn').html();
        $('#confirm-order-btn').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý đơn hàng...').prop('disabled', true);

        $.ajax({
            url: '{{ URL::to("/confirm-order") }}',
            method: 'POST',
            data: formData,
            dataType: 'json',
            timeout: 30000, // 30 second timeout
            success: function(response){
                console.log('✅ Order response:', response);
                
                if(response.success){
                    alert('🎉 ' + response.message);
                    
                    // Clear form data
                    $('#checkout-form')[0].reset();
                    
                    if(response.redirect_url) {
                        console.log('Redirecting to:', response.redirect_url);
                        window.location.href = response.redirect_url;
                    } else {
                        window.location.href = '{{ URL::to("/order-success") }}';
                    }
                } else {
                    alert('❌ Lỗi: ' + (response.message || 'Có lỗi không xác định xảy ra'));
                    $('#confirm-order-btn').html(originalText).prop('disabled', false);
                }
            },
            error: function(xhr, status, error){
                console.error('❌ Order submission error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    readyState: xhr.readyState
                });
                
                var errorMessage = 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!';
                
                if(xhr.status === 422) {
                    // Validation errors
                    try {
                        var errorData = JSON.parse(xhr.responseText);
                        if(errorData.errors) {
                            var errorList = [];
                            for(var field in errorData.errors) {
                                errorList.push(...errorData.errors[field]);
                            }
                            errorMessage = 'Thông tin không hợp lệ:\n• ' + errorList.join('\n• ');
                        } else if(errorData.message) {
                            errorMessage = errorData.message;
                        }
                    } catch(e) {
                        console.error('Error parsing validation response:', e);
                    }
                } else if(xhr.status === 500) {
                    errorMessage = 'Lỗi server. Vui lòng thử lại sau hoặc liên hệ hỗ trợ.';
                } else if(xhr.status === 0) {
                    errorMessage = 'Mất kết nối mạng. Vui lòng kiểm tra kết nối và thử lại.';
                } else if(status === 'timeout') {
                    errorMessage = 'Yêu cầu quá lâu. Vui lòng thử lại.';
                } else if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                alert('❌ ' + errorMessage);
                $('#confirm-order-btn').html(originalText).prop('disabled', false);
            }
        });
        
        return false;
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

/* Error field styling */
.error-field {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25) !important;
    background-color: #fff5f5 !important;
}

.error-field:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25) !important;
}

/* Loading button styling */
#confirm-order-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

/* Payment method error styling */
.payment-options.error-field {
    border: 2px solid #dc3545;
    border-radius: 5px;
    background-color: #fff5f5;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .col-sm-6 {
        margin-bottom: 20px;
    }
    
    .order-summary {
        margin-top: 20px;
    }
}

/* Better select styling */
select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

select.form-control:disabled {
    background-color: #f8f9fa;
    opacity: 0.6;
    cursor: not-allowed;
}

/* Loading animation */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Credit card form styles */
#credit-card-form {
    transition: all 0.3s ease;
}

#credit-card-form .form-group {
    margin-bottom: 15px;
}

#credit-card-form label {
    font-weight: 500;
    margin-bottom: 5px;
}

#credit-card-form .form-control {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 8px 12px;
}

#credit-card-form .form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

#credit-card-form .alert-info {
    background-color: #e8f4f8;
    border-color: #bee5eb;
    color: #0c5460;
}

/* Payment method selection styles */
.payment-method input[type="radio"]:checked + i {
    color: #007bff !important;
}

.payment-method:hover {
    border-color: #007bff !important;
    background-color: #f8f9fa;
}

.shipping-method:hover {
    border-color: #28a745 !important;
    background-color: #f8f9fa;
}

.shipping-method input:checked + i {
    color: #28a745 !important;
}
</style>

@endsection
