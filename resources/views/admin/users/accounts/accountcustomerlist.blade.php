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
    <div class="card">
      <div class="card-header">
        <div class="card-tools">
          <form action="" class="form-inline">
            <div class="input-group input-group-lg">
              <input class="form-control form-control-lg" name="keyword" placeholder="Tên hoặc số điện thoại">
              <div class="input-group-append">
                <button type="submit" class="btn btn-lg btn-default">
                  <i class="fa fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th style="width: 15%">
                Họ và Tên
              </th>
              <th style="width: 10%">
                Ngày sinh
              </th>
              <th style="width: 10%">
                Số điện thoại
              </th>
              <th style="width: 20%">
                Địa chỉ
              </th>
              <th style="width: 20%">
                Email
              </th>
              <th style="width: 6%">
                Quyền hạn
              </th>
              <th style="width: 9%"></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
            <tr>
              <td>{{ $user->name }}</td>
              @if($user->birth!=null)
                <td>{{ $user->birth ? \Carbon\Carbon::parse($user->birth)->format('d/m/Y') : '' }}</td>
              @else
                <td>Trống</td>
              @endif
              @if($user->phone != null)
                <td class="{{ $user->phone == session('duplicate_phone') ? 'text-danger' : '' }}">{{ $user->phone }}</td>
              @else
                <td>Trống</td>
              @endif
              @if($user->address != null)
                <td>{{ $user->address }}</td>
              @else
                <td>Trống</td>
              @endif
              <td class="{{ $user->email == session('duplicate_email') ? 'text-danger' : '' }}">{{ $user->email }}</td>
              <td>{{ $user->role }}</td>
              <td class="project-actions text-right">
                <a class="btn btn-primary btn-sm" style="padding: 0 0 0 0;">
                  <form action="account/detail/{{ $user->id }}" method="get" class="active">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                      <i class="fas fa-eye"></i>
                    </button>
                  </form>
                </a>
                <a class="btn btn-info btn-sm" style="padding: 0 0 0 0;">
                  <form action="account/edit/{{ $user->id }}" method="get" class="active">
                    <button type="submit" class="btn btn-info btn-sm">
                      <i class="fas fa-pencil-alt"></i>
                    </button>
                  </form>
                </a>
                <a class="btn btn-danger btn-sm" style="padding: 0 0 0 0;">
                  <form action="delete/{{ $user->id }}" method="post" class="active">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="fas fa-trash">
                      </i>
                    </button>
                  </form>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        </br>
        <div class="card-footer clearfix" style="background-color: white; border-radius: 5px">
          {{$users->appends(request()->all())->links()}}
        </div>

      </div> <!-- /.card-body -->
    </div>
    <!-- /.card -->
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
        if (inputValue === "") {
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