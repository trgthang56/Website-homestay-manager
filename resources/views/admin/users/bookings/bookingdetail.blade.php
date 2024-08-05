@extends('admin.main')

@section('content')
@php
    // Chuyển đổi chuỗi ngày vào đối tượng Carbon
    $checkInDate = \Carbon\Carbon::parse($booking->check_in_date);
    $checkOutDate = \Carbon\Carbon::parse($booking->check_out_date);

    // Tính số ngày giữa hai ngày
    $numberOfDays = $checkOutDate->diffInDays($checkInDate);
@endphp
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Chi tiết đơn đặt phòng</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Reservation</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-6" style="padding: 0 7.5px 0 0;">
              <label>Ngày</label>
              <label class="float-right">{{$numberOfDays}} Đêm</label>
              <div class="row">
                <div class="col-sm-6">
                  <div class="field">
                    <input type="text" id="min_budget" name="min_budget" class="input-min"
                      value="{{ \Carbon\Carbon::parse($booking->check_in_date)->locale('vi')->isoFormat('dddd, DD [Tháng] M') }}"
                      readonly style="height: calc(1.5em + 10px);">
                  </div>
                </div>
                <div class="col-sm-6 float-right">
                  <div class="field">
                    <input type="text" id="min_budget" name="min_budget" class="input-min"
                      value="{{ \Carbon\Carbon::parse($booking->check_out_date)->locale('vi')->isoFormat('dddd, DD [Tháng] M') }}"
                      readonly style="height: calc(1.5em + 10px);">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <label for="max_budget" style="padding: 0 7.5px 0 0;">Số khách</label>
              <div class="row">
                <div class="col-sm-6">
                  <div class="field">
                    <input type="text" value="{{$guestArray['guests']}} Người lớn, {{$guestArray['babies']}} Trẻ/Phòng" readonly style="height: calc(1.5em + 10px);">
                  </div>
                </div>
                <div class="col-sm-6">
                  @if($guestArray['babies'] != 0)
                    <div class="field">
                      <input type="text" value="{{ str_replace(',', '', $guestArray['babies_age_txt']) }}" readonly style="height: calc(1.5em + 10px);">
                    </div>
                  @else
                    <div class="field">
                      <input type="text" value="Không có" readonly style="height: calc(1.5em + 10px);">
                    </div>
                  @endif
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Đơn đặt phòng</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="name">Người đặt</label>
                    @if($booking->id_customer==null)
                    <input type="text" class="form-control" id="name" name="name" value="không có thông tin" disabled>
                    @else
                    <input type="text" class="form-control" id="name" name="name" value="{{$customerName}}"
                      disabled>
                    @endif
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="name">Người nhận phòng</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{$booking->name}}" disabled>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="name">Nhân viên xác nhận</label>
                    @if($userName == "")
                      <input type="text" class="form-control" id="staff" name="staff" value="Đơn chưa được xác nhận" disabled>
                    @else
                      <input type="text" class="form-control" id="staff" name="staff" value="{{$userName}}" disabled>
                    @endif
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="name">Thời gian xác nhận</label>
                    @if($booking->accepted_date==null)
                    <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="đơn chưa được xác nhận" disabled>
                    @else
                    <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="{{ \Carbon\Carbon::parse($booking->accepted_date)->format('H:i - d/m/Y') }}" disabled>
                    @endif
                  </div>
                </div>
              </div>
              @if($booking->status == 1)
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="name">Thời gian check-in</label>
                      @if($booking->checkIn_time !== null)
                        <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="{{ \Carbon\Carbon::parse($booking->checkIn_time)->format('H:i - d/m/Y') }}" disabled>
                      @else
                        <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="Phòng chưa được check-in" disabled>
                      @endif
                    </div>
                  </div>
                </div>
              @endif
              @if($booking->status == 2)
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="name">Thời gian check-in</label>
                      @if($booking->checkIn_time != null)
                        <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="{{ \Carbon\Carbon::parse($booking->checkIn_time)->format('H:i - d/m/Y') }}" disabled>
                      @else
                        <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="Phòng chưa được check-in" disabled>
                      @endif
                    </div>
                  </div>
                  <div class="col-sm-6 float-right">
                    <div class="form-group">
                      <label for="name">Thời gian check-out</label>
                      <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="{{ \Carbon\Carbon::parse($booking->completed_date)->format('H/i - d/m/Y') }}" disabled>
                    </div>
                  </div>
                </div>
              @endif
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{$booking->phone}}"
                      disabled>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{$booking->email}}"
                      disabled>
                  </div>
                </div>
              </div>
              @for($i = 0; $i < count($rooms); $i++)
              <div class="card-body" style="cursor:pointer; padding: 20px 0px" onclick="toggleCardBody({{$rooms[$i]->id }})">
                <div class="row" style="border-top: solid 1px #000;padding-top: 15px; margin-right: 0px; margin-left: 0px">
                  <div class="col-md-6" style="padding: unset">
                    <h4 style="margin: 0px">{{$rooms[$i]->name }}</h4>
                  </div>
                  <div class="col-md-6 ">
                    <div class="float-right" id="icon-{{$rooms[$i]->id }}">
                      <i class="fa-solid fa-chevron-right" style="font-size:24px"></i>
                    </div>
                    <div class="float-right total-price-{{$rooms[$i]->id }}" style="margin-right:20px">
                      <span style="font-size: 16px">{{ number_format($rooms[$i]->price, 0, ',', '.') }} VNĐ/đêm</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body" id="{{$rooms[$i]->id }}" style="display: none; padding: unset;padding-top:10px">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="">
                        <label class="">Các dịch vụ đi kèm</label>
                        <textarea class="form-control" rows="2" disabled>{{$servicesInfo[$i]}}</textarea>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="special_requirement">Yêu cầu đặc biệt</label>
                      @if($requestInfo[$i]==null)
                      <textarea class="form-control" rows="2" disabled>không có yêu cầu</textarea>
                      @else
                      <textarea class="form-control" rows="2" disabled>{{$requestInfo[$i]}}</textarea>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              @endfor
              <div class="row" style="padding-top:10px">
                <div class="col-md-6">
                  <a href="javascript:history.go(-1)" class="btn btn-primary float-left">Trở lại danh sách đặt phòng</a>
                </div>
                <div class="col-md-6">
                  @if(auth()->user()->role=='admin'|| auth()->user()->role=='manager')
                    @if($booking->status == 0)
                      <form method="POST" action="{{route('booking.accept')}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$booking->id}}">
                        <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px; margin-left: 15px">Xác nhận</button>
                      </form>
                    @elseif($booking->status == 1)
                      @if($booking->checkInOut == 0 && \Carbon\Carbon::now()->between($booking->check_in_date, $booking->check_out_date))
                        <form method="POST" action="{{route('check-In')}}">
                          @csrf
                          <input type="hidden" name="id" value="{{$booking->id}}">
                        <form method="POST" action="{{route('check-In')}}" style="">
                          <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px; margin-left: 15px">Check-In</button>
                        </form>
                      @endif
                      @if(\Carbon\Carbon::now() > $booking->check_out_date)
                        <form method="POST" action="{{route('booking.complete')}}">
                          @csrf
                          <input type="hidden" name="id" value="{{$booking->id}}">
                          <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px; margin-left: 15px">Check-Out</button>
                        </form>
                      @endif
                    @elseif($booking->status == 3)
                      @if(\Carbon\Carbon::now()->between($booking->check_in_date, $booking->check_out_date))
                        <form method="POST" action="{{route('booking.accept')}}">
                          @csrf
                          <input type="hidden" name="id" value="{{$booking->id}}">
                          <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px; margin-left: 15px">Hồi phục lại đơn</button>
                        </form>
                      @elseif( $booking->checkInOut == 0 && !(\Carbon\Carbon::now()->between($booking->check_in_date, $booking->check_out_date)))
                        <form method="POST" action="{{route('booking.accept')}}">
                          @csrf
                          <input type="hidden" name="id" value="{{$booking->id}}">
                          <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px; margin-left: 15px">Hoàn tiền</button>
                        </form>
                      @endif
                    @endif
                  @else
                    @if($booking->status == 1)
                      @if($booking->checkInOut == 0 && \Carbon\Carbon::now()->between($booking->check_in_date, $booking->check_out_date))
                        <form method="POST" action="{{route('check-In')}}">
                          @csrf
                          <input type="hidden" name="id" value="{{$booking->id}}">
                        <form method="POST" action="{{route('check-In')}}" style="">
                          <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px; margin-left: 15px">Check-In</button>
                        </form>
                      @endif
                      @if(\Carbon\Carbon::now() > $booking->check_out_date)
                        <form method="POST" action="{{route('booking.complete')}}">
                          @csrf
                          <input type="hidden" name="id" value="{{$booking->id}}">
                          <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px; margin-left: 15px">Check-Out</button>
                        </form>
                      @endif
                    @endif
                  @endif
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
        </div>  
        <!-- /.card -->
        <!-- About Me Box -->
        <!-- /.card -->
        <div class="col-md-4">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">
                Chi phí hóa đơn
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <b>Phương thức thanh toán</b>
                  <span class="float-right" id="total-price">{{$booking->payment_method}}</span>
                </div>
              </div>
              <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                  <b>Tổng hóa đơn</b> 
                  <span class="float-right" id="total-price">{{ number_format($booking->total, 0, ',', '.') }} VNĐ</span>
                  <input type="hidden" name="totalPrice" id="bill-price">
                  @if($booking->payment_status ==1)
                    <span class="float-right" style="clear: both;">(Đã thanh toán)</span>
                  @else
                    <span class="float-right" style="clear: both;">(Chưa thanh toán)</span>
                  @endif
                </div>
              </div>
              <div class="row" style="margin-top: 10px;">
                <div class="col-md-6" >
                  <b>Giá phòng ({{$numberOfDays}} đêm) </b><span class="float-right" id="total-rooms-price">0 VNĐ</span>
                  <div class="row">
                    @for($i = 0; $i < count($rooms); $i++)
                    <div class="col-sm-12" style="margin-top: 10px; padding-left: 20px">
                        <b style="font-weight: unset; margin: 0px">{{$rooms[$i]->name }}</b>
                        <span class="float-right">{{ number_format($rooms[$i]->price, 0, ',', '.') }} VNĐ</span>
                        <span class="room-price" style="display: none">{{ $rooms[$i]->price }} VNĐ</span>
                    </div>
                    @endfor
                  </div>
                </div>
                <div class="col-md-6">
                  <b>Dịch vụ đặt trước</b> <span class="float-right" id="total-service-price">0 VNĐ</span>
                  <div class="row">
                    @for($i = 0; $i < count($rooms); $i++)
                      <div class="col-sm-12" style="margin-top: 10px; padding-left: 20px">
                        <b style="font-weight: unset; margin: 0px">{{$rooms[$i]->name }}</b> 
                        @if($serviceTotalPrices[$i] !=null)
                          <span class="float-right service-price" value="{{$serviceTotalPrices[$i]}}">{{ number_format($serviceTotalPrices[$i], 0, ',', '.') }} VNĐ</span>
                        @else
                          <span class="float-right service-price">0 VNĐ</span>    
                        @endif
                      </div>
                    @endfor
                  </div>
                </div>
              </div>
            </div>    
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script>
// Đợi cho trang web được tải hoàn toàn
document.addEventListener('DOMContentLoaded', function() {
    // Lấy danh sách các phần tử có class 'service-price'
    var servicePrices = document.querySelectorAll('.service-price');
    
    // Khởi tạo biến để tính tổng giá dịch vụ
    var totalServicePrice = 0;

    // Lặp qua từng phần tử có class 'service-price'
    servicePrices.forEach(function(servicePriceElement) {
        // Lấy giá dịch vụ từ thuộc tính 'value' của phần tử
        var servicePrice = parseFloat(servicePriceElement.getAttribute('value'));

        // Kiểm tra nếu giá dịch vụ là một số hợp lệ thì thêm vào tổng giá dịch vụ
        if (!isNaN(servicePrice)) {
            totalServicePrice += servicePrice;
        }
    });

    // Hiển thị tổng giá dịch vụ đã tính được
    var totalServicePriceElement = document.getElementById('total-service-price');
    totalServicePriceElement.textContent = totalServicePrice.toLocaleString('vi-VN') + ' VNĐ';
});


