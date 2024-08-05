@extends('admin.main')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Giỏ hàng</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin">Home</a></li>
            <li class="breadcrumb-item active">Cart</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    @if(optional($cart)->count() > 0)
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6" style="padding: 0 7.5px 0 0;">
            <label>Ngày</label>
            <label class="float-right">{{$numberOfDays}} Đêm</label>
            <div class="row">
              <div class="col-sm-6">
                <div class="field">
                  <input type="text" id="min_budget" name="min_budget" class="input-min"
                    value="{{ \Carbon\Carbon::parse($checkin)->locale('vi')->isoFormat('dddd, DD [Tháng] M') }}"
                    readonly style="height: calc(1.5em + 10px);">
                </div>
              </div>
              <div class="col-sm-6 float-right">
                <div class="field">
                  <input type="text" id="min_budget" name="min_budget" class="input-min"
                    value="{{ \Carbon\Carbon::parse($checkout)->locale('vi')->isoFormat('dddd, DD [Tháng] M') }}"
                    readonly style="height: calc(1.5em + 10px);">
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <label for="max_budget" style="padding: 0 7.5px 0 0;">Số khách</label>
            <div class="row">
              <div class="col-sm-9">
                <div class="field">
                  <input type="text" id="max_budget" name="max_budget" class="input-max"
                    value="{{$numberOfRooms}} Phòng: {{$guests}} Người lớn, {{$babies}} Trẻ/Phòng{{ $babies_age_txt}}"
                    readonly style="height: calc(1.5em + 10px);">
                </div>
              </div>
              <div class="col-sm-3 d-flex justify-content-center">
                <div class="field">
                  <form method="get" action="{{ route('search') }}">
                    @csrf
                    <button type="submit" class="btn float-center custom-btn"
                      style="font-size: 16px;border: -27px;">Chỉnh sửa <i class="fa-solid fa-rotate"></i></button>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th style="width: 7%">
                mã phòng </th>
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
                giá phòng
              </th>

              <th style="width: 11%;">
              </th>
            </tr>
          </thead>
          <tbody>
            @foreach($cart as $datum)
            <tr>
              <td>{{ $datum->idRoom}}</td>
              <td>{{ $datum->name}}</td>

              <td style="text-align:center">{{ $datum->number }}</td>
              <td style="text-align:center">{{ $datum->surface }} m<sup style="size: 100%;">2</sup></td>
              <td style="text-align:center">{{ $datum->capacity }} Người</td>
              <td style="text-align:center">{{ $datum->bed }} Chiếc</td>
              <td style="text-align:center">{{ $datum->kind_of_room }}</td>
              <td style="text-align:center">{{ $datum->price }}</td>

              <td style="text-align:center">{{ $datum->submitTime }}</td>



            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md-6">
            <form method="POST" action="{{ route('cart.cancel') }}">
              @csrf
              <button type="submit" class="btn btn-danger float-left" style="font-size: 16px; border: 0px;"> <i
                  class="fa-solid fa-trash-can"></i> Xóa toàn
                bộ </button>
            </form>
          </div>
          <div class="col-md-6">
            <form method="get" action="{{ route('cart.detail') }}">
              @csrf
              <button type="submit" class="btn btn-success float-right" style="font-size: 16px; border: 0px;">Trang
                thanh toán <i class="fa-solid fa-arrow-right"></i></button>
            </form>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    @else
    <div class="card">
      <div class="card-body">
        <div class="row justify-content-center align-items-center" style="min-height: 400px;">
          <h2>Chưa có phòng trong giỏ hàng</h2>
        </div>
      </div>
    </div>
    @endif

  </section>
  <!-- /.content -->
</div>

<script>
  function confirmDelete(id) {
    if (confirm("Bạn có chắc chắn muốn xóa không?")) {
      document.getElementById('deleteForm_' + id).submit();
    }
  }
  function confirmAccept(id) {
    if (confirm("Bạn có chắc chắn muốn xác nhận phòng không?")) {
      document.getElementById('acceptForm_' + id).submit();
    }
  }
  function confirmComplete(id) {
    if (confirm("Bạn có chắc chắn muốn hoàn thành đơn và trả phòng không?")) {
      document.getElementById('completeForm_' + id).submit();
    }
  }
</script>

@endsection