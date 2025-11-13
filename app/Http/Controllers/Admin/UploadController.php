<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
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
}
