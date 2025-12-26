<template>
    <AdminLayout>

        <div class="d-flex">

            <!-- Right menu -->
            <v-sheet
                class="pa-4 settings-menu"
                elevation="1"
                width="260"
                style="background:#fff; border-radius:8px; height:100%;"
            >
                <v-tabs
                    direction="vertical"
                    color="primary"
                    grow
                    class="w-100"
                    hide-slider
                    :model-value="null"
                >
                    <v-tab
                        v-for="(item, index) in tabs"
                        :key="index"
                        class="w-100 text-start"
                        :href="item.route"
                        :class="{ 'active-tab': isActive(item.route) }"
                    >
                        {{ item.title }}
                    </v-tab>

                </v-tabs>
            </v-sheet>

            <!-- Left content -->
            <div class="flex-grow-1" style="overflow-y: auto;">
                <v-sheet elevation="1" rounded="lg" class="pa-6 mr-4">
                    <slot />
                </v-sheet>
            </div>
        </div>

    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";

const page = usePage()

const tabs = [
    { title: 'تنظیمات عمومی', route: '/admin/settings/general' },
    { title: 'فهرست‌ها', route: '/admin/settings/menus' }
]

const isActive = (route) => {
    return page.url.startsWith(route)
}
</script>
<style scoped>
.settings-menu a,
.settings-menu a:visited {
    color: #000 !important;
}

.active-tab {
    background: #eef1ff !important;
    border-radius: 6px;
    font-weight: 600;
}

.settings-menu .v-tab--selected::before,
.settings-menu .v-tab--selected::after,
.settings-menu .v-tab--selected .v-tab__overlay {
    display: none !important;
    opacity: 0 !important;
}
</style>
