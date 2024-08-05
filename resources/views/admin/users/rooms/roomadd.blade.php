@extends('admin.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Thêm phòng</h1>
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

  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><a class="nav-link" style="padding: 0px; color: white;">Thêm Phòng</a></h3>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div>
                <form action="{{ route('addroom') }}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Tên phòng</label>
                        <input type="text" name="name" placeholder="Tên phòng" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Số phòng</label>
                        <input type="number" name="number" placeholder="Số phòng" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Diện tích</label>
                        <input type="number" name="surface" placeholder="Diện tích" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Sức chứa</label>
                        <input type="number" name="capacity" placeholder="Sức chứa" class="form-control">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Giá phòng</label>
                        <input type="text" name="price" placeholder="Giá phòng" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName">Số Giường</label>
                        <input type="number" name="bed" placeholder="Số Giường" class="form-control">
                      </div>
                    </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <label for="inputName">Tên Loại phòng</label>
                    <select id="inputStatus" class="form-control custom-select" name="kind_of_room">
                      <option selected disabled>Select one</option>
                      @foreach ($kindofroom as $kindofrooms)
                      <option value="{{$kindofrooms->id}}">{{$kindofrooms->kind_of_room}}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="inputName">Nội thất phòng</label>
                    <input type="text" name="room_amenity" placeholder="Máy lạnh, Nước đóng chai miễn phí,..."
                      class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="inputName">Tiện ích cho phòng tắm</label>
                    <input type="text" name="bathroom_amenity"
                      placeholder="Nước nóng, Phòng tắm riêng, Vòi tắm đứng, Máy sấy tóc,..." class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="inputName">Mô tả</label>
                    <input type="text" name="description"
                      placeholder="Phòng được trang bị đầy đủ tiện nghi hiện đại, bao gồm TV màn hình phẳng, tủ lạnh, máy sấy tóc,..."
                      class="form-control">
                  </div>
                  <input type="submit" value="Thêm" class="btn btn-success float-right">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@endsection