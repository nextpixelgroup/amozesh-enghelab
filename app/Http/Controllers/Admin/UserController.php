<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/List');
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(UserCreateRequest $request)
    {
        try {
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            return redirectMessage('success', 'کاربر با موفقیت ایجاد شد.', redirect: route('admin.users.index'));

        }
        catch (\Exception $e) {
            return redirectMessage('error', $e->getMessage());
        }
    }

    public function edit()
    {
        return Inertia::render('Admin/Users/Edit');
    }
}
