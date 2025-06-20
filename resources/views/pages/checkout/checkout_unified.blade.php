@extends('layout')
@section("title","Trang thanh toán")
@section("content")
<section id="cart_items">
    <!-- Compact breadcrumb and header -->
    <div style="background: #f8f9fa; padding: 10px 0; margin-bottom: 20px; border-bottom: 1px solid #dee2e6;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb" style="background: none; margin: 0; padding: 0;">
                            <li><a href="#" style="color: #6c757d; text-decoration: none;">Home</a></li>
                            <li class="active" style="color: #007bff; font-weight: 500;">Thanh toán</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 ">
                    <h4 style="margin: 0; color: #495057;">
                        <i class="fa fa-credit-card"></i> Hoàn tất đơn hàng
                    </h4>
                </div>
            </div>
        </div>
    </div>

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
            <div class="row" style="margin-left: -10px; margin-right: -10px;">
                <!-- CỘT 1: THÔNG TIN GIAO HÀNG + THÔNG TIN ĐƠN HÀNG -->
                <div class="col-lg-7 col-md-7" style="padding-left: 10px; padding-right: 10px;">
                    <!-- PHẦN 1: THÔNG TIN GIAO HÀNG -->
                    <div class="shipping-info-panel" style="background: #fff; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="panel-header" style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #007bff;">
                            <i class="fa fa-truck" style="font-size: 20px; color: #007bff; margin-right: 10px;"></i>
                            <h4 style="margin: 0; color: #495057; font-weight: 600;">Thông tin giao hàng</h4>
                        </div>
                        
                        <!-- Compact form layout -->
                        <div class="shipping-form-compact">
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-8">
                                    <input type="text" name="shipping_name" class="shipping_name form-control" placeholder="Tên người nhận *" required style="height: 40px;">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="shipping_phone" class="shipping_phone form-control" placeholder="Số điện thoại *" required style="height: 40px;">
                                </div>
                            </div>
                            
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12">
                                    <input type="email" name="shipping_email" class="shipping_email form-control" placeholder="Địa chỉ email *" required style="height: 40px;">
                                </div>
                            </div>
                            
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12">
                                    <input type="text" name="shipping_address_detail" class="shipping_address_detail form-control" placeholder="Địa chỉ chi tiết (số nhà, ngõ, đường) *" required style="height: 40px;">
                                </div>
                            </div>
                            
                            <!-- Location selects - compact 3-column layout -->
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-4">
                                    <select class="form-control choose city" name="nameCity" id="nameCity" required style="height: 40px;">
                                        <option value="0">Tỉnh/Thành phố</option>
                                        @foreach($cityData as $key => $ci) 
                                            <option value="{{ $ci->matp }}">{{ $ci->name_city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control choose province" name="nameProvince" id="nameProvince" required style="height: 40px;">
                                        <option value="0">Quận/Huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control choose ward" name="nameWards" id="nameWards" required style="height: 40px;">
                                        <option value="0">Xã/Phường</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea name="shipping_note" class="shipping_note form-control" placeholder="Ghi chú đơn hàng (không bắt buộc)" rows="3" style="resize: vertical;"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields -->
                        <input type="hidden" name="fee_shipping" class="fee_shipping" value="0">
                        <input type="hidden" name="selected_discount_type" id="selected-discount-type" value="none">
                        @if(Session::get('coupon'))
                            @foreach(Session::get('coupon') as $key => $val)
                                <input type="hidden" name="coupon_value" class="coupon_value" value="{{$val['coupon_code']}}">
                            @endforeach
                        @else
                            <input type="hidden" name="coupon_value" class="coupon_value" value="0">
                        @endif
                    </div>
                    
                    <!-- PHẦN 2: THÔNG TIN ĐƠN HÀNG (GIỎ HÀNG) -->
                    <div class="cart-info-panel" style="background: #fff; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="panel-header" style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #28a745;">
                            <i class="fa fa-shopping-cart" style="font-size: 20px; color: #28a745; margin-right: 10px;"></i>
                            <h4 style="margin: 0; color: #495057; font-weight: 600;">Đơn hàng của bạn</h4>
                        </div>
                        
                        <!-- Compact cart display -->
                        <div class="cart-summary-compact" style="background: #f8f9fa; border-radius: 6px; padding: 15px;">
                            <?php $totalcartPrice = 0; ?>
                            
                            <div class="cart-items-list">
                                @if(Session::get('cart'))
                                    @foreach(Session::get('cart') as $key => $cart)
                                    <div class="cart-item-row" style="display: flex; align-items: center; padding: 8px 0; border-bottom: 1px solid #e9ecef;">
                                        <div class="item-info" style="flex: 1; font-size: 14px;">
                                            <strong>{{$cart['product_name']}}</strong>
                                            <div style="color: #6c757d; font-size: 12px;">Mã: {{$cart['product_id']}}</div>
                                        </div>
                                        <div class="item-price" style="text-align: right; font-size: 13px; min-width: 80px;">
                                            <div>{{number_format($cart['product_price'],0,',','.')}}đ</div>
                                            <div style="color: #6c757d;">x{{$cart['product_qty']}}</div>
                                        </div>
                                        <div class="item-total" style="text-align: right; font-weight: 600; min-width: 90px; color: #007bff;">
                                            {{number_format($cart['product_price'] * $cart['product_qty'],0,',','.')}}đ
                                        </div>
                                        <?php $totalcartPrice += $cart['product_price'] * $cart['product_qty']; ?>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted" style="padding: 20px;">
                                        <i class="fa fa-shopping-cart"></i> Giỏ hàng trống
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- CỘT 2: CÁC PHẦN CÒN LẠI (ƯU ĐÃI, TỔNG KẾT, SHIPPING/PAYMENT) -->
                <div class="col-lg-5 col-md-5" style="padding-left: 10px; padding-right: 10px;">
                    <div class="order-panel" style="background: #fff; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <div class="panel-header" style="display: flex; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #dc3545;">
                            <i class="fa fa-calculator" style="font-size: 20px; color: #dc3545; margin-right: 10px;"></i>
                            <h4 style="margin: 0; color: #495057; font-weight: 600;">Thanh toán</h4>
                        </div>

                        <!-- 🎯 TỔNG KẾT ĐƠN HÀNG - Ultra Compact -->
                        <div class="order-summary-ultra-compact" style="background: #fff; border: 1px solid #dee2e6; border-radius: 6px; padding: 15px; margin-bottom: 15px;">
                            <!-- Subtotal -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-size: 14px; color: #6c757d;">Tạm tính:</span>
                                <strong id="cart_subtotal" style="font-size: 15px;">{{number_format($totalcartPrice,0,',','.')}}đ</strong>
                            </div>

                            <!-- Applied coupon (if any) -->
                            @if(Session::get('coupon'))
                                <div class="applied-coupon-ultra-compact" style="background: #d1ecf1; border-radius: 4px; padding: 8px; margin-bottom: 10px; font-size: 13px;">
                                    @foreach(Session::get('coupon') as $key => $val)
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span>
                                            <i class="fa fa-ticket text-info"></i> {{$val['coupon_code']}}
                                        </span>
                                        <div>
                                            <span style="color: #0c5460;">
                                                -@if($val['coupon_condition']==1){{$val['coupon_number']}}%@else{{number_format($val['coupon_number'],0,',','.')}}đ@endif
                                            </span>
                                            <button type="button" class="btn btn-sm" onclick="removeCouponCode()" style="padding: 2px 6px; margin-left: 5px; color: #721c24;">
                                                <i class="fa fa-times" style="font-size: 10px;"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- 🎫 COUPON INPUT - Ultra Compact -->
                            @if(!Session::get('coupon'))
                            <div class="coupon-input-ultra-compact" style="margin-bottom: 10px;">
                                <div style="display: flex; gap: 5px;">
                                    <input type="text" id="coupon-code-input" class="form-control form-control-sm" placeholder="Mã giảm giá" style="height: 32px; font-size: 13px;">
                                    <button type="button" id="apply-coupon-btn" class="btn btn-sm btn-outline-primary" onclick="applyCouponCode()" style="height: 32px; padding: 0 12px; font-size: 12px; white-space: nowrap;">
                                        Áp dụng
                                    </button>
                                </div>
                                <div style="font-size: 10px; color: #6c757d; margin-top: 3px;">
                                    💡 Test: SAVE20, DISCOUNT50K, VIP30
                                </div>
                            </div>
                            @endif

                            <!-- 🎯 DISCOUNT SUGGESTIONS - Ultra Compact -->
                            <div id="discount-suggestions" class="discount-suggestions-ultra-compact" style="display: none; background: #fff3cd; border-radius: 4px; padding: 8px; margin-bottom: 10px; font-size: 12px;">
                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <i class="fa fa-lightbulb-o text-warning"></i>
                                    <div id="discount-suggestions-content"></div>
                                </div>
                            </div>

                            <!-- 🎯 DISCOUNT SELECTION - Ultra Compact -->
                            <div id="discount-selection" style="margin-bottom: 15px; border: 2px solid #ffc107; background: #fff3cd; padding: 10px; border-radius: 6px;">
                                <div style="border: 1px solid #007bff; border-radius: 6px; overflow: hidden;">
                                    <div style="background: #007bff; color: white; padding: 8px 12px; font-size: 13px; font-weight: 500;">
                                        <i class="fa fa-gift"></i> Chọn loại ưu đãi
                                    </div>
                                    <div id="available-discounts" style="padding: 12px; background: #f8f9fa; min-height: 50px;">
                                        <div style="text-align: center; color: #6c757d; font-size: 12px;">
                                            <i class="fa fa-spinner fa-spin"></i> Đang tải ưu đãi...
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 🎯 SELECTED DISCOUNT - Ultra Compact -->
                            <div id="selected-discount-section" style="display: none; background: #d4edda; border-radius: 4px; padding: 8px; margin-bottom: 10px; font-size: 13px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span>
                                        <i class="fa fa-check-circle text-success"></i>
                                        <span id="selected-discount-description">Ưu đãi</span>
                                    </span>
                                    <strong id="selected-discount-amount" class="text-success">-0đ</strong>
                                </div>
                                <div id="selected-discount-details" style="font-size: 10px; color: #155724; margin-top: 3px;"></div>
                            </div>

                            <!-- Shipping & Final Total -->
                            <div style="border-top: 1px solid #dee2e6; padding-top: 10px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; font-size: 13px;">
                                    <span style="color: #6c757d;">Vận chuyển:</span>
                                    <span class="order_fee">10,000đ</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 8px; border-top: 2px solid #007bff;">
                                    <strong style="font-size: 16px; color: #495057;">Tổng cộng:</strong>
                                    <strong class="order_total" style="font-size: 18px; color: #007bff;">
                                        {{number_format($totalcartPrice + 10000,0,',','.')}}đ
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <!-- Compact Shipping & Payment Methods -->
                        <div class="methods-compact-container" style="margin-bottom: 15px;">
                            <!-- Shipping Methods -->
                            <div class="method-section" style="background: #fff; border: 1px solid #e9ecef; border-radius: 6px; margin-bottom: 10px;">
                                <div class="method-header" style="background: #f8f9fa; padding: 8px 12px; border-bottom: 1px solid #e9ecef; font-size: 13px; font-weight: 500;">
                                    <i class="fa fa-truck text-success"></i> Vận chuyển
                                </div>
                                <div class="method-options" style="padding: 10px;">
                                    <label class="method-option" style="display: block; padding: 6px 0; cursor: pointer; font-size: 13px;">
                                        <input type="radio" name="shipping_select" value="1" checked style="margin-right: 8px;">
                                        <i class="fa fa-truck" style="color: #28a745; margin-right: 5px;"></i>
                                        <strong>Tiêu chuẩn</strong> <span style="color: #6c757d;">(3 ngày - 10,000đ)</span>
                                    </label>
                                    <label class="method-option" style="display: block; padding: 6px 0; cursor: pointer; font-size: 13px;">
                                        <input type="radio" name="shipping_select" value="2" style="margin-right: 8px;">
                                        <i class="fa fa-bolt" style="color: #ffc107; margin-right: 5px;"></i>
                                        <strong>Nhanh</strong> <span style="color: #6c757d;">(1 ngày - 20,000đ)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Payment Methods -->
                            <div class="method-section" style="background: #fff; border: 1px solid #e9ecef; border-radius: 6px;">
                                <div class="method-header" style="background: #f8f9fa; padding: 8px 12px; border-bottom: 1px solid #e9ecef; font-size: 13px; font-weight: 500;">
                                    <i class="fa fa-credit-card text-primary"></i> Thanh toán
                                </div>
                                <div class="method-options" style="padding: 10px;">
                                    <label class="method-option" style="display: block; padding: 6px 0; cursor: pointer; font-size: 13px;">
                                        <input type="radio" name="payment_select" value="2" checked style="margin-right: 8px;">
                                        <i class="fa fa-money" style="color: #28a745; margin-right: 5px;"></i>
                                        <strong>COD</strong> <span style="color: #6c757d;">(Thanh toán khi nhận hàng)</span>
                                    </label>
                                    <label class="method-option" style="display: block; padding: 6px 0; cursor: pointer; font-size: 13px;">
                                        <input type="radio" name="payment_select" value="3" style="margin-right: 8px;">
                                        <i class="fa fa-credit-card" style="color: #007bff; margin-right: 5px;"></i>
                                        <strong>Thẻ tín dụng</strong> <span style="color: #6c757d;">(Visa, MasterCard)</span>
                                    </label>
                                    
                                    <!-- Credit Card Form - Ultra Compact -->
                                    <div id="credit-card-form" style="display: none; background: #f8f9fa; border-radius: 4px; padding: 10px; margin-top: 8px;">
                                        <div class="row" style="margin-bottom: 8px;">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control form-control-sm" id="card_number" name="card_number" placeholder="Số thẻ: 1234 5678 9012 3456" maxlength="19" style="height: 32px; font-size: 12px;">
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: 8px;">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control form-control-sm" id="card_holder" name="card_holder" placeholder="Tên chủ thẻ: NGUYEN VAN A" style="height: 32px; font-size: 12px;">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control form-control-sm" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" style="height: 32px; font-size: 12px;">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control form-control-sm" id="cvv" name="cvv" placeholder="CVV" maxlength="4" style="height: 32px; font-size: 12px;">
                                            </div>
                                        </div>
                                        <div style="font-size: 10px; color: #6c757d; margin-top: 5px; text-align: center;">
                                            <i class="fa fa-lock"></i> Thông tin thẻ được bảo mật
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Compact Confirm Button -->
                        <button type="button" class="btn btn-primary btn-block" id="confirm-order-btn" style="padding: 12px; font-size: 15px; font-weight: 600; border-radius: 6px;">
                            <i class="fa fa-check-circle"></i> Xác nhận đặt hàng
                        </button>
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
    
    // 🎯 Tự động tải và hiển thị discount options khi trang load
    console.log('🚀 Page loaded, checking for discount options...');
    setTimeout(function() {
        updateTotal();
    }, 500);
    
    // 🎯 Xử lý khi chọn discount option - CLEAN VERSION
    function handleDiscountSelection() {
        $(document).off('change', 'input[name="discount_choice"]').on('change', 'input[name="discount_choice"]', function() {
            const selectedType = $(this).val();
            const selectedOption = $(this).closest('.discount-choice-option');
            const discountAmount = parseFloat(selectedOption.attr('data-amount')) || 0;
            
            console.log('🎯 Discount selected:', selectedType, 'Amount:', discountAmount);
            
            // Cập nhật UI cho selected option
            $('.discount-choice-option').removeClass('selected');
            selectedOption.addClass('selected');
            
            // Cập nhật hidden input để gửi lên server
            $('#selected-discount-type').val(selectedType);
            
            // Hiển thị thông báo tạm thời
            showDiscountFeedback(selectedType);
            
            // Cập nhật tổng tiền
            updateFinalTotal(parseInt('{{ $totalcartPrice }}'), discountAmount);
        });
        
        // Xử lý click vào div container (tăng khả năng click)
        $(document).off('click', '.discount-choice-option').on('click', '.discount-choice-option', function(e) {
            e.preventDefault();
            if ($(this).hasClass('disabled')) return;
            
            const radio = $(this).find('input[name="discount_choice"]');
            if (radio.length && !radio.is(':disabled')) {
                radio.prop('checked', true).trigger('change');
            }
        });
    }
    
    // Hiển thị feedback khi chọn discount
    function showDiscountFeedback(selectedType) {
        let message = '';
        switch(selectedType) {
            case 'none':
                message = '✅ Không áp dụng ưu đãi';
                break;
            case 'membership':
                message = '✅ Đã chọn: VIP Discount';
                break;
            case 'volume':
                message = '✅ Đã chọn: Volume Discount';
                break;
        }
        
        // Tạo thông báo tạm thời
        if (message) {
            const feedback = $('<div class="discount-feedback" style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 10px 15px; border-radius: 5px; z-index: 1000; font-size: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">' + message + '</div>');
            $('body').append(feedback);
            
            setTimeout(() => {
                feedback.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 2000);
        }
    }
    
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
        var cartTotal = parseInt('{{ $totalcartPrice }}');
        var fee = parseInt($('.fee_shipping').val()) || 0;
        
        // 🎯 Tính discount trước khi cộng shipping fee
        calculateDiscount(cartTotal);
        
        // Total sẽ được update trong calculateDiscount callback
    }

    // 🎯 Hàm tính discount tự động (deprecated - sử dụng loadDiscountOptions thay thế)
    function calculateDiscount(cartTotal) {
        console.log('⚠️ calculateDiscount called - redirecting to loadDiscountOptions');
        loadDiscountOptions();
    }

    // 🎯 Hiển thị các tùy chọn discount để khách hàng chọn 1 trong 3 - FIXED VERSION
    function displayDiscountOptions(discounts, cartTotal) {
        console.log('💎 Display discount options:', discounts);
        
        let optionsHtml = '';
        
        // Option 1: Không áp dụng giảm giá  
        optionsHtml += `
            <div class="discount-choice-option" data-type="none" data-amount="0" style="padding: 8px; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 6px; cursor: pointer; background: #ffffff;">
                <input type="radio" name="discount_choice" value="none" checked style="display: none;">
                <div style="display: flex; align-items: center;">
                    <div style="margin-right: 8px;">
                        <i class="fa fa-ban" style="font-size: 16px; color: #6c757d;"></i>
                    </div>
                    <div style="flex: 1;">
                        <strong>Không áp dụng ưu đãi</strong>
                        <div style="font-size: 11px; color: #6c757d;">Giữ nguyên giá gốc</div>
                    </div>
                    <div style="font-weight: 600; color: #6c757d;">0đ</div>
                </div>
            </div>
        `;
        
        // Options cho từng loại discount (chỉ strategy tự động)
        if (discounts && discounts.length > 0) {
            discounts.forEach((discount) => {
                const isApplicable = discount.applicable;
                const bgColor = isApplicable ? '#d4edda' : '#f8d7da';
                const textColor = isApplicable ? '#155724' : '#721c24';
                const amountText = isApplicable ? `-${formatNumber(discount.amount)}đ` : 'Không đủ điều kiện';
                const disabledClass = !isApplicable ? 'disabled' : '';
                
                let icon = 'fa-gift';
                let title = 'Ưu đãi';
                
                if (discount.type === 'membership') {
                    icon = 'fa-crown';
                    title = 'VIP Discount';
                } else if (discount.type === 'volume') {
                    icon = 'fa-shopping-cart';
                    title = 'Volume Discount';
                }
                
                optionsHtml += `
                    <div class="discount-choice-option ${disabledClass}" data-type="${discount.type}" data-amount="${discount.amount}" style="padding: 8px; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 6px; cursor: ${isApplicable ? 'pointer' : 'not-allowed'}; background: ${bgColor};">
                        <input type="radio" name="discount_choice" value="${discount.type}" ${!isApplicable ? 'disabled' : ''} style="display: none;">
                        <div style="display: flex; align-items: center;">
                            <div style="margin-right: 8px;">
                                <i class="fa ${icon}" style="font-size: 16px; color: ${textColor};"></i>
                            </div>
                            <div style="flex: 1;">
                                <strong style="color: ${textColor};">${title}</strong>
                                <div style="font-size: 11px; color: ${textColor};">${discount.description}</div>
                            </div>
                            <div style="font-weight: 600; color: ${textColor};">${amountText}</div>
                        </div>
                    </div>
                `;
            });
        }
        
        $('#available-discounts').html(optionsHtml);
        showDiscountSelection();
        
        // Khởi tạo event handlers sau khi render
        handleDiscountSelection();
        
        console.log('✅ Discount options rendered and events attached');
    }
    
    // 🎯 Load discount options từ server
    function loadDiscountOptions() {
        console.log('🎯 Loading discount options...');
        
        $.ajax({
            url: '/api/check-available-discounts',
            type: 'POST',
            data: {
                cart_items: getCartItems(),
                user_id: '{{ auth()->id() ?? "null" }}',
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                console.log('✅ Discount options loaded:', response);
                
                if (response.success && response.discounts) {
                    displayDiscountOptions(response.discounts, parseInt('{{ $totalcartPrice }}'));
                } else {
                    console.error('❌ Failed to load discounts:', response.message || 'Unknown error');
                    hideDiscountOptions();
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ AJAX Error loading discounts:', error);
                console.error('❌ Response:', xhr.responseText);
                hideDiscountOptions();
            }
        });
    }
    
    // Helper function để lấy cart items
    function getCartItems() {
        // Giả sử cart items được lưu trong session hoặc có thể lấy từ DOM
        var cartData = '{{ json_encode(session("cart", [])) }}';
        try {
            return JSON.parse(cartData);
        } catch (e) {
            return [];
        }
    }
    
    // 🎯 Hiển thị/ẩn discount selection
    function showDiscountSelection() {
        $('#discount-selection').show();
    }
    
    function hideDiscountOptions() {
        $('#discount-selection').hide();
        hideSelectedDiscount();
    }
    
    function hideSelectedDiscount() {
        $('#selected-discount-section').hide();
    }

    // 🎯 Cập nhật updateFinalTotal để tương thích với các function khác
    function updateFinalTotal(cartTotal, discountAmount) {
        var fee = parseInt($('.fee_shipping').val()) || 10000;
        var finalTotal = cartTotal - discountAmount + fee;
        
        // Cập nhật hiển thị
        $('.order_total').text(formatNumber(finalTotal) + 'đ');
        
        console.log('💰 Final calculation:', {
            cartTotal: cartTotal,
            discount: discountAmount, 
            shipping: fee,
            final: finalTotal
        });
        
        // Hiển thị discount section nếu có giảm giá
        if (discountAmount > 0) {
            $('#selected-discount-section').show();
            $('#selected-discount-amount').text('-' + formatNumber(discountAmount) + 'đ');
        } else {
            $('#selected-discount-section').hide();
        }
    }

    // 🎯 Hiển thị gợi ý discount (compact version)
    function displaySuggestions(suggestions) {
        if(suggestions && suggestions.length > 0) {
            var suggestionsHtml = suggestions.map(function(suggestion) {
                var icon = '';
                switch(suggestion.type) {
                    case 'amount_needed':
                        icon = '<i class="fa fa-money" style="color: #17a2b8;"></i>';
                        break;
                    case 'quantity_needed':
                        icon = '<i class="fa fa-shopping-cart" style="color: #28a745;"></i>';
                        break;
                    case 'membership_upgrade':
                        icon = '<i class="fa fa-star" style="color: #ffc107;"></i>';
                        break;
                    default:
                        icon = '<i class="fa fa-gift" style="color: #17a2b8;"></i>';
                }
                return icon + ' ' + suggestion.message;
            }).join(' • ');
            
            $('#discount-suggestions-content').html(suggestionsHtml);
            $('#discount-suggestions').slideDown();
        } else {
            hideSuggestions();
        }
    }

    // 🎯 Ẩn gợi ý discount
    function hideSuggestions() {
        $('#discount-suggestions').slideUp();
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

        // Debug discount selection
        var selectedDiscountType = $('#selected-discount-type').val() || 'none';
        console.log('🎯 Discount debug:', {
            hiddenInputValue: $('#selected-discount-type').val(),
            selectedDiscountType: selectedDiscountType,
            discountOptions: $('input[name="discount_choice"]:checked').val()
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
            selected_discount_type: selectedDiscountType,
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
        console.log('🎯 Selected discount type:', formData.selected_discount_type);
        console.log('🎯 Discount radio checked:', $('input[name="selected_discount_type"]:checked').val());
        console.log('🎯 Discount hidden input:', $('input[name="selected_discount_type"]').val());

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
    
    // 🎯 COUPON CODE FUNCTIONS
    window.applyCouponCode = function() {
        var couponCode = $('#coupon-code-input').val().trim();
        
        if (!couponCode) {
            alert('⚠️ Vui lòng nhập mã giảm giá!');
            return;
        }
        
        // Show loading
        var $btn = $('#apply-coupon-btn');
        var originalText = $btn.html();
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...').prop('disabled', true);
        
        // Send AJAX request to apply coupon
        $.ajax({
            url: '/apply-coupon-code',
            method: 'POST',
            data: {
                coupon_code: couponCode,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.error) {
                    alert('❌ ' + response.error);
                } else {
                    alert('✅ Mã giảm giá đã được áp dụng thành công!');
                    // Reload page to show updated coupon
                    location.reload();
                }
            },
            error: function(xhr) {
                var errorMessage = 'Có lỗi xảy ra khi áp dụng mã giảm giá!';
                
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                alert('❌ ' + errorMessage);
            },
            complete: function() {
                $btn.html(originalText).prop('disabled', false);
            }
        });
    };
    
    window.removeCouponCode = function() {
        if (!confirm('Bạn có chắc muốn bỏ mã giảm giá đã áp dụng?')) {
            return;
        }
        
        // Send AJAX request to remove coupon
        $.ajax({
            url: '/remove-coupon-code',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert('✅ Đã bỏ mã giảm giá!');
                // Reload page to show updated state
                location.reload();
            },
            error: function(xhr) {
                var errorMessage = 'Có lỗi xảy ra khi bỏ mã giảm giá!';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                alert('❌ ' + errorMessage);
            }
        });
    };
    
    // Allow pressing Enter to apply coupon
    $('#coupon-code-input').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            applyCouponCode();
        }
    });
});

