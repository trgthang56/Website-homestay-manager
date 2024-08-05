@extends('admin.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Danh sách dịch vụ</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Service</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="card">

      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th style="width: 13%">
                Dịch Vụ
              </th>
              <th style="width: 10%; text-align:center">
                Trạng Thái
              </th>
              <th style="width: 7% ;text-align:right">
                Giá (VNĐ)
              </th>
              <th style="width: 10%; text-align:center">
                Đơn vị tính (lần/giờ)
              </th>
              <th style="width: 25%">
                Mô Tả
              </th>

              <th style="width: 10%;">
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $datum)
            <tr>
              <td>{{ $datum->name }}</td>
              @if( $datum-> status === 'Tạm dừng')
              <td style="text-align:center"><span class="badge badge-danger">{{ $datum-> status}}</span></td>
              @else
              <td style="text-align:center"><span class="badge badge-success">{{ $datum-> status}}</span></td>
              @endif

              <td style="text-align:right">{{ number_format($datum->price, 0, ',', '.') }}</td>
              <td style="text-align:center">{{ $datum->unit}}</td>

              <td>{{ $datum->description }}</td>

              @if(auth()->user()->role=='admin'|| auth()->user()->role=='manager' || auth()->user()->role=='sale' )
              <td class="project-actions text-right">
                <button type="submit" class="btn btn-primary btn-sm">
                  <i class="fas fa-eye">
                  </i>
                  feedbacks
                </button>
                <a class="btn btn-sm" style="padding: 0 0 0 0;">
                  <form action="service/edit/{{ $datum->id }}" method="post" class="active">
                    @csrf
                    <button type="submit" class="btn btn-info btn-sm">
                      <i class="fas fa-pencil-alt">
                      </i>
                    </button>
                  </form>
                </a>
                <a class="btn btn-sm" style="padding: 0 0 0 0;">
                  <form action="deleteservice/{{ $datum->id }}" method="post" class="active">
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
                  feedbacks
                </button>
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="card-footer clearfix" style="background-color: white; border-radius: 5px">
          {{$data->appends(request()->all())->links()}}
        </div>
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
              <a href="#" id="addLink" style="padding: 0px; color: white;">Thêm dịch vụ</a>
            </h3>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane" id="setting">
                <form action="{{ route('addservice') }}" method="POST">
                  @csrf
                  <div class="form-group">
                    <label for="inputName">Dịch vụ</label>
                    <input type="text" name="name" placeholder="Tên dịch vụ" class="form-control">
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="inputStatus">Trạng thái</label>
                      <select id="inputStatus" class="form-control custom-select" name="status">
                        <option selected disabled>Select one</option>
                        <option value="Hoạt động">Hoạt động</option>
                        <option value="Tạm dừng">Tạm dừng</option>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputStatus">Hình thức</label>
                      <select id="inputStatus" class="form-control custom-select" name="type">
                        <option selected disabled>Select one</option>
                        <option value="Đặt trước">Đặt trước </option>
                        <option value="Khi dùng">Khi dùng</option>
                        <option value="Đặt trước + Khi dùng">Đặt trước + Khi dùng</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label for="inputName">Giá dịch vụ</label>
                      <input type="number" name="price" placeholder="Giá dịch vụ" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="inputUnit">Đơn vị tính</label>
                      <select id="inputUnit" class="form-control custom-select" name="unit">
                        <option selected disabled>Select one</option>
                        <option value="Lần">Lần</option>
                        <option value="Giờ">Giờ</option>
                      </select>
                    </div>
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
@endsection