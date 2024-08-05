<!DOCTYPE html>
<html lang="en">

<head>
  @include('admin.customers.head')
  <style>
  /* CSS cho modal */
  .modal {
    display: none;
    /* Ẩn modal ban đầu */
    position: fixed;
    z-index: 9999;
    left: 0;
    margin: 50px;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
  }

  /* Nội dung của modal */
  .modal-content {
    margin: auto;
    display: block;
    max-width: 640px;
    /* Kích thước tối đa chiều rộng của ảnh */
    max-height: 640px;
    /* Kích thước tối đa chiều cao của ảnh */
    width: auto;
    height: auto;
  }

  /* Responsive: thay đổi kích thước của ảnh trong modal khi màn hình thu nhỏ */
  @media screen and (max-width: 768px) {
    .modal-content {
      max-width: 90%;
      /* Kích thước tối đa khi màn hình nhỏ hơn */
      max-height: 90%;
    }
  }


  /* Đóng modal nút x */
  .close {
    position: absolute;
    top: 10px;
    right: 25px;
    font-size: 35px;
    color: #ffffff;
    cursor: pointer;
  }

  .close:hover,
  .close:focus {
    color: #aaaaaa;
  }

  #selectedImage {
    max-width: 120px;
    /* Chiều rộng tối đa */
    max-height: 120px;
    /* Chiều cao tối đa */
    margin: 20px;
  }
</style>
<style>
  .profile-user-img {
    border-radius: 50%;
    width: 100px;
    /* Điều chỉnh kích thước tùy theo nhu cầu */
    height: 100px;
    /* Điều chỉnh kích thước tùy theo nhu cầu */
    object-fit: cover;
    /* Giữ tỉ lệ của hình ảnh */
  }

  .image img {
    width: 35px;
    /* Điều chỉnh kích thước tùy theo nhu cầu */
    height: 35px;
    /* Điều chỉnh kích thước tùy theo nhu cầu */
    object-fit: cover;
    /* Giữ tỉ lệ của hình ảnh */
  }
</style>
<style>
  .border-bot{
    border-bottom: 0.5px solid #cdcdcd;
  }
  .spad{
    padding-top: 25px;
    padding-bottom: 25px;
  }
