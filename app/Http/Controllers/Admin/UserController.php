<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GenderEnum;
use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserCreateRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = UserResource::collection(
            User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'super-admin');
            })
                ->with('roles')
                ->paginate(config('app.per_page'))
        );
        return Inertia::render('Admin/Users/List', compact('users'));
    }

    public function create()
    {
        $status = enumFormated(UserStatusEnum::cases());
        $roles = User::allRoles();
        $gender = enumFormated(GenderEnum::cases());
        $years = years();
        $months = months();
        $days = days();
        return Inertia::render('Admin/Users/Create', compact('status', 'roles', 'gender', 'years', 'months', 'days'));
    }

    public function store(UserCreateRequest $request)
    {
        try {
            $birthDate = null;
            if($request->birth_day && $request->birth_month && $request->birth_year){
                $jalali = "$request->birth_year-$request->birth_month-$request->birth_day";
                $birthDate = Verta::parse($jalali)->toCarbon()->format('Y-m-d');
            }
            $password = null;
            if($request->password){
                $password = Hash::make($request->password);
            }
            $user = User::create([
                'firstname'     => $request->firstname,
                'lastname'      => $request->lastname,
                'gender'        => $request->gender,
                'national_code' => $request->national_code,
                'mobile'        => $request->mobile,
                'email'         => $request->email,
                'address'       => $request->address,
                'postal_code'   => $request->postal_code,
                'birth_date'    => $birthDate,
                'company'       => $request->company,
                'status'        => $request->status,
                'username'      => $request->username,
                'password'      => $password,
                'slug'          => $request->slug,
            ]);
            if($user){
                $user->assignRole($request->role);
            }
            return redirectMessage('success', 'کاربر با موفقیت ایجاد شد.', redirect: route('admin.users.index'));

        }
        catch (\Exception $e) {
            return redirectMessage('error', $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $status = enumFormated(UserStatusEnum::cases());
        $roles = User::allRoles();
        $gender = enumFormated(GenderEnum::cases());
        $years = years();
        $months = months();
        $days = days();
        $user = new UserResource($user);
        return Inertia::render('Admin/Users/Edit', compact('status', 'roles', 'gender', 'years', 'months', 'days', 'user'));
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        try {
            $birthDate = null;
            if($request->birth_day && $request->birth_month && $request->birth_year){
                $jalali = "$request->birth_year-$request->birth_month-$request->birth_day";
                $birthDate = Verta::parse($jalali)->toCarbon()->format('Y-m-d');
            }
            $args = [
                'firstname'     => $request->firstname,
                'lastname'      => $request->lastname,
                'gender'        => $request->gender,
                'national_code' => $request->national_code,
                'mobile'        => $request->mobile,
                'email'         => $request->email,
                'address'       => $request->address,
                'postal_code'   => $request->postal_code,
                'birth_date'    => $birthDate,
                'company'       => $request->company,
                'status'        => $request->status,
                'username'      => $request->username,
                'slug'          => $request->slug,
            ];
            if($request->password){
                $args['password'] = Hash::make($request->password);
            }
            $update = $user->update($args);
            if($update){
                $user->assignRole($request->role);
            }
            return redirectMessage('success', 'کاربر با موفقیت ویرایش شد.');

        }
        catch (\Exception $e) {
            return redirectMessage('error', $e->getMessage());
        }
    }
}
