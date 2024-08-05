<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Extra_service;
use App\Models\Room;
use App\Models\User;
use App\Models\Service;
use App\Models\Kind_of_room;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


class BookingController extends Controller
{
    public function index(){
        $data = Booking::with('outputkindofroom')->whereIn('status', [2, 3])->orderBy('id', 'desc')->paginate(10);
        if ($keyword = request()->keyword) {
            $data = Booking::orderBy('status', 'ASC')->whereIn('status', [2, 3])->where('phone', '=', $keyword)->orWhere('id', '=', $keyword)->paginate(10);
        }
        $countData = count(@$data);
        return view(
            'admin.users.bookings.bookinglist',[
                'title' => 'Danh sách hóa đơn đã hoàn thành',
                'countData' => $countData
            ],
            compact('data')
        );

    }
    public function bookingNewView(){
        $data = Booking::with('outputkindofroom')->whereIn('status', [0, 1])->orderBy('id', 'desc')->paginate(10);
        if ($keyword = request()->keyword) {
            $data = Booking::orderBy('status', 'ASC')->whereIn('status', [0, 1])->where('phone', '=', $keyword)->orWhere('id', '=', $keyword)->paginate(10);
        }
        $countData = count(@$data);
        return view(
            'admin.users.bookings.bookinglist',
            [
                'title' => 'Danh sách hóa đơn mới',
                'countData' => $countData
            ],
            compact('data')
        );
    }

    public function detailView($id){
        $booking = Booking::find($id);
        $rooms = Room::all();
        $id_room = $booking->id_room;
        $id_room = explode(', ', $id_room);
        $rooms = Room::whereIn('id', $id_room)->get();

        $id_user = $booking->id_user;
        $user = User::find($id_user);
        $userName='';
        if($id_user!=null){
            $userName = $user->name;
        }
        $guestArray = json_decode($booking->guest_detail, true);


        $serviceDetail = $booking->id_service;        
        $serviceResult = $this->processDetails($serviceDetail);
        $roomServiceIds = $serviceResult['id'];
        $combinedIdServices = $serviceResult['info'];
        $servicesPrice = [];
        $servicesInfo = [];
        //chuyển từ dạng chuỗi id -> chuỗi text để chuyển sang view
        foreach ($combinedIdServices as $item) {
            $serviceIds = explode(', ', $item);
            $serviceNames = [];
            $servicesPriceElement = [];
            foreach ($serviceIds as $serviceId) {
                $service = Service::find($serviceId);
                if ($service) {
                    $serviceNames[] = $service->name;
                    $servicesPriceElement[] = $service->price;
                }
            }
        
            $servicesInfo[] = implode(', ', $serviceNames);
            $servicesPrice[] = implode(', ', $servicesPriceElement);
        }
        $servicesInfo = $this->missingError($id_room, $servicesInfo);

        $serviceTotalPrices = [];
        foreach($servicesPrice as $item) {
            // Tách các số từ chuỗi dựa trên dấu phẩy và lưu vào một mảng
            $numbers = explode(", ", $item);
            
            // Chuyển các chuỗi số thành số nguyên và tính tổng
            $sum = array_sum(array_map('intval', $numbers));
        
            // Lưu kết quả vào mảng kết quả
            $serviceTotalPrices[] = $sum;
        }
        $serviceTotalPrices = $this->missingError($id_room, $serviceTotalPrices);
        $requestDetail = $booking->special_requirement;
        $requestResult = $this->processDetails($requestDetail);
        $requestIds = $requestResult['id'];
        $requestInfo = $requestResult['info'];
        $requestInfo = $this->missingError($id_room, $requestInfo);
        // dd($requestResult);
        // dd($rooms, $roomServiceIds, $servicesPrice, $serviceTotalPrices, $serviceResult['info'], $servicesInfo, $requestResult, $requestInfo);
        // dd($combinedIdRooms, $servicesInfo);
        $customer = User::find($booking->id_customer);
        if($customer != null)
            $customerName = ucwords($customer->name);
        else 
            $customerName= '';

        return view('admin.users.bookings.bookingdetail', [
            'title' => 'Chi tiết hóa đơn đặt phòng',
            'kindofroom' => Kind_of_room::all(),
            'services' => Service::all(),
            'rooms'=>$rooms,
            'servicesInfo' => $servicesInfo,
            'customerName' => $customerName,
            'requestInfo' => $requestInfo,
            'serviceTotalPrices'=> $serviceTotalPrices,
            'user' => $user,
            'userName' => $userName,
            'guestArray'=> $guestArray,
            'booking' => $booking,
        ]);
    }

