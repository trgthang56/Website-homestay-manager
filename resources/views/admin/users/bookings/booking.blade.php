@extends('admin.main')

@section('content')
@php
// Xử lý cho room_amenity
$amenities = explode('/', $room->room_amenity);
$limitedAmenities = implode(', ', array_slice($amenities, 0, 5));
$displayAmenities = strlen($limitedAmenities) > 35 ? substr($limitedAmenities, 0, 35) . '...' : $limitedAmenities;

// Xử lý cho bathroom_amenity
$bathroomAmenities = explode('/', $room->bathroom_amenity);
$limitedBathroomAmenities = implode(', ', array_slice($bathroomAmenities, 0, 5));
$displayBathroomAmenities = strlen($limitedBathroomAmenities) > 35 ? substr($limitedBathroomAmenities, 0, 35) . '...' :
$limitedBathroomAmenities;

// Xử lý cho description
$displayDescription = strlen($room->description) > 35 ? substr($room->description, 0, 35) . '...' : $room->description;

@endphp

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Đặt phòng</h1>
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
      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Đặt phòng</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form action="{{ route('booking.store') }}" method="post">
                @csrf
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="name">Tên</label>
                      <input type="text" class="form-control" id="name" name="name">
                      <span id="name-error" class="text-danger"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="phone">Số điện thoại</label>
                      <input type="text" class="form-control" id="phone" name="phone">
                      <span id="phone-error" class="text-danger"></span>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" name="email">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="name">Số Khách</label>
                      <input type="number" class="form-control" id="number_of_guest" name="number_of_guest">
                      <span id="guest-error" class="text-danger"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="check_in_date">Ngày nhận phòng</label>
                      <input type="datetime-local" class="form-control" id="check_in_date" name="check_in_date">

                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="check_out_date">Ngày trả phòng</label>
                      <input type="datetime-local" class="form-control" id="check_out_date" name="check_out_date">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="radio" id="guest" name="forother" checked>
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
                    </div>
                  </div>
                </div>
                <div class="row" style="display: none;">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="id_room" id="id_room"
                          value="{{ $room->id }}" checked>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="service" id="serviceToggle">
                        <label for="serviceToggle" class="custom-control-label">Các dịch vụ đi kèm</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row service-list" style="display: none;">
                  @foreach ($services as $service)
                  <div class="col-sm-6">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input service-checkbox" type="checkbox" name="serviceCheckbox[]"
                          id="{{ $service->id }}" value="{{ $service->id }}" data-price="{{ $service->price }}">
                        <label for="{{ $service->id }}" class="custom-control-label" style="font-weight: normal;">{{
                          $service->name }}</label>
                        <label for="{{ $service->id }}" class="service-price float-right"
                          style="font-weight: normal;">{{ number_format($service->price, 0, ',', '.') }} VNĐ</label>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label for="special_requirement">Yêu cầu đặc biệt</label>
                      <p style="font-size: 14px;">Chúng tôi sẽ cố gắng đáp ứng yêu cầu của bạn dựa trên tình trạng sẵn
                        có. Lưu ý rằng bạn có thể phải trả thêm phí cho một số yêu cầu và bạn không thể sửa yêu cầu sau
                        khi đã gửi.</p>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <!-- checkbox -->
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="rqCheckbox[]" id="smoking"
                          value="Phòng không hút thuốc">
                        <label for="smoking" class="custom-control-label" style="font-weight: normal;">Phòng không hút
                          thuốc</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="rqCheckbox[]" id="floors"
                          value="Tầng lầu">
                        <label for="floors" class="custom-control-label" style="font-weight: normal;">Tầng lầu</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="rqCheckbox[]" id="others"
                          data-toggle="collapse" href="#collapseOne">
                        <label for="others" class="custom-control-label" style="font-weight: normal;">Khác</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <!-- checkbox -->
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="rqCheckbox[]" id="rooms"
                          value="Phòng liên thông">
                        <label for="rooms" class="custom-control-label" style="font-weight: normal;">Phòng liên
                          thông</label>
                      </div>
                      <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="rqCheckbox[]" id="bed"
                          value="Loại giường">
                        <label for="bed" class="custom-control-label" style="font-weight: normal;">Loại giường</label>
                      </div>
                    </div>
                  </div>
                  <div id="collapseOne" class="collapse col-sm-12">
                    <div class="form-group">
                      <textarea class="form-control" rows="3" placeholder="Chi tiết yêu cầu"></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <b>Giá phòng</b> <span id="room-price" class=" float-right" data-price="{{ $room->price }}">{{
                        $room->price }} VNĐ</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <b>Dịch vụ</b> <span class=" float-right" id="service-price">0 VNĐ</span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <b>Tổng hóa đơn đặt phòng</b> <span class=" float-right" id="total-price">0 VNĐ</span>
                    </div>
                  </div>
                </div>
                <div class="row" style="display: none;">
                  <div class="col-sm-12">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input type="hidden" id="total-price-input" name="total_price" value="">
                      </div>
                    </div>
                  </div>
                </div>
                <input type="submit" value="Đặt phòng" class="btn btn-success float-right">
              </form>
              <form action="{{ route('booking.cancel') }}" method="get">
                <input type="submit" value="Hủy đơn" class="btn btn-danger float-left"></input>
                <input type="hidden" id="id_room_cancel" name="id_room_cancel" value="{{ $room->id }}">
              </form>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
        <div class="col-md-4">
          <!-- Profile Image -->
          <div class="card card-primary">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-rectangle" src="/template/admin/dist/img/room-1.jpg"
                  alt="User profile picture" style="width: fit-content;">
                <br>
                <!-- Sử dụng dữ liệu được truyền từ Controller -->
                <b class="text-center" style="color: black;">{{ $room->name }}</b>
                <!-- Dùng biến $room->ten_phong để hiển thị Tên phòng -->
              </div>
              <!-- Các thông tin khác cũng tương tự -->
              <ul class="list-group list-group-unbordered mb-3">
                <li style="display: flex; justify-content: space-between;">
                  <b>Sức chứa</b> <span style="text-align: right;">{{ $room->capacity }} người lớn</span>
                </li>
                <li style="display: flex; justify-content: space-between;">
                  <b>Giường</b> <span style="text-align: right;">{{ $room->bed }} kingbed</span>
                </li>
                <li style="display: flex; justify-content: space-between;">
                  <b>Loại phòng</b> <span style="text-align: right;">{{$room->outputkindofroom->kind_of_room }}</span>
                </li>
                <li style="display: flex; justify-content: space-between;">
                  <b>Nội thất phòng:</b>
                  <span style="text-align: right;" title="{{ implode(', ', $amenities) }}">{{ $displayAmenities
                    }}</span>
                </li>
                <li style="display: flex; justify-content: space-between;">
                  <b>Phòng tắm:</b>
                  <span style="text-align: right;" title="{{ implode(', ', $bathroomAmenities) }}">{{
                    $displayBathroomAmenities }}</span>
                </li>
                <li style="display: flex; justify-content: space-between;">
                  <b>Mô tả:</b>
                  <span style="text-align: right;" title="{{ $room->description }}">{{ $displayDescription }}</span>
                </li>
              </ul>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </section>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

  //xử lý phần tổng hóa đơn đặt phòng
  // Lấy các phần tử cần thiết từ DOM
  const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
  const roomPriceSpan = document.getElementById('room-price');
  const servicePriceSpan = document.getElementById('service-price');
  const totalPriceSpan = document.getElementById('total-price');
  const totalPriceInput = document.getElementById('total-price-input');



  // Function để tính tổng giá tiền
  function calculateTotalPrice() {
    let roomPrice = parseInt(roomPriceSpan.getAttribute('data-price'));
    let servicePrice = 0;
    serviceCheckboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        servicePrice += parseInt(checkbox.getAttribute('data-price'));
      }
    });
    // Hiển thị giá dịch vụ và tổng giá tiền
    servicePriceSpan.textContent = servicePrice.toLocaleString('vi-VN') + ' VNĐ';
    const totalPrice = roomPrice + servicePrice;
    totalPriceSpan.textContent = totalPrice.toLocaleString('vi-VN') + ' VNĐ';
    // Gán giá trị cho input ẩn
    totalPriceInput.value = totalPrice; // Gán giá trị vào input hidden
  }
  // Sự kiện thay đổi khi chọn dịch vụ
  serviceCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', calculateTotalPrice);
  });
  // Ban đầu, tính toán tổng giá tiền
  calculateTotalPrice();



  //check xem người dùng đặt phòng cho mình hay cho người khác
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



  document.getElementById('phone').addEventListener('input', function () {
    var phoneValue = this.value;
    var phoneRegex = /^(0\d{9})$/; // Regex kiểm tra số điện thoại bắt đầu bằng 0 và có tổng cộng 10 số.

    if (!phoneRegex.test(phoneValue)) {
      document.getElementById('phone-error').innerText = 'Số điện thoại không hợp lệ.';
    } else {
      document.getElementById('phone-error').innerText = '';
    }
  });



  document.getElementById('number_of_guest').addEventListener('input', function () {
    var guestValue = parseInt(this.value);

    if (isNaN(guestValue) || guestValue <= 0) {
      document.getElementById('guest-error').innerText = 'Số khách phải là số lớn hơn 0.';
    } else {
      document.getElementById('guest-error').innerText = '';
    }
  });



  // Lấy giá trị sức chứa của phòng
  const roomCapacity = {{ $room-> capacity }};
  // Lấy phần tử input số khách
  const numberOfGuestInput = document.getElementById('number_of_guest');
  // Xử lý sự kiện khi người dùng thay đổi giá trị
  numberOfGuestInput.addEventListener('change', function () {
    // Lấy giá trị nhập vào từ input
    const numberOfGuest = parseInt(this.value);
    // Kiểm tra nếu số khách nhập vào lớn hơn sức chứa của phòng
    if (numberOfGuest > roomCapacity) {
      alert(`Số khách không thể lớn hơn ${roomCapacity}`);
      this.value = roomCapacity; // Đặt lại giá trị là sức chứa của phòng
    }
  });



  const nameInput = document.getElementById('name');
  const nameError = document.getElementById('name-error');
  nameInput.addEventListener('input', function () {
    const name = this.value;

    if (/[\d~`!@#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?]/g.test(name)) {
      nameError.textContent = 'Tên không được chứa số hoặc ký tự đặc biệt.';
    } else {
      nameError.textContent = '';
    }
  });


  // Lấy thời gian hiện tại được truyền từ controller
  var currentDate = new Date().toISOString().split('T')[0];

  // Đặt giá trị cho thuộc tính min của input date
  document.getElementById("check_in_date").min = currentDate;

  // Lấy các phần tử input check in và check out
  const checkInInput = document.getElementById('check_in_date');
  const checkOutInput = document.getElementById('check_out_date');

  // Xử lý sự kiện khi người dùng thay đổi giá trị của input check in
  checkInInput.addEventListener('change', function () {
    // Lấy giá trị ngày check in nhập vào từ input
    const checkInDate = new Date(this.value);

    if (!this.value) {
      alert('Vui lòng nhập ngày check-in trước');
      checkOutInput.value = '';
    } else {
      checkOutInput.min = this.value;
      checkOutInput.value = '';
    }
  });

  // Xử lý sự kiện khi người dùng thay đổi giá trị của input check out
  checkOutInput.addEventListener('change', function () {
    // Lấy giá trị ngày check out nhập vào từ input
    const checkOutDate = new Date(this.value);
    const currentDate = new Date();

    // Lấy giá trị ngày check in nhập vào từ input
    const checkInDate = new Date(checkInInput.value);

    if (!checkInInput.value) {
      alert('Vui lòng nhập ngày check-in trước');
      this.value = '';
    } else if (checkOutDate < currentDate || checkOutDate < checkInDate) {
      alert('Ngày trả phòng không hợp lệ');
      this.value = ''; // Xoá giá trị ngày nhập sai
    }
  });

</script>
@endsection