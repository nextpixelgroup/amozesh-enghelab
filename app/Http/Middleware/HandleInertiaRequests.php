<?php

namespace App\Http\Middleware;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $path = $request->path();
        $shared = [
            ...parent::share($request),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
            ],
        ];

        if (str_starts_with($request->path(), 'admin')) {
            $shared['menuItems'] = [
                [
                    'title' => 'دوره‌ها',
                    'icon' => 'mdi-book-open-page-variant',
                    'route' => 'admin.courses.index',
                    'children' => [
                        [
                            'title' => 'لیست دوره‌ها',
                            'icon'  => 'mdi-format-list-bulleted',
                            'route' => 'admin.courses.index'
                        ],
                        [
                            'title' => 'ایجاد دوره',
                            'icon'  => 'mdi-file-plus',
                            'route' => 'admin.courses.create'
                        ],
                        [
                            'title' => 'دسته‌بندی‌ها',
                            'icon' => 'mdi-format-list-group-plus',
                            'route' => 'admin.courses.categories.index'
                        ],
                    ]
                ],
                [
                    'title' => 'کتب',
                    'icon' => 'mdi-book',
                    'route' => 'admin.books.index',
                    'children' => [
                        [
                            'title' => 'لیست کتب',
                            'icon'  => 'mdi-format-list-bulleted',
                            'route' => 'admin.books.index'
                        ],
                        [
                            'title' => 'ایجاد کتاب',
                            'icon'  => 'mdi-file-plus',
                            'route' => 'admin.books.create'
                        ],
                        [
                            'title' => 'دسته‌بندی‌ها',
                            'icon' => 'mdi-format-list-group-plus',
                            'route' => 'admin.books.categories.index'
                        ],
                    ]
                ],
                [
                    'title' => 'مسیرها',
                    'icon' => 'mdi-multicast',
                    'route' => 'admin.paths.index'
                ],
                [
                    'title' => 'سفارش‌ها',
                    'icon' => 'mdi-cart',
                    'route' => 'admin.orders.index'
                ],
                [
                    'title' => 'نظرها',
                    'icon' => 'mdi-forum',
                    'route' => 'admin.comments.index'
                ],
                [
                    'title' => 'پیام ها',
                    'icon' => 'mdi-message-text-fast',
                    'route' => 'admin.tickets.index'
                ],
                [
                    'title' => 'کاربران',
                    'icon' => 'mdi-account-group',
                    'route' => 'admin.users.index'
                ],
                [
                    'title' => 'تنظیمات',
                    'icon' => 'mdi-cog',
                    'route' => 'admin.settings.index'
                ],
            ];
            $shared['ticketCount'] = Ticket::where('read_at', null)->count();
        }
        elseif (str_starts_with($request->path(), 'panel')) {

        }
        else{

        }
        return $shared;
    }
}
