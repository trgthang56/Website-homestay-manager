<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Extra_service;
use App\Models\Kind_of_room;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerController extends Controller
{

    public function index(){
        $menuData = Kind_of_room::all();
        $kors = Kind_of_room::orderBy('available', 'desc')->limit(4)->get();
        $rooms = [];
        foreach ($kors as $kor) {
            $roomsForThisKor = Room::where('id_kind_of_room', $kor->id)->limit(1)->get();
            if ($roomsForThisKor->isNotEmpty()) {
                $rooms[] = $roomsForThisKor->first();
            }
        }
        return view("admin.customers.homepage")->
        with(['kors'=>$kors,
              'rooms'=>$rooms,
              'menuData'=>$menuData
              
        ]);
    }
    

    public function reloadBooking(){
        $users = User::all();
        $bookings = Booking::all();
        foreach($users as $user) {
            $bookingIds = [];
        
            foreach($bookings as $booking) {
                if($booking->id_customer == $user->id) {
                    $bookingIds[] = $booking->id;
                }
            }
        
            // Chuyển đổi mảng ID của booking thành chuỗi
            $user->id_bill = implode(', ', $bookingIds);
        
            // Lưu lại thông tin của user
            $user->save();
        }
    }
    public function profileIndex(){
        $this->reloadBooking();
        $user = auth()->user();
        $menuData = Kind_of_room::all();
        $bookingIds = explode(', ', $user->id_bill);
        $bookings = Booking::whereIn('id', $bookingIds)->get();
        $countBookings = count($bookings);
        $data = $bookings;
        $data = $data->sortBy('price');
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $data->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $data = new LengthAwarePaginator($currentItems, $data->count(), $perPage); // Tạo một LengthAwarePaginator
        $data->setPath(request()->url());
        return view("admin.customers.profile")->
        with(['data' => $data,
              'countBookings'=>$countBookings,
              'menuData'=>$menuData
              
        ]);
    }
    public function search(Request $request){
        $checkin = $request->date_in;
        $checkout = $request->date_out;
        if($request->date_in){
            $checkin = $request->date_in;
        }else{
            $checkin = $request->checkin;
        }
        if($request->date_out){
            $checkout = $request->date_out;
        }else{
            $checkout = $request->checkout;
        }
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
        //kiểm tra trạng thái phòng theo lịch booking
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
        // loại các phòng "Đã đặt" và "Đang bận ..."
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
        // xóa phòng đã có trong cart ra khỏi danh sách
        // foreach ($cart as $key => $cartItem) {
        //     foreach ($rooms as $index => $room) {
        //         if ($room->id == $cartItem['idRoom']) {
        //             unset($rooms[$index]);
        //         }
        //     }
        // }
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
        $data = new LengthAwarePaginator($currentItems, $data->count(), $perPage);
        $data->setPath(request()->url());
        $menuData = Kind_of_room::all();
        $cart = Session::get('cart', []);
        $cart = collect($cart)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        return view('admin.customers.searched', [
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
            'menuData'=>$menuData,
            'maxPer' => $maxPer,
            'cart'=> $cart
        ], compact('data'));
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
        $customerName = ucwords($customer->name);
        $extraServices = Extra_service::where("id_booking", $booking->id)
        ->whereNotIn("status", [4])
        ->get();
        
        $menuData = Kind_of_room::all();
        return view('admin.customers.billdetail', [
            'kindofroom' => Kind_of_room::all(),
            'serviceData' => Service::all(),
            'menuData'=> $menuData,
            'rooms'=>$rooms,
            'extraServices'=>$extraServices,
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
    public function formatDate($dateString)
    {
        // Chuyển đổi ngày từ định dạng "25 March, 2024" sang "25/03/2024"
        $date = Carbon::createFromFormat('d F, Y', $dateString)->format('Y-m-d');
        
        return $date;
    }
}
