import { createInertiaApp } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'

createServer(page => createInertiaApp({
    page,
    render: renderToString,
    resolve: async (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        const path = `./Pages/${name}.vue`

        if (!pages[path]) {
            throw new Error(`Page not found: ${path}`)
        }

        const page = pages[path].default

        // غیرفعال کردن SSR برای مسیرهای admin و panel
        const noSsrPaths = ['admin', 'panel']
        const shouldDisableSsr = noSsrPaths.some(prefix =>
            name.toLowerCase().startsWith(prefix.toLowerCase() + '/') ||
            name.toLowerCase() === prefix.toLowerCase()
        )

        if (shouldDisableSsr) {
            page.ssr = false
            console.log(`SSR disabled for: ${name}`) // برای دیباگ
        }
        else{

            console.log(`SSR enable for: ${name}`) // برای دیباگ
        }

        return page
    },
    setup({ el, App, props, plugin }) {
        return createSSRApp({
            render: () => h(App, props)
        }).use(plugin)
    },
}))
