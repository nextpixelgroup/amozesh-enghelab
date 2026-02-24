<?php

namespace App\Http\Middleware;

use App\Models\Contact;
use App\Models\Menu;
use App\Models\Ticket;
use App\Services\SettingsService;
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
        $user = $request->user();
        $shared = array_merge(parent::share($request), [
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
                // --- این خط را اضافه کنید ---
                'data' => fn () => $request->session()->get('data'),
            ],
        ]);

        if (str_starts_with($request->path(), 'admin')) {
            if($user &&in_array($user->roles->first()->name,['admin','super-admin'])){
                $shared['menuItems'] = [
                    [
                        'title' => 'دوره‌ها',
                        'icon' => 'mdi-laptop',
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
                        'icon' => 'mdi-book-open-page-variant',
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
                        'title' => 'برگه ها',
                        'icon' => 'mdi-page-layout-sidebar-right',
                        'route' => 'admin.pages.index'
                    ],
                    [
                        'title' => 'سفارش‌ها',
                        'icon' => 'mdi-cart',
                        'route' => 'admin.orders.index'
                    ],
                    [
                        'title' => 'آزمون‌ها',
                        'icon' => 'mdi-help-box-multiple',
                        'route' => 'admin.quizzes.index'
                    ],
                    [
                        'title' => 'نظرها',
                        'icon' => 'mdi-forum',
                        'route' => 'admin.comments.index'
                    ],
                    [
                        'title' => 'پشتیبانی',
                        'icon' => 'mdi-message-text-fast',
                        'route' => 'admin.tickets.index'
                    ],
                    [
                        'title' => 'تماس با ما',
                        'icon' => 'mdi-bell-ring',
                        'route' => 'admin.contacts.index'
                    ],
                    [
                        'title' => 'کاربران',
                        'icon' => 'mdi-account-group',
                        'route' => 'admin.users.index'
                    ],
                    [
                        'title' => 'تنظیمات',
                        'icon' => 'mdi-cog',
                        'route' => 'admin.settings.general'
                    ],
                ];
                $shared['ticketCount'] = Ticket::where('read_at', null)->count();
                $shared['contactCount'] = Contact::where('read_at', null)->count();
            }
            elseif($user && in_array($user->roles->first()->name,['content-manager'])){
                $shared['menuItems'] = [
                    [
                        'title' => 'دوره‌ها',
                        'icon' => 'mdi-laptop',
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
                ];
            }
            $setting = new SettingsService;
            $shared['social'] = $setting->get('index.social');
        }
        else{

            $shared['menuItems'] = [
                [
                    'title' => 'پروفایل کاربری',
                    'icon' => 'mdi-account-circle',
                    'url' => route('panel.profile.index'),
                    'slug' => 'profile'
                ],
                [
                    'title' => 'آموزش‌های من',
                    'icon' => 'mdi-human-male-board',
                    'url' => route('panel.courses.index'),
                    'slug' => 'courses'
                ],
                [
                    'title' => 'علاقه‌مندی‌ها',
                    'icon' => 'mdi-bookmark',
                    'url' => route('panel.bookmarks.courses'),
                    'slug' => ['bookmarks/courses', 'bookmarks/books']
                ],
                [
                    'title' => 'گواهینامه‌ها',
                    'icon' => 'mdi-certificate',
                    'url' => route('panel.certificates.index'),
                    'slug' => 'certificates'
                ],
                [
                    'title' => 'سفارش‌ها',
                    'icon' => 'mdi-cart',
                    'url' => route('panel.orders.index'),
                    'slug' => 'orders'
                ],
                [
                    'title' => 'ارتباط با پشتیبان',
                    'icon' => 'mdi-phone-in-talk',
                    'url' => route('panel.supports.index'),
                    'slug' => 'supports'
                ],
            ];

            $menuTypes = ['header', 'footer-1', 'footer-2', 'footer-3'];
            $allMenus = Menu::where('is_active', 1)
                ->whereIn('type', $menuTypes)
                ->orderBy('type')
                ->orderBy('order')
                ->get()
                ->groupBy('type')
                ->mapWithKeys(function ($menus, $type) {
                    $grouped = $menus->groupBy('parent_id');
                    return [$type => buildMenuTree($grouped->get(null, collect()), $grouped)];
                });

            $shared['header'] = $allMenus->get('header', []);
            $shared['footer1'] = $allMenus->get('footer-1', []);
            $shared['footer2'] = $allMenus->get('footer-2', []);
            $shared['footer3'] = $allMenus->get('footer-3', []);

            $setting = new SettingsService;
            $shared['social'] = $setting->get('index.social');
            $shared['logos'] = $setting->get('web.logos');
            $shared['showCart'] = false;
            $shared['cartCount'] = 0;
            if($user){
                $shared['cartCount'] = $user->cart_items_count;
            }
            if(str_starts_with($request->path(), 'books')){
                $shared['showCart'] = true;
            }
            $shared['isAuth'] = false;
            if (auth()->check()){
                $shared['isAuth'] = true;
            }
        }
        return $shared;
    }
}
