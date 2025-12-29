<?php

namespace App\Http\Controllers\Panel;

use App\Enums\DegreeEnum;
use App\Enums\GenderEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\ProfileStoreRequest;
use App\Http\Resources\PanelProfileResource;
use App\Services\City;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user = PanelProfileResource::make($user);
        $gender = enumFormated(GenderEnum::cases());
        $years = years();
        $months = months();
        $days = days();
        $degree = enumFormated(DegreeEnum::cases());
        $cities = City::getAll();
        $provinces = City::getProvincesList();
        $pageTitle = 'پروفایل کاربری';
        return inertia('Panel/Profile/Index', compact('user', 'gender', 'years', 'months', 'days', 'degree', 'cities', 'provinces', 'degree', 'pageTitle'));
    }

    public function update(ProfileStoreRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        try {
            $user->update([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'birth_date' => $validated['birth_year'] && $validated['birth_month'] && $validated['birth_day'] ? $this->convertToGregorian(
                    $validated['birth_year'],
                    $validated['birth_month'],
                    $validated['birth_day']
                ) : null,
                'national_code' => $validated['national_code'],
                'address' => $validated['address'],
                'postal_code' => $validated['postal_code'],
                'province' => $validated['province'] ?? null,
                'city' => $validated['city'] ?? null,
                'national_card_image_id' => $validated['national_card_image_id'] ?? null,
            ]);
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

            return redirectMessage('success','اطلاعات پروفایل با موفقیت به‌روزرسانی شد.');

        } catch (\Exception $e) {
            $error = log_error($e);

            return redirectMessage('error',"خطایی پیش آمد.(کدخطا $error->id)");
        }
    }

    private function convertToGregorian($year, $month, $day)
    {
        try {
            return \Hekmatinasser\Verta\Verta::createJalali($year, $month, $day,0,0,0)->datetime();
        } catch (\Exception $e) {
            return now();
        }
    }
}