// 🎯 Khởi tạo trang checkout - CLEAN VERSION
$(document).ready(function() {
    console.log('🚀 Checkout page initialized');
    
    // 1. Load discount options ngay khi trang load
    console.log('🎯 Calling loadDiscountOptions...');
    loadDiscountOptions();
    
    // 2. Setup các event handlers khác
    setupAddressHandlers();
    setupShippingHandlers();
    setupPaymentHandlers();
    setupFormValidation();
    
    // 3. Tính toán ban đầu
    updateTotal();
    
    console.log('✅ All handlers initialized');
});

// Setup address selection handlers
function setupAddressHandlers() {
    $(document).off('change', '.choose').on('change', '.choose', function(){
        var action = $(this).attr('id');
        var ma_id = $(this).val();
        var _token = $('input[name="_token"]').val();
        var result = '';
        
        console.log('Address selection:', { action: action, ma_id: ma_id });
        
        if (action !== 'nameCity' && action !== 'nameProvince' && action !== 'nameWards') {
            console.error('Invalid action:', action);
            return;
        }

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
                success: function(data) {
                    console.log('Address data received:', data);
                    $('#' + result).html(data);
                    
                    // Auto-calculate delivery fee when ward is selected
                    if (action === 'nameWards') {
                        calculate_delivery();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Address AJAX error:', error);
                }
            });
        }
    });
}

