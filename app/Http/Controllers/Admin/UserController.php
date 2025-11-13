<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DegreeEnum;
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
        $institutions = User::whereHas('roles', function($query) {
            $query->where('name', 'institution');
        })->with('roles')->get()->map(function ($institution) {
            return [
                'value' => $institution->id,
                'title' => $institution->firstname,
            ];
        });
        $gender = enumFormated(GenderEnum::cases());
        $years = years();
        $months = months();
        $days = days();
        $site_url = env('APP_URL');
        $degree = enumFormated(DegreeEnum::cases());
        return Inertia::render('Admin/Users/Create', compact('roles', 'gender', 'years', 'months', 'days', 'site_url', 'institutions', 'degree'));
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
                $slug = $request->slug;
                if($request->role == 'teacher') {
                    $slug = createSlug($request->slug);
                    $slug = makeSlugUnique($slug, User::class);
                }
                $user = User::create([
                    'firstname'      => $request->firstname,
                    'lastname'       => $request->lastname,
                    'gender'         => $request->gender,
                    'national_code'  => $request->national_code,
                    'mobile'         => $request->mobile,
                    'tel'            => $request->tel,
                    'email'          => $request->email,
                    'address'        => $request->address,
                    'postal_code'    => $request->postal_code,
                    'birth_date'     => $birthDate,
                    'company'        => $request->company,
                    'username'       => $request->username,
                    'institution_id' => $request->institution_id,
                    'password'       => $password,
                    'slug'           => $slug,
                    'avatar_id'      => $request->avatar_id,
                ]);
                if($user){
                    $user->teacherDetails()->create([
                        'image_id' => $request->image_id,
                        'academic_title' => $request->academic_title,
                        'job_title' => $request->job_title,
                        'degree' => $request->degree,
                        'history' => $request->history,
                        'teaching' => $request->teaching,
                        'skills' => $request->skills,
                        'bio' => $request->bio,
                    ]);
                    if(isset($request->educations) && count($request->educations)){
                        foreach ($request->educations as $education){
                            $start_date = null;
                            $end_date = null;
                            if($education['start_month'] && $education['start_year']){
                                $jalali = "{$education['start_year']}-{$education['start_month']}-01";
                                $start_date = Verta::parse($jalali)->toCarbon()->format('Y-m-d');
                            }
                            if($education['end_month'] && $education['end_month']){
                                $jalali = "{$education['end_year']}-{$education['end_month']}-01";
                                $end_date = Verta::parse($jalali)->toCarbon()->format('Y-m-d');
                            }
                            $user->educationals()->create([
                                'university'     => $education['university'],
                                'city'           => $education['city'],
                                'field_of_study' => $education['field_of_study'],
                                'degree'         => $education['degree'],
                                'start_date'     => $start_date,
                                'end_date'       => $end_date,
                                'is_studying'    => $education['is_studying'],
                                'description'    => $education['description'],
                            ]);
                        }
                    }

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
        $institutions = User::whereHas('roles', function($query) {
            $query->where('name', 'institution');
        })->with('roles')->get()->map(function ($institution) {
            return [
                'value' => $institution->id,
                'title' => $institution->firstname,
            ];
        });
        $degree = enumFormated(DegreeEnum::cases());
        $user = new UserResource($user);
        return Inertia::render('Admin/Users/Edit', compact('roles', 'gender', 'years', 'months', 'days', 'user', 'site_url', 'restrictions', 'restrictionTypes', 'institutions', 'degree'));
    }

    public function update(User $user, UserUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($user, $request) {
                $birthDate = null;
                if($request->birth_day && $request->birth_month && $request->birth_year){
                    $jalali = "$request->birth_year-$request->birth_month-$request->birth_day";
                    $birthDate = Verta::parse($jalali)->toCarbon()->format('Y-m-d');
                }

                $slug = $request->slug;
                if($user->role == 'teacher' && $user->slug !== $request->slug) {
                    $slug = $request->slug ? createSlug($request->slug) : createSlug($request->title);
                    $slug = makeSlugUnique($slug, User::class);
                }

                $args = [
                    'firstname'      => $request->firstname,
                    'lastname'       => $request->lastname,
                    'gender'         => $request->gender,
                    'national_code'  => $request->national_code,
                    'mobile'         => $request->mobile,
                    'tel'            => $request->tel,
                    'email'          => $request->email,
                    'address'        => $request->address,
                    'postal_code'    => $request->postal_code,
                    'birth_date'     => $birthDate,
                    'company'        => $request->company,
                    'username'       => $request->username,
                    'institution_id' => $request->institution_id,
                    'slug'           => $slug,
                    'avatar_id'      => $request->avatar_id,
                ];
                if($request->password){
                    $args['password'] = Hash::make($request->password);
                }
                $update = $user->update($args);
                if($update){
                    $user->syncRoles($request->role);
                    if($request->role == 'teacher' && $user->teacherDetails()->exists()){
                        $user->teacherDetails()->update([
                            'image_id' => $request->image_id,
                            'academic_title' => $request->academic_title,
                            'job_title' => $request->job_title,
                            'degree' => $request->degree,
                            'history' => $request->history,
                            'teaching' => $request->teaching,
                            'skills' => $request->skills,
                            'bio' => $request->bio,
                        ]);
                    }
                    else{
                        $user->teacherDetails()->create([
                            'image_id' => $request->image_id,
                            'academic_title' => $request->academic_title,
                            'job_title' => $request->job_title,
                            'degree' => $request->degree,
                            'history' => $request->history,
                            'teaching' => $request->teaching,
                            'skills' => $request->skills,
                            'bio' => $request->bio,
                        ]);
                    }

                    if(isset($request->educations) && count($request->educations)){
                        foreach ($request->educations as $education) {
                            $start_date = null;
                            $end_date = null;
                            if ($education['start_month'] && $education['start_year']) {
                                $jalali = "{$education['start_year']}-{$education['start_month']}-01";
                                $start_date = Verta::parse($jalali)->toCarbon()->format('Y-m-d');
                            }
                            if ($education['end_month'] && $education['end_month']) {
                                $jalali = "{$education['end_year']}-{$education['end_month']}-01";
                                $end_date = Verta::parse($jalali)->toCarbon()->format('Y-m-d');
                            }
                            if ($education['id'] == null) {
                                $user->educationals()->create([
                                    'university' => $education['university'],
                                    'city' => $education['city'],
                                    'field_of_study' => $education['field_of_study'],
                                    'degree' => $education['degree'],
                                    'start_date' => $start_date,
                                    'end_date' => $end_date,
                                    'is_studying' => $education['is_studying'],
                                    'description' => $education['description'],
                                ]);
                            } else {
                                $user->educationals()->where('id', $education['id'])->update([
                                    'university' => $education['university'],
                                    'city' => $education['city'],
                                    'field_of_study' => $education['field_of_study'],
                                    'degree' => $education['degree'],
                                    'start_date' => $start_date,
                                    'end_date' => $end_date,
                                    'is_studying' => $education['is_studying'],
                                    'description' => $education['description'],
                                ]);
                            }
                        }
                    }
                    else{
                        $user->educationals()->delete();
                    }

                }
            });
            return redirectMessage('success', 'کاربر با موفقیت ویرایش شد.');

        }
        catch (\Exception $e) {
            return redirectMessage('error', $e->getMessage());
        }
    }
}
