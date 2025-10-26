<!-- resources/js/Layouts/AppLayout.vue -->
<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const isMenuOpen = ref(false)
</script>

<template>
    <div class="app-layout min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <Link href="/" class="text-xl font-bold text-gray-800">
                        آموزشگاه
                    </Link>

                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex space-x-8">
                        <Link href="/" class="text-gray-700 hover:text-blue-600 transition">
                            صفحه اصلی
                        </Link>
                        <Link href="/courses" class="text-gray-700 hover:text-blue-600 transition">
                            دوره‌ها
                        </Link>
                        <Link href="/about" class="text-gray-700 hover:text-blue-600 transition">
                            درباره ما
                        </Link>
                        <Link href="/contact" class="text-gray-700 hover:text-blue-600 transition">
                            تماس با ما
                        </Link>
                    </nav>

                    <!-- Mobile menu button -->
                    <button
                        @click="isMenuOpen = !isMenuOpen"
                        class="md:hidden text-gray-700 focus:outline-none"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation -->
                <div v-show="isMenuOpen" class="md:hidden mt-4 space-y-2 pb-4">
                    <Link href="/" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        صفحه اصلی
                    </Link>
                    <Link href="/courses" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        دوره‌ها
                    </Link>
                    <Link href="/about" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        درباره ما
                    </Link>
                    <Link href="/contact" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        تماس با ما
                    </Link>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About Section -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">درباره ما</h3>
                        <p class="text-gray-300">
                            آموزشگاه ما با سال‌ها تجربه در زمینه آموزش، بهترین خدمات آموزشی را به شما ارائه می‌دهد.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">لینک‌های سریع</h3>
                        <ul class="space-y-2">
                            <li><Link href="/about" class="text-gray-300 hover:text-white">درباره ما</Link></li>
                            <li><Link href="/courses" class="text-gray-300 hover:text-white">دوره‌ها</Link></li>
                            <li><Link href="/contact" class="text-gray-300 hover:text-white">تماس با ما</Link></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">راه‌های ارتباطی</h3>
                        <ul class="space-y-2 text-gray-300">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                info@example.com
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                021-12345678
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400">
                    <p>© 2025 آموزشگاه. تمامی حقوق محفوظ است.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Add any custom styles here */
</style>
