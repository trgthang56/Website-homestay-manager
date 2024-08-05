<!-- Footer Section Begin -->
<footer class="footer-section">
  <div class="container">
    <div class="footer-text">
      <div class="row">
        <div class="col-lg-4">
          <div class="ft-about">
            <div class="logo">
              <a href="#">
                <img src="/template/customer/img/footer-Logo2.png" style="width: 165px; height: 65px" alt="">
              </a>
            </div>
            <p>VHomeStay - Khoảnh Khắc Bình Yên <br /> Trong Hành Trình Khám Phá</p>
            <div class="fa-social">
              <a href="#"><i class="fa fa-facebook"></i></a>
              <a href="#"><i class="fa fa-twitter"></i></a>
              <a href="#"><i class="fa fa-tripadvisor"></i></a>
              <a href="#"><i class="fa fa-instagram"></i></a>
              <a href="#"><i class="fa fa-youtube-play"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-3 offset-lg-1">
          <div class="ft-contact">
            <h6>Liên Hệ Chúng tôi</h6>
            <ul>
              <li>(84) 982 362 617</li>
              <li>VHomeStay@gmail.com</li>
              <li>022 Thảo Nguyên, Khu đô thị Ecopark, Hưng Yên</li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 offset-lg-1">
          <div class="ft-newslatter">
            <h6>Lời Nhắn Nhủ</h6>
            <p>Những gì cần cải thiện và nâng cấp. </p>
            <form action="#" class="fn-form">
              <input type="text" placeholder="...">
              <button type="submit"><i class="fa fa-send"></i></button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
<!-- Footer Section End -->

<!-- Search model Begin -->
<div class="search-model">
  <div class="h-100 d-flex align-items-center justify-content-center">
    <div class="search-close-switch"><i class="icon_close"></i></div>
    <form class="search-model-form">
      <input type="text" id="search-input" placeholder="Search here.....">
    </form>
  </div>
</div>
<!-- Search model end -->
<script src="/template/customer/js/jquery-3.3.1.min.js"></script>
<script src="/template/customer/js/bootstrap.min.js"></script>
<script src="/template/customer/js/jquery.magnific-popup.min.js"></script>
<script src="/template/customer/js/jquery.nice-select.min.js"></script>
<script src="/template/customer/js/jquery-ui.min.js"></script>
<script src="/template/customer/js/jquery.slicknav.js"></script>
<script src="/template/customer/js/owl.carousel.min.js"></script>
<script src="/template/customer/js/main.js"></script>
<!-- Toastr -->
<script src="/template/admin/plugins/toastr/toastr.min.js"></script>
@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
    toastr.error("{{$error}}", 'Lỗi!', { timeout: 2000 });
</script>
@endforeach
@endif

@if (Session::has('notification'))
<script>
    toastr.success("{{ Session::get('notification')}}", 'Thành công!', { timeout: 0 });
</script>
@endif
@if (Session::has('warning_notification'))
<script>
    toastr.warning("{{ Session::get('warning_notification')}}", 'Trục trặc!', { timeout: 2000 });
</script>
@endif
@if (Session::has('error_notification'))
<script>
    toastr.error("{{ Session::get('error_notification')}}", 'Lỗi!', { timeout: 2000 });
</script>
@endif
<!-- <script src="/template/customer/js/jquery-ui.js"></script>
<script src="/template/customer/js/jquery.ui.datepicker-vi.js"></script> -->