<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebPathResource;
use App\Models\EnrollPath;
use App\Models\Path;
use Exception;
use Illuminate\Http\Request;

class PathController extends Controller
{
    public function index(Request $request)
    {
        $query = Path::with(['items.course' => function ($q) {
            $q->where('status', 'publish');
        }])->whereHas('items.course', function ($q) {
            $q->where('status', 'publish');
        })->get();

        $enrollPath = [];
        $isAuth = auth()->check();
        if($isAuth){
            $pathIds = $query->pluck('id')->toArray();
            $enrollPath = EnrollPath::query()->whereIn('path_id',$pathIds)->where('user_id',$request->user()->id)->get()->toArray();
        }
        $enrollPathMap = array_column($enrollPath, null, 'path_id');

        $finalQuery = $query->map(function ($path) use ($enrollPathMap) {
            $path->enrolled = isset($enrollPathMap[$path->id]) ? true : false;
            return $path;
        });

        $path = WebPathResource::collection($finalQuery);


        $pageTitle = 'سیرمطالعاتی';
        return inertia('Web/Path', compact('path', 'pageTitle','enrollPath'));
    }

    public function enroll(Path $path, Request $request)
    {
        try {
            $user = auth()->user();
            if(!$user){
                return redirectMessage('error', 'ابتدا وارد سایت شوید', redirect: route('panel.index', ['redirect' => url()->previous()]));
            }

            $enrollPath = EnrollPath::where('path_id',$path->id)->where('user_id',$user->id)->exists();
            if($enrollPath){
                return redirectMessage('error','شما قبلا در این مسیر ثبت‌نام شده‌اید.');
            }
            $create = EnrollPath::create([
                'user_id' => $user->id,
                'path_id' => $path->id,
            ]);
            return redirectMessage('success', 'با موفقیت در مسیر ثبت‌نام ثبت‌نام کردید.');
        }
        catch (Exception $e){
            $error = log_error($e);
            return redirectMessage('error', "خطایی پیش آمد(کدخطا: $error->id)");
        }

    }
}
