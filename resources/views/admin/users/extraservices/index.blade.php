@extends('admin.main')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Dịch vụ phát sinh</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Extra Service</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container mt-5">
      <form action="{{route('extra.service.add')}}" method="POST"> <!-- Your route here -->
        @csrf
        <div class="card">
          <div class="card-header">
            <h2 class="text-center mb-4">Đơn dịch vụ phát sinh</h2>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col">
                <div class="form-group">
                  <label for="id_booking">ID Booking</label>
                  <input type="text" class="form-control" value="{{$booking->id}}" name="id_booking" id="id_booking" readonly>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="name">Tên</label>
                  <input type="text" class="form-control" value="{{auth()->user()->name}}" name="name" id="name" readonly>
                  <input type="hidden" value="{{auth()->user()->id}}" name="id_user">
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col">
                <button type="button" class="btn btn-primary" id="addServiceBtn">Thêm dịch vụ</button>
                <br><i class="fa-solid fa-circle-info"></i><span> Yêu cầu nhập số đơn vị tính trước khi chọn dịch vụ</span>
              </div>
            </div>
            <div id="serviceList"></div>
            <div class="row mb-3" style="margin-top: 30px">
              <div class="col-md-7">
                <input type="teratext" class="form-control" name="description" placeholder="khác">
              </div>
              <div class="col-md-5">
                <input type="text" class="form-control price-other-input" name="other_price" placeholder="chi phí khác">
              </div>
            </div>
            <div class="row mt-3">
              <div class="col">
                <h5 class="float-left">Tổng</h5>
                <h5 class="float-right" name="total" id="total">0 VNĐ</h5>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('edit.booking', ['id' => $booking->id]) }}" class="btn btn-primary">
              <i class="fa-solid fa-arrow-left"></i> Quay lại 
            </a>
            <button type="submit" class="btn btn-success float-right">Thêm</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    var total = 0;
    var services = {!! json_encode($services) !!}; // Convert PHP array to JavaScript array
    var rowCount = 0; // Biến đếm

    $('#addServiceBtn').click(function() {
        var serviceOptions = '';
        services.forEach(function(service) {
            serviceOptions += '<option value="' + service.id + '" data-price="' + service.price + '" name="service[' + rowCount + ']">' + service.name + ' / ' + service.price + ' VNĐ' + '</option>';
        });

        var serviceRow = '<div class="row mb-3 service-row service-row-' + rowCount + '">' +
                            '<div class="col-md-7">' +
                                '<label>Dịch vụ</label>' + 
                                '<select class="form-control service-select" name="service[' + rowCount + ']">' +
                                    serviceOptions +
                                '</select>' +
                            '</div>' +
                            '<div class="col-md-2">' +
                                '<label>Đơn vị tính</label>' + '<span class="float-right">Lần/giờ</span>' +
                                '<input type="text" class="form-control quantity-input" value="1" placeholder="Số lượng" name="quantity[' + rowCount + ']">' +
                            '</div>' +
                            '<div class="col-md-2">' +
                                '<label>Giá</label>' + '<span class="float-right">VNĐ</span>' +
                                '<input type="text" class="form-control price-input" placeholder="Giá" readonly name="price[' + rowCount + ']">' +
                            '</div>' +
                            '<div class="col">' +
                                '<button type="button" class="btn btn-danger btn-remove-service float-right" style="width: 100%; margin-top: 32px">Xóa</button>' +
                            '</div>' +
                        '</div>';
        $('#serviceList').append(serviceRow);

        rowCount++; // Tăng biến đếm sau mỗi lần thêm
    });

    $('#serviceList').on('change', '.service-select', function() {
        var price = $(this).find('option:selected').data('price');
        var quantity = $(this).closest('.service-row').find('.quantity-input').val();
        var totalPrice = price * quantity;
        $(this).closest('.service-row').find('.price-input').val(totalPrice); // Update the price input field
        updateTotal();
    });

    $('#serviceList').on('change', '.quantity-input', function() {
        updateTotal();
    });

    $('#serviceList').on('click', '.btn-remove-service', function() {
        $(this).closest('.service-row').remove();
        updateTotal();
    });

    $('.price-other-input').on('change', function() {
        updateTotal();
    });

    function updateTotal() {
        total = 0;
        $('.price-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        // Add the value of price-other-input to the total
        total += parseFloat($('.price-other-input').val()) || 0;
        $('#total').text(total.toLocaleString('en-US') + ' VNĐ');
    }
});

</script>

@endsection
