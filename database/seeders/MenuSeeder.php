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
        $home = Menu::create([
            'title' => 'خانه',
            'icon' => 'mdi-home',
            'url' => '/',
            'order' => 1,
            'is_active' => true,
            'target' => '_self',
            'type' => 'header',
        ]);

        $about = Menu::create([
            'title' => 'درباره ما',
            'icon' => 'mdi-information',
            'url' => '/about',
            'order' => 2,
            'is_active' => true,
            'target' => '_self',
            'type' => 'header',
        ]);

        $contact = Menu::create([
            'title' => 'تماس با ما',
            'icon' => 'mdi-phone',
            'url' => '/contact',
            'order' => 3,
            'is_active' => true,
            'target' => '_self',
            'type' => 'footer',
        ]);

        // زیرمنوها برای "خدمات"
        Menu::create([
            'title' => 'دوره ها',
            'icon' => 'mdi-monitor',
            'url' => '/courses',
            'order' => 4,
            'is_active' => true,
            'target' => '_self',
            'type' => 'submenu',
        ]);

        Menu::create([
            'title' => 'کتب',
            'icon' => 'mdi-code-tags',
            'url' => '/books',
            'order' => 5,
            'is_active' => true,
            'target' => '_self',
            'type' => 'submenu',
        ]);

        Menu::create([
            'title' => 'اساتید',
            'icon' => 'mdi-code-tags',
            'url' => '/teachers',
            'order' => 6,
            'is_active' => true,
            'target' => '_self',
            'type' => 'submenu',
        ]);

    }
}
