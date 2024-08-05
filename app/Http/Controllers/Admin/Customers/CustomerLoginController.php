<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CustomerLoginController extends Controller
{
    public function register(Request $request){
        // Kiểm tra xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:10',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{10,}$/',
            ],
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không chính xác.',
            'email.unique' => 'Email đã tồn tại.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.regex' => 'Mật khẩu phải chứa ít nhất một ký tự in hoa, một số, một ký tự đặc biệt và có ít nhất 10 ký tự.',
        ]);
        
    
        // Tạo một bản ghi mới trong cơ sở dữ liệu sử dụng Eloquent ORM
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->role = "customer";
        // Lưu thông tin người dùng vào cơ sở dữ liệu
        if ($user->save()) {
            // Trả về thông báo thành công nếu lưu thành công
            return redirect()->back()->withErrors(['error' => 'Tài khoản của bạn đã được đăng kí thành công!']);
        }
    }
    public function index()
    {
        
        return view('admin.customers.login');
    }

    public function checkUserRole(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['role' => $user->role]);
        }

        return response()->json(['role' => '']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email:filter',
            'password' => 'required'
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.email:filter' => 'Email không hợp lệ hoặc không được chấp nhận bởi hệ thống.',
            'password.required' => 'Mật khẩu không được để trống.'
        ]);
        if (
            Auth::attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password')],
                $request->input('remember'))
        ) {
            // Lấy thông tin của người dùng vừa đăng nhập
            $user = Auth::user();
            if($user->role =='customer'){
                // Lưu thông tin người dùng vào session
                Session::put([
                    'name' => $user->name,
                    'birth' => $user->birth,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'email' => $user->email,
                    'role' => $user->role
                ]);
                return redirect()->route('homepage');
            }else{
                Session::put([
                    'name' => $user->name,
                    'birth' => $user->birth,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'email' => $user->email,
                    'role' => $user->role
                ]);
                return redirect()->route('admin');
            }

        }
        return redirect()->back()->withErrors(['error' => 'Email hoặc mật khẩu không đúng.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('homepage');
    }
}