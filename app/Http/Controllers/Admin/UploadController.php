<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Exception;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function bookImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'], // 5MB
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
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'], // 5MB
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
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'], // 5MB
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
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'], // 5MB
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

    public function destroy(Media $media, $type)
    {
        if($type == 'course'){
            $media->courseThumbnail()->update(['thumbnail_id' => null]);
        }
        elseif($type == 'book'){
            $media->bookThumbnail()->update(['thumbnail_id' => null]);
        }
        elseif($type == 'lesson'){

            $media->lessonPoster()->update(['poster_id' => null]);
        }
        elseif($type == 'page'){

            $media->pageThumbnail()->update(['poster_id' => null]);
        }
        elseif($type == 'pageSection1Thumbnail'){

            $media->pageThumbnail()->update(['poster_id' => null]);
        }
        else{
            return responseJSon('error', 'نوع فایل مشخص نشده است');
        }
        $media->deleteFile();
        return responseJSon('success', 'فایل از هاست حذف شد. لطفا تغییرات را ذخیره کنید تا اعمال شود');
    }
}
