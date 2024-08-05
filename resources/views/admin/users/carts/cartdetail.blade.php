@extends('admin.main')

@section('content')
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
  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg-info-bill" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"  style="max-width: 799px">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa thông tin đặt phòng</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="updateDates" action="{{route('cart.updates') }}" method="post">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="checkin">Check-in:</label>
                  <input type="date" id="checkin" name="checkin" class="form-control" value="{{$checkIn}}">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="checkout">Check-out:</label>
                  <input type="date" id="checkout" name="checkout" class="form-control" value="{{$checkOut}}"
                    min="{{$checkIn}}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="guests">Người lớn <span style="font-weight: 500">(Tối đa: tổng cộng 6
                      khách/phòng)</span></label>
                  <div class="input-group">
                    <input type="text" id="guests-txt" name="guests_txt" class="form-control"
                      data-unit="Người lớn mỗi phòng" value="{{$guests_txt}}" readonly
                      style="background-color: #fff">
                    <div class="input-group-append">
                      <span class="input-group-text" id="decrement"
                        style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-minus"></i></span>
                      <span class="input-group-text" id="increment"
                        style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-plus"></i></span>
                    </div>
                  </div>
                  <input type="number" id="guests" name="guests" class="form-control" min="0" max="6"
                    value="{{$guests}}" style="display: none;">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="babies">Trẻ em <span style="font-weight: 500">(Tối đa: tổng cộng 6
                      khách/phòng)</span></label>
                  <div class="input-group">
                    <input type="text" id="babies-txt" name="babies_txt" class="form-control"
                      data-unit="Trẻ em mỗi phòng" value="{{$babies_txt}}" readonly
                      style="background-color: #fff">
                    <div class="input-group-append">
                      <span class="input-group-text" id="babies-decrement"
                        style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-minus"></i></span>
                      <span class="input-group-text" id="babies-increment"
                        style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-plus"></i></span>
                    </div>
                  </div>
                  <input type="number" id="babies" name="babies" class="form-control" min="0" max="6"
                    value="{{$babies}}" style="display: none;">
                </div>
                @if($babies)
                <div class="form-group" data-babies-age>
                  @else
                  <div class="form-group" style="display: none" data-babies-age>
                    @endif
                    <p style="display: block;">(Có thể có mức giá dựa trên độ tuổi)</p>
                    <label for="babies-age" style="font-weight: 500">Tuổi(Bắt buộc)</label>
                    <div class="input-group">
                      <input type="text" id="babies-age-txt" name="babies_age_txt" class="form-control"
                        value="{{$babies_age_txt}}" readonly style="background-color: #fff">
                      <div class="input-group-append">
                        <span class="input-group-text" id="babies-age-decrement"
                          style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-minus"></i></span>
                        <span class="input-group-text" id="babies-age-increment"
                          style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-plus"></i></span>
                      </div>
                    </div>
                    <input type="number" id="babies-age" name="babies_age" class="form-control" min="0" max="5"
                      value="{{$babies_age}}" style="display: none;">
                  </div>
                </div>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger float-left" data-dismiss="modal">Đóng</button>
          <button type="submit" form="updateDates" class="btn btn-success float-right">Cập nhật</button>
        </div>
      </div>
    </div>
  </div> 
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-md-12" style="padding:10px 7.5px">
              <h4>Thời gian nhận và trả phòng</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6" style="padding: 0 7.5px;">
              <label>Ngày</label>
              <label class="float-right">{{$numberOfDays}} Đêm</label>
              <div class="row">
                <div class="col-sm-6">
                  <div class="field">
                    <input type="text" id="min_budget" name="min_budget" class="input-min"
                      value="{{ \Carbon\Carbon::parse($checkin)->locale('vi')->isoFormat('dddd, DD [Tháng] M') }}"
                      readonly style="height: calc(1.5em + 10px); font-size: 16px;">
                  </div>
                </div>
                <div class="col-sm-6 float-right">
                  <div class="field">
                    <input type="text" id="min_budget" name="min_budget" class="input-min"
                      value="{{ \Carbon\Carbon::parse($checkout)->locale('vi')->isoFormat('dddd, DD [Tháng] M') }}"
                      readonly style="height: calc(1.5em + 10px); font-size: 16px;">
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <label for="max_budget" style="padding: 0 7.5px 0 0; font-size: 16px;">Số khách</label>
              <div class="row">
                <div class="col-sm-9">
                  <div class="field">
                    <input type="text" id="max_budget" name="max_budget" class="input-max"
                      value="{{$numberOfRooms}} Phòng: {{$guests}} Người lớn, {{$babies}} Trẻ em/Phòng{{$babies_age_txt}}"
                      readonly style="height: calc(1.5em + 10px); font-size: 16px;">
                  </div>
                </div>
                <div class="col-sm-3 d-flex justify-content-center">
                  <div class="field">
                      <button class="btn float-center custom-btn" style="font-size: 16px; border: -27px;" data-toggle="modal" data-target=".bd-example-modal-lg-info-bill">
                        Chỉnh sửa 
                        <i class="fa-solid fa-rotate"></i>
                      </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      @if($numberOfRooms > 0)
      <form name="submitForm" method="get" onsubmit="return validateForm()">
      @csrf
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h4 style="margin: 0px">Thông tin cơ bản</h4>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="name">Tên </label>
                  <input type="text" class="form-control" id="name" name="name" >
                  <span id="name-error" class="text-danger"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="phone">Số điện thoại</label>
                  <input type="phone" class="form-control" id="phone" name="phone" >
                  <span id="phone-error" class="text-danger"></span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" id="email" name="email">
                  <span id="email-error" class="text-danger"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="guest" name="forother" value="no" checked>
                    <label for="guest" class="custom-control-label">Tôi là khách</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="forother" name="forother">
                    <label for="forother" class="custom-control-label">Tôi đặt cho người khác</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12" id="otherNameInput" style="display: none;">
                <div class="form-group">
                  <label for="otherName">Tên người đặt phòng</label>
                  <input type="text" class="form-control" id="otherName" name="otherName">
                  <span id="otherName-error" class="text-danger"></span>

                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 style="margin: 0px">Dịch vụ và yêu cầu phòng </h4>
          </div>
          @foreach($cart as $datum)
          <div class="card-body" style="cursor:pointer;" onclick="toggleCardBody({{ $datum->idRoom }})">
            <div class="row" style="border-top: solid 1px #000;padding-top: 15px">
              <div class="col-md-6">
                <h4 style="margin: 0px">{{ $datum->name }}</h4>
              </div>
              <div class="col-md-6 ">
                <div class="float-right" id="icon-{{ $datum->idRoom }}">
                  <i class="fa-solid fa-chevron-right" style="font-size:24px"></i>
                </div>
                <div class="float-right total-price-{{ $datum->idRoom }}" style="margin-right:20px">
                  <span style="font-size: 16px">0 VNĐ</span>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body" id="{{ $datum->idRoom }}" style="display: none;">
            <div class=" row">
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="service" id="serviceToggle-{{ $datum->idRoom }}">
                    <label for="serviceToggle-{{ $datum->idRoom }}" class="custom-control-label" onclick="toggleServiceList('{{$datum->idRoom}}')">Các dịch vụ đặt trước <i class="fa-solid fa-circle-info" style="font-size: 16px" title="Các dịch vụ đặt trước sẽ giúp homestay chuẩn bị trước theo yêu cầu của quý khách"></i></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row service-list-{{ $datum->idRoom }}" style="display: none; ">
              @foreach ($services as $service)
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="service{{ $datum->idRoom }}Checkbox[]" 
                      id="{{ $service->id }}-{{ $datum->idRoom }}" value="{{ $service->id }}"
                      data-price="{{ $service->price }}" onchange="handleServiceChange({{ $datum->idRoom }})">
                    <label for="{{ $service->id }}-{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">{{$service->name }}</label>
                    <label for="{{ $service->id }}-{{ $datum->idRoom }}" class="service-price float-right"
                      style="font-weight: normal;">{{number_format($service->price, 0, ',', '.')}} VNĐ/{{$service->unit}}</label>
                  </div>
                </div>
              </div>
              @endforeach

            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group" style="margin: 0px;">
                  <label for="special_requirement">Yêu cầu đặc biệt <i class="fa-regular fa-circle-question"
                      style="font-size: 12px"
                      title="Chúng tôi sẽ cố gắng đáp ứng yêu cầu của bạn dựa trên tình trạng sẵn có. Lưu ý rằng bạn có thể phải trả thêm phí cho một số yêu cầu và bạn không thể sửa yêu cầu sau khi đã gửi."></i></label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <!-- checkbox -->
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rq{{ $datum->idRoom }}Checkbox[]"
                      id="smoking{{ $datum->idRoom }}" value="Phòng không hút thuốc">
                    <label for="smoking{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">Phòng không hút
                      thuốc</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rq{{ $datum->idRoom }}Checkbox[]"
                      id="floors{{ $datum->idRoom }}" value="Tầng lầu">
                    <label for="floors{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">Tầng lầu</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rq{{ $datum->idRoom }}Checkbox[]"
                      id="others{{$datum->idRoom }}" data-toggle="collapse" href="#collapseOne{{ $datum->idRoom }}">
                    <label for="others{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">Khác</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <!-- checkbox -->
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rq{{ $datum->idRoom }}Checkbox[]"
                      id="rooms{{ $datum->idRoom }}" value="Phòng liên thông">
                    <label for="rooms{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">Phòng liên
                      thông</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rq{{ $datum->idRoom }}Checkbox[]"
                      id="bed{{ $datum->idRoom }}" value="Loại giường">
                    <label for="bed{{ $datum->idRoom }}" class="custom-control-label" style="font-weight: normal;">Loại
                      giường</label>
                  </div>
                </div>
              </div>
              <div id="collapseOne{{ $datum->idRoom }}" class="collapse col-sm-12">
                <div class="form-group">
                  <textarea class="form-control" rows="3" name="rq{{ $datum->idRoom }}other" placeholder="Chi tiết yêu cầu"></textarea>
                </div>
              </div>
            </div>

          </div>
          @endforeach
        </div>
        <!-- /.card-body -->
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h4 style="margin: 0px">Tóm tắt chi phí </h4>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <b>Tổng hóa đơn</b> <span class="float-right" id="total-price">0 VNĐ</span>
                  <input type="hidden" name="totalPrice" id="bill-price">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <b>Giá phòng ({{$numberOfDays}} đêm) </b><span class="float-right" id="total-rooms-price">0 VNĐ</span>
                </div>
                <div class="row">
                  @foreach($cart as $datum)
                  <div class="col-sm-12" style="padding-left: 20px">
                    <div class="form-group">
                      <b style="font-weight: unset; margin: 0px">{{ $datum->name }}</b> 
                      <span class="float-right room-price" value="{{$datum->price}}">{{ number_format($datum->price, 0, '.', ',') }} VNĐ</span>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <b>Dịch vụ</b> <span class="float-right" id="total-service-price">0 VNĐ</span>
                </div>
                <div class="row">
                  @foreach($cart as $datum)
                  <div class="col-sm-12" style="padding-left: 20px">
                    <div class="form-group">
                      <b style="font-weight: unset; margin: 0px">{{ $datum->name }}</b>
                      <span class="float-right total-room-{{ $datum->idRoom }}-prices"
                        id="total-room-{{ $datum->idRoom }}-prices" value="total-room-{{ $datum->idRoom }}-prices">0
                        VNĐ</span>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
            <a type="button" class="btn btn-success float-right" data-toggle="modal" data-target=".bd-example-modal-lg-payment-method">Phương thức thanh toán</a>
            <!-- <input type="submit" value="Chọn phương thức thanh toán" class="btn btn-success float-right"></input> -->
            <a href="javascript:history.go(-1)" class="btn btn-danger float-left">Quay lại giỏ hàng</a>
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
      </form>
    </div>
    @else
    <div class="card">
      <div class="card-body">
        <div class="row justify-content-center align-items-center" style="min-height: 400px;">
          <h2>Đơn phòng của bạn đã hết hạn</h2>
        </div>
      </div>
    </div>
    @endif
  </section>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>.
