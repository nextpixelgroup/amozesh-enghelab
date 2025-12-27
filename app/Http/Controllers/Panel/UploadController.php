<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
            return responseJson('error', ' خطا در آپلود فایل');
        }

        return responseJson('success', 'فایل با موفقیت آپلود شد', [
            'path'    => $media->path,
            'url'     => $media->url,
            'id'      => $media->id,
        ]);
    }

    public function destroy(Media $media, $type, Request $request)
    {
        if (Gate::denies('delete', $media)) {
            return responseJson('error', 'شما اجازه حذف این فایل را ندارید');
        }
        if($type === 'nationalCardImage'){
            $media->userNationalCardImage()->update(['national_card_image_id' => null]);
        }
        elseif($type === 'avatar'){
            $media->userAvatar()->update(['avatar_id' => null]);
        }
        else{
            return responseJson('error', 'نوع فایل مشخص نشده است');
        }
        $media->deleteFile();
        return responseJson('success', 'فایل از هاست حذف شد. لطفا تغییرات را ذخیره کنید تا اعمال شود');
    }
}
