<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Admin\Users\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function Laravel\Prompts\alert;

class AccountController extends Controller{

    public function index(){
        $users = User::where('role', '!=', 'customer')->orderBy('role', 'ASC')->paginate(10);
    
        $phone = session('search_phone');
        if ($phone) {
            $users = User::where('role', '!=', 'customer')->orderBy('name', 'ASC')->where('phone', $phone)->paginate(10);
            Session::forget('search_phone');
        }
    
        if ($keyword = request()->keyword) {
            $users = User::where('role', '!=', 'customer')->orderBy('name', 'ASC')->where('phone', 'like', '%' . $keyword . '%')->paginate(10);
        }
        if($users->isEmpty() && $keyword = request()->keyword){
            $users = User::where('role', '!=', 'customer')->orderBy('name', 'ASC')->Where('name', 'like', '%' . $keyword . '%')->paginate(10);
        }
    
        return view('admin.users.accounts.accountlist', ['title' => 'Danh sách nhân viên'], compact('users'));
    }
    

    public function customerListIndex(){
        $users = User::where('role', 'customer')->orderBy('name', 'ASC')->paginate(10);
    
        $phone = session('search_phone');
        if ($phone) {
            $users = User::where('role', 'customer')->orderBy('name', 'ASC')->where('phone', $phone)->paginate(10);
            Session::forget('search_phone');
        }
    
        if ($keyword = request()->keyword) {
            $users = User::where('role', 'customer')->orderBy('name', 'ASC')->where('phone', 'like', '%' . $keyword . '%')->paginate(10);
        }
        if($users->isEmpty() && $keyword = request()->keyword){
            $users = User::where('role', 'customer')->orderBy('name', 'ASC')->Where('name', 'like', '%' . $keyword . '%')->paginate(10);
        }
        return view('admin.users.accounts.accountcustomerlist', ['title' => 'Danh sách khách hàng'], compact('users'));
    }
    


    public function indexAccount($id){
        $user = User::find($id);
        if($user->role!='customer')
        return view('admin.users.accounts.accountdetail', ['title' => 'Thông tin nhân viên ' . $user->name], compact('user'));
        else return view('admin.users.accounts.accountdetail', ['title' => 'Thông tin khách hàng ' . $user->name], compact('user'));
    }

    public function indexAccEdit($id){
        $user = User::find($id);
        if($user->role!='customer')
        return view('admin.users.accounts.accountedit', ['title' => 'Chỉnh sửa nhân viên ' . $user->name], compact('user'));
        else return view('admin.users.accounts.accountedit', ['title' => 'Chỉnh sửa khách hàng ' . $user->name], compact('user'));

    }

    public function add(Request $request)
    {
        $name = $request->input('name');
        $birth = $request->input('birth');
        $phone = $request->input('phone');
        $address = $request->input('address');
        $email = $request->input('email');
        $role = $request->input('role');

        $name = ucwords(strtolower($name));
        $address = ucwords(strtolower($address));

        $phoneExists = User::where('phone', $phone)->first();
        if ($phoneExists) {
            session(['search_phone' => $phone]);
            return redirect()->back()->with(['error_notification' => 'Số điện thoại đã tồn tại', 'duplicate_phone' => $phone]);
        }

        $emailExists = User::where('email', $email)->first();
        if ($emailExists) {
            session(['search_email' => $email]);
            return redirect()->back()->with(['error_notification' => 'Số địa chỉ email đã tồn tại', 'duplicate_email' => $email]);
        }

        $user = new User();
        $user->name = $name;
        $user->birth = $birth;
        $user->phone = $phone;
        $user->address = $address;
        $user->password = Hash::make($phone);
        $user->email = $email;
        $user->role = $role;

        $user->save();

        return redirect()->route('accountlist')->with('notification', 'Đã thêm nhân viên thành công');
    }


