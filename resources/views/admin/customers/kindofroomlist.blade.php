<!DOCTYPE html>
<html lang="en">

<head>
  @include('admin.customers.head')
</head>

<body>
  <!-- Breadcrumb Section Begin -->
  <div class="breadcrumb-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="breadcrumb-text">
            <h2>Các hạng phòng</h2>
            <div class="bt-option">
              <a href="/homepage">Trang chủ</a>
              <span>Loại Phòng</span>
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
      <div class="row">
        @foreach($rooms as $room)
          @foreach($kors as $kor)
            @if($room->id_kind_of_room == $kor->id)
              <div class="col-lg-4 col-md-6">
                <div class="room-item">
                  <img style="height: 250px" src="/kind_of_rooms/{{$kor->image}}" alt="" >
                  <div class="ri-text">
                    <h4>{{$kor->kind_of_room}}</h4>
                    <table>
                      <tbody>
                        <tr>
                          <td class="r-o">Còn Trống:</td>
                          <td>{{ $kor->available }} phòng</td>
                        </tr>
                        <tr>
                          <td class="r-o">Độ rộng:</td>
                          <td>{{ $room->surface }} m<sup>2</sup></td>
                        </tr>
                        <tr>
                          <td class="r-o">Sức chứa:</td>
                          <td>Tối đa {{ $room->capacity }} người</td>
                        </tr>
                        <tr>
                          <td class="r-o">Nội thất:</td>
                          <td title="{{$kor->description}}">{{ Illuminate\Support\Str::limit($kor->description, 40) }}</td>
                        </tr>
                      </tbody>
                    </table>
                    <a href="{{ route('customer.roomlist', ['id' => $kor->id]) }}" class="primary-btn">Thông tin chi tiết</a>
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        @endforeach
      </div>
    </div>
  </section>
  <!-- Rooms Section End -->
</body>
@include('admin.customers.footer')

</html>