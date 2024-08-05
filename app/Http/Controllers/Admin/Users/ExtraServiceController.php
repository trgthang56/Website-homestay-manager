<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Extra_service;
use App\Models\Service;
use Illuminate\Http\Request;

class ExtraServiceController extends Controller
{
    public function index($id){
        $booking = Booking::find($id);
        $services = Service::where('status', '!=', 'Tạm dừng')->where('type', 'like', '%Khi dùng%')->get();
        
        // dd( $booking, $services);
        return view('admin.users.extraservices.index', [
            'title' => 'Dịch vụ phát sinh',
            'booking' => $booking,
            'services' => $services
            ]
        );
    }
    public function add(Request $request){
        $prices = $request->price;
        $total=0;
        foreach($prices as $price){
            $total +=  $price;
        }
        $total += $request->other_price;

        $serviceJson = json_encode($request->service);
        $quantityJson = json_encode($request->quantity);
        $priceJson = json_encode($request->price);

        $extraService = new Extra_service();
        $extraService->id_user = $request->id_user;
        $extraService->id_booking = $request->id_booking;
        $extraService->id_service = $serviceJson;
        $extraService->quantity = $quantityJson;
        $extraService->price = $priceJson;
        $extraService->other_price = $request->other_price;
        $extraService->status = 0;
        $extraService->total = $total;
        $extraService->description = $request->description;
        $extraService->save();
        return redirect()->back()->with('notification','Tạo đơn dịch vụ phát sinh thành công!');
    }

    public function cancel($id){
        $extraService = Extra_service::find($id);
        $extraService->status = 4;
        $extraService->save();
        return redirect()->back()->with('notification','Hủy đơn thành công!');
    }
    public function completed($id){
        $extraService = Extra_service::find($id);
        $extraService->status = 3;
        $extraService->payment_method = "Tiền mặt";
        if($extraService->paid_time==null) $extraService->paid_time = now();
        if($extraService->completed_time==null) $extraService->completed_time = now();
        $extraService->save();
        return redirect()->back()->with('notification','Đơn phát sinh đã xong!');
    }
    public function paid($id){
        $extraService = Extra_service::find($id);
        $extraService->status = 1;
        $extraService->payment_method = "Tiền mặt";
        $extraService->paid_time = now();
        if($extraService->completed_time){
            $extraService->status = 3;
        }
        $extraService->save();
        return redirect()->back()->with('notification','Đơn phát sinh đã được thanh toán!');
    }
    public function finished($id){
        $extraService = Extra_service::find($id);
        $extraService->status = 2;
        $extraService->completed_time = now();
        if($extraService->paid_time){
            $extraService->status = 3;
        }
        $extraService->save();
        return redirect()->back()->with('notification','Đơn phát sinh đã được hoàn thành!');
    }
    public function completedAll($id){
        $extraServices = Extra_service::where("id_booking", $id)->get();
        foreach($extraServices as $extraService){
            $extraService->status = 3;
            $extraService->payment_method = "Tiền mặt";
            if($extraService->paid_time==null) $extraService->paid_time = now();
            if($extraService->completed_time==null) $extraService->completed_time = now();
            $extraService->save();
        }
        return redirect()->back()->with('notification','Đơn phát sinh đã xong!');
    }
    public function paidAll($id){
        $extraServices = Extra_service::where("id_booking", $id)->get();
        foreach($extraServices as $extraService){
            $extraService->status = 1;
            $extraService->payment_method = "Tiền mặt";
            $extraService->paid_time = now();
            if($extraService->completed_time){
                $extraService->status = 3;
            }
            $extraService->save();
        }
        return redirect()->back()->with('notification','Đơn phát sinh đã được thanh toán!');
    }
    public function finishedAll($id){
        $extraServices = Extra_service::where("id_booking", $id)->get();
        foreach($extraServices as $extraService){
            $extraService->status = 2;
            $extraService->completed_time = now();
            if($extraService->paid_time){
                $extraService->status = 3;
            }
            $extraService->save();
        }
        return redirect()->back()->with('notification','Đơn phát sinh đã được hoàn thành!');
    }
    public function edit($id){
        $extraService = Extra_service::find($id);
        $extraService->status = 4;
        $extraService->save();
        return redirect()->back()->with('notification','Hủy đơn thành công!');
    }
    public function info($id){
        $extraService = Extra_service::find($id);
        $extraService->status = 4;
        $extraService->save();
        return redirect()->back()->with('notification','Hủy đơn thành công!');
    }
}
