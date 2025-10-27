import './bootstrap'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import { VApp, VMain, VForm, VTextField, VBtn } from 'vuetify/components'
import { Ripple } from 'vuetify/directives'

const vuetify = createVuetify({
    components: { VApp, VMain, VForm, VTextField, VBtn },
    directives: { Ripple },
    theme: {
        defaultTheme: 'light',
    },
})

createInertiaApp({
    resolve: async name => {
        const pages = import.meta.glob('./Pages/**/*.vue')
        const importer = pages[`./Pages/${name}.vue`]
        if (!importer) throw new Error(`Page not found: ${name}`)
        const mod = await importer()
        return mod.default
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify)
            .mount(el)
    },
})
