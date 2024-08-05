<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function index()
    {
        
        $data = 1;
        // $data = Room::with('outputkindofroom')->orderBy('status', 'DESC')->paginate(10);
        // if($keyword = request()->keyword){
        //     $data = Room::orderBy('status', 'DESC')->where('name', 'like', '%'.$keyword.'%')->paginate(10);
        // }
        return view('admin.users.search.index', [
            'title' => 'Tìm kiếm phòng',
        ], compact('data'));
    }

    public function search(request $request)
    {
        if ($request->babies == 0) {
            $totalGuests = $request->guests;
        } else {
            $totalGuests = $request->guests + $request->babies;
        }
        $checkin = $request->checkin;
        $checkout = $request->checkout;
        // dd($checkin, $checkout);

        if ($checkin == null) {
            $checkin = now()->format('Y-m-d');
            $checkout = now()->addDay()->format('Y-m-d');
        } else if ($checkout == null) {
            $checkout = strtotime($checkin); // Chuyển đổi $checkin sang timestamp
            $checkout = strtotime('+1 day', $checkout); // Cộng thêm 1 ngày vào $checkout
            $checkout = date('Y-m-d', $checkout);
        }

        $data = collect();
        $rooms = Room::all();
        foreach ($rooms as $room) {
            $room->status = 'Trống';
            $room->save(); // Lưu thay đổi vào cơ sở dữ liệu
        }
        $bookings = Booking::all();
        $bookingsCount = $bookings->count();

        $dates = Session::get('dates', []);
        $cart = Session::get('cart', []); //lấy dữ liệu từ cart để kiểm tra

        if ($dates == null) {
            $dates[] = [
                'checkin' => $checkin,
                'checkout' => $checkout,
                "guests_txt" => $request->guests_txt,
                "guests" => $request->guests,
                "babies_txt" => $request->babies_txt,
                "babies" => $request->babies,
                "babies_age_txt" => $request->babies_age_txt,
                "babies_age" => $request->babies_age,

            ];
            Session::put('dates', $dates);
        } else {
            foreach ($dates as $key => $date) {
                if (
                    $checkin != $date['checkin'] ||
                    $checkout != $date['checkout'] ||
                    $request->guests != $date['guests'] ||
                    $request->babies != $date['babies'] ||
                    $request->babies_age != $date['babies_age']
                ) {
                    Session::forget('dates');
                    $dates[] = [
                        'checkin' => $checkin,
                        'checkout' => $checkout,
                        "guests_txt" => $request->guests_txt,
                        "guests" => $request->guests,
                        "babies_txt" => $request->babies_txt,
                        "babies" => $request->babies,
                        "babies_age_txt" => $request->babies_age_txt,
                        "babies_age" => $request->babies_age,
                    ];
                    Session::put('dates', $dates);
                    break;
                }
            }
        }
        //kiểm tra phòng nào còn đang trống
        foreach ($rooms as $room) {
            $checkRoomBooked = 0;
            foreach ($bookings as $booking) {
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
                                if($idRoom == $room->id && $check == 0){
                                    $room->status = 'Đã đặt';
                                    $room->save();
                                    $check++;
                                }
                            }
                        }
                        if($booking->status == 0){
                            $idRooms = $booking->id_room;
                            $idRooms = explode(', ', $idRooms);
                            foreach($idRooms as $idRoom){
                                if($idRoom == $room->id && $check == 0){
                                    $room->status = 'Đang bận ...';
                                    $room->save();
                                    $check++;
                                }
                            }
                        }
                    }else{
                        $checkRoomBooked++;
                    }

                }
            }
        }
            
        // Kiểm tra phòng trống
        $rooms = $rooms->reject(function ($datum) {
            return $datum->status == "Đang bận ..." || $datum->status == "Đã đặt";
        });
        // Kiểm tra xem giá của phòng có nằm trong khoảng từ min_budget đến max_budget không
        $rooms = $rooms->reject(function ($datum) use ($request) {
            return $datum->price < $request->min_budget || $datum->price > $request->max_budget;
        });
        $rooms = $rooms->reject(function ($datum) use ($request) {
            $numberOfGuest = $request->guests + $request->babies;
            return $datum->capacity < $numberOfGuest;
        });
        foreach ($cart as $key => $cartItem) {
            foreach ($rooms as $index => $room) {
                if ($room->id == $cartItem['idRoom']) {
                    unset($rooms[$index]);
                }
            }
        }
        $minPer = ($request->min_budget / 2500000) * 100;
        $maxPer = 100 - (($request->max_budget / 2500000) * 100);
        if ($maxPer < 0) {
            $maxPer = 0;
        }
        if ($minPer < 0) {
            $minPer = 0;
        }
        $min_budget = $request->min_budget;
        if ($request->min_budget < 0) {
            $min_budget = 0;
        }
        
        $data = $rooms;
        $data = $data->sortBy('price');
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $data->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $data = new LengthAwarePaginator($currentItems, $data->count(), $perPage); // Tạo một LengthAwarePaginator
        $data->setPath(request()->url());
        return view('admin.users.search.searchedrooms', [
            'title' => 'Danh sách tìm kiếm',
            'checkin' => date('Y-m-d', strtotime($checkin)),
            'checkout' => date('Y-m-d', strtotime($checkout)),
            "guests_txt" => $request->guests_txt,
            "guests" => $request->guests,
            "babies_txt" => $request->babies_txt,
            "babies" => $request->babies,
            "babies_age_txt" => $request->babies_age_txt,
            "babies_age" => $request->babies_age,
            'rooms' => $request->rooms,
            'min_budget' => $min_budget,
            'max_budget' => $request->max_budget,
            'minPer' => $minPer,
            'maxPer' => $maxPer
        ], compact('data'));
    }

}
