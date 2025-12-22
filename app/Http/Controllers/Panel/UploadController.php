<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class UploadController extends Controller
{
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

    public function destroy(Media $media, $type, Request $request)
    {
        if($type === 'nationalCardImage'){
            $media->userAvatar()->update(['national_card_image_id' => null]);
        }
        elseif($type === 'avatar'){
            $media->userNationalCardImage()->update(['avatar_id' => null]);
        }
        else{
            return responseJSon('error', 'نوع فایل مشخص نشده است');
        }
        $media->deleteFile();
        return responseJSon('success', 'فایل از هاست حذف شد. لطفا تغییرات را ذخیره کنید تا اعمال شود');
    }
}
