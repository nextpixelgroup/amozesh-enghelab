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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $status = enumFormated(UserStatusEnum::cases());
        $roles = User::allRoles();
        $query = User::query()
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'super-admin');
            })
            ->with('roles');

        // فیلتر بر اساس وضعیت
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فیلتر بر اساس نقش
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // جستجو
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = UserResource::collection($query->orderBy('id', 'desc')->paginate(config('app.per_page')));
        return Inertia::render('Admin/Users/List', compact('users', 'status', 'roles'));
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
        Log::info(1);
        try {
            DB::transaction(function () use ($request) {
                Log::info(2);
                $birthDate = null;
                if($request->birth_day && $request->birth_month && $request->birth_year){
                    $jalali = "$request->birth_year-$request->birth_month-$request->birth_day";
                    $birthDate = Verta::parse($jalali)->toCarbon()->format('Y-m-d');
                }
                $password = null;
                if($request->password){
                    $password = Hash::make($request->password);
                }
                Log::info(3);
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
                Log::info(4);
                if($user){
                    $user->assignRole($request->role);
                }
                Log::info(5);
            });
            Log::info(6);
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
        $site_url = env('APP_URL');
        $user = new UserResource($user);
        return Inertia::render('Admin/Users/Edit', compact('status', 'roles', 'gender', 'years', 'months', 'days', 'user', 'site_url'));
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
