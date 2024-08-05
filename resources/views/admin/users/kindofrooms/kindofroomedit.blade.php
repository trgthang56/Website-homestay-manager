@extends('admin.main')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{$data->kind_of_room}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit {{$data->kind_of_room}}</li>
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
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">
              <a href="#" id="addLink" style="padding: 0px; color: white;">Sửa {{$data->kind_of_room}}</a>
            </h3>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div id="setting" style=""> <!-- Sử dụng inline style để ẩn ban đầu -->
                <form action="{{ route('kindofroom.edit') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                    <input type="hidden" name="id" placeholder="Tên Loại phòng" class="form-control" value="{{$data->id}}">
                  </div>
                  <div class="form-group">
                    <label for="inputName">Tên Loại phòng</label>
                    <input type="text" name="name" placeholder="Tên Loại phòng" class="form-control" value="{{$data->kind_of_room}}">
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
                    <input type="text" name="description" placeholder="Mô tả" class="form-control" value="{{$data->description}}">
                  </div>
                  <input type="submit" value="sửa" class="btn btn-success float-right">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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