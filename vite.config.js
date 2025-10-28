import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'

import path from 'path';

export default defineConfig(({ ssrBuild }) => ({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({ autoImport: true }),
    ],

    // ✅ این بخش برای پشتیبانی Vuetify در SSR و جلوگیری از خطای .css
    ssr: {
        noExternal: ['vuetify'], // Vuetify رو از external خارج می‌کنه تا در SSR درست build بشه
    },

    build: {
        chunkSizeWarningLimit: 1600,

        // 👇 فقط برای SSR فایل‌های CSS رو ignore کن
        ...(ssrBuild
            ? {
                rollupOptions: {
                    external: [/\.css$/],
                },
            }
            : {}),
    },
    optimizeDeps: {
        include: ['vue', '@inertiajs/vue3', 'ziggy-js', 'vuetify'],
    },
    server: {
        // اختیاری: برای اینکه ارورهای HMR تجربه رفحص را متوقف نکند
        hmr: { overlay: false },
    },
}))
