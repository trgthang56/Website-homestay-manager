<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kind_of_room;
use App\Models\Room;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;


class CartController extends Controller
{
    public function index()
    {
        $bookings = Booking::orderBy('id', 'ASC')->get();

        $checkin = null;
        $checkout = null;
        $numberOfDays = null;
        $numberOfRooms = null;
        $guests = null;
        $babies = null;
        $babies_age_txt = null;
        $cart = Session::get('cart', []);
        $dates = Session::get('dates', []);
        //tạo ra bản sao và chuyển chúng sang dạng collection
        $dates = collect($dates)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        //lấy dữ liệu từ dates để truyền sang cho thẻ index
        foreach ($dates as $date) {
            $checkin = $date->checkin;
            $checkout = $date->checkout;
            $guests = $date->guests;
            $babies = $date->babies;
            if ($babies != 0) {
                if ($date->babies_age == 0) {
                    $babies_age_txt = ", trẻ 0-2 tuổi";
                }
                if ($date->babies_age == 1) {
                    $babies_age_txt = ", trẻ 3-5 tuổi";
                }
                if ($date->babies_age == 2) {
                    $babies_age_txt = ", trẻ 6-11 tuổi";
                }
                if ($date->babies_age == 3) {
                    $babies_age_txt = ", trẻ 12-14 tuổi";
                }
                if ($date->babies_age == 4) {
                    $babies_age_txt = ", trẻ 15-17 tuổi";
                }
            } else {
                $babies_age_txt = null;
            }
            $checkin = Carbon::parse($date->checkin);
            $checkout = Carbon::parse($date->checkout);
            $numberOfDays = $checkin->diffInDays($checkout);
        }

        $error_notification = 0;
        //kiểm tra xem có phòng nào nằm trong lịch booking không
        foreach ($bookings as $booking) {
            foreach ($cart as $key => $cartItem) {
                if (
                    ($booking->check_in_date < $checkin && $checkin < $booking->check_out_date) ||
                    ($booking->check_in_date < $checkout && $checkout < $booking->check_out_date)
                ) {
                    if ($cartItem['idRoom'] == $booking->id_room) {
                        Session::forget("cart.$key");
                        $error_notification = 1;
                        $idErrorRoom = $booking->id_room;
                        $room = Room::find($booking->id_room);
                        $room->status = 'Trống';
                        $room->save();
                    }
                }
            }
        }
        //kiểm tra phòng xem có phòng nào được đưa vào session quá 15p hay không
        //để trả lại phòng cho ng khác đặt
        foreach ($cart as $key => $cartItem) {
            $submitTime = Carbon::parse($cartItem['submitTime']);
            $now = Carbon::now();
            if ($submitTime->diffInMinutes($now) > 15) {

                Session::forget("cart.$key");
            }
        }
        //tạo ra bản sao và chuyển chúng sang dạng collection
        $cart = collect($cart)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        $numberOfRooms = $cart->count();
        if ($error_notification == 1) {
            //thông báo ra là đã xóa phòng khi bị phòng được chọn trùng với lịch booking nhưng cần có realtime
            return view('admin.users.carts.index', [
                'title' => 'Thông tin giỏ hàng',
                'checkin' => $checkin,
                'checkout' => $checkout,
                'numberOfDays' => $numberOfDays,
                'numberOfRooms' => $numberOfRooms,
                'guests' => $guests,
                'babies' => $babies,
                'babies_age_txt' => $babies_age_txt,
                'cart' => $cart,
            ])->with('error_notification', 'xin lỗi vì phòng ' . $idErrorRoom . ' đã được đặt');
        }
        return view('admin.users.carts.index', [
            'title' => 'Thông tin giỏ hàng',
            'checkin' => $checkin,
            'checkout' => $checkout,
            'numberOfDays' => $numberOfDays,
            'numberOfRooms' => $numberOfRooms,
            'guests' => $guests,
            'babies' => $babies,
            'babies_age_txt' => $babies_age_txt,
            'cart' => $cart,
        ]);
    }
    public function addProduct(request $request)
    {
        $idRoom = $request->input('idRooms');
        $submitTime = $request->input('submitTime');
        $room = Room::find($idRoom);
        // Lấy danh sách phòng từ session, nếu không có thì tạo mảng mới
        $kind_of_room = $room->outputkindofroom->kind_of_room;
        $cart = Session::get('cart', []); //tạo session cart để lưu phòng
        // Kiểm tra phòng đó có trong giỏ hàng chưa
        // Nếu có rồi sẽ kh thêm phòng đó vào danh sách nữa
        foreach ($cart as $key => $cartItem) {
            if ($idRoom == $cartItem['idRoom'])
                return redirect()->back();
        }
        // Chuyển phòng đó vào trạng thái giữ phòng để tránh bị ng dùng khác đặt trùng
        $room->status = "Đang bận ...";
        $room->save();
        // lưu hết vào mảng
        $cart[] = [
            'idRoom' => $idRoom,
            'room' => $room,
            'name' => $room->name,
            'number' => $room->number,
            'surface' => $room->surface,
            'bed' => $room->bed,
            'capacity' => $room->capacity,
            'kind_of_room' => $kind_of_room,
            'price' => $room->price,
            'submitTime' => $submitTime,
        ];

        // Lưu mảng vào session
        Session::put('cart', $cart);
        // Quay lại trang và thông báo đã lưu thành công
        return redirect()->back()->with('notification', 'Thêm thành công ' . $room->name . ' vào giỏ hàng');

    }


