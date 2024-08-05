<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kind_of_room;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CustomerKORController extends Controller
{
    public function index()
    {
        $menuData = Kind_of_room::all();
        $rooms = Room::all();
        $data = Kind_of_room::orderBy('kind_of_room', 'ASC')->get();
        $data = Kind_of_room::orderBy('kind_of_room', 'ASC')->get();
        $rooms = Room::all();
        $bookings = Booking::all();
        $bookingsCount = $bookings->count();
        foreach ($rooms as $room) {
            $checkRoomBooked = 0;
            foreach ($bookings as $booking) {
                $check = 0;
                $checkInDate = Carbon::parse($booking->check_in_date);
                $checkOutDate = Carbon::parse($booking->check_out_date);
                $now = Carbon::now();
                if ($now->between($checkInDate, $checkOutDate)) {
                    if($booking->status == 3){
                        $idRooms = $booking->id_room;
                        $idRooms = explode(', ', $idRooms);
                        foreach($idRooms as $idRoom){
                            if($idRoom == $room->id && $check == 0){
                                $room->status = 'Trống';
                                $room->save();
                                $check++;
                            }
                        }
                    }
                    if($booking->status == 2){
                        $idRooms = $booking->id_room;
                        $idRooms = explode(', ', $idRooms);
                        foreach($idRooms as $idRoom){
                            if($idRoom == $room->id && $check == 0){
                                $room->status = 'Trống';
                                $room->save();
                                $check++;
                            }
                        }
                    }
                    if($booking->status == 1){
                        $idRooms = $booking->id_room;
                        $idRooms = explode(', ', $idRooms);
                        foreach($idRooms as $idRoom){
                            if($idRoom == $room->id && $check == 0 && $booking->checkInOut ==1){
                                $room->status = 'Đang thuê';
                                $room->save();
                                $check++;
                            }else if($idRoom == $room->id && $check == 0 && $booking->checkInOut==0){
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
            if($checkRoomBooked==$bookingsCount){
                $room->status = 'Trống';
                $room->save();
            }
        }
        //kiểm tra phòng trống và sửa lại db
        foreach ($data as $item) {
            $count_available = 0;
            $count_total = 0;
            $kind_of_room = Kind_of_room::find($item['id']);
            $check = $kind_of_room->id;
            foreach ($rooms as $room) {
                if ($check == $room->id_kind_of_room) {
                    $count_total++;
                }
                if ($check == $room->id_kind_of_room && $room->status == "Trống")
                    $count_available++;
            }
            $kind_of_room->available = $count_available;
            $kind_of_room->total = $count_total;
            $kind_of_room->save();
        }
        $kors = Kind_of_room::orderBy('id', 'asc')->where('available', '!=', 0)->get();
        $rooms = [];
        foreach ($kors as $kor) {
            $roomsForThisKor = Room::where('id_kind_of_room', $kor->id)->limit(1)->get();
            if ($roomsForThisKor->isNotEmpty()) {
                $rooms[] = $roomsForThisKor->first();
            }
        }
        
        return view("admin.customers.kindofroomlist")->
        with(['menuData'=>$menuData,
              'rooms'=>$rooms,
              'kors'=>$kors
        ]);
    }

}
