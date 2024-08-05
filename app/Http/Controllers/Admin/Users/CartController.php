<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kind_of_room;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;


class CartController extends Controller
{
    public function updates(Request $request){{
        $dates = Session::get('dates', []);

        // dd($request->all());
        $dates[] = [
            'checkin' => $request->checkin,
            'checkout' => $request->checkout,
            "guests_txt" => $request->guests_txt,
            "guests" => $request->guests,
            "babies_txt" => $request->babies_txt,
            "babies" => $request->babies,
            "babies_age_txt" => $request->babies_age_txt,
            "babies_age" => $request->babies_age,

        ];
        Session::put('dates', $dates);
        $numberOfGuest = $request->guests + $request->babies;
        $cart = Session::get('cart', []);
        if (!empty($cart)) {
            foreach ($cart as $key => $cartItem) {
                if ($cartItem['capacity'] < $numberOfGuest) {
                    Session::forget("cart.$key");
                    return redirect()->back()->with('error_notification', 'Xin lỗi phòng ' . $cartItem['capacity'] . ' đã xóa vì có sức chứa ít hơn so với yêu cầu');
                }
            }
        }
        return redirect()->back();
    }}
    public function index()
    {
        $rooms = Room::all();
        $cart = Session::get('cart', []);

        if (!empty($cart)) { // Kiểm tra nếu giỏ hàng có phòng
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
        $numberOfGuest = $guests + $babies;
        $cart = Session::get('cart', []);
        if (!empty($cart)) {
            foreach ($cart as $key => $cartItem) {
                if ($cartItem['capacity'] < $numberOfGuest) {
                    Session::forget("cart.$key");
                    Session::flash('error_notification', 'Xin lỗi phòng ' . $cartItem['capacity'] . ' đã xóa vì có sức chứa ít hơn so với yêu cầu');
                }
            }
        }
        //tạo ra bản sao và chuyển chúng sang dạng collection
        $cart = collect($cart)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        $numberOfRooms = $cart->count();
        
        return view('admin.users.carts.index', [
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
        ]);
    }
    public function addProduct(request $request)
    {
        $idRoom = $request->input('idRooms');
        $submitTime = $request->input('submitTime');
        $room = Room::find($idRoom);
        $kind_of_room = $room->outputkindofroom->kind_of_room;
        $cart = Session::get('cart', []);   
        // Kiểm tra phòng đó có trong giỏ hàng chưa
        // Nếu có rồi sẽ kh thêm phòng đó vào danh sách nữa
        foreach ($cart as $key => $cartItem) {
            if ($idRoom == $cartItem['idRoom'])
                return redirect()->back();
        }
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
    public function cartDeleteItem(Request $request){
        $idRoom = $request->input('idRooms');

        $cart = Session::get('cart', []); // Lấy mảng session cart
        foreach ($cart as $key => $cartItem) {
            if ($idRoom == $cartItem['idRoom']) {
                Session::forget('cart.' . $key); // Xóa phần tử khỏi session
            }
        }

        // Sau khi xóa, reset lại các khóa của mảng session
        Session::put('cart', array_values(Session::get('cart', [])));

        return redirect()->back()->with('notification', 'Đã xóa khỏi giỏ hàng');

    }

    public function cartCancel()
    {
        //xóa toàn bộ giỏ hàng
        Session::forget('cart');
        return redirect()->back()->with('notification', 'Xóa toàn bộ giỏ hàng thành công!');

    }

    public function cartDetail()
    {
        $rooms = Room::all();
        $cart = Session::get('cart', []);
        if (!empty($cart)) { // Kiểm tra nếu giỏ hàng có phòng
            foreach ($cart as $key => $cartItem) {
                foreach ($rooms as $room) {
                    if ($room->id == $cartItem['idRoom']) {
                        if ($room->status == 'Đang bận ...' || $room->status == 'Đang thuê' ||  $room->status == 'Đã đặt') {
                            // Xóa phòng khỏi giỏ hàng
                            Session::forget("cart.$key");
                            // Chuyển hướng về trang trước với thông báo lỗi
                            return redirect()->back()->with('error_notification', 'Xin lỗi vì phòng ' . $room->name . ' đã được đặt. Mời bạn chọn phòng khác');
                        }
                    }
                }
            }
        }
        
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


        $cart = collect($cart)->map(function ($item) {
            return is_array($item) ? (object) $item : $item;
        });
        $numberOfRooms = $cart->count();
        $cart1 = Session::get('cart', []);
        $services = Service::where('status', '!=', 'Tạm dừng')->where('type', 'like', '%Đặt trước%')->get();
        return view('admin.users.carts.cartdetail', [
            'title' => 'Chi tiết đơn đặt phòng',
            'kindofroom' => Kind_of_room::all(),
            'services' => $services,
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
            'cart1' => $cart1,

        ]);
    }

    public function vnpay_payment(request $request){
        $cart = Session::get('cart', []);
        $bookings = Booking::orderBy('id', 'ASC')->get();
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
        // Lưu dữ liệu vào session
        $request->session()->put('billData', $request->all());
        $id = strval(Carbon::now()->timestamp);//lấy id theo thời gian thực
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = 'http://127.0.0.1:8000/admin/return/vnpay';
        $vnp_TmnCode = "82S4L2CU";//Mã website tại VNPAY 
        $vnp_HashSecret = "HAAVZMKBVQFNBROIWPCJIXLJJRYOMDBE"; //Chuỗi bí mật

        $vnp_TxnRef = $id; 
        $vnp_OrderInfo = (!empty($request->decription))? $request->decription: 'Thanh Toán đơn đặt phòng #'.$id.' qua VNPay';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $request->totalPrice * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );
        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'url' => $vnp_Url 
        );
        
        if ($returnData['code'] == '00') { 
            return redirect()->away($returnData['url']);
        }
        return redirect()->route('cart.detail')->with('error', 'Đặt hàng không thành công. Hãy thử lại');
    }

