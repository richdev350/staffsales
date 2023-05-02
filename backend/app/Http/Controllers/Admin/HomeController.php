<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * 管理画面ダッシュボード.
     */
    public function index(
    )
    {

        return view('admins.home');
    }
}
