<!-- resources/js/Layouts/PanelLayout.vue -->
<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="panel-layout">
        <header class="bg-blue-600 text-white p-4">
            <nav class="container mx-auto flex justify-between items-center">
                <h1 class="text-xl font-bold">پنل کاربری</h1>
                <div class="space-x-4">
                    <Link href="/panel/dashboard" class="hover:text-blue-200">داشبورد</Link>
                    <Link href="/panel/profile" class="hover:text-blue-200">پروفایل</Link>
                </div>
            </nav>
        </header>

        <main class="container mx-auto p-4">
            <slot />
        </main>

        <footer class="bg-gray-100 p-4 mt-8">
            <div class="container mx-auto text-center text-gray-600">
                پنل کاربری شما
            </div>
        </footer>
    </div>
</template>
