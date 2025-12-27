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
        $slides = isset($setting['index.slider']) ? $setting['index.slider'] : [];
        $social = isset($setting['index.social']) ? $setting['index.social'] : [];
        $logos = isset($setting['web.logos']) ? $setting['web.logos'] : [];
        return inertia('Admin/Settings/General', compact('slides', 'social', 'logos'));
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

    public function imagesUpdate(Request $request)
    {
        try {
            $setting = new SettingsService;
            $logoHeader = $request->logoHeader;
            $logoFooter = $request->logoFooter;

            $images = [
                'logoHeader' => [
                    'id' => null,
                    'url' => '',
                ],
                'logoFooter' => [
                    'id' => null,
                    'url' => '',
                ],
            ];
            if(!empty($logoHeader)) {
                $media = Media::find($logoHeader);

                if($media) {
                    $images['logoHeader'] = [
                        'id' => $media->id,
                        'url' => $media->url,
                    ];
                }
            }
            if(!empty($logoFooter)) {
                $media = Media::find($logoFooter);

                if($media) {
                    $images['logoFooter'] = [
                        'id' => $media->id,
                        'url' => $media->url,
                    ];
                }
            }

            $setting->set('web.logos',$images,'setting');
            return redirectMessage('success', 'با موفقیت ذخیره شد');
        }
        catch (\Exception $exception){
            $error = log_error($exception);
            return redirectMessage('error',"خطایی پیش آمد(کدخطا: $error->id)");
        }
    }
}
