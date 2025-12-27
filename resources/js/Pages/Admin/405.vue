<template>
    <v-container fluid class="fill-height  justify-center align-center">
        <v-row no-gutters>
            <v-col cols="12" class="text-center persian-wrapper">

                <!-- آیکون مینیمال -->
                <div class="mb-2">
                    <v-icon
                        icon="mdi-leaf"
                        size="80"
                        color="green-lighten-2"
                        class="opacity-50"
                    ></v-icon>
                </div>

                <!-- عدد 404 بزرگ -->
                <h1 class="text-h1 font-weight-black text-green-darken-3 mb-5" style="font-size: 8rem !important; line-height: 1;">
                    405
                </h1>

                <!-- تیتر پیام -->
                <h2 class="text-h4 font-weight-bold text-blue-grey-darken-3 mb-7">
                    صفحه پیدا نشد
                </h2>

                <!-- توضیحات کوتاه -->
                <p class="text-body-1 text-grey-darken-1 mb-10 mx-auto" style="max-width: 500px;">
                    صفحه‌ای که به دنبال آن هستید وجود ندارد یا جابجا شده است.
                    <br>
                    نگران نباشید، می‌توانید به خانه برگردید.
                </p>

                <!-- دکمه بازگشت (استایل Tonal برای مینیمال بودن) -->
                <v-btn
                    color="green-darken-1"
                    variant="tonal"
                    size="x-large"
                    rounded="pill"
                    class="px-10 font-weight-bold"
                    elevation="0"
                    @click="goBack"
                >
                    بازگشت
                </v-btn>

            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import {Head, router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const goBack = () => {
    return router.visit(route('admin.courses.index'));
};
</script>

<style scoped>
/* اعمال تنظیمات فونت فارسی */
.persian-wrapper :deep(*) {
    font-family: inherit !important;
    letter-spacing: 0 !important;
}

/* برای اینکه عدد 404 در موبایل خیلی بزرگ نباشد */
@media (max-width: 600px) {
    h1.text-h1 {
        font-size: 5rem !important;
    }
}
</style>