// Setup shipping method handlers
function setupShippingHandlers() {
    $(document).off('change', 'input[name="shipping_select"]').on('change', 'input[name="shipping_select"]', function() {
        console.log('Shipping method changed:', $(this).val());
        calculate_delivery();
    });
}

// Setup payment method handlers  
function setupPaymentHandlers() {
    $(document).off('change', 'input[name="payment_select"]').on('change', 'input[name="payment_select"]', function() {
        console.log('Payment method changed:', $(this).val());
        var selectedPayment = $(this).val();
        
        if (selectedPayment === '3') {
            $('#credit-card-form').slideDown();
        } else {
            $('#credit-card-form').slideUp();
        }
    });
}

// Setup form validation
function setupFormValidation() {
    $('.shipping_name, .shipping_email, .shipping_phone, .shipping_address_detail').on('blur', function() {
        var $this = $(this);
        var value = $this.val().trim();
        
        if(!value) {
            $this.addClass('error-field');
        } else {
            $this.removeClass('error-field');
            
            if($this.hasClass('shipping_email')) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if(!emailRegex.test(value)) {
                    $this.addClass('error-field');
                } else {
                    $this.removeClass('error-field');
                }
            }
            
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
    
    $('#nameCity, #nameProvince, #nameWards').on('change', function() {
        var $this = $(this);
        if($this.val() === '0') {
            $this.addClass('error-field');
        } else {
            $this.removeClass('error-field');
        }
    });
    
    $('input[name="payment_select"]').on('change', function() {
        $('.payment-options').removeClass('error-field');
    });
}
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

/* 🎯 Discount Section Styles */
#discount-section {
    border-top: 1px dashed #28a745;
    padding-top: 8px;
    margin-top: 8px;
}

#discount-amount {
    color: #28a745 !important;
    font-weight: bold;
}

