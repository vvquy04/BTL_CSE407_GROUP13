@extends('layout')
@section('content')

<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
                <li class="active">Thanh toán</li>
            </ol>
        </div>
        
        <div class="step-one">
            <h2 class="heading">Đang xử lý thanh toán</h2>
        </div>

        <div class="payment-processing">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Xử lý thanh toán thẻ tín dụng</h3>
                        </div>
                        <div class="panel-body text-center">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                            <h4>Đang xử lý thanh toán của bạn...</h4>
                            <p>Vui lòng không đóng trang này trong quá trình xử lý.</p>
                            
                            <div class="progress" style="margin-top: 20px;">
                                <div class="progress-bar progress-bar-striped active" 
                                     role="progressbar" 
                                     style="width: 100%">
                                    Đang xử lý...
                                </div>
                            </div>
                              <div style="margin-top: 20px;">
                                <p><strong>Thông tin đơn hàng:</strong></p>
                                <p>Mã đơn hàng: #{{ Session::get('order_code', 'N/A') }}</p>
                                @php
                                    $cart = Session::get('cart');
                                    $total = 0;
                                    if ($cart && count($cart) > 0) {
                                        foreach ($cart as $item) {
                                            $total += $item['product_price'] * $item['product_qty'];
                                        }
                                    }
                                @endphp
                                <p>Tổng tiền: {{ number_format($total, 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            // Simulate processing time
            setTimeout(function() {
                alert('Thanh toán thành công! Đơn hàng của bạn đã được xử lý.');
                window.location.href = "{{ URL::to('/') }}";
            }, 3000);
        </script>
    </div>
</section>

@endsection