    function missingError($roomIds, $data){
        $emptyRequests = array_fill(0, count($roomIds), "");

        // Duyệt qua mảng id các phòng
        foreach ($roomIds as $index => $roomId) {
            // Nếu không có yêu cầu cho phòng này, thêm vào mảng rỗng
            if (!isset($roomRequests[$index])) {
                $emptyRequests[$index] = "";
            }
        }

        // Kết hợp mảng yêu cầu với mảng rỗng nếu cần thiết
        $result = array_map(function($request, $emptyRequest) {
            return $request != "" ? $request : $emptyRequest;
        }, $data, $emptyRequests);
        return $result;
    }
    function processDetails($serviceDetail){
        $serviceDetail = preg_replace('/\/+/', '/', $serviceDetail);
        $serviceDetail = explode('/, ', $serviceDetail);
        $roomIds = [];
        $serviceIds = [];
        foreach ($serviceDetail as $item) {
            // Sử dụng preg_match để lấy ra số và chuỗi '6, 9, 10' từ mỗi mục
            preg_match('/\/(\d+)\/\'(.*?)\'/', $item, $matches);
            
            // Nếu có kết quả từ preg_match
            if (count($matches) === 3) {
                // Thêm số vào mảng $roomIds
                $roomIds[] = $matches[1];
                // Thêm chuỗi '6, 9, 10' vào mảng $serviceIds
                $serviceIds[] = $matches[2];
            }
        }
        $combinedIdRooms = [];
        $combinedIdServices = [];
        foreach ($roomIds as $key => $roomId) {
            if (!in_array($roomId, $combinedIdRooms)) {
                $combinedIdRooms[] = $roomId;
                $combinedIdServices[] = $serviceIds[$key];
            } else {
                $index = array_search($roomId, $combinedIdRooms);
                $combinedIdServices[$index] .= ", " . $serviceIds[$key];
            }
        }
        return [
            'id' => $combinedIdRooms,
            'info' => $combinedIdServices
        ];
    }
    public function editView($id){
        
        $booking = Booking::find($id);
        $rooms = Room::all();
        $id_room = $booking->id_room;
        $id_room = explode(', ', $id_room);
        $rooms = Room::whereIn('id', $id_room)->get();

        $id_user = $booking->id_user;
        $user = User::find($id_user);
        $userName='';
        if($id_user!=null){
            $userName = $user->name;
        }
        $guestArray = json_decode($booking->guest_detail, true);


        $serviceDetail = $booking->id_service;        
        $serviceResult = $this->processDetails($serviceDetail);
        $roomServiceIds = $serviceResult['id'];
        $combinedIdServices = $serviceResult['info'];
        $servicesPrice = [];
        $servicesInfo = [];
        //chuyển từ dạng chuỗi id -> chuỗi text để chuyển sang view
        foreach ($combinedIdServices as $item) {
            $serviceIds = explode(', ', $item);
            $serviceNames = [];
            $servicesPriceElement = [];
            foreach ($serviceIds as $serviceId) {
                $service = Service::find($serviceId);
                if ($service) {
                    $serviceNames[] = $service->name;
                    $servicesPriceElement[] = $service->price;
                }
            }
        
            $servicesInfo[] = implode(', ', $serviceNames);
            $servicesPrice[] = implode(', ', $servicesPriceElement);
        }
        $servicesInfo = $this->missingError($id_room, $servicesInfo);

        $serviceTotalPrices = [];
        foreach($servicesPrice as $item) {
            // Tách các số từ chuỗi dựa trên dấu phẩy và lưu vào một mảng
            $numbers = explode(", ", $item);
            
            // Chuyển các chuỗi số thành số nguyên và tính tổng
            $sum = array_sum(array_map('intval', $numbers));
        
            // Lưu kết quả vào mảng kết quả
            $serviceTotalPrices[] = $sum;
        }
        $serviceTotalPrices = $this->missingError($id_room, $serviceTotalPrices);
        $requestDetail = $booking->special_requirement;
        $requestResult = $this->processDetails($requestDetail);
        $requestIds = $requestResult['id'];
        $requestInfo = $requestResult['info'];
        $requestInfo = $this->missingError($id_room, $requestInfo);
        // dd($requestResult);
        // dd($rooms, $roomServiceIds, $servicesPrice, $serviceTotalPrices, $serviceResult['info'], $servicesInfo, $requestResult, $requestInfo);
        // dd($combinedIdRooms, $servicesInfo);
        $customer = User::find($booking->id_customer);
        if($customer != null)
            $customerName = ucwords($customer->name);
        else 
            $customerName= '';
        $extraServices = Extra_service::where("id_booking", $booking->id)
        ->whereNotIn("status", [4])
        ->get();
        
        //    dd($extraServices);
        return view('admin.users.bookings.bookingedit', [
            'title' => 'Chi tiết hóa đơn đặt phòng',
            'kindofroom' => Kind_of_room::all(),
            'serviceData' => Service::all(),
            'extraServices'=>$extraServices,
            'customerName'=> $customerName,
            'rooms'=>$rooms,
            'servicesInfo' => $servicesInfo,
            'requestInfo' => $requestInfo,
            'serviceTotalPrices'=> $serviceTotalPrices,
            'user' => $user,
            'userName' => $userName,
            'guestArray'=> $guestArray,
            'booking' => $booking,
        ]);
    }


