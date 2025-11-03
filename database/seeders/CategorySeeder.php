<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test category for courses
        $categories = [
            [
                'title' => 'دوره‌های تستی',
                'type' => 'course',
                'slug' => 'test-courses',
                'icon' => 'test-tube',
                'description' => 'این دسته بندی برای دوره‌های تستی در نظر گرفته شده است.'
            ],
            [
                'title' => 'دوره‌های تستی دو',
                'type' => 'course',
                'slug' => 'test-courses2',
                'icon' => 'test-tube2',
                'description' => 'این دسته بندی برای دوره‌های تست دو در نظر گرفته شده است.'
            ]
        ];

        // Create some general categories as examples

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
