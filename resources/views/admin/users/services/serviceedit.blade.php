@extends('admin.main')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{$data->name}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Edit </li>
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
              <a href="#" id="addLink" style="padding: 0px; color: white;">{{$data->name}}</a>
            </h3> 
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div>
                <form action="{{ route('service.edit') }}" method="POST">
                  @csrf
                  <input type="hidden" value="{{$data->id}}" name="id">
                  <div class="form-group">
                    <label for="inputName">Dịch vụ</label>
                    <input type="text" value="{{$data->name}}" name="name" placeholder="Tên dịch vụ" class="form-control">
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="inputStatus">Trạng thái</label>
                      <select id="inputStatus" class="form-control custom-select" name="status">
                        <option selected value="{{$data->status}}">{{$data->status}}</option>
                        @if($data->status == "Hoạt động")
                          <option value="Tạm dừng">Tạm dừng</option>
                        @else
                          <option value="Hoạt động">Hoạt động</option>
                        @endif
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputStatus">Hình thức</label>
                      <select id="inputStatus" class="form-control custom-select" name="type">
                        <option value="Đặt trước">Đặt trước </option>
                        <option value="Khi dùng">Khi dùng</option>
                        <option value="Đặt trước + Khi dùng">Đặt trước + Khi dùng</option>
                      </select>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="inputName">Giá dịch vụ</label>                      <label class="float-right">VNĐ</label>
                      <input type="number" value="{{$data->price}}"  name="price" placeholder="Giá dịch vụ" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputUnit">Đơn vị tính</label>
                      <select id="inputUnit" class="form-control custom-select" name="unit">
                      <option selected value="{{$data->unit}}">{{$data->unit}}</option>
                      @if($data->unit == "Lần")
                        <option value="Giờ">Giờ</option>
                      @else
                        <option value="Lần">Lần</option>
                      @endif
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputName">Mô tả</label>
                    <input type="text" value="{{$data->description}}" name="description" placeholder="Mô tả" class="form-control">
                  </div>
                  <input type="submit" value="Sửa" class="btn btn-success float-right">
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