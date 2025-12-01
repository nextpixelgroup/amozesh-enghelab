<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebAboutResource;
use App\Models\Page;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about = WebAboutResource::make(Page::where('type', 'about')->first());
        return inertia('Web/About', compact('about'));
    }
}
