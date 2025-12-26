<template>
    <AdminLayout>
        <!-- استفاده از v-container برای مدیریت padding کلی و v-row برای ساختاردهی -->
        <v-container fluid class="pa-0">
            <v-row no-gutters>
                <!-- ستون منوی تنظیمات (سایدبار) -->
                <v-col cols="12" sm="4" md="3" lg="2" class="pl-4">
                    <v-sheet
                        class="pa-4 settings-menu sticky-sheet"
                        elevation="4"
                        rounded="lg"
                    >
                        <v-tabs
                            direction="vertical"
                            color="primary"
                            class="w-100"
                            hide-slider
                            :model-value="null"
                        >
                            <div v-for="(item, index) in tabs" :key="index" class="w-100">
                                <Link
                                    :href="item.route"
                                    class="v-tab d-flex align-center px-4 text-decoration-none"
                                    :class="{ 'active-tab': isActive(item.route) }"
                                    style="min-height: 48px; width: 100%;"
                                    preserve-scroll
                                >
                                    {{ item.title }}
                                </Link>
                            </div>
                        </v-tabs>
                    </v-sheet>
                </v-col>

                <!-- ستون محتوا (اسلات) -->
                <v-col cols="12" sm="8" md="9" lg="10">
                    <v-sheet elevation="2" rounded="lg" class="pa-6">
                        <slot />
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>

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

/**
 * تعیین اینکه آیا تب جاری فعال است یا خیر
 * Vuetify Tab را به لینک Inertia تبدیل می کند.
 */
const isActive = (route) => {
    // از startsWith برای پوشش دادن مسیرهای فرزند استفاده می کنیم
    return page.url.startsWith(route)
}
</script>

<style scoped>
/* ثابت کردن موقعیت سایدبار در حالت اسکرول */
.sticky-sheet {
    position: sticky;
    top: 24px; /* یا هر مقدار دیگری که فاصله از بالا را مشخص کند */
}

/* رنگ متن پیش فرض تب ها (سیاه) */
.settings-menu a,
.settings-menu a:visited {
    color: #1b5e20;
    /* اضافه کردن ترنزیشن برای هاور نرم */
    transition: all 0.2s ease-in-out;
}

/* استایل تب فعال (سبز درخواستی) */
.active-tab {
    /* رنگ پس زمینه سبز ملایم: light-green-lighten-4 */
    background: #0b693c !important;
    border-radius: 6px !important;
    font-weight: 600 !important;
    /* رنگ متن سبز تیره: green-darken-4 */
    color: #fff !important;
}

/* تنظیمات Vuetify: مخفی کردن نوارهای فعال سازی پیش فرض */
.settings-menu .v-tab--selected::before,
.settings-menu .v-tab--selected::after,
.settings-menu .v-tab--selected .v-tab__overlay {
    display: none !important;
    opacity: 0 !important;
}

</style>
