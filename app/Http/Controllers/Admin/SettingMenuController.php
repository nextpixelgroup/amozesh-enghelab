<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingMenuController extends Controller
{


    public function index()
    {
        $query = Menu::whereNull('parent_id')
            ->with('children')
            ->get()
            ->groupBy('type');
        $menus = [
            [
                'type' => 'header',
                'title' => 'هدر',
                'items' => isset($query['header']) ? $query['header']->toArray() : []
            ],
            [
                'type' => 'footer-1',
                'title' => 'فوتر اول',
                'items' => isset($query['footer-1']) ? $query['footer-1']->toArray() : []
            ],
            [
                'type' => 'footer-2',
                'title' => 'فوتر دوم',
                'items' => isset($query['footer-2']) ? $query['footer-2']->toArray() : []
            ],
            [
                'type' => 'footer-3',
                'title' => 'فوتر سوم',
                'items' => isset($query['footer-3']) ? $query['footer-3']->toArray() : []
            ]
        ];
        return inertia('Admin/Settings/Menus', compact('menus'));
    }

    public function store(Request $request)
    {

        try {
            $maxOrder = Menu::where('type', $request->type)
                ->whereNull('parent_id')
                ->max('order') ?? 0;
            $menu = Menu::create([
                'title' => $request->title,
                'icon' => $request->icon,
                'url' => $request->url,
                'parent_id' => null,
                'order' => $maxOrder+1,
                'is_active' => true,
                'target' => $request->target,
                'type' => $request->type,
            ]);
            if($menu){
                return redirectMessage('success', 'فهرست جدید با موفقیت اضافه شد');
            }
        }
        catch (\Exception $exception){
            $error = log_error($exception);
            return redirectMessage('error', "مشکلی در ایجاد پیش آمد(کدخطا: $error->id)");
        }
    }
    public function update(Request $request, Menu $menu)
    {
        try {
            $menu->update([
                'title' => $request->title,
                'icon' => $request->icon,
                'url' => $request->url,
                'target' => $request->target,
            ]);
            return redirectMessage('success', 'فهرست با موفقیت ویرایش شد');
        }
        catch (\Exception $exception){
            $error = log_error($exception);
            return redirectMessage('error', "مشکلی در ویرایش پیش آمد(کدخطا: $error->id)");
        }
    }

    public function order(Request $request)
    {
        try {
            DB::beginTransaction();

            $menuType = $request->menu_type;
            $items = $request->items;

            if (!is_array($items) || empty($items)) {
                return redirectMessage('success', 'آیتمی وجود ندارد');
            }

            // Update each menu item's order
            foreach ($items as $item) {
                Menu::where('id', $item['id'])
                    ->where('type', $menuType)
                    ->update([
                        'order' => $item['order'],
                        'parent_id' => $item['parent_id'] ?? null
                    ]);
            }

            DB::commit();
            return redirectMessage('success', 'ترتیب فهرست با موفقیت ویرایش شد');

        } catch (\Exception $e) {
            DB::rollBack();
            $error = log_error($e);
            return redirectMessage('error', "مشکلی در حذف پیش آمد(کدخطا: $error->id)");
        }
    }

    public function destroy(Request $request, Menu $menu)
    {
        try {
            $menu->delete();
            return redirectMessage('success', 'فهرست با موفقیت حذف شد');

        }
        catch (\Exception $exception){
            $error = log_error($exception);
            return redirectMessage('error', "مشکلی در حذف پیش آمد(کدخطا: $error->id)");
        }
    }
}