#discount-details {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 3px;
    padding: 8px;
    margin-top: 5px;
    font-size: 11px;
    color: #155724;
}

/* 🎯 Discount Choice Options - CLEAN STYLES */
.discount-choice-option {
    border: 2px solid #e9ecef;
    border-radius: 6px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.discount-choice-option:hover:not(.disabled) {
    border-color: #007bff;
    background-color: #f8f9ff !important;
    box-shadow: 0 2px 8px rgba(0,123,255,0.15);
}

.discount-choice-option.selected {
    border-color: #007bff !important;
    background-color: #e3f2fd !important;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
}

.discount-choice-option.selected:before {
    content: '✓';
    position: absolute;
    top: 5px;
    right: 8px;
    background: #007bff;
    color: white;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.discount-choice-option.disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

.discount-choice-option.disabled:hover {
    border-color: #e9ecef !important;
    background-color: inherit !important;
    box-shadow: none !important;
}

/* Ẩn radio buttons nhưng vẫn giữ accessibility */
.discount-choice-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

/* Focus styles for accessibility */
.discount-choice-option:focus-within {
    outline: 2px solid #007bff;
    outline-offset: 2px;
}

.discount-option-content {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    gap: 12px;
}

.discount-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    flex-shrink: 0;
}

.discount-info {
    flex: 1;
    min-width: 0;
}

.discount-info strong {
    display: block;
    font-size: 14px;
    color: #2c3e50;
    margin-bottom: 2px;
    line-height: 1.2;
}

.discount-desc {
    font-size: 12px;
    color: #6c757d;
    line-height: 1.3;
    margin-bottom: 4px;
}

.discount-badge {
    display: inline-block;
    padding: 1px 6px;
    border-radius: 8px;
    font-size: 9px;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.discount-amount {
    text-align: right;
    flex-shrink: 0;
}

.discount-amount .amount {
    display: block;
    font-size: 15px;
    font-weight: bold;
    color: #27ae60;
    line-height: 1.1;
}

.discount-amount .percentage {
    font-size: 10px;
    color: #6c757d;
}

/* Compact order summary */
.order-summary-container {
    font-size: 14px;
}

.order-summary-container .row {
    margin-bottom: 4px;
}

.order-summary-container hr {
    margin: 8px 0;
}

/* Compact coupon input */
.coupon-input-compact input {
    height: 32px;
    font-size: 13px;
}

.coupon-input-compact button {
    height: 32px;
    font-size: 12px;
    padding: 0 12px;
}

/* Compact suggestions */
.discount-suggestions-compact {
    font-size: 13px;
}

/* Remove excess margins and paddings */
.payment-options, .shipping-method, .payment-method {
    margin-bottom: 8px;
}

.payment-options h4 {
    margin-bottom: 10px;
    font-size: 16px;
}

.shipping-method label, .payment-method label {
    padding: 10px 12px;
}

.shipping-method p, .payment-method p {
    margin: 3px 0 0 25px;
    font-size: 12px;
}

/* Compact payment button */
.payment-button {
    margin-top: 15px;
}

.payment-button .btn {
    padding: 12px;
    font-size: 15px;
}

/* Reduce form spacing */
.form-one input, .form-one select, .form-one textarea {
    margin-bottom: 8px;
}

.form-one .row {
    margin: 5px 0;
}

/* Compact table */
.table-responsive {
    margin-bottom: 15px;
}

.table td {
    padding: 8px;
    font-size: 13px;
}

.table th {
    padding: 10px 8px;
    font-size: 13px;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .order-summary-container {
        padding: 15px;
    }
    
    .discount-option-content {
        padding: 8px 10px;
        gap: 8px;
    }
    
    .discount-icon {
        width: 30px;
        height: 30px;
        font-size: 14px;
    }
    
    .discount-info strong {
        font-size: 13px;
    }
    
    .discount-desc {
        font-size: 11px;
    }
    
    .discount-amount .amount {
        font-size: 13px;
    }
}

/* 🎯 Legacy Discount Selection Styles (keep for compatibility) */
.discount-option {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.discount-option:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.15);
}

.discount-option input[type="radio"]:checked + label {
    color: #007bff;
}

.discount-option:has(input[type="radio"]:checked) {
    border-color: #007bff;
    background-color: #f8f9ff;
}

.discount-option label {
    width: 100%;
    margin-bottom: 0;
    font-weight: normal;
}

.discount-option input[type="radio"] {
    margin-top: 2px;
}

#selected-discount-section {
    border-top: 1px dashed #28a745;
    padding-top: 8px;
    margin-top: 8px;
}

