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
                      value="{{$numberOfRooms}} Phòng: {{$guests}} Người lớn, {{$babies}} Trẻ/Phòng{{ $babies_age_txt}}"
                      readonly style="height: calc(1.5em + 10px);">
                  </div>
                </div>
                <div class="col-sm-3 d-flex justify-content-center">
                  <div class="field">
                    <form method="get" action="{{ route('search') }}">
                      @csrf
                      <button type="submit" class="btn float-center custom-btn"
                        style="font-size: 16px;border: -27px;">Chỉnh sửa <i class="fa-solid fa-rotate"></i></button>
                    </form>
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
      @if(optional($cart)->count() > 0)
      <form action="" method="post">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col-md-6">
                <h4 style="margin: 0px">Thông tin cơ bản</h4>
              </div>
              <div class="col-md-6 ">
                <div class="float-right">
                  <i class="fa-solid fa-chevron-right" style="font-size:24px"></i>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @csrf
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="name">Tên </label>
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
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="custom-control custom-radio">
                    <input class="custom-control-input" type="radio" id="guest" name="forother" value="no" checked>
                    <label for="guest" class="custom-control-label">Tôi là người nhận phòng</label>
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
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h4 style="margin: 0px">Dịch vụ và yêu cầu phòng</h4>
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
              </div>
            </div>
          </div>
          <div class="card-body-content" id="{{ $datum->idRoom }}" style="display: none;">
            <div class=" row">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="service"
                      id="serviceToggle-{{ $datum->idRoom }}">
                    <label for="serviceToggle-{{ $datum->idRoom }}" class="custom-control-label">Các dịch vụ đi
                      kèm</label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row service-list-{{ $datum->idRoom }}" style="display: none;">
              @foreach ($services as $service)
              <div class="col-sm-6">
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input service-checkbox" type="checkbox" name="serviceCheckbox[]"
                      id="{{ $service->id }}-{{ $datum->idRoom }}" value="{{ $service->id }}"
                      data-price="{{ $service->price }}">
                    <label for="{{ $service->id }}-{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">{{
                      $service->name }}</label>
                    <label for="{{ $service->id }}-{{ $datum->idRoom }}" class="service-price float-right"
                      style="font-weight: normal;">{{
                      number_format($service->price, 0, ',', '.') }}VNĐ</label>
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
                    <input class="custom-control-input" type="checkbox" name="rqCheckbox[]"
                      id="smoking-{{ $datum->idRoom }}" value="Phòng không hút thuốc">
                    <label for="smoking-{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">Phòng không hút
                      thuốc</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rqCheckbox[]"
                      id="floors-{{ $datum->idRoom }}" value="Tầng lầu">
                    <label for="floors-{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">Tầng lầu</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rqCheckbox[]"
                      id="others-{{ $datum->idRoom }}" data-toggle="collapse" href="#collapseOne-{{ $datum->idRoom }}">
                    <label for="others-{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">Khác</label>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <!-- checkbox -->
                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rqCheckbox[]"
                      id="rooms-{{ $datum->idRoom }}" value="Phòng liên thông">
                    <label for="rooms-{{ $datum->idRoom }}" class="custom-control-label"
                      style="font-weight: normal;">Phòng liên
                      thông</label>
                  </div>
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="rqCheckbox[]"
                      id="bed-{{ $datum->idRoom }}" value="Loại giường">
                    <label for="bed-{{ $datum->idRoom }}" class="custom-control-label" style="font-weight: normal;">Loại
                      giường</label>
                  </div>
                </div>
              </div>
              <div id="collapseOne-{{ $datum->idRoom }}" class="collapse col-sm-12">
                <div class="form-group">
                  <textarea class="form-control" rows="3" placeholder="Chi tiết yêu cầu"></textarea>
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
          </div>
          @endforeach
        </div>
        <!-- /.card-body -->
    </div>

    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6">
            <h4 style="margin: 0px">Tóm tắt chi phí </h4>
          </div>
          <div class="col-md-6">
            <div class="float-right">
              <i class="fa-solid fa-chevron-right" style="font-size:24px"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <b>Giá phòng</b> <span id="room-price" class=" float-right" data-price="">0 VNĐ</span>
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
          <div class="col-md-12">
            <div class="form-group">
              <b>Tổng hóa đơn đặt phòng</b> <span class=" float-right" id="total-price">0 VNĐ</span>
            </div>
          </div>
        </div>
        <input type="submit" value="Đặt phòng" class="btn btn-success float-right"></input>
        <input type="submit" value="Hủy đơn" class="btn btn-danger float-left"></input>
      </div>
    </div>
    </form>
</div>
@else
@endif
</section>
</div>

<script>
  function toggleCardBody(id) {
    var icon = document.getElementById('icon-' + id);
    if (icon) {
      // Kiểm tra lớp CSS của icon để xác định trạng thái hiện tại
      if (icon.classList.contains('fa-chevron-right')) {
        // Nếu là fa-chevron-right, chuyển sang fa-chevron-down
        icon.innerHTML = '<i class="fa-solid fa-chevron-down" style="font-size:24px"></i>';
      } else {
        // Ngược lại, chuyển về fa-chevron-right
        icon.innerHTML = '<i class="fa-solid fa-chevron-right" style="font-size:24px"></i>';
      }
    }

    var body = document.getElementById('card-body-' + id);
    if (body) {
      // Thay đổi trạng thái hiển thị của card-body
      if (body.style.display === "none") {
        body.style.display = "block";
      } else {
        body.style.display = "none";
      }
    }
  }


</script>
<script>
  document.querySelectorAll('input[type="radio"]').forEach(function (radio) {
    radio.addEventListener('click', function () {
      // Kiểm tra xem radio button có id là 'forother' được chọn không
      if (this.id === 'forother') {
        // Nếu được chọn, hiển thị thẻ chứa input tên người khác
        document.getElementById('otherNameInput').style.display = 'block';
      } else {
        // Nếu không được chọn, ẩn thẻ chứa input tên người khác
        document.getElementById('otherNameInput').style.display = 'none';
      }
    });
  });

</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Lặp qua tất cả các phần tử có class là 'custom-control-input'
    var checkboxes = document.querySelectorAll('.custom-control-input');

    checkboxes.forEach(function (checkbox) {
      // Thêm sự kiện 'click' cho mỗi checkbox
      checkbox.addEventListener('click', function () {
        // Lấy id của checkbox
        var id = checkbox.getAttribute('id');
        // Kiểm tra nếu checkbox được kiểm tra
        if (checkbox.checked) {
          // Hiển thị phần tử có class là 'service-list-{{ $datum->idRoom }}' tương ứng
          var serviceList = document.querySelector('.service-list-' + id.split('-')[1]);
          if (serviceList) {
            serviceList.style.display = 'block';
          }
        } else {
          // Ẩn phần tử có class là 'service-list-{{ $datum->idRoom }}' tương ứng
          var serviceList = document.querySelector('.service-list-' + id.split('-')[1]);
          if (serviceList) {
            serviceList.style.display = 'none';
          }
        }
      });
    });
  });
</script>
@endsection