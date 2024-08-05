<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{$title}}</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="/template/admin/plugins/fontawesome-free/css/all.min.css">
<!-- <link rel="stylesheet" href="/template/admin/dist/awesome/all.css"> -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
<!-- rangeSlider -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rangeslider.js/2.3.2/rangeSlider.min.css"
  integrity="sha512-EUxRyanOXA+KW87Qdi8d/Yv8gLW5/J5q/3QXug6XeMtAerLq/66KzCZkxKRvN4xJ28kyXHx9UYJtiXvG/cNRAg=="
  crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
<!--Plugin CSS file with desired skin-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css" />
<!-- Toastr -->
<link rel="stylesheet" href="/template/admin/plugins/toastr/toastr.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="/template/admin/dist/css/adminlte.min.css">
<!-- icheck bootstrap -->
<link rel="stylesheet" href="/template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="/template/admin/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
  integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />


<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Lora:400,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700&display=swap" rel="stylesheet">

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
<style>
  .table img {
    max-width: 100%;
    /* Hình ảnh sẽ không vượt quá chiều rộng của cột */
    height: auto;
    /* Chiều cao sẽ tự động điều chỉnh để giữ tỷ lệ */
    display: block;
    /* Để xóa phần dư của hình ảnh nếu có */
    margin: auto;
    /* Căn giữa hình ảnh trong cột */
  }
</style>
<style>
  .toggle-text {
    cursor: pointer;
    color: blue;
    /* Màu chữ khi không hover */
    transition: color 0.3s ease;
    /* Hiệu ứng màu chữ */
  }

  .toggle-text:hover {
    color: red;
    /* Màu chữ khi hover */
  }
</style>
<style>
  .profile-user-img {
    border-radius: 50%;
    width: 100px;
    /* Điều chỉnh kích thước tùy theo nhu cầu */
    height: 100px;
    /* Điều chỉnh kích thước tùy theo nhu cầu */
    object-fit: cover;
    /* Giữ tỉ lệ của hình ảnh */
  }

  .image img {
    width: 35px;
    /* Điều chỉnh kích thước tùy theo nhu cầu */
    height: 35px;
    /* Điều chỉnh kích thước tùy theo nhu cầu */
    object-fit: cover;
    /* Giữ tỉ lệ của hình ảnh */
  }
</style>
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
    background: #17A2B8;
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
    background: #17A2B8;
    pointer-events: auto;
    -webkit-appearance: none;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
  }

  input[type="range"]::-moz-range-thumb {
    height: 17px;
    width: 17px;
    border: none;
    border-radius: 50%;
    background: #17A2B8;
    pointer-events: auto;
    -moz-appearance: none;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
  }
</style>
<style>
  .custom-btn {
    border: 1px solid #000;
    /* Màu đen cho border */
    transition: background-color 0.3s, color 0.3s;
    /* Hiệu ứng chuyển động trong 0.3s */
  }

  .custom-btn:hover {
    background-color: #000;
    /* Màu đen khi hover */
    color: #fff;
    /* Màu trắng cho chữ khi hover */
  }
</style>