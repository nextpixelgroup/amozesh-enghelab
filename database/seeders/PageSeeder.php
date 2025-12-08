<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageMeta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $about = Page::create([
            'slug' => 'about',
            'type' => 'about',
            'title' => 'درباره ما',
            'summary' => '',
            'content' => '',
            'status' => 'publish',
            'published_at' => now(),
        ]);

        $contact = Page::create([
            'slug' => 'contact',
            'type' => 'contact',
            'title' => 'تماس با ما',
            'summary' => '',
            'content' => '',
            'status' => 'publish',
            'published_at' => now(),
        ]);
    }
}
