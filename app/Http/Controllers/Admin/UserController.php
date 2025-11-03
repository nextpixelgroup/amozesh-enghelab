<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GenderEnum;
use App\Enums\UserRestrictionTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserCreateRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Resources\RestrictionResource;
use App\Http\Resources\UserResource;
use App\Models\Restriction;
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
        $roles = User::allRoles();
        $query = User::query()
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'super-admin');
            })
            ->with('roles');

        // فیلتر بر اساس وضعیت
        if ($request->filled('status')) {
            if($request->status == 'ban'){
                $query->whereHas('restrictions', function($query) {
                    $query->where('expires_at', '>', now())
                        ->orWhereNull('expires_at');
                });
            }
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
        return Inertia::render('Admin/Users/List', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = User::allRoles();
        $gender = enumFormated(GenderEnum::cases());
        $years = years();
        $months = months();
        $days = days();
        $site_url = env('APP_URL');
        return Inertia::render('Admin/Users/Create', compact('roles', 'gender', 'years', 'months', 'days', 'site_url'));
    }

    public function store(UserCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
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
                    'username'      => $request->username,
                    'password'      => $password,
                    'slug'          => $request->slug,
                ]);
                if($user){
                    $user->assignRole($request->role);
                }
            });
            return redirectMessage('success', 'کاربر با موفقیت ایجاد شد.', redirect: route('admin.users.index'));

        }
        catch (\Exception $e) {
            return redirectMessage('error', $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        $roles = User::allRoles();
        $gender = enumFormated(GenderEnum::cases());
        $years = years();
        $months = months();
        $days = days();
        $site_url = env('APP_URL');
        $restrictions = RestrictionResource::collection($user->restrictions()->latest()->get());
        $restrictionTypes = enumFormated(UserRestrictionTypeEnum::cases());
        $user = new UserResource($user);
        return Inertia::render('Admin/Users/Edit', compact('roles', 'gender', 'years', 'months', 'days', 'user', 'site_url', 'restrictions', 'restrictionTypes'));
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
                'username'      => $request->username,
                'slug'          => $request->slug,
            ];
            if($request->password){
                $args['password'] = Hash::make($request->password);
            }
            $update = $user->update($args);
            if($update){
                $user->syncRoles($request->role);
            }
            return redirectMessage('success', 'کاربر با موفقیت ویرایش شد.');

        }
        catch (\Exception $e) {
            return redirectMessage('error', $e->getMessage());
        }
    }
}
