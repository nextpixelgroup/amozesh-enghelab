import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'

export default defineConfig(({ ssrBuild }) => ({
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
}))
