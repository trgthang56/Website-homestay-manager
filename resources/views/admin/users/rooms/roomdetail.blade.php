@extends('admin.main')

@section('content')
<style>
  /* Import Google font - Poppins */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');


.wrapper-calendar{
  width: 100%;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 15px 40px rgba(0,0,0,0.12);
}
.wrapper-calendar .header-calendar{
  display: flex;
  align-items: center;
  padding: 25px 30px 10px;
  justify-content: space-between;
}
.header-calendar .icons{
  display: flex;
}
.header-calendar .icons span{
  height: 38px;
  width: 38px;
  margin: 0 1px;
  cursor: pointer;
  color: #878787;
  text-align: center;
  line-height: 38px;
  font-size: 1.9rem;
  user-select: none;
  border-radius: 50%;
}
.icons span:last-child{
  margin-right: -10px;
}
.header-calendar .icons span:hover{
  background: #f2f2f2;
}
.header-calendar .current-date{
  font-size: 1.45rem;
  font-weight: 500;
}
.calendar{
  padding: 20px;
}
.calendar ul{
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  text-align: center;
}
.calendar .days{
  margin-bottom: 20px;
}
.calendar li{
  color: #333;
  width: calc(100% / 7);
  font-size: 1.07rem;
}
.calendar .weeks li{
  font-weight: 500;
  cursor: default;
}
.calendar .days li{
  z-index: 1;
  cursor: pointer;
  position: relative;
  margin-top: 30px;
}
.days li.inactive{
  color: #aaa;
}
.days li.active{
  color: #fff;
}
.days li.active-today{
  color: #fff;
}
.days li::before{
  position: absolute;
  content: "";
  left: 50%;
  top: 50%;
  height: 40px;
  width: 40px;
  z-index: -1;
  border-radius: 50%;
  transform: translate(-50%, -50%);
}
.days li.active::before{
  background: #dfa974;
}
.days li.active-today::before{
  background: #222224;
}
.days li:not(.active):hover::before{
  background: #f2f2f2;
}


</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Thông tin {{$data->name}}</h1>
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
        <div class="card">
          <!-- <div class="card-header">
            <h3 class="card-title"><a class="nav-link" style="padding: 0px; color: white;">Thông tin {{$data->name}}</a></h3>
          </div> -->
          <div class="card-body">
            <div class="tab-content">
              <div class="row">
                <div class="col-md-4 d-flex justify-content-center">
                    <img src="{{asset('rooms/'.$data->image)}}" alt="Room Image" style="width: 450px;">
                </div>
                <div class="col-md-8" style="font-size: 18px">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="inputName">Tên phòng</label>
                        <p>{{$data->name}}</p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="inputName">Phòng</label>
                        <p>{{$data->number}}</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="inputName">Diện tích</label>
                        <p>{{$data->surface}} m<sup style="size: 100%;">2</sup></p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="inputName">Sức chứa</label>
                        <p>{{$data->capacity}} Người</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="inputName">Giường</label>
                        <p>{{$data->bed}} Chiếc kingbed</p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label for="inputName">Giá phòng</label>
                        <p>{{ number_format($data->price, 0, ',', '.') }} VNĐ</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="wrapper-calendar">
          <div class="header-calendar">
            <p class="current-date"></p>
            <div class="icons">
              <span id="prev" class="material-symbols-rounded">chevron_left</span>
              <span id="next" class="material-symbols-rounded">chevron_right</span>
            </div>
          </div>
          <div class="calendar">
            <ul class="weeks">
              <li>CN</li>
              <li>T2</li>
              <li>T3</li>
              <li>T4</li>
              <li>T5</li>
              <li>T6</li>
              <li>T7</li>
              </ul>
            <ul class="days"></ul>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="wrapper-calendar">
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
                </tr>
              </thead>
              <tbody>
                @foreach ($bookedList as $datum)
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
                    <form id=" completeForm_{{ $datum->id }}" method="POST" action="{{route('booking.complete')}}">
                      @csrf
                      <input type="hidden" name="id_room" value="{{$datum->id_room}}">
                      <input type="hidden" name="id" value="{{$datum->id}}">
                      <button type="button" class="badge badge-primary" onclick="confirmComplete({{ $datum->id }})"
                        style="font-size: 14px; border: 0px;">Đã xác nhận</button>
                    </form>
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
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="card-footer clearfix" style="background-color: white; border-radius: 5px">
            {{$bookedList->appends(request()->all())->links()}}
          </div>
        </div>
      </div>
    </div>
    <!-- Default box -->
    
  </section>
  <!-- /.content -->
</div>

<script>
 const daysTag = document.querySelector(".days"),
  currentDate = document.querySelector(".current-date"),
  prevNextIcon = document.querySelectorAll(".icons span");
  let bookedList = ({!! json_encode($bookedDate) !!});

  // getting new date, current year and month
  let date = new Date(),
  currYear = date.getFullYear(),
  currMonth = date.getMonth();

  // storing full name of all months in array
  const months = ["Tháng 1, ", "Tháng 2, ", "Tháng 3, ", "Tháng 4, ", "Tháng 5, ", "Tháng 6, ", "Tháng 7, ", "Tháng 8, ", "Tháng 9, ", "Tháng 10, ", "Tháng 11, ", "Tháng 12, "];

  const renderCalendar = () => {
      let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // getting first day of month
      lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // getting last date of month
      lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), // getting last day of month
      lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // getting last date of previous month
      let liTag = "";
      
      for (let i = firstDayofMonth; i > 0; i--) { // creating li of previous month last days
          liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
      }

      for (let i = 1; i <= lastDateofMonth; i++) { // creating li of all days of current month
        let isToday = i === date.getDate() && currMonth === new Date().getMonth() 
                      && currYear === new Date().getFullYear() ? "active-today" : "";

        // Kiểm tra xem ngày i có nằm giữa check_in_date và check_out_date của bất kỳ phần tử nào trong bookedList hay không
        bookedList.forEach(item => {
            let checkIn = new Date(item.check_in_date);
            let checkOut = new Date(item.check_out_date);
            if (checkIn <= new Date(currYear, currMonth, i) && new Date(currYear, currMonth, i) <= checkOut) {
                isToday = "active"; // Nếu có, gán class "active" cho ngày đó
            }
        });

        liTag += `<li class="${isToday}">${i}</li>`;
    }
      
      for (let i = lastDayofMonth; i < 6; i++) { // creating li of next month first days
          liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`
      }
      
      currentDate.innerText = `${months[currMonth]} ${currYear}`; // passing current mon and yr as currentDate text
      daysTag.innerHTML = liTag;
  }
  renderCalendar();


  let bookedListArray = ({!! json_encode($bookedDate) !!});
  bookedListArray.forEach(item => {
      console.log(item.check_in_date);
  });



  
  prevNextIcon.forEach(icon => { // getting prev and next icons
      icon.addEventListener("click", () => { // adding click event on both icons
          // if clicked icon is previous icon then decrement current month by 1 else increment it by 1
          currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

          if(currMonth < 0 || currMonth > 11) { // if current month is less than 0 or greater than 11
              // creating a new date of current year & month and pass it as date value
              date = new Date(currYear, currMonth, new Date().getDate());
              currYear = date.getFullYear(); // updating current year with new date year
              currMonth = date.getMonth(); // updating current month with new date month
          } else {
              date = new Date(); // pass the current date as date value
          }
          renderCalendar(); // calling renderCalendar function
      });
  });
</script>
@endsection