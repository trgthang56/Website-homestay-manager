<meta charset="UTF-8">
<meta name="description" content="Sona Template">
<meta name="keywords" content="Sona, unica, creative, html">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>VHomeStay</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Lora:400,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css'>

<!-- Css Styles -->
<link rel="stylesheet" href="/template/customer/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/elegant-icons.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/flaticon.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/owl.carousel.min.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/nice-select.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/jquery-ui.min.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/magnific-popup.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/slicknav.min.css" type="text/css">
<link rel="stylesheet" href="/template/customer/css/style.css" type="text/css">

<!-- Toastr -->
<link rel="stylesheet" href="/template/admin/plugins/toastr/toastr.min.css">
<style>
  
  header h2 {
    font-size: 24px;
    font-weight: 600;
  }

  header p {
    margin-top: 5px;
    font-size: 16px;
  }

  .price-input {
    width: 100%;
    display: flex;
    margin: 0px 0 35px;
  }

  .price-input .field {
    display: flex;
    width: 100%;
    height: 45px;
    align-items: center;
  }

  .field input {
    width: 100%;
    height: 100%;
    outline: none;
    font-size: 19px;
    border-radius: 5px;
    text-align: center;
    border: 1px solid #ced4da;
    -moz-appearance: textfield;
  }

  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
  }

  .slider {
    height: 5px;
    position: relative;
    background: #ddd;
    border-radius: 5px;
  }

  .slider .progress {
    height: 100%;
    left: 25%;
    right: 25%;
    position: absolute;
    border-radius: 5px;
    background: #dfa974;
  }

  .range-input {
    position: relative;
  }

  .range-input input {
    position: absolute;
    width: 100%;
    height: 5px;
    top: -5px;
    background: none;
    pointer-events: none;
    -webkit-appearance: none;
    -moz-appearance: none;
  }

  input[type="range"]::-webkit-slider-thumb {
    height: 17px;
    width: 17px;
    border-radius: 50%;
    background: #dfa974;
    pointer-events: auto;
    -webkit-appearance: none;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
  }

  input[type="range"]::-moz-range-thumb {
    height: 17px;
    width: 17px;
    border: none;
    border-radius: 50%;
    background: #dfa974;
    pointer-events: auto;
    -moz-appearance: none;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
  }
</style>
<style>
  /* CSS cho modal */
  .modal {
    display: none;
    /* Ẩn modal ban đầu */
    position: fixed;
    z-index: 9999;
    left: 0;
    margin: 50px;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
  }

  .modal-content {
    margin: auto;
    display: block;
    max-width: 640px;
    max-height: 640px;
    width: auto;
    height: auto;
  }

  /* Responsive: thay đổi kích thước của ảnh trong modal khi màn hình thu nhỏ */
  @media screen and (max-width: 768px) {
    .modal-content {
      max-width: 90%;
      /* Kích thước tối đa khi màn hình nhỏ hơn */
      max-height: 90%;
    }
  }


  /* Đóng modal nút x */
  .close {
    position: absolute;
    top: 10px;
    right: 25px;
    font-size: 35px;
    color: #ffffff;
    cursor: pointer;
  }

  .close:hover,
  .close:focus {
    color: #aaaaaa;
  }

  #selectedImage {
    max-width: 120px;
    /* Chiều rộng tối đa */
    max-height: 120px;
    /* Chiều cao tối đa */
    margin: 20px;
  }
</style>
<!-- Page Preloder -->
<div id="preloder">
  <div class="loader"></div>
</div>

<!-- Offcanvas Menu Section Begin -->
<div class="offcanvas-menu-overlay"></div>
<div class="canvas-open">
  <i class="fas fa-bars"></i>
