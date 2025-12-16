<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PageStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminPageDetailsResource;
use App\Http\Resources\AdminPagesResource;
use App\Models\Media;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $query = Page::query()->orderBy('published_at','desc')->paginate(10);
        $pages = AdminPagesResource::collection($query);
        return inertia('Admin/Pages/List', compact('pages'));
    }

    public function edit(Page $page)
    {
        $compact = [];
        $page = AdminPageDetailsResource::make($page);
        $site_url = env('APP_URL') . '/';
        $status = enumFormated(PageStatusEnum::cases());
        if($page->type === 'about') {

            $institutions = User::whereHas('roles', function ($query) {
                $query->where('name', 'institution');
            })->with('roles')->get()->map(function ($institution) {
                return [
                    'value' => $institution->id,
                    'title' => $institution->firstname,
                ];
            });
            $teachers = User::whereHas('roles', function ($query) {
                $query->where('name', 'teacher');
            })->with('roles')->get()->map(function ($institution) {
                return [
                    'value' => $institution->id,
                    'title' => $institution->firstname . ' ' . $institution->lastname,
                ];
            });
            $compact = compact('page', 'status', 'site_url', 'institutions', 'teachers');
        }
        elseif ($page->type == 'contact'){
            $compact = compact('page', 'status', 'site_url');
        }
        return inertia('Admin/Pages/Edit', $compact);
    }

    public function update(Request $request, Page $page){

        try {

            $user = auth()->user();
            if($request->type == 'about'){
                $update = $page->update([
                    'title' => $request->input('title'),
                    'content' => $request->input('content'),
                    'status' => $request->input('status'),
                    'thumbnail_id' => $request->input('thumbnail_id'),
                    'updated_by' => $user->id,
                ]);
                if($update){
                    $page->updateMeta('institutions', $request->input('meta.institutions'));
                    $page->updateMeta('teachers', $request->input('meta.teachers'));
                    $page->updateMeta('section1Title', $request->input('meta.section1Title'));
                    $page->updateMeta('section1Content', $request->input('meta.section1Content'));
                    if($request->filled('meta.section1Thumbnail.id')){
                        $media = Media::find($request->input('meta.section1Thumbnail.id'));
                        $section1Thumbnail = '';
                        if($media) {
                            $section1Thumbnail = [
                                'id' => $media->id,
                                'url' => $media->url,
                            ];
                        }
                        $page->updateMeta('section1Thumbnail', $section1Thumbnail);
                    }
                    $page->updateMeta('section2Title', $request->input('meta.section2Title'));
                    $page->updateMeta('section2Content', $request->input('meta.section2Content'));
                }

                return redirectMessage('success',  "با موفقیت ویرایش شد.");
            }
            elseif ($request->type == 'contact'){
                $update = $page->update([
                    'title' => $request->input('title'),
                    'content' => $request->input('content'),
                    'status' => $request->input('status'),
                    'updated_by' => $user->id,
                ]);
                if($update){
                    $page->updateMeta('address', $request->input('meta.address'));
                    $page->updateMeta('tel', $request->input('meta.tel'));
                    $page->updateMeta('email', $request->input('meta.email'));
                }
                if($request->filled('meta.mapImage.id')){
                    $media = Media::find($request->input('meta.mapImage.id'));
                    $mapImage= '';
                    if($media) {
                        $mapImage = [
                            'id' => $media->id,
                            'url' => $media->url,
                        ];
                    }
                    $page->updateMeta('mapImage', $mapImage);
                }
                return redirectMessage('success',  "با موفقیت ویرایش شد.");
            }
            else{
                return redirectMessage('error',  "نوع مشخص نشده است");
            }
        }
        catch (\Exception $exception){
            $error = log_error($exception);
            return redirectMessage('error',  "خطایی پیش آمد(کدخطا:$error->id)");
        }
    }
}
