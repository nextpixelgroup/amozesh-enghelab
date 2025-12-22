<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Page;
use Exception;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function bookImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:3120'], // 5MB
        ]);

        $file = $request->file('file');
        $media = Media::uploadFile($file, 'image', 'uploads/images/books');

        if (!$media) {
            return responseJSon('error', ' خطا در آپلود فایل');
        }

        return responseJSon('success', 'فایل با موفقیت آپلود شد', [
            'path'    => $media->path,
            'url'     => $media->url,
            'id'      => $media->id,
        ]);
    }

    public function courseImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:3120'], // 3MB
        ]);

        $file = $request->file('file');
        $media = Media::uploadFile($file, 'image', 'uploads/images/courses');

        if (!$media) {
            return responseJSon('error', ' خطا در آپلود فایل');
        }

        return responseJSon('success', 'فایل با موفقیت آپلود شد', [
            'path'    => $media->path,
            'url'     => $media->url,
            'id'      => $media->id,
        ]);
    }

    public function userImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:3120'], // 3MB
        ]);

        $file = $request->file('file');
        $media = Media::uploadFile($file, 'image', 'uploads/images/users');
        if (!$media) {
            return responseJSon('error', ' خطا در آپلود فایل');
        }

        return responseJSon('success', 'فایل با موفقیت آپلود شد', [
            'path'    => $media->path,
            'url'     => $media->url,
            'id'      => $media->id,
        ]);
    }

    public function pageImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:3120'], // 3MB
        ]);

        $file = $request->file('file');
        try {
            $media = Media::uploadFile($file, 'image', 'uploads/images/pages');
            return responseJSon('success', 'فایل با موفقیت آپلود شد', [
                'path'    => $media->path,
                'url'     => $media->url,
                'id'      => $media->id,
            ]);
        }
        catch (Exception $e){
            return responseJSon('error', $e->getMessage());
        }


    }
    public function generalImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:3120'], // 3MB
        ]);

        $file = $request->file('file');
        try {
            $media = Media::uploadFile($file, 'image', 'uploads/images/general');
            return responseJSon('success', 'فایل با موفقیت آپلود شد', [
                'path'    => $media->path,
                'url'     => $media->url,
                'id'      => $media->id,
            ]);
        }
        catch (Exception $e){
            return responseJSon('error', $e->getMessage());
        }


    }

    public function destroy(Media $media, $type, Request $request)
    {
        if($type === 'course'){
            $media->courseThumbnail()->update(['thumbnail_id' => null]);
        }
        elseif($type === 'book'){
            $media->bookThumbnail()->update(['thumbnail_id' => null]);
        }
        elseif($type === 'lesson'){

            $media->lessonPoster()->update(['poster_id' => null]);
        }
        elseif($type === 'page'){

            $media->pageThumbnail()->update(['thumbnail_id' => null]);
        }
        elseif($type === 'section1Thumbnail'){
            $page = Page::find($request->pageId);
            $page->updateMeta($type, ['id' => null, 'url' => '']);
        }
        elseif($type === 'mapImage'){
            $page = Page::find($request->pageId);
            $page->updateMeta($type, ['id' => null, 'url' => '']);
        }
        elseif($type === 'userAvatar'){
            $media->userAvatar()->update(['avatar_id' => null]);
        }
        elseif($type === 'userNationalCardImage'){
            $media->userNationalCardImage()->update(['national_card_image_id' => null]);
        }
        else{
            return responseJSon('error', 'نوع فایل مشخص نشده است');
        }
        $media->deleteFile();
        return responseJSon('success', 'فایل از هاست حذف شد. لطفا تغییرات را ذخیره کنید تا اعمال شود');
    }
}
