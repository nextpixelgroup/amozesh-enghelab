<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebPathResource;
use App\Models\Path;
use Illuminate\Http\Request;

class PathController extends Controller
{
    public function index()
    {
        $query = Path::query()->orderBy('order')->get();
        $path = WebPathResource::collection($query);
        $pageTitle = 'سیرمطالعاتی';
        return inertia('Web/Path', compact('path', 'pageTitle'));
    }
}
