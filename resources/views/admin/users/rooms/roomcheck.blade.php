@extends('admin.main')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Danh sách phòng</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Room</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><a class="nav-link" style="padding: 0px; color: white;">Tìm kiếm</a></h3>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="active tab-pane">
                <form action="{{ route('checkroom') }}" method="get">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="checkin">Check-in:</label>
                        <input type="date" id="checkin" name="checkin" class="form-control" value="{{$checkin}}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="checkout">Check-out:</label>
                        <input type="date" id="checkout" name="checkout" class="form-control" value="{{$checkout}}">
                      </div>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary float-right">Tìm kiếm</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Default box -->
    <div class="card">
      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th style="width: 7%">
                Tên phòng
              </th>
              <th style="width: 7%; text-align:center">
                Số Phòng
              </th>
              <th style="width: 7%; text-align:center">
                Diện tích
              </th>
              <th style="width: 7%; text-align:center">
                Sức chứa
              </th>
              <th style="width: 7%; text-align:center">
                Giường
              </th>
              <th style="width: 7% ; text-align:center">
                Loại phòng
              </th>
              <th style="width: 7%; text-align:center">
                Trạng thái
              </th>
              <th style="width: 7%; text-align:center">
                giá phòng
              </th>
              <th style="width: 11%">
                Mô tả chung
              </th>
              <th style="width: 11%;">
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $datum)
            <tr>
              <td>{{ $datum->name }}</td>
              <td style="text-align:center">{{ $datum->number }}</td>
              <td style="text-align:center">{{ $datum->surface }} m<sup style="size: 100%;">2</sup></td>
              <td style="text-align:center">{{ $datum->capacity }} Người</td>
              <td style="text-align:center">{{ $datum->bed }} Chiếc</td>
              <td style="text-align:center">{{ $datum->outputkindofroom->kind_of_room }}</td>
              @if( $datum-> status === 'Đang thuê')
              <td style="text-align:center"><span class="badge badge-danger" style="font-size: 14px;">{{ $datum->
                  status}}</span></td>
              @elseif( $datum-> status === 'Chờ xác nhận' || $datum-> status === 'Đang bận ...' || $datum-> status ===
              'Đã đặt')
              <td style="text-align:center"><span class="badge badge-warning" style="font-size: 14px;">{{ $datum->
                  status}}</span></td>
              @else
              <td style="text-align:center"><span class="badge badge-success" style="font-size: 14px;">{{ $datum->
                  status}}</span></td>
              @endif
              <td style="text-align:center">{{ $datum->price }} VNĐ</td>
              <td class="shorten" data-expanded="false">{{ $datum->description }}</td>
              @if(auth()->user()->role=='admin'|| auth()->user()->role=='manager')
              <td class="project-actions text-right">
                <a class="btn btn-primary btn-sm" style="padding: 0 0 0 0;">
                  <form action="" method="get" class="active">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                      <i class="fas fa-eye"></i>
                    </button>
                  </form>
                </a>
                <a class="btn btn-info btn-sm" style="padding: 0 0 0 0;">
                  <button type="submit" class="btn btn-info btn-sm">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                </a>
                <a class="btn btn-danger btn-sm" style="padding: 0 0 0 0;">
                  <form action="deleteroom/{{ $datum->id }}" method="post" class="active">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </a>
              </td>
              @elseif(auth()->user()->role=='staff')
              <td class="project-actions text-center">
                <a class="btn btn-primary btn-sm" style="padding: 0 0 0 0;">
                  <form action="bookingdetail/{{ $datum->id }}" method="get" class="active">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                      <i class="fas fa-eye"></i>
                    </button>
                  </form>
                </a>
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
    <div class="row">
      <div class="col-12" style="display: none;">
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const shortenElements = document.querySelectorAll('.shorten');
    shortenElements.forEach(element => {
      const content = element.innerHTML;
      const maxLength = 50; // Độ dài tối đa của nội dung
      if (content.length > maxLength) {
        const shortText = content.substr(0, maxLength) + '...';
        element.innerHTML = shortText;
        element.innerHTML += `<span class="toggle-text" onclick="toggleText(this)"> Xem thêm</span>`;
        element.setAttribute('data-full-text', content);
        element.setAttribute('data-status', 'collapsed');
      }
    });
  });

  function toggleText(element) {
    const parentElement = element.parentElement;
    const status = parentElement.getAttribute('data-status');
    const fullText = parentElement.getAttribute('data-full-text');
    if (status === 'collapsed') {
      parentElement.innerHTML = fullText;
      parentElement.innerHTML += `<span class="toggle-text" onclick="toggleText(this)"> Thu gọn</span>`;
      parentElement.setAttribute('data-status', 'expanded');
    } else {
      const maxLength = 50; // Độ dài tối đa của nội dung
      const shortText = fullText.substr(0, maxLength) + '...';
      parentElement.innerHTML = shortText;
      parentElement.innerHTML += `<span class="toggle-text" onclick="toggleText(this)"> Xem thêm</span>`;
      parentElement.setAttribute('data-status', 'collapsed');
    }
  }

</script>
@endsection