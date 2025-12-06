<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminMenusResource;
use App\Models\Menu;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function general()
    {
        return inertia('Admin/Settings/General');
    }

}