    public function cancelBooking($id){   
        $booking = Booking::find($id);
        $booking->status = 3;
        
        $id_room = $booking->id_room;
        $arrayIdRoom = explode(", ",$id_room);
        foreach($arrayIdRoom as $id){
            $room = Room::where('id', $id)->first();
            $room->status = 'Trống';
            $room->save();
        }
        if($booking->save()) return redirect()->back()->with('notification','Hủy đơn #'. $booking->id . ' thành công!');
        else  return redirect()->back()->with('error_notification','Thao tác hủy không thành công!');
       
    }

    // public function booking(Request $request)
    // {

    //     $id_customer = Auth::user()->id;
    //     $id_room = $request->input('id_room');
    //     $total = $request->input('total_price');

    //     $selectedValues = $request->input('serviceCheckbox');
    //     //kiểm tra mảng nhận vào có rỗng hay không
    //     if (empty($selectedValues)) {
    //         $serviceCheckbox = 'Trống';
    //     } else {
    //         $serviceCheckbox = implode(', ', $selectedValues);
    //     }

    //     $selectedrq = $request->input('rqCheckbox');
    //     //tương tự
    //     if (empty($selectedrq)) {
    //         $checkboxrq = 'Trống';
    //     } else {
    //         $checkboxrq = implode(', ', $selectedrq);
    //     }
    //     $isForOther = $request->input('forother');
    //     // kiểm tra dữ liệu nhập vào
    //     $bookings = Booking::all();
    //     foreach ($bookings as $booking) {
    //         $created_checkIn = $booking->check_in_date;
    //         $created_checkOut = $booking->check_out_date;
    //     }
    //     $validatedData = $request->validate([
    //         'phone' => 'required|string',
    //         'email' => 'required|email',
    //         'number_of_guest' => 'required|numeric|min:1',
    //         'check_in_date' => 'required|date',
    //         'check_out_date' => 'required|date|after:check_in_date',
    //     ]);

    //     // Xác định trường 'name' dựa trên lựa chọn của người dùng
    //     $name = ($isForOther == 'on') ? $request->input('otherName') : $request->input('name');
    //     $formattedName = ucwords(Str::lower($name));
    //     if ($isForOther == 'on')
    //         $set_for_other = 1;
    //     else
    //         $set_for_other = 0;
    //     $number_of_room = 1;
    //     $status = 0;
    //     $checkInOut = 0;
    //     $created_at = now();
    //     $updated_at = now();

