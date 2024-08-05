@extends('admin.main')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Thông tin tài khoản</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">User Profile</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
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
              <p class="text-bold text-center" style="font-size: 20px; margin-bottom: 0px">{{
                ucwords(strtolower(Auth::user()->name)) }}</p>
              @if( Auth::user()->role == 'admin')
              <p class="text-bold text-center" style="">({{ucfirst(Auth::user()->role)}})</p>
              @elseif (Auth::user()->role == 'manager')
              <p class="text-bold text-center" style="">(Quản Lý)</p>
              @elseif (Auth::user()->role == 'staff')
              <p class="text-bold text-center" style="">(Nhân viên Quán)</p>
              @elseif (Auth::user()->role == 'sale')
              <p class="text-bold text-center" style="">(Nhân viên Sale)</p>
              @endif
              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>số giờ đã làm/tháng</b> <a class="float-right">543</a>
                </li>
                <li class="list-group-item">
                  <b>Mức lương</b> <a class="float-right">25,000đ/H</a>
                </li>
                <li class="list-group-item">
                  <b>Ngày bắt vào làm</b> <a class="float-right">{{
                    \Carbon\Carbon::parse(Auth::user()->created_at)->format('d/m/Y') }}</a>
                </li>
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

          <!-- About Me Box -->

          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#infomation" data-toggle="tab">thông tin cá
                    nhân</a></li>
                <li class="nav-item"><a class="nav-link" href="#setting" data-toggle="tab">sửa thông tin</a></li>
                <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">đổi mật khẩu</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="infomation">
                  <form>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Họ và Tên</label>
                            <p style="font-size: 18px;">{{ Auth::user()->name }}</p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Quyền hạn</label>
                            <p style="font-size: 18px;">{{ Auth::user()->role }}</p>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Ngày sinh</label>
                            <p style="font-size: 18px;">{{ \Carbon\Carbon::parse(Auth::user()->birth)->format('d/m/Y')}}</p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Số Điện Thoại</label>
                            <p style="font-size: 18px;">{{ Auth::user()->phone }}</p>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Địa chỉ</label>
                            <p style="font-size: 18px;">{{ ucwords(strtolower(Auth::user()->address)) }}</p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Email</label>
                            <p style="font-size: 18px;">{{ Auth::user()->email }}</p>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Ngày vào làm</label>
                            <p style="font-size: 18px;">
                              {{\Carbon\Carbon::parse(Auth::user()->created_at)->format('d/m/Y') }}</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="setting">
                  <form action="{{ route('user.update', Auth::user()->id) }}" method="post" id="userForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="name">Họ và Tên</label>
                          <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" oninput="validateInput(this)">
                          <span id="nameError" style="color: red;"></span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="birth">Ngày Sinh</label>
                          <input type="date" class="form-control" id="birth" name="birth" value="{{ Auth::user()->birth }}" oninput="validateInput(this)">
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
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Submit</button>
                  </form>
                </div><!-- /.tab-pane -->
                <div class="tab-pane" id="password">
                  <div class="card-body">
                    <form action="{{ route('change-password', Auth::user()->id)  }}" method="post">
                      @csrf
                      <div class="form-group">
                        <label for="old_password">Mật khẩu cũ</label>
                        <input type="password" name="old_password" id="old_password" class="form-control">

                      </div>
                      <div class="form-group">
                        <label for="password">Mật khẩu mới</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <span><i>Mật khẩu mới phải chứa ít nhất 8 ký tự, bao gồm ít nhất một chữ hoa, một chữ thường và
                            một số</i< /span>
                      </div>

                      <div class="form-group">
                        <label for="password_confirmation">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                          class="form-control">
                      </div>
                      <button type="submit" class="btn btn-success toastrDefaultSuccess">
                        Đổi mật khẩu
                      </button>
                    </form>
                  </div>
                </div><!-- /.tab-pane -->
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
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
    modalImg.src = '/uploads/' + '{{ Auth::user()->image }}';

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
</script>
@endsection