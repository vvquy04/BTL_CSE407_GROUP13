@extends('layout')
@section('content')
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/') }}">Trang chủ</a></li>
                <li class="active">Thanh toán khi nhận hàng</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-check-circle"></i> Xác nhận đơn hàng - Thanh toán khi nhận hàng (COD)
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success text-center">
                                    <h4><i class="fa fa-check-circle fa-3x"></i></h4>
                                    <h3>Đặt hàng thành công!</h3>
                                    <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn sẽ được giao đến địa chỉ và bạn sẽ thanh toán khi nhận hàng.</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4>Thông tin đơn hàng</h4>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><strong>Mã đơn hàng:</strong></td>
                                                <td>{{ $order_info['order_code'] ?? 'Đang cập nhật' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Phương thức thanh toán:</strong></td>
                                                <td><span class="label label-info">Thanh toán khi nhận hàng (COD)</span></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Phương thức vận chuyển:</strong></td>
                                                <td>{{ $order_info['shipping_method'] ?? 'Đang cập nhật' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tạm tính:</strong></td>
                                                <td>{{ number_format($order_info['subtotal'] ?? 0, 0, ',', '.') }} VND</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Phí vận chuyển:</strong></td>
                                                <td>{{ number_format($order_info['shipping_fee'] ?? 0, 0, ',', '.') }} VND</td>
                                            </tr>
                                            @if(($order_info['discount_amount'] ?? 0) > 0)
                                            <tr>
                                                <td><strong>Giảm giá:</strong></td>
                                                <td style="color: #28a745;">-{{ number_format($order_info['discount_amount'], 0, ',', '.') }} VND</td>
                                            </tr>
                                            @if($order_info['discount_description'] ?? '')
                                            <tr>
                                                <td><strong>Loại ưu đãi đã sử dụng:</strong></td>
                                                <td style="font-size: 12px;">{{ $order_info['discount_description'] }}</td>
                                            </tr>
                                            @endif
                                            @endif
                                            <tr style="background-color: #f8f9fa; border-top: 2px solid #007bff;">
                                                <td><strong>Tổng tiền:</strong></td>
                                                <td><strong style="color: #e74c3c; font-size: 18px;">{{ number_format($order_info['order_total'] ?? 0, 0, ',', '.') }} VND</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trạng thái:</strong></td>
                                                <td><span class="label label-warning">Chờ xác nhận</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4>Thông tin giao hàng</h4>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td><strong>Người nhận:</strong></td>
                                                <td>{{ $order_info['shipping_name'] ?? 'Đang cập nhật' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Điện thoại:</strong></td>
                                                <td>{{ $order_info['shipping_phone'] ?? 'Đang cập nhật' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Địa chỉ chi tiết:</strong></td>
                                                <td>{{ $order_info['shipping_address_detail'] ?? 'Đang cập nhật' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Xã/Phường:</strong></td>
                                                <td>{{ $order_info['ward_name'] ?? 'Đang cập nhật' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Quận/Huyện:</strong></td>
                                                <td>{{ $order_info['district_name'] ?? 'Đang cập nhật' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tỉnh/Thành phố:</strong></td>
                                                <td>{{ $order_info['city_name'] ?? 'Đang cập nhật' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h4><i class="fa fa-info-circle"></i> Lưu ý quan trọng:</h4>
                                    <ul>
                                        <li>Đơn hàng sẽ được xác nhận trong vòng 24 giờ</li>
                                        <li>Thời gian giao hàng: 2-3 ngày làm việc</li>
                                        <li>Quý khách vui lòng chuẩn bị đầy đủ số tiền khi nhận hàng</li>
                                        <li>Kiểm tra kỹ sản phẩm trước khi thanh toán</li>
                                        <li>Hotline hỗ trợ: <strong>1900-xxxx</strong></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-md-12">
                                <a href="{{ URL::to('/') }}" class="btn btn-primary btn-lg">
                                    <i class="fa fa-home"></i> Về trang chủ
                                </a>
                                <a href="{{ URL::to('/trang-chu') }}" class="btn btn-success btn-lg">
                                    <i class="fa fa-shopping-cart"></i> Tiếp tục mua hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.panel-success {
    border-color: #27ae60;
}
.panel-success > .panel-heading {
    color: #fff;
    background-color: #27ae60;
    border-color: #27ae60;
}
.fa-check-circle {
    color: #27ae60;
}
</style>

@endsection