    public function cash_payment(request $request){
        $cart = Session::get('cart', []);
        $bookings = Booking::orderBy('id', 'ASC')->get();
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
        $payment_method = "Tiền mặt";
        session(['payment_method' => $payment_method]);
        $cart = Session::get('cart', []);
        // Lưu dữ liệu vào session
        $request->session()->put('billData', $request->all());
        return redirect()->route('cart.store')->with('notification', 'Đặt phòng thành công');
    }

    public function store(request $request){
        //lấy dữ liệu từ trong session
        $billData = $request->session()->get('billData');
        $cart = $request->session()->get('cart');
        $dates = $request->session()->get('dates');
        // dd($request, $billData, $cart, $dates);
        //xử lý số lượng phòng
        $id_room = ''; // Khởi tạo chuỗi rỗng
        foreach ($cart as $item) {
            $idRoom = $item['idRoom'];
            $id_room .= $idRoom . ', '; // Nối vào chuỗi
        }
        $id_room = rtrim($id_room, ', ');//kiểm tra lại xem chuỗi có thừa dấu ',' hay k


        //xử lý phần đặt phòng cho người khác
        $isForOther = $billData['forother'];
        $name = ($isForOther == 'on') ? $billData['otherName'] : $billData['name'];
        $formattedName = ucwords(str::lower($name));
        if ($isForOther == 'on')
            $set_for_other = 1;
        else $set_for_other = 0;

        //xử lý phần dịch vụ và yêu cầu đặc biệt
        //dịch vụ
        $serviceCheckboxString = '';
        foreach ($billData as $key => $value) {
            if (strpos($key, 'service') === 0 && is_array($value) && !empty($value)) {
                $idRoom = substr($key, 7, -8); // Lấy idRoom từ tên của service{{$idroom}}Checkbox
                $serviceCheckboxString .= "/////{$idRoom}/////'" . implode(', ', $value) . "'/////, ";
            }
        }
        // Loại bỏ dấu phẩy và khoảng trắng cuối cùng nếu có
        $serviceCheckboxString = rtrim($serviceCheckboxString, ', ');

        // Khởi tạo mảng để lưu trữ kết quả
        $processedData = [];
        // Duyệt qua các phần tử của rq{{$idroom}}Checkbox
        foreach ($billData as $key => $value) {
            if (strpos($key, 'rq') === 0 && is_array($value)) {
                $roomId = substr($key, 2,-8); // Lấy ra id của phòng từ key
                $roomDetails = '';
                foreach($value as $element){
                    // Kiểm tra xem 'on' có trong mảng không
                    if ($element=='on') {
                        // Nếu có 'on', thêm vào chuỗi kết quả
                        $otherKey = 'rq' . $roomId . 'other';
                        $otherValue = $billData[$otherKey];
                        $roomDetails .= 'khác: "' . $otherValue . '", ';
                    } else {
                        // Nếu không có 'on', thêm các giá trị vào chuỗi kết quả
                        $roomDetails .= $element . ', ';
                    }
                }
                $roomDetails = rtrim($roomDetails, ', ');
                // Thêm thông tin của phòng vào mảng kết quả
                $processedData[] = "//////$roomId/////'{$roomDetails}'//////";
            }
        }
        $requestCheckboxString = implode(', ', $processedData);
        $requestCheckboxString = rtrim($requestCheckboxString, ', ');
        $number_of_guest=2;
        $check_in_date ='';
        $check_out_date='';
        foreach ($dates as $key => $value) {
            $number_of_guest=$value['guests'];
            $check_in_date =$value['checkin'];
            $check_out_date=$value['checkout'];
        }
        $lastElement = end($dates);
        $jsonString = json_encode($lastElement);
        // dd($request, $billData, $cart, $dates,$lastElement);
        $user = auth()->user()->id;
        $payment_method = session('payment_method');
        if($payment_method == "Tiền mặt")
            $payment_status = 0;
        else{
            $payment_status = 1;
        }
        $booking = Booking::create([
            'id_customer' => $user,
            'id_room' => $id_room,
            'id_service' => $serviceCheckboxString,
            'set_for_other' => $set_for_other,
            'name' => $formattedName,
            'phone' =>  $billData['phone'],
            'email' =>  $billData['email'],
            'number_of_guest' => $number_of_guest,
            'guest_detail' => $jsonString,
            'number_of_room' => count($cart),
            'total' => $billData['totalPrice'],
            'special_requirement' => $requestCheckboxString,
            'check_in_date' => $check_in_date,
            'check_out_date' => $check_out_date,
            'status' => '0',
            'payment_method'=>$payment_method,
            'payment_status'=> $payment_status,
            'checkInOut' => 0,

        ]);
        if ($booking) {
            // Lưu trạng thái của phòng sau khi booking thành công
            foreach ($cart as $item) {
                Room::where('id', $item['idRoom'])->update(['status' => 'Đã đặt']);
            }
            $request->session()->forget('billData');
            $request->session()->forget('cart');
            $request->session()->forget('dates');
            if(auth()->user()->role == "customer"){
                return redirect()->route('customer.profile')->with('notification','Đơn hàng đã được thanh toán, xin chờ đợi xác nhận từ phía khách sạn' );

            }else{
                return redirect()->route('bookingnew')->with('notification','Đơn hàng đã được thanh toán, xin chờ đợi xác nhận từ phía khách sạn' );
            }
        }
        else {
            // Xử lý khi có lỗi trong quá trình booking (nếu cần)
            // Redirect hoặc trả về response thất bại

            return redirect()->route('cart.index')->with('error_notification', ' không thành công!');
        }
    }
}
