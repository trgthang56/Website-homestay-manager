@extends('admin.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Danh sách loại phòng</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Kind of Room</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- Modal -->
  <div id="imageModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
    <div id="caption"></div>
  </div>
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <div class="card-tools">
          <form action="" class="form-inline">
            <div class="input-group input-group-lg">
              <input class="form-control form-control-lg" name="keyword" placeholder="Nhập thông tin cần tìm">
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
              <th style="width: 10%; border-right: 1px solid #dee2e6">
                Tên Loại Phòng
              </th>
              <th style="width: 10%; text-align:right; border-right: 1px solid #dee2e6">
                Phòng Trống
              </th>
              <th style="width: 10%; text-align:right; border-right: 1px solid #dee2e6">
                Tổng Số Phòng
              </th>
              <th style="width: 20%; text-align:center; border-right: 1px solid #dee2e6;">
                Hình ảnh
              </th>
              <th style="width: 35%; border-right: 1px solid #dee2e6">
                Mô Tả
              </th>
              <th style="width: 15%; text-align:right; border-right: 1px solid #dee2e6">
                Chức năng
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $datum)
            <tr>
              <td style=" border-right: 1px solid #dee2e6">{{ $datum->kind_of_room }}</td>
              <td style="text-align:right; border-right: 1px solid #dee2e6 ">{{ $datum-> available }}</td>
              <td style="text-align:right; border-right: 1px solid #dee2e6">{{ $datum->total }}</td>
              @if($datum->image != null)
              <td style="text-align:center; border-right: 1px solid #dee2e6; max-width: 100%; max-height: auto"><img
                  src="{{ asset('kind_of_rooms/'.$datum->image) }}" alt="Tên ảnh">
              </td>
              @else
              <td style="text-align:center; border-right: 1px solid #dee2e6">chưa có ảnh</td>
              @endif
              <td style=" border-right: 1px solid #dee2e6">{{ $datum->description }}</td>
              @if(auth()->user()->role=='admin'|| auth()->user()->role=='manager')
              <td class="project-actions text-right">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="fas fa-eye">
                  </i>
                </button>
                <a class="btn btn-info btn-sm" style="padding: unset;">
                  <form action="kindofroom/edit/{{$datum->id}}" method="get" class="active">
                    <button type="submit" class="btn btn-info btn-sm">
                      <i class="fas fa-pencil-alt"></i>
                    </button>
                  </form>
                </a>
                <a class="btn btn-danger btn-sm" style="padding: 0 0 0 0;">
                  <form action="deletekindofroom/{{ $datum->id }}" method="post" class="active">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="fas fa-trash">
                      </i>
                    </button>
                  </form>
                </a>
              </td>
              @elseif(auth()->user()->role=='staff')
              <td class="project-actions text-center">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="fas fa-eye">
                  </i>
                </button>
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
    @if (auth()->user()->role=='admin'|| auth()->user()->role=='manager')
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              <a href="#" id="addLink" style="padding: 0px; color: white;">Thêm loại Phòng</a>
            </h3>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div id="setting" style="display: none;"> <!-- Sử dụng inline style để ẩn ban đầu -->
                <form action="{{ route('addkindofroom') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                    <label for="inputName">Tên Loại phòng</label>
                    <input type="text" name="name" placeholder="Tên Loại phòng" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="roomImage">Ảnh (640 x 640px)</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="roomImage" name="roomImage"
                          onchange="displayImage(this)">
                        <label class="custom-file-label" for="roomImage">Chọn file ảnh</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <img id="selectedImage" class="img-fluid" style="display: none;" alt="Selected Image"
                      onclick="openModal()">
                  </div>
                  <div class="form-group">
                    <label for="inputName">Mô tả</label>
                    <input type="text" name="description" placeholder="Mô tả" class="form-control">
                  </div>
                  <input type="submit" value="Thêm" class="btn btn-success float-right">
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
  document.addEventListener('DOMContentLoaded', function () {
    const addLink = document.getElementById('addLink');
    const settingTab = document.getElementById('setting');

    addLink.addEventListener('click', function (event) {
      event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết

      if (settingTab.style.display === 'none') {
        settingTab.style.display = 'block'; // Hiển thị form khi click
      } else {
        settingTab.style.display = 'none'; // Ẩn form nếu đã hiển thị
      }
    });
  });
</script>
<script>
  document.getElementById('roomImage').addEventListener('change', function (e) {
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