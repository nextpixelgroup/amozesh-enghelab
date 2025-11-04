<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function general()
    {
        return inertia('Admin/Settings/General');
    }

    public function menus()
    {
        return inertia('Admin/Settings/Menus');
    }
}