<script>
  var vnpayImg = document.getElementById("vnpay-payment-img");
  var vnpayBtn = document.getElementById("vnpay-payment");

  vnpayImg.addEventListener("click", function() {
      vnpayBtn.click();
  });
</script>
<script>
  var cashImg = document.getElementById("cash-payment-img");
  var cashBtn = document.getElementById("cash-payment");
  cashImg.addEventListener("click", function() {
      cashBtn.click();
  });
</script>
<script>
  function validateForm() {
    var name = document.forms["submitForm"]["name"].value;
    var nameRegex = /[\d~`!@#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g;
    var phone = document.forms["submitForm"]["phone"].value;
    var phoneRegex = /^(0\d{9})$/;
    var email = document.forms["submitForm"]["email"].value;
    var forOtherChecked = document.getElementById('forother').checked;
    var otherName = document.forms["submitForm"]["otherName"].value;

    if (name== "") {
      alert("Tên không được bỏ trống");
      document.getElementById('name-error').innerText = 'Tên không được bỏ trống, chứa số hoặc ký tự đặc biệt.';
      return false;
    }else if(nameRegex.test(name)){
      alert("Tên không được chứa số hoặc ký tự đặc biệt.");
      document.getElementById('name-error').innerText = 'Tên không được chứa số hoặc ký tự đặc biệt.';
      return false;
    }else{
      document.getElementById('name-error').innerText = '';
    }
    
    
    if (phone== "") {
      alert("Số điện thoại không được bỏ trống");
      document.getElementById('phone-error').innerText = 'Số điện thoại không được bỏ trống.';
      return false;
    }else if(!phoneRegex.test(phone)){
      alert("Số điện thoại không hợp lệ.");
      document.getElementById('phone-error').innerText = 'Số điện thoại không hợp lệ.';
      return false;
    }else{
      document.getElementById('phone-error').innerText = '';
    }
    
    
    if (email== "") {
      alert("Email không được bỏ trống");
      document.getElementById('email-error').innerText = 'Email không được bỏ trống.';
      return false;
    }


    if (forOtherChecked && otherName=="") {
      document.getElementById('otherName-error').innerText = 'Xin mời nhập tên người nhận phòng';
      return false;
    } else if( forOtherChecked && nameRegex.test(otherName)){
      document.getElementById('otherName-error').innerText = 'Tên không được chứa số hoặc ký tự đặc biệt.';
      return false;
    }else{
      document.getElementById('otherName-error').innerText = '';
      return true;

    }
  }
  $(document).ready(function () {
    $('#guest').change(function () {
      if ($(this).is(':checked')) {
        $('#otherNameInput').hide();
      }
    });
    $('#forother').change(function () {
      if ($(this).is(':checked')) {
        $('#otherNameInput').show();
      }
    });
    //xử lý người dùng muốn xem thêm về danh sách dịch vụ đang có
    $(document).ready(function () {
      $('#serviceToggle').change(function () {
        $('.service-list').toggle();
      });
    });
  });

  // Hiển thị hoặc ẩn card-body khi click
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

  // Hiển thị hoặc ẩn danh sách dịch vụ
  function toggleServiceList(roomId) {
    var serviceList = document.querySelector('.service-list-' + roomId);

    if (serviceList.style.display === 'none') {
      serviceList.style.display = 'flex';
    } else {
      serviceList.style.display = 'none';
    }
  }


  // Xử lý thay đổi giá dịch vụ
  function handleServiceChange(roomId) {
    var totalPrice = 0;
    var checkboxes = document.getElementsByName('service' + roomId + 'Checkbox[]');
    var totalSpan = document.querySelector('.total-price-' + roomId + ' span');
    var totalRoomServicesPrice = document.querySelector('.total-room-' + roomId + '-prices');
    for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i].checked) {
        totalPrice += parseInt(checkboxes[i].getAttribute('data-price'));
      }
    }
    totalSpan.textContent = totalPrice.toLocaleString('en') + ' VNĐ';
    totalRoomServicesPrice.textContent = totalPrice.toLocaleString('en') + ' VNĐ';
    totalRoomServicesPrice.setAttribute('value', totalPrice);

    calculateTotalServicesPrice();
    calculateTotalBill();

 
  }
 

  function calculateTotalServicesPrice() {
    var cartArray = {!! json_encode($cart1) !!};
    console.log(cartArray);
    if (!Array.isArray(cartArray)) {
        console.error('cartArray is not an array');
        return;
    }
    var TotalServicesPrice = 0;
    cartArray.forEach(function(item) {
        var totalRoomServicesElement = document.getElementById('total-room-' + item.idRoom + '-prices');
        var textTotalRoomServicesPrice = totalRoomServicesElement.getAttribute('value');
        var totalRoomServicesPrice = parseInt(textTotalRoomServicesPrice, 10);
        console.log(totalRoomServicesPrice);
        if (!isNaN(totalRoomServicesPrice)) TotalServicesPrice += totalRoomServicesPrice;
    });
    document.getElementById('total-service-price').textContent = TotalServicesPrice.toLocaleString('en') + ' VNĐ';
    document.getElementById('total-service-price').setAttribute('value', TotalServicesPrice);
}




  function calculateTotalRoomsPrice() {
  var totalRoomsPrice = 0;
  var roomPrices = document.querySelectorAll('.room-price');
  roomPrices.forEach(function(roomPriceElement) {
    var roomPrice = parseFloat(roomPriceElement.getAttribute('value').replace(',', '')); 
    if (!isNaN(roomPrice)) {
      totalRoomsPrice += roomPrice;
    }
  });
  // Lấy số ngày từ {{$numberOfDays}}
  var numberOfDays = parseInt("{{$numberOfDays}}");
  // Tính tổng giá phòng
  var totalPrice = totalRoomsPrice * numberOfDays;
  // Hiển thị tổng giá phòng
  document.getElementById('total-rooms-price').textContent = totalPrice.toLocaleString('en') + ' VNĐ';
  document.getElementById('total-rooms-price').setAttribute('value', totalPrice);
}


// Gọi hàm tính tổng giá phòng khi trang được tải
calculateTotalRoomsPrice();


  function calculateTotalBill() {
    var totalbill = 0;
    var totalServicesPrice = parseInt(document.getElementById('total-service-price').getAttribute('value'), 10);
    if (isNaN(totalServicesPrice)) {
      totalServicesPrice = 0;
    }
    var totalRoomsPrice = parseInt(document.getElementById('total-rooms-price').getAttribute('value'), 10);
    var totalbill = totalServicesPrice + totalRoomsPrice;
    document.getElementById('total-price').textContent = totalbill.toLocaleString('en') + ' VNĐ';
    document.getElementById('bill-price').value = totalbill;
  }
  calculateTotalBill();

</script>

<script>
  var guestsInput = document.getElementById('guests');
  var guestsTxtInput = document.getElementById('guests-txt');
  var guestsIncrementButton = document.getElementById('increment');
  var guestsDecrementButton = document.getElementById('decrement');

  var babiesInput = document.getElementById('babies');
  var babiesTxtInput = document.getElementById('babies-txt');
  var babiesIncrementButton = document.getElementById('babies-increment');
  var babiesDecrementButton = document.getElementById('babies-decrement');

  var babiesAgeContainer = document.querySelector('.form-group[data-babies-age]');
  var babiesAgeInput = document.getElementById('babies-age');
  var babiesAgeTxtInput = document.getElementById('babies-age-txt');
  var babiesAgeIncrementButton = document.getElementById('babies-age-increment');
  var babiesAgeDecrementButton = document.getElementById('babies-age-decrement');

  // Bắt sự kiện khi nút "+" của Người lớn được nhấn
  guestsIncrementButton.addEventListener('click', function () {
    incrementCount(guestsInput, guestsTxtInput);
  });

  // Bắt sự kiện khi nút "-" của Người lớn được nhấn
  guestsDecrementButton.addEventListener('click', function () {
    decrementCount(guestsInput, guestsTxtInput);
  });

  // Bắt sự kiện khi nút "+" của Trẻ em được nhấn
  babiesIncrementButton.addEventListener('click', function () {
    incrementCount(babiesInput, babiesTxtInput);
    showBabiesAgeInput();
  });

  // Bắt sự kiện khi nút "-" của Trẻ em được nhấn
  babiesDecrementButton.addEventListener('click', function () {
    decrementCount(babiesInput, babiesTxtInput);
    hideBabiesAgeInput();
  });

  // Bắt sự kiện khi nút "+" của Tuổi Trẻ em được nhấn
  babiesAgeIncrementButton.addEventListener('click', function () {
    incrementBabiesAge();
  });

  // Bắt sự kiện khi nút "-" của Tuổi Trẻ em được nhấn
  babiesAgeDecrementButton.addEventListener('click', function () {
    decrementBabiesAge();
  });

  function incrementCount(inputElement, textElement) {
    var currentValue = parseInt(inputElement.value);
    if (currentValue < inputElement.max && (getTotalGuests() + 1) <= 6) {
      inputElement.value = currentValue + 1;
      textElement.value = inputElement.value + " " + textElement.getAttribute('data-unit');
    }
  }

  function decrementCount(inputElement, textElement) {
    var currentValue = parseInt(inputElement.value);
    if (currentValue > inputElement.min) {
      inputElement.value = currentValue - 1;
      textElement.value = inputElement.value + " " + textElement.getAttribute('data-unit');
    }
  }

  // Hàm tính tổng số khách
  function getTotalGuests() {
    return parseInt(guestsInput.value) + parseInt(babiesInput.value);
  }

  // Hiển thị phần nhập tuổi khi có trẻ em
  function showBabiesAgeInput() {
    if (babiesInput.value != 0) {
      babiesAgeContainer.style.display = 'block';
    }
  }

  // Ẩn phần nhập tuổi khi không có trẻ em
  function hideBabiesAgeInput() {
    if (babiesInput.value == 0) {
      babiesAgeContainer.style.display = 'none';
    }
  }

  // Hàm tăng giảm tuổi của Trẻ em
  function incrementBabiesAge() {
    var currentValue = parseInt(babiesAgeInput.value);
    if (currentValue < babiesAgeInput.max && currentValue == 3) {
      babiesAgeInput.value = currentValue + 1;
      babiesAgeTxtInput.value = "15-17 tuổi: Thanh thiếu niên";
    } else if (currentValue < babiesAgeInput.max && currentValue == 2) {
      babiesAgeInput.value = currentValue + 1;
      babiesAgeTxtInput.value = "12-14 tuổi: Thiếu niên trung học";
    } else if (currentValue < babiesAgeInput.max && currentValue == 1) {
      babiesAgeInput.value = currentValue + 1;
      babiesAgeTxtInput.value = "6-11 tuổi: Thiếu niên sơ cấp";
    } else if (currentValue < babiesAgeInput.max && currentValue == 0) {
      babiesAgeInput.value = currentValue + 1;
      babiesAgeTxtInput.value = "3-5 tuổi: Trẻ mẫu giáo";
    }
  }

  function decrementBabiesAge() {
    var currentValue = parseInt(babiesAgeInput.value);
    if (currentValue > babiesAgeInput.min && currentValue == 4) {
      babiesAgeInput.value = currentValue - 1;
      babiesAgeTxtInput.value = "12-14 tuổi: Thiếu niên trung học";
    } else if (currentValue > babiesAgeInput.min && currentValue == 3) {
      babiesAgeInput.value = currentValue - 1;
      babiesAgeTxtInput.value = "6-11 tuổi: Thiếu niên sơ cấp";
    } else if (currentValue > babiesAgeInput.min && currentValue == 2) {
      babiesAgeInput.value = currentValue - 1;
      babiesAgeTxtInput.value = "3-5 tuổi: Trẻ mẫu giáo";
    } else if (currentValue > babiesAgeInput.min && currentValue == 1) {
      babiesAgeInput.value = currentValue - 1;
      babiesAgeTxtInput.value = "0-2 tuổi: Trẻ nhỏ";
    }
  }
</script>
@endsection