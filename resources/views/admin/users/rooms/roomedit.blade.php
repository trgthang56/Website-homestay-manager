@extends('admin.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Sửa {{$data->name}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Room Add</li>
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
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><a class="nav-link" style="padding: 0px; color: white;">Sửa {{$data->name}}</a></h3>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div>
                <form action="{{ route('room.edit') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Tên phòng</label>
                        <input type="text" name="name" value="{{$data->name}}" placeholder="Tên phòng" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Số phòng</label>
                        <input type="number" name="number" value="{{$data->number}}" placeholder="Số phòng" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Diện tích</label>
                        <input type="number" name="surface" value="{{$data->surface}}" placeholder="Diện tích" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Sức chứa</label>
                        <input type="number" name="capacity" value="{{$data->capacity}}" placeholder="Sức chứa" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Số Giường</label>
                        <input type="number" name="bed" value="{{$data->bed}}" placeholder="Số Giường" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Giá phòng</label>
                        <input type="text" name="price" value="{{$data->price}}" placeholder="Giá phòng" class="form-control">
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="id" placeholder="Tên Loại phòng" class="form-control" value="{{$data->id}}">
                  <div class="form-group">
                    <label for="inputName">Loại phòng</label>
                    <select id="inputStatus" class="form-control custom-select" name="kind_of_room">
                      @foreach ($kindofrooms as $kindofroom)
                        @if($kindofroom->id == $data->id_kind_of_room)
                          <option selected value="{{$kindofroom->id}}">{{$kindofroom->kind_of_room}}</option>
                        @else
                          <option value="{{$kindofroom->id}}">{{$kindofroom->kind_of_room}}</option>
                        @endif
                      @endforeach
                    </select>
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
                    <label for="inputName">Nội thất phòng</label>
                    <input type="text" name="room_amenity" value="{{$data->room_amenity}}" placeholder="Máy lạnh, Nước đóng chai miễn phí,..."
                      class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="inputName">Tiện ích cho phòng tắm</label>
                    <input type="text" name="bathroom_amenity" value="{{$data->bathroom_amenity}}" placeholder="Nước nóng, Phòng tắm riêng, Vòi tắm đứng, Máy sấy tóc,..." class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="inputName">Mô tả</label>
                    <input type="text" name="description" value="{{$data->description}}" placeholder="Phòng được trang bị đầy đủ tiện nghi hiện đại, bao gồm TV màn hình phẳng, tủ lạnh, máy sấy tóc,..." class="form-control">
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
</div>



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