    public function cartCancel()
    {
        //xóa toàn bộ giỏ hàng
        Session::forget('cart');
        return redirect()->back()->with('notification', 'Xóa toàn bộ giỏ hàng thành công!');

    }

    public function cartDetail()
    {

        $checkin = null;
        $checkout = null;
        $numberOfDays = null;
        $numberOfRooms = null;
        $guests = null;
        $babies = null;
        $babies_age_txt = null;
        $cart = Session::get('cart', []);
        $dates = Session::get('dates', []);
        $dates = collect($dates)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        //lấy dữ liệu từ dates để truyền sang cho thẻ index
        foreach ($dates as $date) {
            $checkin = $date->checkin;
            $checkout = $date->checkout;
            $guests = $date->guests;
            $babies = $date->babies;
            if ($babies != 0) {
                if ($date->babies_age == 0) {
                    $babies_age_txt = ", trẻ 0-2 tuổi";
                }
                if ($date->babies_age == 1) {
                    $babies_age_txt = ", trẻ 3-5 tuổi";
                }
                if ($date->babies_age == 2) {
                    $babies_age_txt = ", trẻ 6-11 tuổi";
                }
                if ($date->babies_age == 3) {
                    $babies_age_txt = ", trẻ 12-14 tuổi";
                }
                if ($date->babies_age == 4) {
                    $babies_age_txt = ", trẻ 15-17 tuổi";
                }
            } else {
                $babies_age_txt = null;
            }
            $checkin = Carbon::parse($date->checkin);
            $checkout = Carbon::parse($date->checkout);
            $numberOfDays = $checkin->diffInDays($checkout);
        }

        $cart = collect($cart)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        $numberOfRooms = $cart->count();
        $services = Service::all();
        foreach ($services as $key => $service) {
            if ($service->status == "Tạm dừng") {
                $services->forget($key);
            }
        }
        return view('admin.users.carts.cartdetail', [
            'title' => 'Chi tiết đơn đặt phòng',
            'kindofroom' => Kind_of_room::all(),
            'services' => $services,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'numberOfDays' => $numberOfDays,
            'numberOfRooms' => $numberOfRooms,
            'guests' => $guests,
            'babies' => $babies,
            'babies_age_txt' => $babies_age_txt,
            'cart' => $cart,

        ]);
    }



}
