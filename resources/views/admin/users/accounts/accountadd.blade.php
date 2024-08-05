@extends('admin.main')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Danh Sách nhân viên</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">User List</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    @if(auth()->user()->role=='admin'|| auth()->user()->role=='manager')
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><a class="nav-link" href="#setting" data-toggle="tab"
                style="padding: 0px; color: white;">Thêm Nhân Viên</a></h3>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane" id="setting">
                <form id="addUserForm" action="{{ route('add') }}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="name">Họ và Tên</label>
                        <input type="text" class="form-control" id="name" name="name" "
                          oninput=" validateInput(this)">
                        <span id="nameError" style="color: red;"></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="birth">Ngày Sinh</label>
                        <input type="date" class="form-control" id="birth" name="birth" oninput="validateInput(this)">
                        <span id="birthError" style="color: red;"></span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" oninput="validateInput(this)">
                        <span id="phoneError" style="color: red;"></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input type="text" class="form-control" id="address" name="address"
                          oninput="validateInput(this)">
                        <span id="addressError" style="color: red;"></span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="email">Địa chỉ Email</label>
                        <input type="email" class="form-control" id="email" name="email" oninput="validateInput(this)">
                        <span id="emailError" style="color: red;"></span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="inputStatus">Quyền hạn</label>
                        <select id="inputStatus" class="form-control custom-select" name="role">
                          <option selected disabled>Select one</option>
                          <option value="admin">admin</option>
                          <option value="manager">manager</option>
                          <option value="staff">staff</option>
                          <option value="sale">sale</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn btn-primary" onclick="submitForm()">Submit</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
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
      document.getElementById("addUserForm").submit();
    }
  }
</script>
@endsection