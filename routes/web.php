<?php

use App\Http\Controllers\Admin\Customers\CustomerCartController;
use App\Http\Controllers\Admin\Customers\CustomerRoomController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\Users\ExtraServiceController;
use App\Http\Controllers\Admin\Users\LoginController;
use App\Http\Controllers\Admin\Users\AccountController;
use App\Http\Controllers\Admin\Users\BookingController;
use App\Http\Controllers\Admin\Users\KindOfRoomController;
use App\Http\Controllers\Admin\Users\RoomController;
use App\Http\Controllers\Admin\Users\SearchController;
use App\Http\Controllers\Admin\Users\ServiceController;
use App\Http\Controllers\Admin\Users\CartController;
use App\Http\Controllers\Admin\Customers\CustomerLoginController;
use App\Http\Controllers\Admin\Customers\CustomerController;
use App\Http\Controllers\Admin\Customers\CustomerKORController;

use App\Http\Controllers\Admin\Users\StatisticController;
use App\Http\Controllers\VnpayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/users/login', [LoginController::class, 'index'])->name('login');
Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/logout/customer', [CustomerLoginController::class, 'logout'])->name('logout.customer');
Route::post('/users/login/homestay', [LoginController::class, 'login_homestay']);
Route::middleware('auth')->group(function () {
  Route::get('admin', [MainController::class, 'index'])->name('admin');
  Route::get('', [MainController::class, 'index']);
  Route::prefix('admin')->group(function () {
    //sửa thông tin cá nhân/nhân viên             
    Route::post('/user/update/{id}', [AccountController::class, 'update'])->name('user.update');
    //sửa mật khẩu của cá nhân/nhân viên
    Route::post('change-password/{id}', [AccountController::class, 'change'])->name('change-password');



    //hiện danh sách nhân viên
    Route::get('accountlist', [AccountController::class, 'index'])->name('accountlist');
    //hiên danh sách khách hàng
    Route::get('accountcustomerlist', [AccountController::class, 'customerListIndex'])->name('accountcustomerlist');
    //thêm nhân viên
    Route::post('accountlist', [AccountController::class, 'add'])->name('add');
    //hiển thị trang thông tin nhân viên
    Route::get('account/detail/{id}', [AccountController::class, 'indexAccount'])->name('account.detail');
    //hiển thị trang chỉnh sửa thông tin nhân viên
    Route::get('account/edit/{id}', [AccountController::class, 'indexAccEdit'])->name('account.edit');
    //xóa nhân viên
    Route::delete('delete/{id}', [AccountController::class, 'delete'])->name('delete');



    //hiện danh sách loại phòng
    Route::get('kindofroom', [KindOfRoomController::class, 'index'])->name('kindofroom');
    //thêm loại phòng
    Route::post('kindofroom/add', [KindOfRoomController::class, 'add'])->name('addkindofroom');
    //trang sửa phòng và thao tác sửa
    Route::get('kindofroom/edit/{id}', [KindOfRoomController::class, 'editView'])->name('kindofroom.edit.view');
    Route::post('kindofroom/edit', [KindOfRoomController::class, 'edit'])->name('kindofroom.edit');
    //xóa loại phòng
    Route::delete('deletekindofroom/{id}', [KindOfRoomController::class, 'delete'])->name('deletekindofroom');



    //hiện danh sách phòng
    Route::get('roomlist', [RoomController::class, 'index'])->name('roomlist');
    //hiển thị trang chỉnh sủa thông tin phòng
    Route::post('room/edit/{id}', [RoomController::class, 'editView'])->name('room.edit.view');
    Route::post('room/edit', [RoomController::class, 'edit'])->name('room.edit');
    // hiển thị trang thông tin chi tiết
    Route::get('room/detail/{id}', [RoomController::class, 'detailView'])->name('room.detail.view');
    //hiện chi tiết thêm phòng
    Route::get('roomadd', [RoomController::class, 'indexRoomAdd'])->name('roomadd');
    //hiển thị tìm kiếm theo lịch trống
    Route::get('checkRoom', [RoomController::class, 'checkRoom'])->name('checkroom');
    //thêm phòng
    Route::post('room', [RoomController::class, 'add'])->name('addroom');
    //xóa loại phòng
    Route::delete('deleteroom/{id}', [RoomController::class, 'delete'])->name('deleteroom');




    //hiện danh sách dịch vụ
    Route::get('servicelist', [ServiceController::class, 'index'])->name('servicelist');
    //trang sửa dịch vụ và thao tác sửa
    Route::post('service/edit/{id}', [ServiceController::class, 'editView'])->name('service.edit.view');
    Route::post('service/edit', [ServiceController::class, 'edit'])->name('service.edit');
    //thêm dịch vụ
    Route::post('service', [ServiceController::class, 'add'])->name('addservice');
    //xóa dịch vụ
    Route::delete('deleteservice/{id}', [ServiceController::class, 'delete'])->name('deleteservice');




    //hiện danh sách booking
    Route::get('bookinglist', [BookingController::class, 'index'])->name('bookinglist');
    //hiện danh sách booking mới
    Route::get('bookingnew', [BookingController::class, 'bookingNewView'])->name('bookingnew');
    //hiển thị chi tiết thông tin của hóa đơn đặt phòng
    Route::get('viewbooking/{id}', [BookingController::class, 'detailView'])->name('view.booking');
    //hiển thị trang chỉnh sửa thông tin của hóa đơn đặt phòng
    Route::get('editbooking/{id}', [BookingController::class, 'editView'])->name('edit.booking');
    //hủy đơn đặt phòng
    Route::get('bookingcancel', [BookingController::class, 'cancelBooking'])->name('booking.cancel');
    //lưu booking (chưa được xác nhận)
    Route::post('/', [BookingController::class, 'booking'])->name('booking.store');
    //xác nhận booking
    Route::post('bookingaccept', [BookingController::class, 'acceptBooking'])->name('booking.accept');
    //xác nhận trả phòng
    Route::post('bookinglist', [BookingController::class, 'completeBooking'])->name('booking.complete');
    //check-In
    Route::post('checkin', [BookingController::class, 'checkIn'])->name('check-In');
    //xóa đơn đặt phòng
    Route::post('cancelbooking/{id}', [BookingController::class, 'cancelBooking'])->name('cancel.booking');



    //trang tìm kiếm
    Route::get('seachIndex', [SearchController::class, 'index'])->name('search.index');
    //tìm kiếm phòng
    Route::get('search', [SearchController::class, 'search'])->name('search');



    //trang giỏ hàng
    Route::get('cart/checktime', [CartController::class, 'checktime'])->name('cart.checktime');
    Route::post('cart/updates', [CartController::class, 'updates'])->name('cart.updates');
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/add', [CartController::class, 'addProduct'])->name('cart.add.product');
    Route::post('cart/cancel', [CartController::class, 'cartCancel'])->name('cart.cancel');
    Route::post('cart/delete', [CartController::class, 'cartDeleteItem'])->name('cart.delete');


    //trang thêm dịch vụ phát sinh
    Route::get('ExtraServiceIndex/{id}', [ExtraServiceController::class, 'index'])->name('extra.service.index');
    Route::post('ExtraServiceIndex/add', [ExtraServiceController::class, 'add'])->name('extra.service.add');
    Route::get('ExtraServiceIndex/cancel/{id}', [ExtraServiceController::class, 'cancel'])->name('extra.service.cancel');
    Route::get('ExtraServiceIndex/completed/{id}', [ExtraServiceController::class, 'completed'])->name('extra.service.completed');
    Route::get('ExtraServiceIndex/paid/{id}', [ExtraServiceController::class, 'paid'])->name('extra.service.paid');
    Route::get('ExtraServiceIndex/finished/{id}', [ExtraServiceController::class, 'finished'])->name('extra.service.finished');
    Route::get('ExtraServiceIndex/completedAll/{id}', [ExtraServiceController::class, 'completedAll'])->name('extra.service.completedAll');
    Route::get('ExtraServiceIndex/paidAll/{id}', [ExtraServiceController::class, 'paidAll'])->name('extra.service.paidAll');
    Route::get('ExtraServiceIndex/finishedAll/{id}', [ExtraServiceController::class, 'finishedAll'])->name('extra.service.finishedAll');

    Route::get('ExtraServiceIndex/edit/{id}', [ExtraServiceController::class, 'edit'])->name('extra.service.edit');
    Route::get('ExtraServiceIndex/info/{id}', [ExtraServiceController::class, 'info'])->name('extra.service.info');


    //trang thanh toán
    Route::get('cartdetail', [CartController::class, 'cartDetail'])->name('cart.detail');
    Route::get('vnpay_payment', [CartController::class, 'vnpay_payment'])->name('vnpay.payment');
    Route::get('cash_payment', [CartController::class, 'cash_payment'])->name('cash.payment');
    Route::post('paid_by_cash', [BookingController::class, 'paidByCash'])->name('cash.paid');
    Route::get('return/vnpay', [VnpayController::class, 'vnpay_returnUrl'])->name('vnpay.returnUrl');
    Route::get('cartdetail/store', [CartController::class, 'store'])->name('cart.store');

    //trang thống kê
    Route::get('statistic', [StatisticController::class, 'index'])->name('statistic.index');
    
  });
  Route::get('/homepage', [CustomerController::class, 'index'])->name('logined.homepage');
  Route::get('/customer/profile', [CustomerController::class, 'profileIndex'])->name('customer.profile');
  Route::get('/customer/bill/{id}', [CustomerController::class, 'detailView'])->name('customer.bill');
  Route::get('/customer/cart', [CustomerCartController::class, 'index'])->name('customer.cart.index');
  //trang thanh toán của khách hàng
  Route::get('/customer/cartdetail', [CustomerCartController::class, 'cartDetail'])->name('customer.cart.detail');


});
Route::get('/customer/search', [CustomerController::class, 'search'])->name('customer.search');

Route::get('/kindofroomlist', [CustomerKORController::class, 'index'])->name('customer.kindofroomlist');
Route::get('/roomlist/{id}', [CustomerRoomController::class, 'index'])->name('customer.roomlist');
Route::get('/', [CustomerController::class, 'index'])->name('homepage');
Route::get('/login', [CustomerLoginController::class, 'index'])->name('login.customer');
Route::post('/login/homestay', [CustomerLoginController::class, 'login']);
Route::post('/register', [CustomerLoginController::class, 'register'])->name('register');