    //     // Tạo mơ sở dữ liệu mới
    //     $booking = Booking::create([
    //         'id_customer' => $id_customer,
    //         'id_room' => $id_room,
    //         'id_service' => $serviceCheckbox,
    //         'set_for_other' => $set_for_other,
    //         'name' => $formattedName,
    //         'phone' => $validatedData['phone'],
    //         'email' => $validatedData['email'],
    //         'number_of_guest' => $validatedData['number_of_guest'],
    //         'number_of_room' => $number_of_room,
    //         'total' => $total,
    //         'special_requirement' => $checkboxrq,
    //         'check_in_date' => $validatedData['check_in_date'],
    //         'check_out_date' => $validatedData['check_out_date'],
    //         'status' => $status,
    //         'checkInOut' => $checkInOut,
    //         'created_at' => $created_at,
    //         'updated_at' => $updated_at,
    //     ]);

    //     if ($booking) {
    //         // Lưu trạng thái của phòng sau khi booking thành công
    //         Room::where('id', $id_room)->update(['status' => 'Chờ xác nhận']);
    //         $room = Room::where('id', $id_room)->first();
    //         $room_name = $room->name;
    //         // Redirect hoặc trả về response thành công
    //         return redirect()->route('bookinglist')->with('notification', $room_name . ' đã được book');
    //     } else {
    //         // Xử lý khi có lỗi trong quá trình booking (nếu cần)
    //         // Redirect hoặc trả về response thất bại

    //         return redirect()->back()->with('error_notification', 'Booking không thành công!');
    //     }


    //     // Redirect or return a response based on the result
    //     return redirect()->route('bookinglist');
    // }

    public function acceptBooking(Request $request)
    {
        // Lấy dữ liệu từ request
        $id = $request->input('id');
        $id_user = $request->input('id_user');
        $id_room = $request->input('id_room');

        // Tìm hóa đơn dựa trên ID và người dùng
        $booking = Booking::where('id', $id)->first();
        $room = Room::where('id', $id_room)->first();

        if ($booking) {
            // Nếu tìm thấy hóa đơn, cập nhật trạng thái hoặc thực hiện các thao tác cần thiết
            $booking->id_user = auth()->user()->id;
            $booking->accepted_date = now();
            $booking->status = 1; // Sửa lại trạng thái theo yêu cầu của bạn
            $booking->save();

            // Redirect hoặc trả về thông báo thành công
            return redirect()->back()->with('notification', 'Mã đơn ' . $booking->id . ' đã được xác nhận thành công!');
        }


        // Nếu không tìm thấy hóa đơn
        return redirect()->back()->with('error_notification', 'Xác nhận không thành công!');
    }

    public function completeBooking(Request $request)
    {
        // Lấy dữ liệu từ request
        $id = $request->input('id');
        $id_room = $request->input('id_room');

        // Tìm hóa đơn dựa trên ID và người dùng
        $booking = Booking::where('id', $id)->first();
        $room = Room::where('id', $id_room)->first();

        if ($booking) {
            // Nếu tìm thấy hóa đơn, cập nhật trạng thái hoặc thực hiện các thao tác cần thiết
            $booking->status = 2; // Sửa lại trạng thái theo yêu cầu của bạn
            $booking->completed_date = now();
            $booking->id_user = auth()->user()->id;
            $booking->save();

            // Redirect hoặc trả về thông báo thành công
            return redirect()->back()->with('notification', 'Mã đơn ' . $booking->id . ' đã được hoàn thành');
        }


        // Nếu không tìm thấy hóa đơn
        return redirect()->back()->with('error_notification', 'Xác nhận hoàn thành không thành công!');
    }
    public function paidByCash(Request $request){
        $id = $request->id;
        $booking = Booking::find($id);
        $booking->payment_status =1;
        $booking->payment_time=now();
        $booking->save();
        return redirect()->back()->with('notification', 'Xác nhận thanh toán thành công!');
    }

    public function checkIn(Request $request)
    {
        $id = $request->id;
        $booking = Booking::find($id);
        $booking->checkInOut = 1;
        $booking->checkIn_time = now();
        $booking->save();
        return redirect()->back()->with('notification', 'Xác nhận Check-In thành công!');
    }

    public function delete($id)
    {
        $booking = Booking::findOrFail($id);
        $temp = $booking->id;
        $booking->delete();

        // Thực hiện các hành động khác nếu cần

        return redirect()->route('bookinglist')->with('notification', 'Đã xóa đơn có mã ' . $temp . ' thành công');
    }

}