document.addEventListener('DOMContentLoaded', function() {
    // Lấy số đêm từ blade template
    var numberOfDays = {{$numberOfDays}};
    
    // Lấy danh sách các phần tử có class 'room-price'
    var roomPrices = document.querySelectorAll('.room-price');
    
    // Khởi tạo biến để tính tổng giá phòng
    var totalRoomsPrice = 0;

    // Lặp qua từng phần tử có class 'room-price'
    roomPrices.forEach(function(roomPriceElement) {
        // Lấy giá phòng từ nội dung của phần tử và loại bỏ chữ 'VNĐ'
        var roomPriceText = roomPriceElement.textContent.trim().replace(' VNĐ', '').replace(/,/g, '');
        var roomPrice = parseFloat(roomPriceText);
        
        // Kiểm tra nếu số lẻ nhỏ hơn 1000, chuyển về dạng có 4 chữ số bằng cách thêm các số 0 vào sau dấu chấm
        var decimalIndex = roomPriceText.indexOf('.');
        if (decimalIndex !== -1 && roomPriceText.length - decimalIndex <= 4) {
            var decimalPart = roomPriceText.substring(decimalIndex + 1);
            while (decimalPart.length < 4) {
                decimalPart += '0';
            }
            roomPriceText = roomPriceText.substring(0, decimalIndex + 1) + decimalPart;
            roomPrice = parseFloat(roomPriceText);
        }
        
        // Tính tổng giá phòng
        totalRoomsPrice += roomPrice;
    });

    // Nhân tổng giá phòng với số đêm
    var totalPrice = totalRoomsPrice * numberOfDays;

    // Hiển thị tổng giá phòng đã tính được
    var totalPriceElement = document.getElementById('total-rooms-price');
    totalPriceElement.textContent = totalPrice.toLocaleString('vi-VN') + ' VNĐ';
});