    function normalizeName($name)
    {
        $lower = Str::lower($name); // Chuyển chuỗi về dạng chữ thường
        $title = Str::title(mb_convert_case($lower, MB_CASE_TITLE, "UTF-8")); // Chuẩn hóa tên
        return $title;
    }

    public function update(Request $request, $id)
    {
        //lấy giữ liệu ra nhờ $id
        $user = User::find($id);
        $file_path = public_path('uploads') . '/' . $user->image;
        if ($request->has("avartarFile")) {
            if (!file_exists($file_path)) {
                $file = $request->avartarFile;
                $ext = $request->avartarFile->extension();
                $file_name = time() . '-' . 'user_' . $id . '_image' . "." . $ext;
                $file->move(public_path('uploads'), $file_name);
            } else {
                $currentFilePath = public_path('uploads') . '/' . $user->image;
                unlink($currentFilePath);
                $file = $request->avartarFile;
                $ext = $request->avartarFile->extension();
                $file_name = time() . '-' . 'user_' . $id . '_image' . "." . $ext;
                $file->move(public_path('uploads'), $file_name);
            }
        } else {
            $file_name = $user->image;
        }
        //chuẩn hóa tên 
        $nameStr = $request->input('name');
        $normalized = $this->normalizeName($nameStr);
        // chuẩn hóa địa chỉ
        $addressStr = $request->input('address');
        $address = ucwords(strtolower($addressStr));

        // Cập nhật thông tin người dùng trong cơ sở dữ liệu
        $user->name = $normalized;
        $user->birth = $request->input('birth');
        $user->phone = $request->input('phone');
        $user->address = $address;
        $user->image = $file_name;
        $user->email = $request->input('email');
        $user->save();

        // Chuyển hướng đến trang người dùng
        if ($user->save()) {
            return redirect()->back()->with('notification', 'Sửa thông tin thành công!');
        } else {
            return redirect()->back()->with('error_notification', 'Sửa thông tin không thành công!');
        }
    }



    public function change(Request $request, $id)
    {
        $user = User::find($id);
        $this->validate($request, [
            'old_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ], [
            'old_password.required' => 'Mật khẩu không được để trống.',
            'old_password.current_password' => 'Mật khẩu cũ không chính xác.',
            'password.required' => 'Mật khẩu mới không được để trống.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.min' => 'Mật khẩu mới phải chứa ít nhất 8 ký tự, bao gồm ít nhất một chữ hoa, một chữ thường và một số',
        ]);

        // Kiểm tra xem mật khẩu cũ và mật khẩu mới có trùng nhau không.
        if (Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error_notification', 'Mật khẩu hiện tại và mới trùng nhau');
        }

        //Kiểm tra xem mật khẩu mới có đáp ứng các yêu cầu về độ mạnh không.
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/', $request->password)) {
            return redirect()->back()->with('error_notification', 'Mật khẩu phải chứa ít nhất 8 ký tự, bao gồm ít nhất một chữ hoa, một chữ thường và một số');
        }

        // Đổi mật khẩu
        $user->password = Hash::make($request->password);
        $user->save();

        // Kiểm tra xem mật khẩu mới có được lưu vào cơ sở dữ liệu thành công không.
        if (!$user->save()) {
            return redirect()->back()->with('warning_notification', 'Có lỗi khi đổi mật khẩu');
        }

        // Redirect về trang chủ
        return redirect()->back()->with('notification', 'Mật khẩu đã được đổi thành công');
    }



    public function delete($id)
    {
        $temp = User::findOrFail($id);
        $name = $temp->name;
        $deleteData = DB::table('users')->where('id', '=', $id)->delete();

        //Kiểm tra lệnh delete để trả về một thông báo
        if ($deleteData) {
            return redirect()->route('accountlist')->with('notification', 'Đã xóa nhân viên ' . $name . ' thành công');
        } else {
            return redirect()->route('accountlist')->with('wrong_notification', 'Xóa nhân viên không thành công');
            ;
        }
    }

}
