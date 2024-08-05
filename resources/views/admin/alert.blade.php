<!-- /resources/views/post/create.blade.php -->
<style>
    /* Loại bỏ dấu chấm cho các mục list */
    .alert.alert-danger ul {
        list-style-type: none;
        text-align: center;
        padding: 0;
    }

    /* Tạo một khoảng cách giữa các mục list */
    .alert.alert-danger ul li {
        margin-bottom: 5px; /* Điều chỉnh khoảng cách giữa các mục tùy ý */
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@if ($errors->any())
    <script>
        $(document).ready(function(){
            @foreach ($errors->all() as $error)
                @if($error == "Tài khoản của bạn đã được đăng kí thành công!")
                toastr.success("{{ $error }}", 'Thông báo', { timeout: 2000 });
                @else
                toastr.error("{{ $error }}", 'Thông báo', { timeout: 2000 });
                @endif
            @endforeach
        });
    </script>
@endif
<!-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li style="font-size: 18px">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif -->
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