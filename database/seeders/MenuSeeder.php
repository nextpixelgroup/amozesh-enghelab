<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::truncate();

        // منوهای ریشه
        Menu::create([
            'title' => 'خانه',
            'icon' => 'mdi-home',
            'url' => '/',
            'order' => 1,
            'is_active' => true,
            'target' => '_self',
            'type' => 'header',
        ]);

        Menu::create([
            'title' => 'دوره ها',
            'icon' => 'mdi-monitor',
            'url' => '/courses',
            'order' => 2,
            'is_active' => true,
            'target' => '_self',
            'type' => 'submenu',
        ]);

        Menu::create([
            'title' => 'فروشگاه کتاب',
            'icon' => 'mdi-code-tags',
            'url' => '/books',
            'order' => 3,
            'is_active' => true,
            'target' => '_self',
            'type' => 'submenu',
        ]);

        Menu::create([
            'title' => 'سیرمطالعاتی',
            'icon' => 'mdi-multicast',
            'url' => '/path',
            'order' => 4,
            'is_active' => true,
            'target' => '_self',
            'type' => 'submenu',
        ]);

        Menu::create([
            'title' => 'معرفی اساتید',
            'icon' => 'mdi-code-tags',
            'url' => '/teachers',
            'order' => 5,
            'is_active' => true,
            'target' => '_self',
            'type' => 'submenu',
        ]);

        Menu::create([
            'title' => 'درباره ما',
            'icon' => 'mdi-information',
            'url' => '/about',
            'order' => 6,
            'is_active' => true,
            'target' => '_self',
            'type' => 'header',
        ]);

        Menu::create([
            'title' => 'تماس با ما',
            'icon' => 'mdi-phone',
            'url' => '/contact',
            'order' => 7,
            'is_active' => true,
            'target' => '_self',
            'type' => 'footer',
        ]);



    }
}
