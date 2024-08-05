<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kind_of_room;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
class CustomerCartController extends Controller
{
    public function index(){
        $rooms = Room::all();
        $cart = Session::get('cart', []);

        if (!empty($cart)) { // Kiểm tra nếu giỏ hàng không rỗng
            foreach ($cart as $key => $cartItem) {
                foreach ($rooms as $room) {
                    if ($room->id == $cartItem['idRoom']) { 
                        if ($room->status == 'Đang bận ...' || $room->status == 'Đang thuê' ||  $room->status == 'Đã đặt') {
                            // Xóa phòng khỏi giỏ hàng
                            Session::forget("cart.$key");
                            // Chuyển hướng về trang trước với thông báo lỗi
                            return redirect()->back()->with('error_notification', 'Xin lỗi vì phòng ' . $room->name . ' đã được đặt.');
                        }
                    }
                }
            }
        }
        $bookings = Booking::orderBy('id', 'ASC')->get();
        $cart = Session::get('cart', []);
        $dates = Session::get('dates', []);
        $dates = collect($dates)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        // Kiểm tra nếu $dates không rỗng
        $checkin = now();
        $checkout = now()->addDays(1);
        $checkIn = $checkin->format('Y-m-d');
        $checkOut = $checkout->format('Y-m-d');
        $numberOfDays = 1;
        $numberOfRooms = null;
        $guests = 2;
        $guests_txt= '2 Người lớn mỗi phòng';
        $babies = 0;
        $babies_txt = '0 Trẻ em mỗi phòng'; 
        $babies_age = 0;
        $babies_age_txt = 'từ 0-2 tuổi';
        $numberOfDays = $checkin->diffInDays($checkout);
        if (!empty($dates)) {
            foreach ($dates as $date) {
                $checkin = $date->checkin;
                $checkout = $date->checkout;
                $guests = $date->guests;
                $babies = $date->babies;
                $babies_txt = $date->babies_txt;
                $guests_txt = $date->guests_txt;
                $babies_age_txt = $date->babies_age_txt;
                $babies_age = $date->babies_age;
                if ($babies != 0) {
                    if ($date->babies_age == 0) {
                        $babies_age_txt = ", trẻ 0-2 tuổi";
                    } elseif ($date->babies_age == 1) {
                        $babies_age_txt = ", trẻ 3-5 tuổi";
                    } elseif ($date->babies_age == 2) {
                        $babies_age_txt = ", trẻ 6-11 tuổi";
                    } elseif ($date->babies_age == 3) {
                        $babies_age_txt = ", trẻ 12-14 tuổi";
                    } elseif ($date->babies_age == 4) {
                        $babies_age_txt = ", trẻ 15-17 tuổi";
                    }
                } else {
                    $babies_age_txt = null;
                }
                $checkin = Carbon::parse($date->checkin);
                $checkout = Carbon::parse($date->checkout);
                $numberOfDays = $checkin->diffInDays($checkout);
                $checkIn = $checkin->format('Y-m-d');
                $checkOut = $checkout->format('Y-m-d');
            }
        } else {
            $checkin = now();
            $checkout = now()->addDays(1);
            $checkIn = $checkin->format('Y-m-d');
            $checkOut = $checkout->format('Y-m-d');
            $numberOfDays = 1;
            $numberOfRooms = null;
            $guests = 2;
            $guests_txt= '2 Người lớn mỗi phòng';
            $babies = 0;
            $babies_txt = '0 Trẻ em mỗi phòng'; 
            $babies_age = 0;
            $babies_age_txt = 'từ 0-2 tuổi';
            $numberOfDays = $checkin->diffInDays($checkout);
            $dates[] = [
                'checkin' => $checkin,
                'checkout' => $checkout,
                "guests_txt" => $guests_txt,
                "guests" => $guests,
                "babies_txt" => $babies_txt,
                "babies" => $babies,
                "babies_age_txt" => $babies_age_txt,
                "babies_age" => $babies_age,
    
            ];
            Session::put('dates', $dates);
        }
        foreach($cart as $key => $item){
            $checkRoomBooked = 0;
            foreach($bookings as $booking){
                $check = 0;
                $checkInDate = Carbon::parse($booking->check_in_date);
                $checkOutDate = Carbon::parse($booking->check_out_date);
                if($booking->status == 3 || $booking->status == 2){
                    $checkRoomBooked++;
                }else{
                    if (($checkInDate >= $checkin && $checkInDate <= $checkout) ||
                    ($checkOutDate >= $checkin && $checkOutDate <= $checkout) ||
                    ($checkInDate <= $checkin && $checkOutDate >= $checkout)) {                       
                        if($booking->status == 1){
                            $idRooms = $booking->id_room;
                            $idRooms = explode(', ', $idRooms);
                            foreach($idRooms as $idRoom){
                                if($idRoom == $item['idRoom'] && $check == 0 && $booking->checkInOut ==1){
                                    unset($cart[$key]);
                                    $errorMessage = 'Xin lỗi vì phòng ' . $item['room']->name . ' đã được đặt. Mời bạn chọn phòng khác';
                                    Session::flash('error_notification', $errorMessage);
                                    Session::put('cart', $cart); 
                                }else if($idRoom == $item['idRoom'] && $check == 0 && $booking->checkInOut==0){
                                    unset($cart[$key]);
                                    $errorMessage = 'Xin lỗi vì phòng ' . $item['room']->name . ' đã được đặt. Mời bạn chọn phòng khác';
                                    Session::flash('error_notification', $errorMessage);
                                    Session::put('cart', $cart);
                                }
                            }
                        }
                        if($booking->status == 0){
                            $idRooms = $booking->id_room;
                            $idRooms = explode(', ', $idRooms);
                            foreach($idRooms as $idRoom){
                                if($idRoom == $item['idRoom'] && $check == 0){
                                    unset($cart[$key]);
                                    $errorMessage = 'Xin lỗi vì phòng ' . $item['room']->name . ' đã được đặt. Mời bạn chọn phòng khác';
                                    Session::flash('error_notification', $errorMessage);
                                    Session::put('cart', $cart); 
                                }
                            }
                        }
                    }else{
                        $checkRoomBooked++;
                    }
                }
            }
        }

        $cart = Session::get('cart', []);

        //tạo ra bản sao và chuyển chúng sang dạng collection
        $cart = collect($cart)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        $numberOfRooms = $cart->count();
        $menuData = Kind_of_room::all();
        return view("admin.customers.cart")->with([
            'title' => 'Thông tin giỏ hàng',
            'checkin' => $checkin,
            'checkout' => $checkout,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'numberOfDays' => $numberOfDays,
            'numberOfRooms' => $numberOfRooms,
            'babies_txt'=>$babies_txt,
            'guests_txt'=> $guests_txt,
            'babies_age'=>$babies_age,
            'guests' => $guests,
            'babies' => $babies,
            'babies_age_txt' => $babies_age_txt,
            'cart' => $cart,
            'menuData'=>$menuData,
            'rooms'=>$rooms,
        ]);
    }
    public function cartDetail()
    {
        $rooms = Room::all();
        $cart = Session::get('cart', []);
        if (!empty($cart)) { 
            foreach ($cart as $key => $cartItem) {
                foreach ($rooms as $room) {
                    if ($room->id == $cartItem['idRoom']) {
                        if ($room->status == 'Đang bận ...' || $room->status == 'Đang thuê' ||  $room->status == 'Đã đặt') {
                            Session::forget("cart.$key");
                            return redirect()->back()->with('error_notification', 'Xin lỗi vì phòng ' . $room->name . ' đã được đặt. Mời bạn chọn phòng khác');
                        }
                    }
                }
            }
        }
        $checkin = now();
        $checkout = now()->addDays(1);
        $checkIn = $checkin->format('Y-m-d');
        $checkOut = $checkout->format('Y-m-d');
        // dd($checkin,$checkIn,  $checkOut);
        $bookings = Booking::orderBy('id', 'ASC')->get();

        $cart = Session::get('cart', []);
        $dates = Session::get('dates', []);
        $dates = collect($dates)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        // Kiểm tra nếu $dates không rỗng
        if (!empty($dates)) {
            foreach ($dates as $date) {
                $checkin = $date->checkin;
                $checkout = $date->checkout;
                $guests = $date->guests;
                $babies = $date->babies;
                $babies_txt = $date->babies_txt;
                $guests_txt = $date->guests_txt;
                $babies_age_txt = $date->babies_age_txt;
                $babies_age = $date->babies_age;
                if ($babies != 0) {
                    if ($date->babies_age == 0) {
                        $babies_age_txt = ", trẻ 0-2 tuổi";
                    } elseif ($date->babies_age == 1) {
                        $babies_age_txt = ", trẻ 3-5 tuổi";
                    } elseif ($date->babies_age == 2) {
                        $babies_age_txt = ", trẻ 6-11 tuổi";
                    } elseif ($date->babies_age == 3) {
                        $babies_age_txt = ", trẻ 12-14 tuổi";
                    } elseif ($date->babies_age == 4) {
                        $babies_age_txt = ", trẻ 15-17 tuổi";
                    }
                } else {
                    $babies_age_txt = null;
                }
                $checkin = Carbon::parse($date->checkin);
                $checkout = Carbon::parse($date->checkout);
                $numberOfDays = $checkin->diffInDays($checkout);
                $checkIn = $checkin->format('Y-m-d');
                $checkOut = $checkout->format('Y-m-d');
            }
        } else {
            $checkin = now();
            $checkout = now()->addDays(1);
            $checkIn = $checkin->format('Y-m-d');
            $checkOut = $checkout->format('Y-m-d');
            $numberOfDays = 1;
            $numberOfRooms = null;
            $guests = 2;
            $guests_txt= '2 Người lớn mỗi phòng';
            $babies = 0;
            $babies_txt = '0 Trẻ em mỗi phòng'; 
            $babies_age = 0;
            $babies_age_txt = 'từ 0-2 tuổi';
            $numberOfDays = $checkin->diffInDays($checkout);
            $dates[] = [
                'checkin' => $checkin,
                'checkout' => $checkout,
                "guests_txt" => $guests_txt,
                "guests" => $guests,
                "babies_txt" => $babies_txt,
                "babies" => $babies,
                "babies_age_txt" => $babies_age_txt,
                "babies_age" => $babies_age,
    
            ];
            Session::put('dates', $dates);
        }
        foreach($cart as $key => $item){
            $checkRoomBooked = 0;
            foreach($bookings as $booking){
                $check = 0;
                $checkInDate = Carbon::parse($booking->check_in_date);
                $checkOutDate = Carbon::parse($booking->check_out_date);
                if($booking->status == 3 || $booking->status == 2){
                    $checkRoomBooked++;
                }else{
                    if (($checkInDate >= $checkin && $checkInDate <= $checkout) ||
                    ($checkOutDate >= $checkin && $checkOutDate <= $checkout) ||
                    ($checkInDate <= $checkin && $checkOutDate >= $checkout)) {                       
                        if($booking->status == 1){
                            $idRooms = $booking->id_room;
                            $idRooms = explode(', ', $idRooms);
                            foreach($idRooms as $idRoom){
                                if($idRoom == $item['idRoom'] && $check == 0 && $booking->checkInOut ==1){
                                    unset($cart[$key]);
                                    $errorMessage = 'Xin lỗi vì phòng ' . $item['room']->name . ' đã được đặt. Mời bạn chọn phòng khác';
                                    Session::flash('error_notification', $errorMessage);
                                    Session::put('cart', $cart); 
                                }else if($idRoom == $item['idRoom'] && $check == 0 && $booking->checkInOut==0){
                                    unset($cart[$key]);
                                    $errorMessage = 'Xin lỗi vì phòng ' . $item['room']->name . ' đã được đặt. Mời bạn chọn phòng khác';
                                    Session::flash('error_notification', $errorMessage);
                                    Session::put('cart', $cart);
                                }
                            }
                        }
                        if($booking->status == 0){
                            $idRooms = $booking->id_room;
                            $idRooms = explode(', ', $idRooms);
                            foreach($idRooms as $idRoom){
                                if($idRoom == $item['idRoom'] && $check == 0){
                                    unset($cart[$key]);
                                    $errorMessage = 'Xin lỗi vì phòng ' . $item['room']->name . ' đã được đặt. Mời bạn chọn phòng khác';
                                    Session::flash('error_notification', $errorMessage);
                                    Session::put('cart', $cart); 
                                }
                            }
                        }
                    }else{
                        $checkRoomBooked++;
                    }
                }
            }
        }

        $cart = Session::get('cart', []);
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
        $menuData = Kind_of_room::all();
        return view('admin.customers.cartdetail', [
            'title' => 'Chi tiết đơn đặt phòng',
            'kindofroom' => Kind_of_room::all(),
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'services' => $services,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'numberOfDays' => $numberOfDays,
            'numberOfRooms' => $numberOfRooms,
            'babies_txt'=>$babies_txt,
            'guests_txt'=> $guests_txt,
            'babies_age'=>$babies_age,
            'guests' => $guests,
            'babies' => $babies,
            'babies_age_txt' => $babies_age_txt,
            'cart' => $cart,
            'menuData'=>$menuData,
            'rooms'=>$rooms,
        ]);
    }
}
