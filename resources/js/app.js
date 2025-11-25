import './bootstrap';
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';

// Import Vuetify and MDI
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css'
import {createVuetify} from 'vuetify';
import * as components from 'vuetify/components';
import {VDateInput} from 'vuetify/labs/VDateInput'

import * as directives from 'vuetify/directives';
import {aliases, mdi} from 'vuetify/iconsets/mdi'
import {fa, en} from 'vuetify/locale'

const vuetify = createVuetify({
    components: {
        VDateInput
    },
    directives,

    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                colors: {
                    primary: 'rgb(5, 105, 60)',
                    secondary: 'rgb(200, 160, 100)',
                },
            },
        },
        defaultAssets: {
            font: {
                family: 'Estedad',
            },
            icons: 'mdi',
        },
    },
    icons: {
        defaultSet: 'mdi',
        aliases: {
            ...aliases,
        },
        sets: {
            mdi,
        },
    },
    defaults: {
        VSelect: {
            menuIcon: 'mdi-chevron-down'
        },
        VAutocomplete: {
            menuIcon: 'mdi-chevron-down'
        }
    },
    rtl: true,
    locale: {
        locale: 'fa',
        fallback: 'en',
        messages: {fa, en},
    },
})
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(vuetify)
            .mount(el)
    },
})
