<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VnpayController extends Controller
{
    public function vnpay_returnUrl(request $request){
        $queryData = $request->query();
        $vnp_TmnCode = "82S4L2CU";//Mã website tại VNPAY 
        $vnp_HashSecret = "HAAVZMKBVQFNBROIWPCJIXLJJRYOMDBE"; //Chuỗi bí mật
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost/vnpay_php/vnpay_return.php";
        $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        $apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
        //Config input format
        //Expire
        $startTime = date("YmdHis");
        $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
        $vnp_SecureHash = $queryData['vnp_SecureHash'];
        $inputData = array();
        foreach ($queryData as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
                $payment_method = "VNPay";
                session(['payment_method' => $payment_method]);
                return redirect()->route('cart.store')->with([
                    'notification' => 'Xóa toàn bộ giỏ hàng thành công!',
                    'returnedData' => $inputData,
                ]);
            } 
            else {
                echo "GD Khong thanh cong";
                }
        } else {
            echo "Chu ky khong hop le";
            }
    }
}
