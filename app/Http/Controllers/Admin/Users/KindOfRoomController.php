<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kind_of_room;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;



class KindOfRoomController extends Controller
{

    public function index()
    {
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
        $data = Kind_of_room::orderBy('kind_of_room', 'ASC')->get();

        return view('admin.users.kindofrooms.kindofroomlist', ['title' => 'Danh sách loại phòng'], compact('data'));
    }

    function normalizeName($name)
    {
        $lower = Str::lower($name); // Chuyển chuỗi về dạng chữ thường
        $title = ucfirst($lower); // Chuyển chuỗi thành viết hoa chữ cái đầu tiên của mỗi từ
        return $title;
    }

    public function editView($id){
        $data = Kind_of_room::find($id);
        
        return view('admin.users.kindofrooms.kindofroomedit', ['title' => 'Loại '. $data->kind_of_room], compact('data'));
    }
    public function edit(Request $request){
        dd( $request->all());
        $id= $request->id;
        $data = Kind_of_room::find($id);
        $description = $request->input('description');
        $file_path = public_path('kind_of_rooms') . '/' . $data->image;
        $file = $request->roomImage;
        if ($request->has("roomImage")) {
            if ($data->image!=null) {
                $currentFilePath = public_path('kind_of_rooms') . '/' . $data->image;
                unlink($currentFilePath);
                $file = $request->roomImage;
                $ext = $request->roomImage->extension();
                $file_name = time() . '-' . 'image' . "." . $ext;
                $file->move(public_path('kind_of_rooms'), $file_name);
            } else {
                $file = $request->roomImage;
                $ext = $request->roomImage->extension();
                $file_name = time() . '-' . 'image' . "." . $ext;
                $file->move(public_path('kind_of_rooms'), $file_name);
            }
        } else {
            $file_name = $request->image;
        }

        $name = $request->input('name');
        $name = $this->normalizeName($name);

        $data->kind_of_room = $name;
        $data->image = $file_name;
        $data->description = $description;

        // Lưu đối tượng User vào cơ sở dữ liệu
        if ($data->save()) {
            // Trở về trang danh sách nhân viên
            return redirect()->route('kindofroom')->with('notification', 'Đã sửa thành công');
        } else {
            // Trở về trang danh sách nhân viên
            return redirect()->route('kindofroom')->with('error_notification', 'Sửa không thành công');
        };
    }
    public function add(Request $request)
    {
        // Tạo một đối tượng mới
        $data = new Kind_of_room();
        $name = $request->input('name');
        $name = $this->normalizeName($name);
        $available = 0;
        $total = 0;
        $description = $request->input('description');
        $file = $request->roomImage;
        if ($request->has("roomImage")) {
            // Xử lý tệp tin hình ảnh
            $file = $request->roomImage;
            $ext = $request->roomImage->extension();
            $file_name = time() . '-' . 'image' . "." . $ext;
            $file->move(public_path('kind_of_rooms'), $file_name);
            // Lưu tên hình ảnh vào cơ sở dữ liệu cho user mới
            $data->image = $file_name;
        }


        $data->kind_of_room = $name;
        $data->available = $available;
        $data->total = $total;
        $data->image = $file_name;
        $data->description = $description;

        // Lưu đối tượng User vào cơ sở dữ liệu
        if ($data->save()) {
            // Trở về trang danh sách nhân viên
            return redirect()->route('kindofroom')->with('notification', 'Đã thêm loại ' . $name . ' thành công');
        } else {
            // Trở về trang danh sách nhân viên
            return redirect()->route('kindofroom')->with('error_notification', 'Đã thêm loại ' . $name . ' không thành công');
        }
        ;
    }


    public function delete($id)
    {
        $kindofroom = Kind_of_room::find($id);
        if ($kindofroom->image != null) {
            $currentFilePath = public_path('kind_of_rooms') . '/' . $kindofroom->image;
            unlink($currentFilePath);
        }
        $deleteData = DB::table('kind_of_rooms')->where('id', '=', $id)->delete();

        //Kiểm tra lệnh delete để trả về một thông báo
        if ($deleteData) {
            return redirect()->route('kindofroom')->with('notification', 'Đã xóa loại phòng thành công');
        } else {
            return redirect()->route('kindofroom')->with('error_notification', 'Xóa loại phòng không thành công');
        }
    }
}
