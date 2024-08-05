<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $data = Service::orderBy('name', 'ASC')->paginate(10);
        if ($keyword = request()->keyword) {
            $data = Service::orderBy('name', 'ASC')->where('name', 'like', '%' . $keyword . '%')->paginate(10);
        }
        return view('admin.users.services.servicelist', ['title' => 'Danh sách dịch vụ'], compact('data'));
    }

    public function editView($id){
        $data = Service::find($id);
        return view('admin.users.services.serviceedit', ['title' => 'Sửa dịch vụ #'. $data->id], compact('data'));

    }

    public function edit(Request $request){
        $name = $request->input('name');
        $status = $request->input('status');
        $price = $request->input('price');
        $unit = $request->input('unit');
        $type = $request->input('type');

        $description = $request->input('description');

        $id= $request->id;
        $data = Service::find($id);
        $data->name = $name;
        $data->status = $status;
        $data->price = $price;
        $data->unit = $unit;
        $data->type = $type;

        $data->description = $description;
        if ($data->save()) {
            return redirect()->route('servicelist')->with('notification', 'Đã sửa thành công');
        } else {
            return redirect()->route('servicelist')->with('error_notification', 'Sửa không thành công');
        };
    }
    public function add(Request $request)
    {
        $name = $request->input('name');
        $status = $request->input('status');
        $price = $request->input('price');
        $unit = $request->input('unit');
        $type = $request->input('type');
        $description = $request->input('description');

        $data = new Service();
        $data->name = $name;
        $data->status = $status;
        $data->price = $price;
        $data->unit = $unit;
        $data->type = $type;
        $data->description = $description;
        $data->save();

        return redirect()->route('servicelist')->with('notification', 'Đã thêm phòng $data->name');
        ;
    }


    public function delete($id)
    {
        $deleteData = DB::table('services')->where('id', '=', $id)->delete();

        if ($deleteData) {
            return redirect()->route('servicelist')->with('notification', 'Đã thêm phòng $data->name');
            ;
        } else {
            return redirect()->route('servicelist')->with('error_notification', 'Đã thêm phòng $data->name');
            ;
        }
    }
}
