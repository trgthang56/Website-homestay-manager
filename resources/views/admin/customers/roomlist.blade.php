<!DOCTYPE html>
<html lang="en">

<head>
  @include('admin.customers.head')
</head>
<style>
  .icon-button {
    border: none;
    background-color: transparent; 
    padding: 0; 
}

.icon-button i {
    font-size: 18px; 
    color: #000;
}
.row .col-lg-5 a {
    text-decoration: none;
    color: inherit;
}

.row .col-lg-5 a:hover {
    text-decoration: none;
    color: inherit;
}
</style>
<body>
  <!-- Breadcrumb Section Begin -->
  <div class="breadcrumb-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-text">
            <h2>{{$kor->kind_of_room}}</h2>
            <div class="bt-option">
              <a href="/homepage">Trang chủ</a>
              <span>Hạng phòng</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumb Section End -->

  <!-- Rooms Section Begin -->
  <section class="rooms-section spad">
    <div class="container">
      @if($rooms->isEmpty())
      <div class="card-body">
        <div class="row">
          <div class="col-md-12 d-flex justify-content-center" style="margin: 50px 0px">
            <h4>
              Hạng phòng này của chúng tôi đang trống đang trống.
            </h4>
          </div>
        </div>
      </div>
      @else
      @foreach($rooms as $data)
      @if($data->status =="Trống")
      <div class="card-body" style="margin: 15px 0px; border: 1px solid rgba(0, 0, 0, .125); box-shadow: 2px 6px 8px rgba(0, 0, 0, 0.1);">
        <div class="row">
          <div class="col-md-3 d-flex justify-content-center">
            @if($data->image!=null)
              <img src="{{asset('rooms/'.$data->image)}}" alt="Room Image" style="height: 175px;">
            @else
              <img src="/template/customer/img/room/house.png" alt="Room Image" style="height: 175px;">
            @endif
          </div>
          <div class="col-md-9" style="font-size: 18px;">
            <div class="row">
              <div class="col-lg-5">
                <div class="form-group">
                  <p>{{$data->name}}</p>
                </div>
              </div>
              <div class="col-lg-5">
                <div class="form-group">
                  <p>Phòng:&#160 {{$data->number}}</p>
                </div>
              </div>
              @foreach($cart as $item)  
              @if($item->idRoom == $data->id)
              <div class="col-lg-2">
                <div class="form-group float-right">
                  <span class="badge" style="background-color: #44b144; color: white; padding: 9px" title="Đã có trong giỏ"><i class="fa-solid fa-cart-arrow-down"></i></span>
                </div>
              </div>
              @endif
              @endforeach
            </div>
            <div class="row">
              <div class="col-lg-5">
                <div class="form-group">
                  <p>{{$data->surface}} m<sup style="size: 100%;">2</sup></p>
                </div>
              </div>
              <div class="col-lg-5">
                <div class="form-group">
                  <p>{{$data->capacity}} Người</p>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-lg-5">
                <div class="form-group">
                  <p>{{$data->bed}} Chiếc kingbed</p>
                </div>
              </div>
              <div class="col-lg-5">
                <div class="form-group">
                  <p>{{ number_format($data->price, 0, ',', '.') }} VNĐ/Đêm</p>
                </div>
              </div>
            </div>
            <div class="row" style="margin-top: 15px">
              
              <div class="col-lg-5" >
                <a href="#" class="primary-btn" style="color: black">Thông tin chi tiết</a>
              </div>
              <div class="col-lg-5">
                <a href="#" style="margin-right: 20px"><i class="fa-regular fa-heart"></i></a>
                <a class="btn" style="margin: 0px 20px; padding: unset">
                  <form action="{{ route('cart.add.product') }}" method="post" id="form_{{ $data->id }}" class="active">
                    @csrf
                    <input type="number" id="idRooms_{{ $data->id }}" name="idRooms" value="{{ $data->id }}" style="display: none;">
                    <input type="datetime-local" id="submitTime_{{ $data->id }}" name="submitTime" value="{{now()}}" style="display: none;">
                    <button type="submit" class="icon-button">
                      <i class="fa-solid fa-cart-plus"></i>
                    </button>
                  </form>
                </a>
                <a href="#" style="margin: 0px 20px"><i class="fa-solid fa-comments"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @endforeach
      @endif
      </div>
    </div>
  </section>
  <!-- Rooms Section End -->
</body>
@include('admin.customers.footer')

</html>