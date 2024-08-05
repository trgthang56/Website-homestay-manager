@extends('admin.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Tìm kiếm</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Search</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('search') }}" method="get">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="checkin">Check-in:</label>
                    <input type="date" id="checkin" name="checkin" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="checkout">Check-out:</label>
                    <input type="date" id="checkout" name="checkout" class="form-control">
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
                        data-unit="Người lớn mỗi phòng" value="2 Người lớn mỗi phòng" readonly
                        style="background-color: #fff">
                      <div class="input-group-append">
                        <span class="input-group-text" id="decrement"
                          style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-minus"></i></span>
                        <span class="input-group-text" id="increment"
                          style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-plus"></i></span>
                      </div>
                    </div>
                    <input type="number" id="guests" name="guests" class="form-control" min="0" max="6" value="2"
                      style="display: none;">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="babies">Trẻ em <span style="font-weight: 500">(Tối đa: tổng cộng 6
                        khách/phòng)</span></label>
                    <div class="input-group">
                      <input type="text" id="babies-txt" name="babies_txt" class="form-control"
                        data-unit="Trẻ em mỗi phòng" value="0 Trẻ em mỗi phòng" readonly
                        style="background-color: #fff">
                      <div class="input-group-append">
                        <span class="input-group-text" id="babies-decrement"
                          style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-minus"></i></span>
                        <span class="input-group-text" id="babies-increment"
                          style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-plus"></i></span>
                      </div>
                    </div>
                    <input type="number" id="babies" name="babies" class="form-control" min="0" max="6" value="0"
                      style="display: none;">
                  </div>
                  <div class="form-group" style="display: none" data-babies-age>
                    <p style="display: block;">(Có thể có mức giá dựa trên độ tuổi)</p>
                    <label for="babies-age" style="font-weight: 500">Tuổi(Bắt buộc)</label>
                    <div class="input-group">
                      <input type="text" id="babies-age-txt" name="babies_age_txt" class="form-control"
                        value="0-2 tuổi: Trẻ nhỏ" readonly style="background-color: #fff">
                      <div class="input-group-append">
                        <span class="input-group-text" id="babies-age-decrement"
                          style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-minus"></i></span>
                        <span class="input-group-text" id="babies-age-increment"
                          style="cursor: pointer; background-color: #fff"><i class="fa-solid fa-plus"></i></span>
                      </div>
                    </div>
                    <input type="number" id="babies-age" name="babies_age" class="form-control" min="0" max="5"
                      value="0" style="display: none;">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="price-input">
                    <div class="col-sm-6" style="padding: 0 7.5px 0 0;">
                      <label for="min_budget">Min</label>
                      <label for="min_budget" class="float-right">VNĐ</label>

                      <div class="field">
                        <input type="number" id="min_budget" name="min_budget" class="input-min" value="625000">
                      </div>
                    </div>
                    <div class="col-sm-6" style="padding: 0 0 0 7.5px;">
                      <label for="max_budget">Max</label>
                      <label for="max_budget" class="float-right">VNĐ</label>

                      <div class="field">
                        <input type="number" id="max_budget" name="max_budget" class="input-max" value="1875000">
                      </div>
                    </div>
                  </div>
                  <div class="slider">
                    <div class="progress"></div>
                  </div>
                  <div class="range-input">
                    <input type="range" class="range-min" min="0" max="2500000" value="625000" step="25000">
                    <input type="range" class="range-max" min="0" max="2500000" value="1875000" step="25000">
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary float-right" style="margin: 10px 0 0px 0">Search</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>

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

  // Hàm tăng giảm giá trị của input và cập nhật input text
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
  const rangeInput = document.querySelectorAll(".range-input input"),
    priceInput = document.querySelectorAll(".price-input input"),
    range = document.querySelector(".slider .progress");
  let priceGap = 1000;

  priceInput.forEach(input => {
    input.addEventListener("input", e => {
      let minPrice = parseInt(priceInput[0].value),
        maxPrice = parseInt(priceInput[1].value);

      if ((maxPrice - minPrice >= priceGap) && maxPrice <= rangeInput[1].max) {
        if (e.target.className === "input-min") {
          rangeInput[0].value = minPrice;
          range.style.left = ((minPrice / rangeInput[0].max) * 100) + "%";
        } else {
          rangeInput[1].value = maxPrice;
          range.style.right = 100 - (maxPrice / rangeInput[1].max) * 100 + "%";
        }
      }
    });
  });

  rangeInput.forEach(input => {
    input.addEventListener("input", e => {
      let minVal = parseInt(rangeInput[0].value),
        maxVal = parseInt(rangeInput[1].value);

      if ((maxVal - minVal) < priceGap) {
        if (e.target.className === "range-min") {
          rangeInput[0].value = maxVal - priceGap
        } else {
          rangeInput[1].value = minVal + priceGap;
        }
      } else {
        priceInput[0].value = minVal;
        priceInput[1].value = maxVal;
        range.style.left = ((minVal / rangeInput[0].max) * 100) + "%";
        range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
      }
    });
  });
</script>
@endsection