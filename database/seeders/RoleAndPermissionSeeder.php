<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ایجاد دسترسی‌های پایه
        $permissions = [
            // مدیریت کاربران

            // مدیریت محتوا

            // تنظیمات سیستم

            // پروفایل کاربری
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ایجاد نقش‌ها و اختصاص دسترسی‌ها
        $superAdmin = Role::create(['name' => 'super-admin']);
        $admin = Role::create(['name' => 'admin']);
        $contentManager = Role::create(['name' => 'content-manager']);
        $teacher = Role::create(['name' => 'teacher']);
        $user = Role::create(['name' => 'user']);

        // اختصاص دسترسی‌ها به نقش‌ها
        // 1. سوپر ادمین - دسترسی به همه چیز
        //$superAdmin->syncPermissions(Permission::all());

        // 2. ادمین - دسترسی کامل به غیر از مدیریت کاربران
        /*$admin->syncPermissions([
            'view course', 'create course', 'edit course', 'delete course',
            'view profile', 'edit profile'
        ]);*/

        // 3. مدیر محتوا - فقط دسترسی به مدیریت محتوا
        /*$contentManager->syncPermissions([
            'view course', 'create course', 'edit course',
            'view profile', 'edit profile'
        ]);*/

        // 4. کاربر عادی - فقط دسترسی به پروفایل خودش
        /*$user->syncPermissions([
            'view profile', 'edit profile'
        ]);*/
    }
}
