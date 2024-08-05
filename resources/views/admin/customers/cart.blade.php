<!DOCTYPE html>
<html lang="en">

<head>
  @include('admin.customers.head')
</head>
<style>
  .custom-btn {
    border: 1px solid #000;
    transition: background-color 0.3s, color 0.3s;
  }

  .custom-btn:hover {
    background-color: #000;
    color: #fff;
  }
  .card-header {
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: #fff;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: 5px;

}
.icon-button {
    border: none;
    background-color: transparent; 
    padding: 0; 
}

.icon-button i {
    font-size: 18px; 
    color: #000;
}
.row .col-lg-6 a {
    text-decoration: none;
    color: inherit;
}

.row .col-lg-6 a:hover {
    text-decoration: none;
    color: inherit;
}
</style>
<body>
  <!-- Breadcrumb Section Begin -->
  <div class="breadcrumb-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-text">
            <h2>Giỏ hàng</h2>
            <!-- <div class="bt-option">
              <a href="/homepage">Trang chủ</a>
              <span>$kor->kind_of_room</span>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumb Section End -->
  <!-- Modal -->
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
  <!-- Rooms Section Begin -->
  <section class="rooms-section spad">
    <div class="container">
      <div class="card-header">
        <div class="row">
          <div class="col-md-12" style="padding:10px 15px">
            <h5>Thông tin nhận và trả phòng</h5>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6" style="padding: 0 7.5px 0 15px;">
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
              <div class="col-sm-8">
                <div class="field">
                  <input type="text" id="max_budget" name="max_budget" class="input-max"
                    value="{{$numberOfRooms}} Phòng: {{$guests}} Người lớn, {{$babies}} Trẻ em/Phòng{{$babies_age_txt}}"
                    readonly style="height: calc(1.5em + 10px); font-size: 16px;">
                </div>
              </div>
              <div class="col-sm-4 d-flex justify-content-center">
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
      @if($numberOfRooms == 0)
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 d-flex justify-content-center" style="margin: 20px 0px">
            <h4>
              Giỏ hàng của bạn đang trống.
            </h4>
          </div>
        </div>
      </div>
      @else
        @foreach($cart as $data)
        <div class="card-body" style="margin: 10px 0px; border-radius: 5px; border: 1px solid rgba(0, 0, 0, .125); box-shadow: 2px 6px 8px rgba(0, 0, 0, 0.1);">
          <div class="row">
            <div class="col-md-3 d-flex justify-content-center">
              @if($data->room->image!=null)
                <img src="{{asset('rooms/'.$data->room->image)}}" alt="Room Image" style="height: 175px;">
              @else
                <img src="/template/customer/img/room/house.png" alt="Room Image" style="height: 175px;">
              @endif
            </div>
            <div class="col-md-9" style="font-size: 18px;">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <p>{{$data->room->name}}</p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <p>{{$data->room->number}}</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <p>{{$data->room->surface}} m<sup style="size: 100%;">2</sup></p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <p>{{$data->room->capacity}} Người</p>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <p>{{$data->room->bed}} Chiếc kingbed</p>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <p>{{ number_format($data->room->price, 0, ',', '.') }} VNĐ/Đêm</p>
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top: 15px">
                
                <div class="col-lg-6" >
                  <a href="#" class="primary-btn" style="color: black">Thông tin chi tiết</a>
                </div>
                <div class="col-lg-6">
                  <a href="#" style="margin-right: 20px"><i class="fa-regular fa-heart"></i></a>
                  <a class="btn" style="margin: 0px 20px">
                    <form id="deleteForm_{{ $data->idRoom }}" action="{{route('cart.delete')}}" method="post">
                      @csrf
                      <button type="button" onclick="confirmDelete({{ $data->idRoom }})" class="btn icon-button btn-sm">
                        <input type="number" id="idRooms" name="idRooms" value="{{ $data->idRoom }}" style="display: none;">
                        <i class="fa-solid fa-delete-left"></i>
                      </button>
                    </form>
                  </a>
                  <!-- <a href="#" style="margin: 0px 20px"><i class="fa-solid fa-comments"></i></a> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
        <div class="card-footer" style="padding-right: unset;padding-left: unset ;border: unset; background-color: white">
          <div class="row">
            <div class="col-md-6">
              <form method="POST" action="{{ route('cart.cancel') }}">
                @csrf
                <button type="submit" class="btn btn-danger float-left" style="font-size: 16px; border: 0px;">
                <i class="fa-solid fa-trash-can"></i> Xóa toàn bộ </button>
              </form>
            </div>
            <div class="col-md-6">
              <form method="get" action="{{ route('customer.cart.detail') }}">
                @csrf
                <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px;">Trang
                  thanh toán <i class="fa-solid fa-arrow-right"></i></button>
              </form>
            </div>
          </div>
        </div>
      @endif 
    </div>
  </section>
  <!-- Rooms Section End -->
</body>
@include('admin.customers.footer')
<script>
  function confirmDelete(id) {
    if (confirm("Bạn có chắc chắn muốn xóa không?")) {
      document.getElementById('deleteForm_' + id).submit();
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
</html>