<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Kind_of_room;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Session;

class RoomController extends Controller
{
    public function index()
    {
        $data = collect();
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
        $rooms = Room::orderBy('status', 'DESC')->paginate(10);
        if ($keyword = request()->keyword) {
            $rooms = Room::orderBy('status', 'DESC')->where('name', 'like', '%' . $keyword . '%')->paginate(10);
        }
        $data = $rooms;
        return view('admin.users.rooms.roomlist', [
            'title' => 'Danh Sách Phòng',
        ], compact('data'));
    }

    public function editView($id){
        $data = Room::find($id);

        return view('admin.users.rooms.roomedit', 
        [
            'title' => $data->name,
            'kindofrooms' => Kind_of_room::all()
        ], compact('data'));

    }
    function normalizeName($name)
    {
        $lower = Str::lower($name); // Chuyển chuỗi về dạng chữ thường
        $title = ucfirst($lower); // Chuyển chuỗi thành viết hoa chữ cái đầu tiên của mỗi từ
        return $title;
    }

    public function edit(Request $request){
        $id= $request->id;
        $data = Room::find($id);
        $file_path = public_path('rooms') . '/' . $data->image;
        $file = $request->roomImage;
        if ($request->has("roomImage")) {
            if ($data->image!=null) {
                $currentFilePath = public_path('rooms') . '/' . $data->image;
                unlink($currentFilePath);
                $file = $request->roomImage;
                $ext = $request->roomImage->extension();
                $file_name = time() . '-'. $id  .'-' . 'image' . "." . $ext;
                $file->move(public_path('rooms'), $file_name);
            } else {
                $file = $request->roomImage;
                $ext = $request->roomImage->extension();
                $file_name = time() . '-'. $id  .'-' . 'image' . "." . $ext;
                $file->move(public_path('rooms'), $file_name);
            }
        } else {
            $file_name = $data->image;
        }
        // dd($request->all());
        $name = $request->input('name');
        
        $data->id_kind_of_room = $request->input('kind_of_room');
        $data->image = $file_name;
        $data->name = $name;
        $data->surface = $request->input('surface');
        $data->number = $request->input('number');
        $data->capacity = $request->input('capacity');
        $data->bed = $request->input('bed');
        $data->price = $request->input('price');
        $data->room_amenity = $request->input('room_amenity');
        $data->bathroom_amenity = $request->input('bathroom_amenity');
        $data->description = $request->input('description');
        if($data->save()){
            return redirect()->route('roomlist')->with('notification', 'Đã sửa phòng #'.$id.' thành công');
        }else{
            return redirect()->back()->with('notification', 'Sửa phòng #'.$id.' không thành công');
        }
        // Trở về trang danh sách nhân viên

    }

    public function detailView($id){

        $data = Room::find($id);
        $bookedList = Booking::where('id_room', 'like', '%' . $id . '%')->get();
        $bookedDate = $bookedList;
        $bookedList = $bookedList->sortByDesc('id');
        $perPage = 5;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $bookedList->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $bookedList = new LengthAwarePaginator($currentItems, $bookedList->count(), $perPage); // Tạo một LengthAwarePaginator
        $bookedList->setPath(request()->url());
        return view('admin.users.rooms.roomdetail', 
        [   'bookedDate' => $bookedDate,
            'bookedList' => $bookedList,
            'title' => 'thông tin '. $data->name,
            'kindofrooms' => Kind_of_room::all()
        ], compact('data'));

    }
    public function checkRoom(request $request){

        $checkin = $request->checkin;
        $checkout = $request->checkout;
        // dd($request->all());
        if ($checkin == null) {
            $checkin = now()->format('Y-m-d');
        }

        if ($checkout == null) {
            $checkout = now()->format('Y-m-d');
        }
        $data = collect();
        $rooms = Room::all();
        foreach ($rooms as $room) {
            $room->status = 'Trống';
            $room->save();
        }
        $bookings = Booking::all();
        $bookingsCount = $bookings->count();
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
            }
        }
        $rooms = Room::orderBy('status', 'asc')->paginate(10);
        $data = $rooms;

        return view('admin.users.rooms.roomcheck', [
            'title' => 'Danh sách tìm kiếm',
            'checkin' => date('Y-m-d', strtotime($checkin)),
            'checkout' => date('Y-m-d', strtotime($checkout)),
        ], compact('data'));
    }
    public function indexRoomAdd(){
        return view('admin.users.rooms.roomadd', [
            'title' => 'Thêm phòng',
            'kindofroom' => Kind_of_room::all()
        ]);
    }

    public function add(Request $request){
        $name = (string) $request->input('name');
        $number = (int) $request->input('number');
        $surface = (int) $request->input('surface');
        $capacity = (int) $request->input('capacity');
        $bed = (int) $request->input('bed');
        $id_kind_of_room = $request->input('kind_of_room');
        $price = (double) $request->input('price');
        $room_amenity = (string) $request->input('room_amenity');
        $bathroom_amenity = (string) $request->input('bathroom_amenity');
        $description = (string) $request->input('description');

        // Tạo một đối tượng User mới
        $data = new Room();
        $data->name = $name;
        $data->number = $number;
        $data->surface = $surface;
        $data->capacity = $capacity;
        $data->bed = $bed;
        $data->id_kind_of_room = $id_kind_of_room;
        $data->status = 'Trống';
        $data->price = $price;
        $data->room_amenity = $room_amenity;
        $data->bathroom_amenity = $bathroom_amenity;
        $data->description = $description;

        // Lưu đối tượng User vào cơ sở dữ liệu
        $data->save();
        // Trở về trang danh sách nhân viên
        return redirect()->route('roomadd')->with('notification', 'Đã thêm phòng thành công');
    }


    public function delete($id){
        $deleteData = DB::table('rooms')->where('id', '=', $id)->delete();
        //Kiểm tra lệnh delete để trả về một thông báo
        if ($deleteData) {
            return redirect()->route('roomlist')->with('notification', 'Xóa thành công');
        } else {
            return redirect()->route('roomlist')->with('error_notification', 'Xóa không thành công');
        }
    }

}