#selected-discount-amount {
    color: #28a745 !important;
    font-weight: bold;
}

#selected-discount-details {
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 3px;
    padding: 8px;
    margin-top: 5px;
    font-size: 11px;
    color: #155724;
}

#discount-suggestions {
    border-left: 4px solid #17a2b8;
    background-color: #e8f4f8;
    border-color: #bee5eb;
}

#discount-suggestions h5 {
    color: #0c5460;
    margin-bottom: 10px;
}

#discount-suggestions i {
    color: #17a2b8;
    margin-right: 5px;
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

/* Ultra Compact Layout Styles */
.discount-choice-option {
    border: 1px solid #e9ecef;
    border-radius: 4px;
    margin-bottom: 6px;
    transition: all 0.2s ease;
    font-size: 12px;
}

.discount-choice-option:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.discount-choice-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.discount-choice-option input[type="radio"]:checked + label {
    background-color: #e3f2fd;
    border-color: #007bff;
}

.discount-choice-option label {
    display: block;
    margin: 0;
    padding: 6px 8px;
    cursor: pointer;
    width: 100%;
}

.discount-option-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.discount-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
    flex-shrink: 0;
}

.discount-info {
    flex: 1;
    min-width: 0;
}

.discount-info strong {
    display: block;
    font-size: 12px;
    line-height: 1.2;
    margin-bottom: 2px;
}

