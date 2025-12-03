<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebAboutResource;
use App\Http\Resources\WebBooksResource;
use App\Http\Resources\WebCoursesResource;
use App\Models\Book;
use App\Models\Course;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

class IndexController extends Controller
{
    public function index()
    {
        $slider = [
            [
                'title' => 'دوره کتاب چهل حکمت نبوی (ص)',
                'subtitle' => 'استاد: علیرضا عباسی',
                'image' => '/assets/img/sample/slide.png',
                'url' => '/courses',
            ],
            [
                'title' => 'دوره انقلاب اسلامی',
                'subtitle' => 'استاد: حسین محبی',
                'image' => '/assets/img/sample/slide.png',
                'url' => '/books',
            ]
        ];

        $limit = 4;
        $query = Course::query()
            ->where('status', 'publish')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
        $popularCourses = WebCoursesResource::collection($query);

        $query = Course::query()
            ->where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
        $latestCourses = WebCoursesResource::collection($query);

        $query = Book::query()
            ->where('status', 'publish')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
        $bestsellerBooks = WebBooksResource::collection($query);

        $stats = Setting::getValue('statCourses');
        $stats['courses'] = number_format(@$stats['courses']);
        $stats['ratings'] = number_format(@$stats['ratings'],1);
        $stats['students'] = number_format(@$stats['students']);
        $stats['seasons'] = number_format(@$stats['seasons']);

        $institutions = User::whereHas('roles', function ($query) {
            $query->where('name', 'institution');
        })->with('roles')->get()->map(function ($institution) {
            return [
                'title' => $institution->firstname,
                'avatar' => $institution->avatar?->url,
            ];
        });

        $query = Page::where('type','about')->first();
        $about = [
            'title'     => $query->title,
            'content'   => $query->content,
            'thumbnail' => $query->thumbnail?->url,
            'url' => route('web.about.index'),
        ];
        return inertia('Web/Index', compact('slider', 'popularCourses', 'latestCourses', 'stats', 'bestsellerBooks', 'institutions', 'about'));
    }
}
