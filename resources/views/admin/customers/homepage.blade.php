<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.customers.head')
</head>
<style>
    .hr-text h3,
.hr-text h2,
.hr-text table {
    text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
}
</style>

<body>
    <!-- Hero Section Begin -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="hero-text">
                        <h1 style="font-size: 55px; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;">VHomeStay</h1>
                        <p style=" text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;">VHomeStay là sự lựa chọn lý tưởng cho chuyến du lịch gia đình nhờ không gian rộng rãi, view
                            đẹp, và sự ấm cúng giống như ở nhà.</p>
                        <a href="#" class="primary-btn">Khám phá ngay</a>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 offset-lg-1">
                    <div class="booking-form">
                        <h3 style="text-align: center">Tìm kiếm phòng</h3>
                        <form action="{{ route('customer.search') }}" method="get">
                        @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="check-date">
                                            <label for="date-in" style="color: black">Ngày nhận phòng:</label>
                                            <input type="text" name="date_in" autocomplete="off" class="date-input" id="date-in">
                                            <i class="fa-regular fa-calendar" ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="check-date">
                                            <label for="date-out" style="color: black">Ngày trả phòng:</label>
                                            <input type="text" name="date_out" autocomplete="off" class="date-input" id="date-out">
                                            <i class="fa-regular fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="guests">Người lớn <span style="font-weight: 500">(Tối đa: tổng cộng 6 khách/phòng)</span></label>
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
                                        <input type="number" id="guests" name="guests" class="form-control" min="0" max="6" value="2" style="display: none;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
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
                            <button type="button" onclick=checkSubmit()>Tìm kiếm phòng</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-slider owl-carousel">
            <div class="hs-item set-bg" data-setbg="/template/customer/img/hero/hero1.jpg"></div>
            <div class="hs-item set-bg" data-setbg="/template/customer/img/hero/hero2.jpg"></div>
            <div class="hs-item set-bg" data-setbg="/template/customer/img/hero/hero3.jpg"></div>
            <div class="hs-item set-bg" data-setbg="/template/customer/img/hero/3.jpg"></div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- About Us Section Begin -->
    <section class="aboutus-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-text">
                        <div class="section-title">
                            <h2>VHomeStay <br />Khoảnh khắc bình yên</h2>
                        </div>
                        <p class="f-para">VHomeStay - Là điểm đến lý tưởng cho những du khách mong muốn trải nghiệm
                            không gian ấm cúng và thân thuộc như ở nhà, đồng thời được tận hưởng tiện nghi và dịch vụ
                            chất lượng cao của một khách sạn chuyên nghiệp. Với đa dạng các lựa chọn từ các căn hộ, biệt
                            thự, đến phòng riêng lẻ, VHomeStay mang đến sự linh hoạt cho du khách khi lựa chọn nơi lưu
                            trú tại các điểm đến yêu thích.</p>
                        <a href="#" class="primary-btn about-btn">Xem thêm</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-pic">
                        <div class="row">
                            <div class="col-sm-6">
                                <img src="/template/customer/img/about/about-3.jpg" alt="">
                            </div>
                            <div class="col-sm-6">
                                <img src="/template/customer/img/about/about-4.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Us Section End -->

    <!-- Services Section End -->
    <section class="services-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Khám phá những dịch vụ</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-036-parking"></i>
                        <h4>Dịch vụ đỗ xe & đưa đón</h4>
                        <p>Dịch vụ đưa đón khách đến homestay và đỗ xe giúp khác hàng .</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-033-dinner"></i>
                        <h4>Dịch vụ ăn uống</h4>
                        <p>Chi tiết dịch vụ ăn uống bao gồm các món ăn và đồ uống được cung cấp tại nhà hàng hoặc quầy bar của khách sạn.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-026-bed"></i>
                        <h4>Dịch vụ phòng</h4>
                        <p>Chi tiết dịch vụ phòng là các tiện ích và dịch vụ được cung cấp trong phòng nghỉ của khách sạn.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-024-towel"></i>
                        <h4>Dịch vụ phòng tắm</h4>
                        <p>Chi tiết dịch vụ phòng tắm bao gồm các tiện ích và dịch vụ được cung cấp trong phòng tắm của khách sạn.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-044-clock-1"></i>
                        <h4>Mở 24/7</h4>
                        <p>Chi tiết dịch vụ mở cửa 24/7 là dịch vụ hoạt động liên tục trong 24 giờ mỗi ngày, 7 ngày mỗi tuần.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-012-cocktail"></i>
                        <h4>Dịch vụ đồ uống free</h4>
                        <p>Chi tiết dịch vụ đồ uống miễn phí là các loại đồ uống được cung cấp cho khách hàng mà không cần trả phí.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services Section End -->

    <!-- Home Room Section Begin -->
    <section class="hp-room-section">
        <div class="container-fluid">
            <div class="hp-room-items">
                <div class="row">
                @foreach($rooms as $room)
                    @foreach($kors as $kor)
                        @if($room->id_kind_of_room == $kor->id)
                            <div class="col-lg-3 col-md-6">
                                <div class="hp-room-item set-bg" data-setbg="/kind_of_rooms/{{$kor->image}}" >
                                    <div class="hr-text">
                                        <h3 style="margin-bottom: 30px">{{ $kor->kind_of_room }}</h3>
                                        <!-- <h2>{{ $kor->price }} VNĐ<span>/Đêm</span></h2> -->
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="r-o">Còn Trống:</td>
                                                    <td>{{ $kor->available }} phòng</td>
                                                </tr>
                                                <tr>
                                                    <td class="r-o">Độ rộng:</td>
                                                    <td>{{ $room->surface }} m<sup>2</sup></td>
                                                </tr>
                                                <tr>
                                                    <td class="r-o">Sức chứa:</td>
                                                    <td>Tối đa {{ $room->capacity }} người</td>
                                                </tr>
                                                <tr>
                                                    <td class="r-o">Mô tả:</td>
                                                    <td title="{{$kor->description}}">{{ Illuminate\Support\Str::limit($kor->description, 35) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <a href="{{ route('customer.roomlist', ['id' => $kor->id]) }}" class="primary-btn">Thông tin chi tiết</a>
                                    </div>
                                </div>
                            </div>
                            @endif

                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Home Room Section End -->

    <!-- Testimonial Section Begin -->
    <section class="testimonial-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Những đánh giá</span>
                        <h2>Người dùng nói gì?</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="testimonial-slider owl-carousel">
                        <div class="ts-item">
                            <p>Kỳ nghỉ của tôi tại VHomeStay thật sự tuyệt vời! Phòng được thiết kế đẹp mắt, phục vụ rất
                                tận tâm và tiện nghi tuyệt vời. Sẽ quay lại đây lần sau!</p>
                            <div class="ti-author">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                </div>
                                <h5> - Alexander Vasquez</h5>
                            </div>
                            <img src="/template/customer/img/testimonial-logo.png" alt="">
                        </div>
                        <div class="ts-item">
                            <p>Phòng ở VHomeStay được trang bị đầy đủ tiện nghi hiện đại, từ đồ nội thất đến các thiết
                                bị. Điều này giúp chuyến du lịch của tôi trở nên thoải mái và dễ dàng hơn.</p>
                            <div class="ti-author">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>
                                <h5> - Alexander Vasquez</h5>
                            </div>
                            <img src="/template/customer/img/testimonial-logo.png" alt="">
                        </div>
                        <div class="ts-item">
                            <p>Tôi đã tổ chức sự kiện của mình tại VHomeStay và không thể hạnh phúc hơn với dịch vụ hỗ
                                trợ và không gian tuyệt vời tại đây. Sự kiện diễn ra suôn sẻ và thành công.</p>
                            <div class="ti-author">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>
                                <h5> - Alexander Vasquez</h5>
                            </div>
                            <img src="/template/customer/img/testimonial-logo.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Section End -->

    <!-- Blog Section Begin -->
    <section class="blog-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Thông tin mới!</span>
                        <h2>Các trang sự kiện</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="/template/customer/img/blog/blog-1.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Chuyến du lịch</span>
                            <h4><a href="#">3 ngày 2 đêm tại Canada</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> ngày 15 tháng 12, 2023</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="/template/customer/img/blog/blog-2.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Cắm trại</span>
                            <h4><a href="#">Một điểm tại Caravan</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> ngày 30 tháng 12, 2023</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item set-bg" data-setbg="/template/customer/img/blog/blog-3.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Sự kiện</span>
                            <h4><a href="#">Truy tìm báu vật</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> ngày 24 tháng 12, 2023</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="blog-item small-size set-bg" data-setbg="/template/customer/img/blog/blog-wide.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Sự kiện</span>
                            <h4><a href="#">Chuyến du lịch miễn phí cho cặp đôi</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> ngày 30 tháng 12, 2023</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="blog-item small-size set-bg" data-setbg="/template/customer/img/blog/blog-10.jpg">
                        <div class="bi-text">
                            <span class="b-tag">Du lịch</span>
                            <h4><a href="#">Chuyến tham quan Barcelona</a></h4>
                            <div class="b-time"><i class="icon_clock_alt"></i> ngày 24 tháng 1, 2024</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

</body>
@include('admin.customers.footer')
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
    document.getElementById("date-in").value = document.getElementById("date-in").textContent;
    document.getElementById("date-out").value = document.getElementById("date-out").textContent;
    function checkSubmit() {
        var dateIn = document.getElementById("date-in").value;
        var dateOut = document.getElementById("date-out").value;
        if(dateIn == 0){
            alert("Mời nhập ngày check-in.");
        }else if (dateOut > dateIn) {
            // Nếu date-out lớn hơn date-in, thực hiện submit form
            document.querySelector("form").submit();
        } else {
            // Nếu date-out không lớn hơn date-in, hiển thị thông báo hoặc xử lý khác
            alert("Ngày check-out phải lớn hơn ngày check-in.");
            // hoặc có thể ngăn chặn việc submit bằng cách return false
            // return false;
        }
    }
</script>
</html>