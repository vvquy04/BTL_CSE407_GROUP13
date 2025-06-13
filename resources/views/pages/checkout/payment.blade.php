@extends('layout')
@section("title","Trang xác nhận thanh toán")
@section("content")
<section id="cart_items">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li class="active">Thanh toán giỏ hàng</li>
				</ol>
			</div><!--/breadcrums-->

			<div class="review-payment">
				<h2>Xem lại giỏ hàng và chọn phương thức thanh toán</h2>
			</div>			<div class="table-responsive cart_info">
			<?php
				$totalcartPrice = 0;
			?>
			
			@if(session()->has('message'))
			<div class="alert alert-danger">
				{{ session()->get('message') }}
			</div>
			@elseif(session()->has('error'))
				{{ session()->get('error') }}
			@endif
			
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Hình ảnh</td>
							<td class="description">Tên sản phẩm</td>
							<td class="price">Giá sản phẩm</td>
							<td class="quantity">Số lượng</td>
							<td class="total">Thành tiền</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
					@if(Session::get('cart'))
                        @foreach(Session::get('cart') as $key => $cart)
						<tr>
							<td class="cart_product">
								<a href=""><img src="{{URL::to('upload/product/'.$cart['product_image'])}}" alt="" width="50" height="50"></a>
							</td>
							<td class="cart_description">
								<h4><a href="{{URL::to('/chi-tiet-san-pham/'.$cart['product_id'])}}">{{$cart['product_name']}}</a></h4>
								<p>Mã đồng hồ: {{$cart['product_id']}}</p>
							</td>
							<td class="cart_price">
								<p>{{number_format($cart['product_price'],0,',','.')}} đ</p>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<input class="cart_quantity_input" type="number" min="1" value="{{$cart['product_qty']}}" size="2" readonly>
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price">
                                   @php
                                   $totalPrice = $cart['product_price'] * $cart['product_qty'];
                                   echo number_format($totalPrice,0,',','.');
                                   $totalcartPrice += $totalPrice;
                                   @endphp đ</p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" href="{{URL::to('/del-cart/'.$cart['session_id'])}}"><i class="fa fa-times"></i></a>
							</td>
                        </tr>
                        @endforeach
                        
                        <!-- Hiển thị tổng tiền -->
                        <tr>
                            <td colspan="6">
                                <div class="pull-right">
                                    <ul style="list-style: none;">
                                        <li><strong>Tổng tiền sản phẩm: {{number_format($totalcartPrice,0,',','.')}} đ</strong></li>
                                        
                                        @if(Session::get('coupon'))
                                            @foreach(Session::get('coupon') as $key => $val)
                                                @if($val['coupon_condition'] == 1)
                                                    <li>Mã giảm: {{ $val['coupon_number']}} %</li>
                                                    @php
                                                        $couponMoney = ($totalcartPrice * $val['coupon_number']) / 100;
                                                        echo '<li>Số tiền được giảm: '.number_format($couponMoney,0,',','.').' đ</li>';
                                                        $totalAfterCoupon = $totalcartPrice - $couponMoney;
                                                    @endphp
                                                @else
                                                    <li>Mã giảm: {{ number_format($val['coupon_number'],0,',','.')}} đ</li>
                                                    @php
                                                        $totalAfterCoupon = $totalcartPrice - $val['coupon_number'];
                                                    @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                        
                                        @if(Session::get('fee'))
                                            <li>Phí vận chuyển: {{number_format(Session::get('fee'),0,',','.')}} đ</li>
                                        @endif
                                        
                                        @php
                                            $finalTotal = $totalcartPrice;
                                            if(Session::get('coupon')) {
                                                $finalTotal = $totalAfterCoupon;
                                            }
                                            if(Session::get('fee')) {
                                                $finalTotal += Session::get('fee');
                                            }
                                        @endphp
                                        
                                        <li><strong style="color: red; font-size: 18px;">Tổng thanh toán: {{number_format($finalTotal,0,',','.')}} đ</strong></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr><td colspan="6"><center><p>Không có sản phẩm nào trong giỏ hàng</p></center></td></tr>
                    @endif
					</tbody>
				</table>
			</div>			<div class="payment-options">
                <div class="review-payment">
                    <h2>Chọn hình thức thanh toán</h2>
                </div>
                  @if(Session::get('cart') && count(Session::get('cart')) > 0)
                <form action="{{URL::to('confirm-order')}}" method="POST" id="payment-form">
                    {{ csrf_field() }}
                    
                    <!-- Hidden shipping info fields (will be populated by JavaScript) -->
                    <input type="hidden" name="shipping_name" id="hidden_shipping_name">
                    <input type="hidden" name="shipping_email" id="hidden_shipping_email"> 
                    <input type="hidden" name="shipping_phone" id="hidden_shipping_phone">
                    <input type="hidden" name="shipping_address" id="hidden_shipping_address">
                    <input type="hidden" name="shipping_note" id="hidden_shipping_note">
                    
                      <div class="payment-methods" style="margin: 20px 0;">
                        <div class="payment-option" style="margin: 10px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                            <label style="display: block; cursor: pointer;">
                                <input type="radio" name="payment_select" value="2" checked style="margin-right: 10px;">
                                <strong>💰 Thanh toán khi nhận hàng (COD)</strong>
                                <p style="margin: 5px 0 0 25px; color: #666; font-size: 14px;">Thanh toán bằng tiền mặt khi nhận được hàng. An toàn, tiện lợi.</p>
                            </label>
                        </div>
                        
                        <div class="payment-option" style="margin: 10px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                            <label style="display: block; cursor: pointer;">
                                <input type="radio" name="payment_select" value="3" style="margin-right: 10px;">
                                <strong>💳 Thanh toán qua thẻ tín dụng</strong>
                                <p style="margin: 5px 0 0 25px; color: #666; font-size: 14px;">Thanh toán qua thẻ tín dụng Visa, MasterCard. Bảo mật cao với SSL.</p>
                            </label>
                        </div>
                    </div>                    
                    <div style="text-align: center; margin: 30px 0;">
                        <input type="submit" class="btn btn-primary btn-lg" value="🛒 Tiếp tục thanh toán" style="padding: 15px 40px; font-size: 18px;">
                    </div>
                </form>
                @else
                <div class="alert alert-warning text-center">
                    <h4>Giỏ hàng của bạn đang trống!</h4>
                    <p>Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.</p>
                    <a href="{{URL::to('/')}}" class="btn btn-primary">← Quay lại mua sắm</a>
                </div>
                @endif
			</div>
					<script>
			document.addEventListener('DOMContentLoaded', function() {
			    // Get shipping info from previous page (if any) and populate hidden fields
			    // This would typically come from session or localStorage
			    // For now, we'll get it from the session during checkout
			    
			    // Add form submission handler to ensure shipping info is collected
			    document.getElementById('payment-form').addEventListener('submit', function(e) {
			        // Basic validation
			        let paymentMethod = document.querySelector('input[name="payment_select"]:checked');
			        if (!paymentMethod) {
			            e.preventDefault();
			            alert('Vui lòng chọn phương thức thanh toán!');
			            return false;
			        }
			        
			        // Set default shipping info for testing (this should come from previous steps)
			        if (!document.getElementById('hidden_shipping_name').value) {
			            document.getElementById('hidden_shipping_name').value = 'Test Customer';
			            document.getElementById('hidden_shipping_email').value = 'test@example.com';
			            document.getElementById('hidden_shipping_phone').value = '0123456789';
			            document.getElementById('hidden_shipping_address').value = 'Test Address, Test City';
			            document.getElementById('hidden_shipping_note').value = 'Test order';
			        }
			    });
			});
			</script>
		</div>
			
	</section> <!--/#cart_items-->
@endsection