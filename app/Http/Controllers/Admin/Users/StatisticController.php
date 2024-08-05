<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function index()
    {
        return view('admin.users.charts.index', [
            'title' => 'Thống kê tài chính',
        ]);
    }
}
