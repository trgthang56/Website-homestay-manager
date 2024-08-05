<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #000000e3;">
  <!-- Brand Logo -->
  <a href="/homepage" class="brand-link" method="post">
    <img src="/template/customer/img/LogoWhite.png" class="brand-image">
    <span class="brand-text font-weight-light">Trang chủ</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        @if(Auth::user()->image)
        <img class="img-circle" src="/uploads/{{ Auth::user()->image }}" alt="User profile picture">

        @else
        <img class="img-circle" src="/template/admin/dist/img/avatar-default.jpg" alt="User profile picture">
        @endif
      </div>
      <div class="info">
        <a href="/admin" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Quản lý
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            @if (auth()->user()->role=='admin'|| auth()->user()->role=='manager'|| auth()->user()->role=='staff' )
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-house-chimney-window nav-icon"></i>
                <p>
                  HomeStay
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="/admin/kindofroom" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Hạng phòng</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/admin/servicelist" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Loại dịch vụ</p>
                  </a>
                </li>
              </ul>
            </li>

            @endif
            @if (auth()->user()->role=='admin'|| auth()->user()->role=='manager'|| auth()->user()->role=='staff' )
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-building nav-icon"></i>
                <p>
                  Phòng
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">

                <li class="nav-item">
                  <a href="/admin/roomlist" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách phòng</p>
                  </a>
                </li>
                @if (auth()->user()->role=='admin'|| auth()->user()->role=='manager')
                <li class="nav-item">
                  <a href="/admin/roomadd" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Thêm phòng</p>
                  </a>
                </li>
                @endif
              </ul>
            </li>
            @endif
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa-solid fa-tents nav-icon"></i>
                <p>Đặt phòng
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="/admin/bookingnew" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Đơn đặt phòng mới</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/admin/bookinglist" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Đơn đã hoàn thành</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('search.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Đặt phòng</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="{{route('statistic.index')}}" class="nav-link">
                <i class="fa-solid fa-chart-line nav-icon"></i>
                <p>Tài chính</p>
              </a>
            </li>
          </ul>
        </li>
        @if (auth()->user()->role=='admin'|| auth()->user()->role=='manager')
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-user nav-icon"></i>
            <p>
              Quản lý người dùng
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Nhân viên
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="/admin/accountlist" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Danh sách nhân viên</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/admin/accountadd" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Thêm nhân viên</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Khách hàng
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="/admin/accountcustomerlist" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Danh sách khách hàng</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/admin/" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Tạo tài khoản khách</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        @endif
        <li class="nav-item">
          <a href="{{ route('search.index') }}" class="nav-link">
            <i class="fa-solid fa-magnifying-glass nav-icon"></i>
            <p>Tìm kiếm phòng</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/admin" class="nav-link">
            <i class=" nav-icon far fa-user"></i>
            <p> Thông tin tài khoản</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="/logout" class="nav-link">
            <i class="fa-solid fa-right-from-bracket nav-icon"></i>
            <p>Đăng xuất</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
</aside>