@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      THÔNG TIN ĐƠN HÀNG
    </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Mã đơn hàng</th>
            <th>Ngày đặt</th>
            <th>Trạng thái</th>
            <th>Phương thức thanh toán</th>
            <th>Phương thức vận chuyển</th>
            <th>Tổng tiền</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$order_by_id->order_code}}</td>
            <td>{{$order_by_id->created_at}}</td>
            <td>
              <span class="label label-{{$order_by_id->order_status == 'Đã thanh toán' ? 'success' : 'warning'}}">
                {{$order_by_id->order_status}}
              </span>
            </td>
            <td>{{$order_by_id->payment_method ?? 'Chưa xác định'}}</td>
            <td>{{$order_by_id->shipping_method == 1 ? 'Giao hàng tiêu chuẩn' : 'Giao hàng nhanh'}}</td>
            <td>{{number_format($order_by_id->order_total).' VND'}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<br>
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      THÔNG TIN NGƯỜI MUA
    </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Tên người mua</th>
            <th>Email</th>
            <th>Số điện thoại</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$order_by_id->customer_name}}</td>
            <td>{{$order_by_id->customer_email}}</td>
            <td>{{$order_by_id->customer_phone}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<br>
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      THÔNG TIN VẬN CHUYỂN
    </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Tên người nhận hàng</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ giao hàng</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$order_by_id->shipping_name}}</td>
            <td>{{$order_by_id->shipping_email}}</td>
            <td>{{$order_by_id->shipping_phone}}</td>
            <td>
              {{ $order_by_id->shipping_street }}, 
              {{ $order_by_id->shipping_ward }}, 
              {{ $order_by_id->shipping_district }}, 
              {{ $order_by_id->shipping_city }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="panel-footer">
      <span style="margin: 15px 10px; display: inline-block; color: #666;">
        <b>Ghi chú đơn hàng:</b> {{$order_by_id->shipping_note ?: 'Không có ghi chú'}}
      </span>
    </div>
  </div>
</div>
<br>
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      CHI TIẾT SẢN PHẨM
    </div>
    <div class="table-responsive">
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng tiền</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order_list as $v_order_list)
          <tr>
            <td>{{ $v_order_list->product_name }}</td>
            <td>{{ $v_order_list->product_sales_quanlity }}</td>
            <td>{{ number_format($v_order_list->product_price).' VND' }}</td>
            <td><b>{{ number_format($v_order_list->product_price*$v_order_list->product_sales_quanlity).' VND' }}</b></td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-right"><strong>Phí vận chuyển:</strong></td>
            <td><b>{{number_format($order_by_id->shipping_fee ?? 0).' VND'}}</b></td>
          </tr>
          <tr>
            <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
            <td><b>{{number_format($order_by_id->order_total).' VND'}}</b></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection