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
                      <input type="text" value="{{str_replace(',', '', $guestArray['babies_age_txt'])}}" readonly style="height: calc(1.5em + 10px);">
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
                    <input type="text" class="form-control" id="name" name="name" value="{{$booking->name}}" >
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
                    <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="Đơn chưa được xác nhận" disabled>
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
                      <input type="text" class="form-control" id="accepted_date" name="accepted_date" value="{{ \Carbon\Carbon::parse($booking->completed_date)->format('H:i - d/m/Y') }}" disabled>
                    </div>
                  </div>
                </div>
              @endif
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{$booking->phone}}">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{$booking->email}}">
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
                      <span style="font-size: 16px">{{ number_format($rooms[$i]->price , 0, ',', '.') }} VNĐ/đêm</span>
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
                        @if($servicesInfo[$i]==null)
                        <textarea class="form-control" rows="2" disabled>Không có dịch vụ</textarea>
                        @else
                        <textarea class="form-control" rows="2" disabled>{{$servicesInfo[$i]}}</textarea>
                        @endif
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
                      @if($booking->checkInOut == 1 && \Carbon\Carbon::now() > $booking->check_out_date)
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
                  <span class="float-right">{{$booking->payment_method}}</span>
                </div>
              </div>
              <div class="row" style="margin-top: 15px;">
                <div class="col-md-12">
                  <b>Tổng hóa đơn</b> 
                  <span class="float-right" id="total-price">{{ number_format($booking->total) }} VNĐ</span>
                  <input type="hidden" name="totalPrice" id="total-bill-price">
                  <span class="float-right" style="clear: both;">(Đã bao gồm VAT)</span>
                </div>
              </div>
              <div class="row" style="margin-top: 15px;">
                <div class="col-md-6">
                  <b>Dịch vụ phát sinh</b>
                  <span style="clear: both;">
                    <button class="btn" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa-solid fa-pen-to-square"></i></button>
                  </span>
                </div>
                <div class="col-md-6">
                  <span class="float-right" id="total-extra-price">{{ number_format($booking->total) }} VNĐ</span>
                  <input type="hidden" name="totalExtraPrice" id="bill-extra-price">
                  <div class="form-group" style="margin: 5px 0px 0px 0px">
                  @php
                    $countPaidItems = 0;
                  @endphp
                  @foreach($extraServices as $item)
                    @if($item->status == 3)
                      @php
                        $countPaidItems++;
                      @endphp
                    @endif
                  @endforeach
                  @if($extraServices->count() == 0)

                  @else
                    @if($countPaidItems == $extraServices->count())
                      <span class="float-right" style="clear: both;">(Đã thanh toán)</span>
                    @else
                      <span class="float-right" style="clear: both;">(Chưa thanh toán hết)</span>
                    @endif
                  @endif
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top: 15px;">
                <div class="col-md-12">
                  <b>Tổng hóa đơn phòng và dịch vụ đặt trước</b> 
                  <span class="float-right" id="total-pre-price">{{ number_format($booking->total) }} VNĐ</span>
                  <input type="hidden" name="totalPrice" id="bill-pre-price" value="{{$booking->total}}">
                  @if($booking->payment_status ==1)
                    <span class="float-right" style="clear: both;">(Đã thanh toán)</span>
                  @else
                    <span class="float-right" style="clear: both;">(Chưa thanh toán)</span>
                  @endif
                </div>
              </div>
              <div class="row" style="margin-top: 15px;">
                <div class="col-md-6" >
                  <b>Giá phòng ({{$numberOfDays}} đêm) </b><span class="float-right" id="total-rooms-price">0 VNĐ</span>
                  <div class="row">
                    @for($i = 0; $i < count($rooms); $i++)
                    <div class="col-sm-12" style="margin-top: 10px; padding-left: 20px">
                        <b style="font-weight: unset; margin: 0px">{{$rooms[$i]->name }}</b> 
                        <span class="float-right room-price" value="{{$rooms[$i]->price}}">{{ number_format($rooms[$i]->price) }} VNĐ</span>
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
                          <span class="float-right service-price" value="{{$serviceTotalPrices[$i]}}">{{ number_format($serviceTotalPrices[$i]) }} VNĐ</span>
                        @else
                          <span class="float-right service-price">0 VNĐ</span>    
                        @endif
                      </div>
                    @endfor
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top: 15px;">
                <div class="col-md-6">
                  
                </div>
                <div class="col-md-6">
                  @if($booking->payment_status == 0)
                    <form method="POST" action="{{route('cash.paid')}}">
                      @csrf
                      <input type="hidden" name="id" value="{{$booking->id}}">
                      <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px; margin-left: 15px">Thanh toán</button>
                    </form>
                  @endif
                </div>
              </div>
            </div>    
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- Modal -->
<div class="modal fade modal-dialog-centered modal-dialog-scrollable bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content"  style="max-width: 799px">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Danh sách các dịch vụ phát sinh</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if($extraServices->count()==0)
        <div class="card">
          <div class="card-body">
            <div class="row justify-content-center align-items-center" style="min-height: 150px;">
              <h2>Chưa có dịch vụ nào được thêm</h2>
            </div>
          </div>
        </div>
        @else
        @foreach($extraServices as $item)
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h4>đơn phát sinh #B{{$booking->id}}-{{$item->id}}</h4>
              </div>
              <div class="col-md-6">
                @if($item->status == 0)
                  <span class="badge badge-danger float-right" style="font-size: 14px; margin-left: 8px">Chưa thanh toán</span>
                  <span class="badge badge-danger float-right" style="font-size: 14px; margin-left: 8px">chưa hoàn thành</span>
                @elseif($item->status == 1)
                  <span class="badge badge-success float-right" style="font-size: 14px; margin-left: 8px">Đã thanh toán</span>
                  <span class="badge badge-danger float-right" style="font-size: 14px; margin-left: 8px">Chưa hoàn thành</span>
                @elseif($item->status == 2)
                  <span class="badge badge-danger float-right" style="font-size: 14px; margin-left: 8px">Chưa thanh toán</span>
                  <span class="badge badge-success float-right" style="font-size: 14px; margin-left: 8px">Đã hoàn thành </span>
                @elseif($item->status == 3)
                  <span class="badge badge-success float-right" style="font-size: 14px;">Đã xong</span>
                @endif
              </div>
            </div>
            
          </div>
          <div class="card-body">
            @php
            $services = json_decode($item->id_service, true);
            $quantities = json_decode($item->quantity, true);
            $prices = json_decode($item->price, true);
            $countStatus=0;

            @endphp
            @for($i = 0; $i < count($services); $i++)
            @foreach($serviceData as $service)
            @if($service->id == $services[$i])
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Dịch vụ</label>
                  <input type="text" class="form-control" value="{{$service->name}}" readonly>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Đơn vị tính</label>
                  <input type="text" class="form-control" value="{{$quantities[$i]}} {{$service->unit}}" readonly>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label>Giá</label>
                  <span class="float-right">VNĐ</span>
                  <input type="text" class="form-control" value="{{ number_format($prices[$i], 0, '.', ',') }}" readonly>
                </div>
              </div>
            </div>
            @endif
            @endforeach
            @endfor
            <div class="row">
              @if($item->description!= null)
              <div class="col-md-8">
                <div class="form-group">
                  <label for="checkin">Mô tả</label>
                  <input type="text" class="form-control" value="{{$item->description}}" readonly>
                </div>
              </div>
              @else
              <div class="col-md-8">
                <div class="form-group">
                  <label for="checkin">Mô tả</label>
                  <input type="text" class="form-control" value="Không có mô tả" readonly>
                </div>
              </div>
              @endif
              <div class="col-md-4">
                <div class="form-group">
                  <label for="checkout">Phí khác</label>
                  <input type="text"  class="form-control" value="{{ number_format($item->other_price, 0, '.', ',') }}" readonly>
                </div>
              </div>  
            </div>
            <div class="row">
              @if($item->status == 0)
              <div class="col-md-6">
                <div class="form-group">
                  <label for="checkin">Ngày tạo</label>
                  <input type="text"  class="form-control" value="{{\Carbon\Carbon::parse($item->created_at)->format('H:i - d/m/Y')}}" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="checkout">Trạng thái</label>
                  <input type="text"  class="form-control" value="Chưa hoàn thành" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="checkout">Thanh toán</label>
                  <input type="text"  class="form-control" value="Chưa thanh toán" readonly>
                </div>
              </div>
              @elseif($item->status == 1)
              <div class="col-md-4">
                <div class="form-group">
                  <label for="checkin">Ngày tạo</label>
                  <input type="text"  class="form-control" value="{{\Carbon\Carbon::parse($item->created_at)->format('H:i - d/m/Y')}}" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="checkout">Thanh toán lúc</label>
                  <input type="text"  class="form-control" value="{{\Carbon\Carbon::parse($item->paid_time)->format('H:i - d/m/Y')}}" readonly>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="checkout">Hình thức thanh toán</label>
                  <input type="text"  class="form-control" value="{{$item->payment_method}}" readonly>
                </div>
              </div>
              @elseif($item->status == 2)
              <div class="col-md-6">
                <div class="form-group">
                  <label for="checkin">Ngày tạo</label>
                  <input type="text"  class="form-control" value="{{\Carbon\Carbon::parse($item->created_at)->format('H:i - d/m/Y')}}" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="checkout">Hoàn thành lúc</label>
                  <input type="text"  class="form-control" value="{{\Carbon\Carbon::parse($item->completed_time)->format('H:i - d/m/Y')}}" readonly>
                </div>
              </div>
              @elseif($item->status == 3)
              <div class="col-md-3">
                <div class="form-group">
                  <label for="checkin">Ngày tạo</label>
                  <input type="text"  class="form-control" value="{{\Carbon\Carbon::parse($item->created_at)->format('H:i - d/m/Y')}}" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="checkout">Hoàn thành lúc</label>
                  <input type="text"  class="form-control" value="{{\Carbon\Carbon::parse($item->completed_time)->format('H:i - d/m/Y')}}" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="checkout">Thanh toán lúc</label>
                  <input type="text"  class="form-control" value="{{\Carbon\Carbon::parse($item->paid_time)->format('H:i - d/m/Y')}}" readonly>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="checkout">Hình thức thanh toán</label>
                  <input type="text"  class="form-control" value="{{$item->payment_method}}" readonly>
                </div>
              </div>
              @endif  
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label for="checkin">Tổng</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="extra-service-price float-right" data-extra-price="{{ $item->total }}">{{ number_format($item->total, 0, '.', ',') }} VNĐ</label>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer" style="background-color:white">
            @if($item->status == 0)
              <a href="{{ route('extra.service.cancel', ['id' => $item->id]) }}" class="btn btn-danger">Hủy đơn</a>
            @endif  
            @if($item->status == 2)
              <a style="margin-left: 8px" href="{{ route('extra.service.completed', ['id' => $item->id]) }}" class="btn btn-success float-right">Xong</a>
              <a style="margin-left: 8px" href="{{ route('extra.service.paid', ['id' => $item->id]) }}" class="btn btn-success float-right">Thanh toán</a>
            @elseif($item->status == 1)
              <a style="margin-left: 8px" href="{{ route('extra.service.completed', ['id' => $item->id]) }}" class="btn btn-success float-right">Xong</a>
              <a style="margin-left: 8px" href="{{ route('extra.service.finished', ['id' => $item->id]) }}" class="btn btn-success float-right">Hoàn thành</a>
            @elseif($item->status == 0)
              <a style="margin-left: 8px" href="{{ route('extra.service.completed', ['id' => $item->id]) }}" class="btn btn-success float-right">Xong</a>
              <a style="margin-left: 8px" href="{{ route('extra.service.paid', ['id' => $item->id]) }}" class="btn btn-success float-right">Thanh toán</a>
              <a style="margin-left: 8px" href="{{ route('extra.service.finished', ['id' => $item->id]) }}" class="btn btn-success float-right">Hoàn thành</a>
            @endif  
            
          </div>
        </div>
        @endforeach
        @endif
      </div>
      @foreach($extraServices as $item)
        @if($item->status == 3)
          @php
            $countStatus++;
          @endphp
        @endif
      @endforeach
      <div class="modal-footer" style="display: flow-root;">
        <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Đóng</button>
        <a href="{{ route('extra.service.index', ['id' => $booking->id]) }}" class="btn btn-success float-right">Thêm</a>
        @if($extraServices->count()!=0 && $extraServices->count() != $countStatus)
        <a href="{{ route('extra.service.completedAll', ['id' => $booking->id]) }}" class="btn btn-success float-right">Xong toàn bộ</a>
        <a href="{{ route('extra.service.paidAll', ['id' => $booking->id]) }}" class="btn btn-success float-right">Hoàn thành toàn bộ</a>
        <a href="{{ route('extra.service.finishedAll', ['id' => $booking->id]) }}" class="btn btn-success float-right">Thanh toán toàn bộ</a>
        @endif
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg-payment-method" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Mời chọn phương thức thanh toán</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6">
            <div style="display: flex; flex-direction: column; align-items: center;">
              <img src="/template/admin/dist/img/payment/momo_img.png" style="width: 100px; height: 100px; cursor: pointer" alt="">
              <div class="row" style="text-align: center;">
                <label>Thanh toán Momo</label>
                <span>(đang hoàn thiện)</span>
              </div>
              <button style="display:none" type="submit" value="Chọn phương thức thanh toán" formaction="" id="momo-payment" class="btn btn-success float-right"></button>
            </div>
          </div>
          <div class="col-sm-6">
            <div style="display: flex; flex-direction: column; align-items: center;">
              <img id="vnpay-payment-img"  src="/template/admin/dist/img/payment/VNPAY_img.png" style="width: 100px; height: 100px; cursor: pointer" alt="">
              <div class="row" style="text-align: center;">
                <label>Thanh toán VNPay</label>
              </div>
              <button style="display:none" type="submit" value="Chọn phương thức thanh toán" formaction="{{route('vnpay.payment') }}" id="vnpay-payment" class="btn btn-success float-right"></button>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div style="display: flex; flex-direction: column; align-items: center;">
              <img src="/template/admin/dist/img/payment/VISA-logo-square.png" style="width: 100px; height: 100px; cursor: pointer" alt="">
              <div class="row" style="text-align: center;">
                <label>Thanh toán Visa</label>
                <span>(đang hoàn thiện)</span>
              </div>
              <button style="display:none" type="submit" value="Chọn phương thức thanh toán" formaction="" id="card-payment" class="btn btn-success float-right"></button>   
            </div>
          </div>
          <div class="col-sm-6">
            <div style="display: flex; flex-direction: column; align-items: center;">
              <img id="cash-payment-img" src="/template/admin/dist/img/payment/cash-payment.png" style="width: 100px; height: 100px; cursor: pointer" alt="">
              <div class="row" style="text-align: center;">
                <label>Thanh toán tiền mặt</label>
              </div>
              <button style="display:none" type="submit" value="Chọn phương thức thanh toán" formaction="{{route('cash.payment') }}" id="cash-payment" class="btn btn-success float-right"></button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="justify-content: start;">
        <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
    // Hàm tính tổng giá tiền extra và cập nhật tổng cộng
    function updateTotalExtraPrice() {
        var totalExtraPrice = 0;
        $('.extra-service-price').each(function() {
            totalExtraPrice += parseFloat($(this).data('extra-price'));
        });
        // Cập nhật tổng giá tiền extra vào input hidden và hiển thị trên trang
        $('#bill-extra-price').val(totalExtraPrice);
        $('#total-extra-price').text(numberWithCommas(totalExtraPrice) + ' VNĐ');

        // Lấy giá trị của bill-pre-price
        var billPrePrice = parseFloat($("#bill-pre-price").val().replace(/[^0-9.-]+/g,""));

        // Tính tổng
        var totalPrice = totalExtraPrice + billPrePrice;

        // Định dạng kết quả theo định dạng tiền tệ VNĐ
        totalPrice = numberWithCommas(totalPrice) + " VNĐ";

        // Gán kết quả cho phần tử total-price
        $('#total-price').text(totalPrice);
    }

    // Gọi hàm khi trang được tải và sau mỗi lần cập nhật
    updateTotalExtraPrice();

    // Gọi hàm khi có sự kiện thay đổi trong các phần tử chứa giá tiền extra
    $('.extra-service-price').on('change', function() {
        updateTotalExtraPrice();
    });

    // Hàm chuyển đổi số sang chuỗi với dấu phân cách hàng nghìn
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
});
</script>

<script>
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
    var numberOfDays = {{$numberOfDays}};
    var roomPrices = document.querySelectorAll('.room-price');

    var totalRoomsPrice = 0;

    // Lặp qua từng phần tử có class 'room-price'
    roomPrices.forEach(function(roomPriceElement) {
        // Lấy giá phòng từ nội dung của phần tử và loại bỏ chữ 'VNĐ'
        var roomPrice = parseFloat(roomPriceElement.getAttribute('value'));
        
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
<!-- <script>
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

</script> -->
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