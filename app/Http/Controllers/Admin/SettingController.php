<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminMenusResource;
use App\Models\Media;
use App\Models\Menu;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function general()
    {
        $setting = new SettingsService;
        $setting = $setting->getGroup('setting');
        $slides = $setting?['index.slider'] : [];
        $social = $setting?['index.social'] : [];
        return inertia('Admin/Settings/General', compact('slides', 'social'));
    }

    public function slideUpdate(Request $request)
    {
        try {
            $slides = $request->slides;
            $setting = new SettingsService;
            $slider = [];
            foreach ($slides as $i => $slide) {
                $image = [
                    'id' => null,
                    'url' => '',
                ];
                if(isset($slide['image']['id'])) {
                    $media = Media::find($slide['image']['id']);

                    if($media) {
                        $image = [
                            'id' => $media->id,
                            'url' => $media->url,
                        ];
                    }
                }

                $slider[$i] = $slide;
                $slider[$i]['id'] = $i+1;
                $slider[$i]['image'] = $image;
            }
            $setting->set('index.slider',$slider,'setting');
            return redirectMessage('success', 'با موفقیت ذخیره شد');
        }
        catch (\Exception $exception){
            $error = log_error($exception);
            return redirectMessage('error',"خطایی پیش آمد(کدخطا: $error->id)");
        }
    }


    public function slideDelete($id)
    {
        $setting = new SettingsService;
        $slides = collect($setting->get('index.slider', []));

        // Filter out the slide with the given ID
        $updatedSlides = $slides->reject(function ($slide) use ($id) {
            return $slide['id'] == $id;
        })->values()->all();

        $setting->set('index.slider', $updatedSlides,'setting');
        return redirectMessage('success', 'با موفقیت حذف شد');
    }

    public function socialUpdate(Request $request)
    {
        $setting = new SettingsService;
        $social = [
            'soroush' => $request->input('soroush'),
            'eitaa' => $request->input('eitaa'),
        ];
        $setting->set('index.social',$social,'setting');

        return redirectMessage('success', 'با موفقیت ذخیره شد');
    }

}
