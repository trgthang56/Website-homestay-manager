<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    
    public function index(){
        $time=  Auth::user()->birth;
        $date = date("d/m/Y", strtotime($time) );
   
        return view('admin.admin',['title' => 'Trang cá nhân',
        'date' => $date
    ]);
    }

    
}
