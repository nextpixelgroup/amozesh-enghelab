<!-- resources/js/Layouts/AdminLayout.vue -->
<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="admin-layout">
        <header class="bg-gray-800 text-white p-4">
            <nav class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">پنل مدیریت</h1>
                <div class="space-x-4">
                    <Link href="/admin/dashboard" class="hover:text-gray-300 mask-b-from-lime-400 mr-4">داشبورد</Link>
                    <Link href="/admin/courses" class="hover:text-gray-300">دروس</Link>
                    <Link href="/admin/users" class="hover:text-gray-300">کاربران</Link>
                </div>
            </nav>
        </header>

        <main class="container mx-auto p-4">
            <slot />
        </main>

        <footer class="bg-gray-100 p-4 mt-8">
            <div class="container mx-auto text-center text-gray-600">
                تمامی حقوق محفوظ است © 2025
            </div>
        </footer>
    </div>
</template>
