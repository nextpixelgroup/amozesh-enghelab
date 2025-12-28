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
        $query = Page::where('type', 'about')->where('status','publish')->first();
        if($query) {
            $about = WebAboutResource::make($query);
        }
        else{
            return redirect()->route('web.404');
        }
        return inertia('Web/About', compact('about'));
    }
}