</div>
<div class="offcanvas-menu-wrapper">
  <div class="canvas-close">
    <i class="icon_close"></i>
  </div>
  <div class="header-configure-area">
    @if(auth()->guest())
    <a href="/login"><button class="bk-btn">Đăng nhập</button></a>
    @else
      <div class="language-option">
        @if(auth()->user()->role == "customer")
          <a href="/customer/profile" style="margin: 0px 5px"><span>{{ auth()->user()->name }}</span></a>
        @else
          <a href="/admin" style="margin: 0px 5px"><span>{{ auth()->user()->name }}</span></a>
        @endif
      </div>
    @endif
  </div>
  <nav class="mainmenu mobile-menu">
    <ul>
      <li><a href="/homepage">Trang chủ</a></li>
      <li><a href="/kindofroomlist">Hạng phòng</a>
        <ul class="dropdown">
          @foreach($menuData as $datum)
            <li><a href="{{ route('customer.roomlist', ['id' => $datum->id]) }}">{{$datum->kind_of_room}}</a></li>
          @endforeach
        </ul>
      </li>
      <!-- <li><a href="./about-us.html">Chúng T</a></li> -->
      <li><a href="./pages.html">Các trang</a>
        <ul class="dropdown">
          <li><a href="#">Dịch vụ</a></li>
          <li><a href="#">Phòng ưa thích</a></li>
          <li><a href="#">Sự kiện</a></li>

        </ul>
      </li>
      <li><a href="#">Hội viên</a></li>
      <li><a href="./contact.html">Liên hệ</a></li>
    </ul>
  </nav>
  <div id="mobile-menu-wrap"></div>
  <div class="top-social">
    <a href="#"><i class="fa fa-instagram"></i></a>
    <a href="#"><i class="fa fa-facebook"></i></a>
    <a href="#"><i class="fa-solid fa-g"></i></a>
    <a href="#"><i class="fa fa-tripadvisor"></i></a>
  </div>
  <ul class="top-widget">
    <li><i class="fa fa-phone"></i>(84) 982 362 617</li>
    <li><i class="fa fa-envelope"></i>VHomeStay@gmail.com</li>
  </ul>
</div>
<!-- Offcanvas Menu Section End -->

<!-- Header Section Begin -->

<header class="header-section header-normal">
  <div class="top-nav menu-item" style="z-index: 10">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <ul class="tn-left">
            <li><i class="fa fa-phone"></i>(84) 982 362 617</li>
            <li><i class="fa fa-envelope"></i>VHomeStay@gmail.com</li>
          </ul>
        </div>
        <div class="col-lg-6">
          <div class="tn-right">
            @if(auth()->guest())
            <div class="top-social">
              <a href="#"><i class="fa fa-instagram"></i></a>
              <a href="#"><i class="fa fa-facebook"></i></a>
              <a href="#"><i class="fa-solid fa-g"></i></a>
              <a href="#"><i class="fa fa-tripadvisor"></i></a>
            </div>
            <a href="/login"><button class="bk-btn">Đăng nhập</button></a>
            @else
            <div class="language-option">
              @if(auth()->user()->role == "customer")
                <a href="/customer/profile" style="margin: 0px 5px"><span>{{ auth()->user()->name }}</span></a>
              @else
                <a href="/admin" style="margin: 0px 5px"><span>{{ auth()->user()->name }}</span></a>
              @endif
            </div>
            <div class="nav-menu" style="width: 4%; float: right">
              <nav class="mainmenu" style="margin-top: 65%;">
                <ul style>
                  <li><i class="fa fa-angle-down"></i>
                    <ul class="dropdown" style="top: 25px;left: -170px;width: 200px;">
                      @if(auth()->user()->role == "customer")
                        <li><a href="/customer/profile"><i class="fa-solid fa-user" style="margin: 0px 5px 0px 10px"></i>Trang cá nhân</a></li>
                        <li><a href="/customer/cart"><i class="fa-solid fa-cart-shopping" style="margin: 0px 5px 0px 7px"></i>Giỏ hàng</a></li>

                      @endif
                      @if(auth()->user()->role != "customer")
                        <li><a href="/admin"><i  class="fa-solid fa-house" style="margin: 0px 5px 0px 10px"></i>Trang quản lý</a></li>

                      @endif
                      <li><a href="{{route('logout.customer')}}"><i class="fa-solid fa-right-from-bracket" style="margin: 0px 5px 0px 10px"></i>Đăng xuất</a></li>
                    </ul>
                  </li>
                </ul>
              </nav>  
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="menu-item">
    <div class="container">
      <div class="row">
        <div class="col-lg-2">
          <div class="logo">
            <a href="/homepage">
              <img src="/template/customer/img/Logo2.png" alt="">
            </a>
          </div>
        </div>
        <div class="col-lg-10">
          <div class="nav-menu">
            <nav class="mainmenu">
              <ul>
                <li><a href="/homepage">Trang chủ</a></li>
                <li><a href="/kindofroomlist">Hạng phòng</a>
                  <ul class="dropdown">
                    @foreach($menuData as $datum)
                      <li><a href="{{ route('customer.roomlist', ['id' => $datum->id]) }}">{{$datum->kind_of_room}}</a></li>
                    @endforeach
                  </ul>
                </li>
                <!-- <li><a href="./about-us.html">Chúng T</a></li> -->
                <li><a href="./pages.html">Các trang</a>
                  <ul class="dropdown">
                    <li><a href="#">Dịch vụ</a></li>
                    <li><a href="#">Phòng ưa thích</a></li>
                    <li><a href="#">Sự kiện</a></li>
                  </ul>
                </li>
                <li><a href="#">Hội viên</a></li>
                <li><a href="./contact.html" style="margin: unset">Liên hệ</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- Header End -->