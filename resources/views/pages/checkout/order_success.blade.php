@extends('layout')
@section("title","Đặt hàng thành công")
@section("content")

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<section id="cart_items">
    <div class="breadcrumbs">
        <ol class="breadcrumb">
            <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
            <li class="active">Đặt hàng thành công</li>
        </ol>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="order-success-container" style="text-align: center; padding: 50px 20px;">
                    <!-- Success Icon -->
                    <div class="success-icon" style="margin-bottom: 30px;">
                        <i class="fa fa-check-circle" style="font-size: 80px; color: #28a745;"></i>
                    </div>

                    <!-- Success Title -->
                    <h2 style="color: #28a745; margin-bottom: 20px;">
                        🎉 Đặt hàng thành công!
                    </h2>

                    <!-- Success Message -->
                    <div class="alert alert-success" style="font-size: 16px; margin: 30px auto; max-width: 600px;">
                        <strong>Cảm ơn bạn đã đặt hàng!</strong><br>
                        Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.
                        <br>Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.
                    </div>

                    <!-- Order Info Card -->
                    <div class="order-info-card" style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 30px; margin: 30px auto; max-width: 600px; text-align: left;">
                        <h4 style="color: #495057; margin-bottom: 20px; text-align: center;">
                            <i class="fa fa-receipt"></i> Thông tin đơn hàng
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Mã đơn hàng:</strong></p>
                                <p style="color: #007bff; font-weight: bold;">#{{ Session::get('order_info.order_code', strtoupper(uniqid())) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Thời gian đặt:</strong></p>
                                <p>{{ date('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tạm tính:</strong></p>
                                <p>{{ number_format(Session::get('order_info.subtotal', 0), 0, ',', '.') }} VNĐ</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phí vận chuyển:</strong></p>
                                <p>{{ number_format(Session::get('order_info.shipping_fee', 0), 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>

                        @if(Session::get('order_info.discount_amount', 0) > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <p><strong>Giảm giá:</strong></p>
                                <p style="color: #28a745; font-weight: bold; font-size: 16px;">
                                    -{{ number_format(Session::get('order_info.discount_amount'), 0, ',', '.') }} VNĐ
                                </p>
                                @if(Session::get('order_info.discount_description'))
                                <p style="color: #155724; font-size: 12px; margin-top: 5px;">
                                    {{ Session::get('order_info.discount_description') }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Phương thức thanh toán:</strong></p>
                                <p style="color: #28a745; font-weight: bold; font-size: 16px;">
                                    {{ Session::get('order_info.payment_method', 'Không xác định') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Phương thức vận chuyển:</strong></p>
                                <p style="color: #007bff; font-weight: bold; font-size: 16px;">
                                    {{ Session::get('order_info.shipping_method', 'Không xác định') }}
                                </p>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-md-6">
                                <p><strong>Tổng tiền:</strong></p>
                                <p style="color: #dc3545; font-size: 20px; font-weight: bold;">
                                    {{ number_format(Session::get('order_info.order_total', 0), 0, ',', '.') }} VNĐ
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Trạng thái:</strong></p>
                                <p style="color: #ffc107; font-weight: bold;">
                                    ⏳ Đang chờ xử lý
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="next-steps" style="margin: 40px auto; max-width: 600px;">
                        <h4 style="color: #495057; margin-bottom: 20px;">📋 Bước tiếp theo</h4>
                        <div class="alert alert-info">
                            <ul style="text-align: left; margin: 0; padding-left: 20px;">
                                <li>Chúng tôi sẽ xác nhận đơn hàng qua điện thoại trong vòng 30 phút</li>
                                <li>Đơn hàng sẽ được chuẩn bị và giao trong 1-3 ngày làm việc</li>
                                <li>Bạn có thể theo dõi tình trạng đơn hàng qua email hoặc điện thoại</li>
                                <li>Liên hệ hotline: <strong>1900-xxxx</strong> nếu cần hỗ trợ</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons" style="margin-top: 40px;">
                        <a href="{{URL::to('/')}}" class="btn btn-primary btn-lg" style="margin: 0 10px;">
                            <i class="fa fa-home"></i> Về trang chủ
                        </a>
                        <a href="{{URL::to('/shop')}}" class="btn btn-success btn-lg" style="margin: 0 10px;">
                            <i class="fa fa-shopping-bag"></i> Tiếp tục mua sắm
                        </a>
                    </div>

                    <!-- Customer Support -->
                    <div class="customer-support" style="margin-top: 50px; padding: 20px; background: #e9ecef; border-radius: 8px;">
                        <h5 style="color: #495057;">💬 Cần hỗ trợ?</h5>
                        <p>Liên hệ với chúng tôi:</p>
                        <p>
                            <i class="fa fa-phone"></i> Hotline: <strong>1900-xxxx</strong><br>
                            <i class="fa fa-envelope"></i> Email: <strong>support@yourstore.com</strong><br>
                            <i class="fa fa-clock-o"></i> Giờ làm việc: <strong>8:00 - 22:00 (T2-CN)</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    // Animation hiệu ứng
    $('.order-info-card').addClass('animate__animated animate__fadeInUp');
    
    // Thêm animation cho success icon
    setTimeout(function() {
        $('.success-icon').addClass('animate__animated animate__pulse animate__infinite');
    }, 1000);

    // Auto redirect về trang chủ sau 3 phút nếu không có tương tác
    let autoRedirectTimer = setTimeout(function() {
        if (confirm('🏠 Bạn có muốn quay về trang chủ không?\n\n(Trang sẽ tự động chuyển sau 10 giây nữa)')) {
            window.location.href = '{{URL::to("/")}}';
        } else {
            // Nếu user chọn không, reset timer thêm 3 phút nữa
            autoRedirectTimer = setTimeout(function() {
                window.location.href = '{{URL::to("/")}}';
            }, 180000);
        }
    }, 180000); // 3 phút

    // Clear timer nếu user có tương tác
    $(document).on('click mousemove keypress', function() {
        clearTimeout(autoRedirectTimer);
    });

    // Thêm hiệu ứng hover cho buttons
    $('.btn').hover(
        function() {
            $(this).addClass('animate__animated animate__pulse');
        },
        function() {
            $(this).removeClass('animate__animated animate__pulse');
        }
    );
});
</script>

<style>
.order-success-container {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.success-icon i {
    animation: bounceIn 0.8s ease-out;
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.1);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.order-info-card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.order-info-card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
</style>
@endsection
