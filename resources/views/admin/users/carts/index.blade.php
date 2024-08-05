@extends('admin.main')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Giỏ hàng</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Cart</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    @if(optional($cart)->count() > 0)
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
                    value="{{ \Carbon\Carbon::parse($checkin)->locale('vi')->isoFormat('dddd, DD [Tháng] M') }}"
                    readonly style="height: calc(1.5em + 10px);">
                </div>
              </div>
              <div class="col-sm-6 float-right">
                <div class="field">
                  <input type="text" id="min_budget" name="min_budget" class="input-min"
                    value="{{ \Carbon\Carbon::parse($checkout)->locale('vi')->isoFormat('dddd, DD [Tháng] M') }}"
                    readonly style="height: calc(1.5em + 10px);">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <label for="max_budget" style="padding: 0 7.5px 0 0;">Số khách</label>
            <div class="row">
              <div class="col-sm-9">
                <div class="field">
                  <input type="text" id="max_budget" name="max_budget" class="input-max"
                    value="{{$numberOfRooms}} Phòng: {{$guests}} Người lớn, {{$babies}} Trẻ em/Phòng{{$babies_age_txt}}"
                    readonly style="height: calc(1.5em + 10px);">
                </div>
              </div>
              <div class="col-sm-3 d-flex justify-content-center">
                <div class="field">
                    <button class="btn float-center custom-btn" style="font-size: 16px; border: -27px;" data-toggle="modal" data-target=".bd-example-modal-lg">
                      Chỉnh sửa 
                      <i class="fa-solid fa-rotate"></i>
                    </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th style="width: 7%">
                mã phòng </th>
              <th style="width: 7%">
                Tên phòng
              </th>
              <th style="width: 7%; text-align:center">
                Số Phòng
              </th>
              <th style="width: 7%; text-align:center">
                Diện tích
              </th>
              <th style="width: 7%; text-align:center">
                Sức chứa
              </th>
              <th style="width: 7%; text-align:center">
                Giường
              </th>
              <th style="width: 7% ; text-align:center">
                Loại phòng
              </th>

              <th style="width: 7%; text-align:center">
                giá phòng
              </th>
              <th style="width: 11%;">
              </th>
              <th style="width: 11%;">
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($cart as $datum)
            <tr>
              <td>{{ $datum->idRoom}}</td>
              <td>{{ $datum->name}}</td>
              <td style="text-align:center">{{ $datum->number }}</td>
              <td style="text-align:center">{{ $datum->surface }} m<sup style="size: 100%;">2</sup></td>
              <td style="text-align:center">{{ $datum->capacity }} Người</td>
              <td style="text-align:center">{{ $datum->bed }} Chiếc</td>
              <td style="text-align:center">{{ $datum->kind_of_room }}</td>
              <td style="text-align:center">{{ number_format($datum->price, 0, ',', '.') }} VNĐ</td>
              <td style="text-align:center">{{ $datum->submitTime }}</td>

              <td class="text-right" style="text-align:center " >
                <a class="btn btn-danger btn-sm" style="margin-right: 20px;">
                  <form id="deleteForm_{{ $datum->idRoom }}" action="{{route('cart.delete')}}" method="post">
                    @csrf
                    <button type="button" onclick="confirmDelete({{ $datum->idRoom }})" class="btn btn-danger btn-sm">
                      <input type="number" id="idRooms" name="idRooms" value="{{ $datum->idRoom }}" style="display: none;">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                  </form>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md-6">
            <form method="POST" action="{{ route('cart.cancel') }}">
              @csrf
              <button type="submit" class="btn btn-danger float-left" style="font-size: 16px; border: 0px;">
              <i class="fa-solid fa-trash-can"></i> Xóa toàn bộ </button>
            </form>
          </div>
          <div class="col-md-6">
            <form method="get" action="{{ route('cart.detail') }}">
              @csrf
              <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px;">Trang
                thanh toán <i class="fa-solid fa-arrow-right"></i></button>
            </form>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    @else
    <div class="card">
      <div class="card-body">
        <div class="row justify-content-center align-items-center" style="min-height: 400px;">
          <h2>Chưa có phòng trong giỏ hàng</h2>
        </div>
      </div>
    </div>
    @endif
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content"  style="max-width: 799px">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Mời chọn phương thức thanh toán</h5>
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
  </section>
  <!-- /.content -->
</div>
<script>
  // Lấy ngày hiện tại
  var currentDate = new Date().toISOString().split('T')[0];

  // Đặt giá trị thuộc tính min của trường input date
  document.getElementById('checkin').min = currentDate;
  // Lấy các phần tử input check in và check out
  const checkInInput = document.getElementById('checkin');
  const checkOutInput = document.getElementById('checkout');

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
<script>
  function confirmDelete(id) {
    if (confirm("Bạn có chắc chắn muốn xóa không?")) {
      document.getElementById('deleteForm_' + id).submit();
    }
  }
  function confirmAccept(id) {
    if (confirm("Bạn có chắc chắn muốn xác nhận phòng không?")) {
      document.getElementById('acceptForm_' + id).submit();
    }
  }
  function confirmComplete(id) {
    if (confirm("Bạn có chắc chắn muốn hoàn thành đơn và trả phòng không?")) {
      document.getElementById('completeForm_' + id).submit();
    }
  }
</script>
<script>
  // Lấy các phần tử cần thiết
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