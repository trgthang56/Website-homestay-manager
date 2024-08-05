<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index(){
        return view('admin.users.login',['title' => 'đăng nhập hệ thống'] );
    }

    public function login_homestay(Request $request){
        $this -> validate($request,  [
            'email' => 'required|email:filter',
            'password' => 'required' 
        
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không chính xác.',
            'password.required' => 'Mật khẩu không được để trống.'
        ]);

        if(Auth::attempt([
            'email'=> $request -> input('email'),
            'password'=> $request -> input('password')],
            $request->input('remember'))){

            // Lấy thông tin của người dùng vừa đăng nhập
            $user = Auth::user();

            // Lưu thông tin người dùng vào session
            Session::put([
                'name' => $user->name,
                'birth' => $user->birth,
                'phone' => $user->phone,
                'address' => $user->address,
                'email' => $user->email,
                'role'=> $user->role
            ]);

            // Trở về trang admin
            return redirect()->route('admin');
        }
        return redirect()->back()->withErrors(['error' => 'Email hoặc mật khẩu không đúng.']);
    }
    
    public function logout()
    {
        // Xóa thông tin người dùng khỏi session
        Auth::logout();
        return redirect()->route('login');
    }
}