</script>
<script>
  // Function để định dạng số
  function formatCurrency(value) {
    let formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    // Kiểm tra xem giá trị có phải là số thập phân không
    if (Number.isInteger(value)) {
      return formattedValue + ' VNĐ';
    } else {
      return formattedValue.replace(/\./, '.') + ' VNĐ';
    }
  }

  // Lấy giá trị từ các phần tử HTML
  let roomPriceElement = document.getElementById('room-price');
  let servicePriceElement = document.getElementById('service-total-price');
  let totalPriceElement = document.getElementById('total-price');

  // Lấy giá trị dạng số từ các phần tử HTML và định dạng lại
  let roomPriceValue = parseFloat(roomPriceElement.innerText.replace(/[^\d.]/g, ''));
  let servicePriceValue = parseFloat(servicePriceElement.innerText.replace(/[^\d.]/g, ''));
  let totalPriceValue = parseFloat(totalPriceElement.innerText.replace(/[^\d.]/g, ''));

  // Định dạng số cho các giá trị
  roomPriceElement.innerText = formatCurrency(roomPriceValue);
  servicePriceElement.innerText = formatCurrency(servicePriceValue);
  totalPriceElement.innerText = formatCurrency(totalPriceValue);

</script>
<script>
  function toggleCardBody(roomId) {
    var cardBody = document.getElementById(roomId);
    var icon = document.getElementById('icon-' + roomId);

    if (cardBody.style.display === 'none') {
      cardBody.style.display = 'block';
      icon.innerHTML = '<i class="fa-solid fa-chevron-down" style="font-size:24px"></i>';
    } else {
      cardBody.style.display = 'none';
      icon.innerHTML = '<i class="fa-solid fa-chevron-right" style="font-size:24px"></i>';
    }
  }
</script>
@endsection