</style>
</head>
<body>
  <section class="section-profile">
    <div class="container">
      <div class="row spad">
        <div class="col-lg-4">

        </div>
      </div>
      <div class="row">
        <div class="col-lg-2">
          <div class="card" style="border: unset">
            <div class="card-body">
              <!-- Modal -->
              <div id="imageModal" class="modal">
                <span class="close">&times;</span>
                <img class="modal-content" id="modalImage">
                <div id="caption"></div>
              </div>
              <!-- Đoạn mã HTML hiển thị ảnh -->
              <div class="text-center">
                @if(Auth::user()->image)
                <label class="image-container" onclick="openModalAvartar()">
                  <img class="profile-user-img img-fluid img-circle" src="/uploads/{{ Auth::user()->image }}"
                    alt="User profile picture">
                </label>
                @else
                <label class="image-container" onclick="openModalAvartar()">
                  <img class="profile-user-img img-fluid img-circle" src="/template/admin/dist/img/avatar-default.jpg"
                    alt="User profile picture">
                </label>
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-10">
          <div class="card" style="border: unset">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h3>{{ Auth::user()->name }}</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <p style="margin: 5px 0px">{{ Auth::user()->email }}</p>
                  <p style="margin: 5px 0px">{{ Auth::user()->phone }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row border-bot">
        <div class="col-md-12">
          <div class="card-body " style="cursor:pointer;" onclick="toggleUserProfile()">
            <div class="row ">
              <div class="col-md-1 text-center">
                <i class="fa-solid fa-user" style="font-size: 16px"></i>
              </div>
              <div class="col-md-6">
                <h4 style="margin: 0px; font-size: 16px">Thông tin cá nhân</h4>
              </div>
            </div>
          </div>
          <div class="card-body" id="user-profile" style="display: none;">
            <form form action="{{ route('user.update', Auth::user()->id) }}" method="post" id="userForm" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name">Họ và Tên</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}"
                      oninput="validateInput(this)">
                    <span id="nameError" style="color: red;"></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="birth">Ngày Sinh</label>
                    <input type="date" class="form-control" id="birth" name="birth"
                      value="{{ Auth::user()->birth }}" oninput="validateInput(this)">
                    <span id="birthError" style="color: red;"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                      value="{{ Auth::user()->phone }}" oninput="validateInput(this)">
                    <span id="phoneError" style="color: red;"></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="address">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address"
                      value="{{ Auth::user()->address }}" oninput="validateInput(this)">
                    <span id="addressError" style="color: red;"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="email">Địa chỉ Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                      value="{{ Auth::user()->email }}" oninput="validateInput(this)">
                    <span id="emailError" style="color: red;"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="avartarFile">Ảnh (640 x 640px)</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="avartarFile" name="avartarFile"
                          onchange="displayImage(this)">
                        <label class="custom-file-label" for="avartarFile">Chọn file ảnh</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <img id="selectedImage" class="img-fluid" style="display: none;" alt="Selected Image"
                    onclick="openModal()">
                </div>
              </div>
              <button type="button" class="btn btn-primary" onclick="submitForm()">Sửa</button>
            </form>
          </div>
        </div>
      </div>
      <div class="row border-bot">
        <div class="col-md-12 ">
          <div class="card-body " style="cursor:pointer;" onclick="">
            <div class="row ">
              <div class="col-md-1 text-center">
              <i class="fa-regular fa-address-card" style="font-size: 16px"></i>
              </div>
              <div class="col-md-6">
                <h4 style="margin: 0px; font-size: 16px">Thẻ thành viên</h4>
              </div>
            </div>
          </div>
          <div class="card-body" id="" style="display: none;">
          
          </div>
        </div>
      </div>
      <div class="row border-bot">
        <div class="col-md-12 ">
          <div class="card-body " style="cursor:pointer;" onclick="toggleUserBookedList()">
            <div class="row ">
              <div class="col-md-1 text-center">
                <i class="fa-solid fa-clock-rotate-left" style="font-size: 16px"></i>
              </div>
              <div class="col-md-6">
                <h4 style="margin: 0px; font-size: 16px">Phòng đã đặt </h4>
              </div>
            </div>
          </div>
          @if(Auth::user()->id_bill != null)
          <div class="card-body table-responsive p-0" id="user-booked-list" style="display: none;">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th style="width: 5%">
                    Mã đơn
                  </th>
                  <th style="width: 12%">
                    Số điện thoại
                  </th>
                  <th style="width: 15%">
                    Nhân viên xác nhận
                  </th>
                  <th style="width: 15%">
                    Thời gian đặt
                  </th>
                  <th style="width: 10%">
                    Người nhận phòng
                  </th>
                  <th style="width: 10%">
                    Số phòng
                  </th>
                  <th style="width: 8%">
                    Tổng chi phí
                  </th>
                  <th style="width: 15%">
                    Thời gian nhận phòng
                  </th>
                  <th style="width: 15%">
                    Thời gian trả phòng
                  </th>
                  <th style="width: 5%; text-align: center;">
                    Trạng thái
                  </th>
                  <th style="width: 5%">
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data as $datum)
                <tr class="{{($datum->status == 1 && \Carbon\Carbon::parse($datum->check_out_date)->isBefore(\Carbon\Carbon::now())) ? 'text-danger' : '' }}">
                  <td>{{ $datum->id}}</td>
                  <td>{{ $datum->phone}}</td>
                  <td>{{ $datum->id_user }}</td>
                  <!-- <td>{{ $datum->id_customer }}</td> -->
                  <td>{{ date('H:i:s - d/m/Y', strtotime($datum->created_at)) }}</td>
                  <td>{{ $datum->name }}</td>
                  <!-- <td>{{ $datum->number_of_guest }} Người</td> -->
                  <td>{{ $datum->number_of_room }} Phòng</td>
                  <td>{{ number_format($datum->total, 0, ',', '.') }} VNĐ</td>
                  <td>
                    {{ date('H:i:s - d/m/Y', strtotime($datum->check_in_date)) }}
                  </td>
                  <td>
                    {{ date('H:i:s - d/m/Y', strtotime($datum->check_out_date)) }}
                  </td>
                  @if( $datum-> status == 0)
                    <td style=" text-align: center;">
                      <span class="badge badge-warning" style="font-size: 14px; border: 0px;">Chờ xác nhận</span>
                    </td>
                    @elseif( $datum-> status == 1)
                    <td style=" text-align: center;">
                      <span class="badge badge-primary" style="font-size: 14px;">Đã xác nhận</span>
                      @if($datum->checkInOut == 0)
                      <span class="badge badge" style="font-size: 12px;">(Chưa check in)</span>
                      @else
                      <span class="badge badge" style="font-size: 12px;">(Đã check in)</span>
                      @endif
                    </td>
                    @elseif( $datum-> status == 2)
                    <td style=" text-align: center;">
                      <span class="badge badge-success" style="font-size: 14px;">Hoàn thành</span>
                    </td>
                    @elseif( $datum-> status == 3)
                    <td style=" text-align: center;">
                      <span class="badge badge-danger" style="font-size: 14px;">Đã hủy</span>
                    </td>
                  @endif
                  <td class="project-actions text-center">
                    <a href="{{ route('customer.bill', ['id' => $datum->id]) }}" class="btn btn-primary btn-sm">
                      <i class="fas fa-eye"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @if($countBookings >= 10)
            <div class="card-footer clearfix" style="background-color: white; border-radius: 5px">
              {{$data->appends(request()->all())->links()}}
            </div>
            @endif
          </div>
          @else
          <div class="card-body" id="user-booked-list" style="display: none;">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <p>Bạn chưa có đơn đặt phong nào</p>
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
      <div class="row border-bot">
        <div class="col-md-12 ">
          <div class="card-body " style="cursor:pointer;" onclick="toggleCardBody({{Auth::user()->id}})">
            <div class="row ">
              <div class="col-md-1 text-center">
                <i class="fa-regular fa-heart" style="font-size: 16px"></i>
              </div>
              <div class="col-md-6">
                <h4 style="margin: 0px; font-size: 16px">Phòng yêu thích</h4>
              </div>
            </div>
          </div>
          <div class="card-body" id="{{Auth::user()->id}}" style="display: none;">
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 ">
          <div class="card-body " style="cursor:pointer;">
            <div class="row ">
              <div class="col-md-1 text-center">
                <i class="fa-solid fa-arrow-right-from-bracket" style="font-size: 16px"></i>
              </div>
              <div class="col-md-6">
                <a href=""><h4 style="margin: 0px; font-size: 16px">Đăng xuất</h4></a>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- <div class="row border-bot">
        <div class="col-lg-1 center">
          <div class="card" style="border: unset">
            <div class="card-body">
              <div class="row text-center">
                <div class="col-md-12">
                  <i class="fa-solid fa-user"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-11">
          <div class="card" style="border: unset">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <p style="">{{ Auth::user()->name }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row border-bot">
        <div class="col-lg-1 center">
          <div class="card" style="border: unset">
            <div class="card-body">
              <div class="row text-center">
                <div class="col-md-12">
                  <i class="fa-solid fa-user"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-11">
          <div class="card" style="border: unset">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <p style="">{{ Auth::user()->name }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
    </div>
  </section>
</body>
  @include('admin.customers.footer')
  <script>
  function validateInput(input) {
    var inputValue = input.value.trim();
    var inputId = input.id;
    var errorSpan = document.getElementById(inputId + "Error");

    switch (inputId) {
      case "name":
        if (inputValue === "") {
          errorSpan.textContent = "Vui lòng nhập họ và tên";
        } else {
          errorSpan.textContent = "";
        }
        break;
      case "birth":
        // Validate ngày sinh ở đây (nếu cần)
        // Ví dụ: Kiểm tra xem ngày sinh có hợp lệ không
        var currentDate = new Date().toISOString().split('T')[0];
        if (inputValue > currentDate) {
          errorSpan.textContent = "Ngày sinh không hợp lệ";
        } else {
          errorSpan.textContent = "";
        }
        break;
      case "phone":
        // Validate số điện thoại ở đây (nếu cần)
        // Ví dụ: Kiểm tra xem số điện thoại có đúng định dạng không
        var phoneRegex = /^[0-9]{10}$/; // Ví dụ định dạng: 10 chữ số
        if (!phoneRegex.test(inputValue)) {
          errorSpan.textContent = "Số điện thoại không hợp lệ";
        } else {
          errorSpan.textContent = "";
        }
        break;
      case "address":
        // Validate địa chỉ ở đây (nếu cần)
        // Ví dụ: Kiểm tra địa chỉ có ít nhất 5 ký tự
        if (inputValue.length < 5) {
          errorSpan.textContent = "Địa chỉ không hợp lệ";
        } else {
          errorSpan.textContent = "";
        }
        break;
      case "email":
        // Validate email ở đây (nếu cần)
        // Ví dụ: Kiểm tra định dạng email
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(inputValue)) {
          errorSpan.textContent = "Email không hợp lệ";
        } else {
          errorSpan.textContent = "";
        }
        break;
      default:
        break;
    }
  }

  function submitForm() {
    var errorSpans = document.querySelectorAll("span[id$='Error']");
    var hasError = false;

    errorSpans.forEach(function (span) {
      if (span.textContent !== "") {
        hasError = true;
      }
    });

    if (!hasError) {
      document.getElementById("userForm").submit();
    }
  }
</script>
  <script>
  document.getElementById('avartarFile').addEventListener('change', function (e) {
    var fileName = e.target.files[0].name;
    var label = document.querySelector('.custom-file-label');
    label.innerHTML = fileName;
  });

  function displayImage(input) {
    var selectedImage = document.getElementById('selectedImage');
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        selectedImage.style.display = 'block';
        selectedImage.src = e.target.result;
      };

      reader.readAsDataURL(input.files[0]);
    }
  }


  // Mở modal và hiển thị ảnh khi click vào ảnh
  function openModalAvartar() {
    var modal = document.getElementById('imageModal');
    var modalImg = document.getElementById('modalImage');
    var captionText = document.getElementById('caption');

    modal.style.display = 'block';
    if ("{{ Auth::user()->image }}" === null || "{{ Auth::user()->image }}" === "") {
      modalImg.src = '/template/admin/dist/img/avatar-default.jpg';
    } else {
      modalImg.src = '/uploads/{{ Auth::user()->image }}';
    }


    // Đóng modal khi người dùng click ra ngoài modal
    window.onclick = function (event) {
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    };

    // Đóng modal khi người dùng click vào nút đóng (x)
    var closeBtn = document.getElementsByClassName('close')[0];
    closeBtn.onclick = function () {
      modal.style.display = 'none';
    };
  }
  function openModal() {
    var modal = document.getElementById('imageModal');
    var modalImg = document.getElementById('modalImage');

    modal.style.display = 'block';
    modalImg.src = document.getElementById('selectedImage').src;

    // Đóng modal khi người dùng click ra ngoài modal
    window.onclick = function (event) {
      if (event.target == modal) {
        modal.style.display = 'none';
      }
    };

    // Đóng modal khi người dùng click vào nút đóng (x)
    var closeBtn = document.getElementsByClassName('close')[0];
    closeBtn.onclick = function () {
      modal.style.display = 'none';
    };
  }

  function toggleUserProfile() {
    var userProfile = document.getElementById('user-profile');

    if (userProfile.style.display === 'none') {
        userProfile.style.display = 'block';
    } else {
        userProfile.style.display = 'none';
    }
  }

  function toggleUserBookedList(){
    var userBookedList = document.getElementById('user-booked-list');
    if (userBookedList.style.display === 'none') {
      userBookedList.style.display = 'block';
    } else {
      userBookedList.style.display = 'none';
    }
  }
</script>
</html>