.discount-desc {
    font-size: 10px;
    color: #6c757d;
    line-height: 1.2;
    margin-bottom: 2px;
}

.discount-badge {
    display: inline-block;
    padding: 1px 4px;
    border-radius: 2px;
    color: white;
    font-size: 9px;
    font-weight: 500;
}

.discount-amount {
    text-align: right;
    flex-shrink: 0;
    min-width: 60px;
}

.discount-amount .amount {
    display: block;
    font-weight: 600;
       font-size: 12px;
    color: #28a745;
}

/* Error field styling */
.error-field {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25) !important;
}

/* Ultra compact cart item styling */
.cart-item-row:last-child {
    border-bottom: none !important;
}

.cart-item-row:hover {
    background-color: #f8f9fa;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .shipping-info-panel, .cart-info-panel, .order-panel {
        margin-bottom: 15px !important;
        padding: 15px !important;
    }
    
    .panel-header h4 {
        font-size: 16px !important;
    }
    
    .discount-option-content {
        gap: 5px;
    }
    
    .discount-amount {
        min-width: 50px;
    }
    
    .method-options {
        padding: 8px !important;
    }
    
    .method-option {
        font-size: 12px !important;
    }
    
    /* Mobile: Stack columns vertically */
    .col-lg-7, .col-lg-5 {
        width: 100% !important;
        margin-bottom: 20px;
    }
    
    /* Adjust cart item display on mobile */
    .cart-item-row {
        flex-direction: column;
        align-items: flex-start !important;
        padding: 10px 0 !important;
    }
    
    .item-info, .item-price, .item-total {
        width: 100% !important;
        text-align: left !important;
        min-width: auto !important;
    }
    
    .item-price {
        margin: 5px 0;
        display: flex;
        justify-content: space-between;
    }
    
    .item-total {
        font-size: 16px !important;
        color: #007bff !important;
        font-weight: bold !important;
    }
}
</style>

@endsection
