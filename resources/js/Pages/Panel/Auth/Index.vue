<template>
    <!-- پس‌زمینه کل صفحه با سبز خیلی ملایم -->
    <WebLayout>
        <v-app class="bg-grey-lighten-5">
            <v-container fluid class="fill-height justify-center align-center">

                <v-row justify="center" align="center" class="w-100">
                    <v-col cols="12" sm="8" md="6" lg="4">

                        <!-- کارت اصلی -->
                        <v-card
                            class="pa-6 pa-md-10 rounded-xl persian-wrapper glass-card"
                            elevation="10"
                            color="white"
                        >
                            <!-- بخش لوگو و هدر -->
                            <div class="text-center mb-10">
                                <v-avatar size="80" color="green-lighten-5" class="mb-4">
                                    <v-icon size="45" color="green-darken-2">mdi-shield-account-outline</v-icon>
                                </v-avatar>

                                <h1 class="text-h5 font-weight-black text-green-darken-3 mb-2">
                                    خوش آمدید
                                </h1>
                                <p class="text-body-2 text-grey-darken-1">
                                    لطفاً برای ادامه، نوع حساب کاربری خود را انتخاب کنید
                                </p>
                            </div>

                            <!-- دکمه‌ها -->
                            <div class="d-flex flex-column gap-4">

                                <!-- دکمه ۱: کاربر معمولی -->
                                <v-hover v-slot="{ isHovering, props }">
                                    <v-btn
                                        v-bind="props"
                                        block
                                        height="65"
                                        :color="isHovering ? 'green-darken-2' : 'green-darken-1'"
                                        class="text-white rounded-lg transition-swing"
                                        :elevation="isHovering ? 8 : 2"
                                        prepend-icon="mdi-account-circle-outline"
                                        href="/login/user"
                                    >
                                        <div class="d-flex flex-column align-start py-2 w-100">
                                            <span class="text-body-1 font-weight-bold">ورود کاربر معمولی</span>
                                            <span class="text-caption text-green-lighten-4 opacity-80" style="font-weight: 300">
                       پنل دانشجویان و متقاضیان
                    </span>
                                        </div>
                                        <template v-slot:append>
                                            <v-icon>mdi-chevron-left</v-icon>
                                        </template>
                                    </v-btn>
                                </v-hover>

                                <!-- جداکننده متنی یا خطی (اختیاری) -->
                                <div class="text-center my-1 text-caption text-grey-lighten-1">یا</div>

                                <!-- دکمه ۲: دانشگاه و موسسه -->
                                <v-hover v-slot="{ isHovering, props }">
                                    <v-btn
                                        v-bind="props"
                                        block
                                        height="65"
                                        variant="outlined"
                                        :color="isHovering ? 'green-darken-3' : 'green-darken-2'"
                                        class="rounded-lg transition-swing border-opacity-75 bg-green-lighten-5"
                                        style="border-width: 2px;"
                                        :elevation="isHovering ? 4 : 0"
                                        prepend-icon="mdi-town-hall"
                                        href="/login/institution"
                                    >
                                        <div class="d-flex flex-column align-start py-2 w-100 text-green-darken-3">
                                            <span class="text-body-1 font-weight-bold">ورود کاربر دانشگاه و موسسه</span>
                                            <span class="text-caption text-green-darken-1 opacity-80" style="font-weight: 300">
                      پنل مدیریت مراکز آموزشی
                    </span>
                                        </div>
                                        <template v-slot:append>
                                            <v-icon>mdi-chevron-left</v-icon>
                                        </template>
                                    </v-btn>
                                </v-hover>

                            </div>

                            <!-- فوتر کارت -->
                            <div class="text-center mt-8">
                                <v-btn variant="text" size="small" color="grey-darken-1" class="text-caption">
                                    نیاز به راهنمایی دارید؟
                                </v-btn>
                            </div>

                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-app>
    </WebLayout>
</template>

<script setup>
// لاجیک خاصی فعلا نیاز نیست، لینک‌ها در href دکمه‌ها قرار دارند
import WebLayout from "@/Layouts/WebLayout.vue";
</script>

<style scoped>
/* --- تنظیمات فونت فارسی --- */
.persian-wrapper :deep(*) {
    font-family: inherit !important;
    letter-spacing: 0 !important;
}

/* --- فاصله‌گذاری بین دکمه‌ها --- */
.gap-4 {
    gap: 16px;
}

/* --- تنظیمات دکمه‌ها برای چیدمان متن --- */
/* این استایل باعث می‌شود متن داخل دکمه راست‌چین و منظم باشد */
:deep(.v-btn__content) {
    width: 100%;
    justify-content: space-between;
}

/* --- اشکال پس‌زمینه (Background Blobs) --- */
.bg-shape {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
    opacity: 0.6;
}

.shape-1 {
    top: -10%;
    right: -5%;
    width: 400px;
    height: 400px;
    background: rgba(76, 175, 80, 0.15); /* سبز کمرنگ */
}

.shape-2 {
    bottom: -10%;
    left: -5%;
    width: 300px;
    height: 300px;
    background: rgba(129, 199, 132, 0.15); /* سبز متفاوت */
}

/* --- افکت کارت شیشه‌ای (اختیاری) --- */
.glass-card {
    position: relative;
    z-index: 1;
    /* اگر می‌خواهید کمی پشت کارت معلوم باشد، opacity را تنظیم کنید، وگرنه bg-white کافی است */
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(10px);
}
</style>
