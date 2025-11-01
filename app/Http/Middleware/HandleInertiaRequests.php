<?php

namespace App\Http\Middleware;

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
        return [
            ...parent::share($request),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
            ],
            'menuItems' => [
                [
                    'title' => 'دروس',
                    'icon' => 'mdi-book-open-page-variant',
                    'route' => 'admin.courses.index',
                    'children' => [
                        [
                            'title' => 'لیست دوره‌ها',
                            'icon'  => 'mdi-format-list-bulleted',
                            'route' => 'admin.courses.index'
                        ],
                        [
                            'title' => 'ایجاد دوره جدید',
                            'icon' => 'mdi-plus-circle',
                            'route' => 'admin.courses.create'
                        ],
                        [
                            'title' => 'دسته‌بندی‌ها',
                            'icon' => 'mdi-shape',
                            'route' => 'admin.courses.categories.index'
                        ],
                    ]
                ],
                [
                    'title' => 'کاربران',
                    'icon' => 'mdi-account-group',
                    'route' => 'admin.users.index'
                ],
                [
                    'title' => 'کتاب‌ها',
                    'icon' => 'mdi-book',
                    'route' => 'admin.books.index',
                    'children' => [
                        [
                            'title' => 'لیست کتب',
                            'icon'  => 'mdi-format-list-bulleted',
                            'route' => 'admin.books.index'
                        ],
                        [
                            'title' => 'دسته‌بندی‌ها',
                            'icon' => 'mdi-shape',
                            'route' => 'admin.books.categories.index'
                        ],
                    ]
                ],
                [
                    'title' => 'مسیرها',
                    'icon' => 'mdi-routes',
                    'route' => 'admin.paths.index'
                ],
                [
                    'title' => 'سفارشات',
                    'icon' => 'mdi-cart',
                    'route' => 'admin.orders.index'
                ],
                [
                    'title' => 'نظرات',
                    'icon' => 'mdi-comment',
                    'route' => 'admin.comments.index'
                ],
                [
                    'title' => 'پیام ها',
                    'icon' => 'mdi-message',
                    'route' => 'admin.supports.index'
                ],
                [
                    'title' => 'تنظیمات',
                    'icon' => 'mdi-cog',
                    'route' => 'admin.settings.index'
                ],
            ],
        ];
    }
}
