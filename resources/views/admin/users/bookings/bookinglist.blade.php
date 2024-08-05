@extends('admin.main')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{$title}}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">booking list</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    @if($countData !=0)
    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <div class="card-tools">
          <form action="" class="form-inline">
            <div class="input-group input-group-lg">
              @if(request()->has('keyword'))
                <input class="form-control form-control-lg" name="keyword"placeholder="{{request()->keyword}}">
              @else
                <input class="form-control form-control-lg" name="keyword"placeholder="Nhập thông tin cần tìm">
              @endif
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
              <th style="width: 5%">
                Mã Đơn
              </th>
              <th style="width: 6%">
                Số điện thoại
              </th>
              <th style="width: 6%">
                Nhân viên Xác nhận
              </th>
              <!-- <th style="width: 3%">
                Người đặt phòng
              </th> -->
              <th style="width: 6%">
                Ngày đặt
              </th>
              <th style="width: 6%">
                Đặt cho người khác
              </th>
              <th style="width: 9%">
                Người nhận phòng
              </th>
              <th style="width: 6%">
                Số khách
              </th>
              <th style="width: 6%">
                Số phòng đặt
              </th>
              <th style="width: 6%">
                Tổng chi phí
              </th>
              <th style="width: 6%">
                Ngày nhận phòng
              </th>
              <th style="width: 6%">
                Ngày trả phòng
              </th>
              <th style="width: 6%; text-align: center;">
                Trạng thái
              </th>
              <th style="width: 9%">
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($data as $datum)
            <tr
              class="{{($datum->status == 1 && \Carbon\Carbon::parse($datum->check_out_date)->isBefore(\Carbon\Carbon::now())) ? 'text-danger' : '' }}">
              <td>{{ $datum->id}}</td>
              <td>{{ $datum->phone}}</td>
              <td>{{ $datum->id_user }}</td>
              <!-- <td>{{ $datum->id_customer }}</td> -->
              <td>{{ date('H:i:s - d/m/Y', strtotime($datum->created_at)) }}</td>
              @if($datum->set_for_other== 0)
              <td><span class="badge badge-danger" style="font-size: 14px;"> Không </span>
              </td>
              @else
              <td><span class="badge badge-success" style="font-size: 14px;"> Có </span></td>
              @endif
              <td>{{ $datum->name }}</td>
              <td>{{ $datum->number_of_guest }} Người</td>
              <td>{{ $datum->number_of_room }} Phòng</td>
              <td>{{ number_format($datum->total, 0, ',', '.') }} VNĐ</td>
              <td>
                {{ date('H:i:s - d/m/Y', strtotime($datum->check_in_date)) }}
              </td>
              <td>
                {{ date('H:i:s - d/m/Y', strtotime($datum->check_out_date)) }}
              </td>
              @if(auth()->user()->role=='admin'|| auth()->user()->role=='manager')
              @if( $datum-> status == 0)
              <td class="status" style=" text-align: center;">
                <form id="acceptForm_{{ $datum->id }}" method="POST" action="{{route('booking.accept')}}">
                  @csrf
                  <input type="hidden" name="id_room" value="{{$datum->id_room}}">
                  <input type="hidden" name="id" value="{{$datum->id}}">
                  <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
                  <button type="button" class="badge badge-warning" onclick="confirmAccept({{ $datum->id }})"
                    style="font-size: 14px; border: 0px;">Chờ xác nhận</button>
                </form>
              </td>
              @elseif( $datum-> status == 1)
              <td class="status" style=" text-align: center;">
                <button type="button" class="badge badge-primary" style="font-size: 14px; border: 0px;">Đã xác nhận</button>
                @if($datum->checkInOut == 0)
                <span class="badge badge" style="font-size: 12px;">(Chưa check in)</span>
                @else
                <span class="badge badge" style="font-size: 12px;">(Đã check in)</span>
                @endif
              </td>
              @elseif( $datum-> status == 2)
              <td class="status" style=" text-align: center;">
                <span class="badge badge-success" style="font-size: 14px;">Hoàn thành</span>
              </td>
              @elseif( $datum-> status == 3)
              <td class="status" style=" text-align: center;">
                <span class="badge badge-danger" style="font-size: 14px;">Đã hủy</span>
              </td>
              @endif
              @elseif(auth()->user()->role=='staff')
              @if( $datum-> status == 0)
              <td style=" text-align: center;">
                <span class="badge badge-warning" style="font-size: 14px; border: 0px;">Chờ xác nhận</span>
              </td>
              @elseif( $datum-> status == 1)
              <td style=" text-align: center;">
                <span class="badge badge-primary" style="font-size: 14px;">Đã xác nhận</span>
                @if($datum->checkInOut == 0)
                <span class="badge badge" style="font-size: 12px;">(Chưa check in)</span>
                @else
                <span class="badge badge" style="font-size: 12px;">(Đã check in)</span>
                @endif
              </td>
              @elseif( $datum-> status == 2)
              <td style=" text-align: center;">
                <span class="badge badge-success" style="font-size: 14px;">Hoàn thành</span>
              </td>
              @elseif( $datum-> status == 3)
              <td style=" text-align: center;">
                <span class="badge badge-danger" style="font-size: 14px;">Đã hủy</span>
              </td>
              @endif
              @endif
              @if(auth()->user()->role=='admin'|| auth()->user()->role=='manager')
              <td class="project-actions text-right">
                <a href="{{ route('view.booking', ['id' => $datum->id]) }}" class="btn btn-primary btn-sm">
                  <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('edit.booking', ['id' => $datum->id]) }}" class="btn btn-info btn-sm">
                  <i class="fas fa-pencil-alt">
                  </i>
                </a>
                <a class="btn btn-danger btn-sm" style="padding: 0;">
                  <form id="cancelForm_{{ $datum->id }}" action="{{ route('cancel.booking', ['id' => $datum->id]) }}"
                    method="post">
                    @csrf
                    <button type="button" onclick="confirmCancel({{ $datum->id }})" class="btn btn-danger btn-sm">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </a>
              </td>
              @elseif(auth()->user()->role=='staff')
              <td class="project-actions text-center">
                <a href="{{ route('view.booking', ['id' => $datum->id]) }}" class="btn btn-primary btn-sm">
                  <i class="fas fa-eye"></i>
                </a>
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix" style="background-color: white; border-radius: 5px">
        {{$data->appends(request()->all())->links()}}
      </div>

      @else
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
        <div class="card-body">
          <div class="row justify-content-center align-items-center" style="min-height: 400px;">
            @if(request()->has('keyword'))
              <h2>Thông tin tìm kiếm không tồn tại</h2>
            @else
              <h2>hiện tại không có đơn mới</h2>
            @endif
          </div>
        </div>
      </div>
      @endif
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
  function confirmCancel(id) {
    if (confirm("Bạn có chắc chắn muốn hủy đơn không?")) {
      document.getElementById('cancelForm_' + id).submit();
    }
  }
  function confirmAccept(id) {
    if (confirm("Bạn có chắc chắn muốn xác nhận phòng không?")==true) {
      document.getElementById('acceptForm_' + id).submit();
    }
  }
  function confirmComplete(id) {
    if (confirm("Bạn có chắc chắn muốn hoàn thành đơn và trả phòng không?")==true) {
      document.getElementById('completeForm_' + id).submit();
    }
  }
</script>

@endsection