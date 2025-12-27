<template>
    <Head title="ورود به سیستم" />
    <WebLayout>
        <v-container class="mt-8 fill-height justify-center align-center position-relative z-index-1">
            <v-row justify="center" class="w-100">
                <v-col cols="12" sm="10" md="7" lg="5" xl="4">

                    <v-card class="pa-6 pa-md-8 rounded-xl persian-wrapper glass-card" elevation="10">

                        <!-- هدر: لوگو و عنوان پویا -->
                        <div class="text-center mb-8">
                            <v-avatar size="80" color="green-lighten-5" class="mb-4 elevation-1">
                                <v-icon
                                    size="40"
                                    color="green-darken-2"
                                    :icon="type === 'student' ? 'mdi-account-school' : 'mdi-account'"
                                />
                            </v-avatar>
                            <h1 class="text-h5 font-weight-black text-green-darken-3 mb-2">
                                {{ type === 'student' ? 'ورود دانشجویان' : 'ورود کاربران' }}
                            </h1>
                            <p class="text-body-2 text-grey-darken-1">
                                برای دسترسی به پنل، احراز هویت الزامی است
                            </p>
                        </div>

                        <!-- ناحیه فرم با انیمیشن تغییر مرحله -->
                        <v-window v-model="step">

                            <!-- مرحله ۱: دریافت شماره موبایل -->
                            <v-window-item :value="1">
                                <v-form @submit.prevent="sendCode">
                                    <div class="mb-2 text-right">
                                        <span class="text-caption font-weight-bold text-grey-darken-2">شماره همراه</span>
                                    </div>

                                    <v-text-field
                                        v-model="form.mobile"
                                        placeholder="مثال: 09123456789"
                                        variant="outlined"
                                        color="green-darken-2"
                                        prepend-inner-icon="mdi-cellphone"
                                        rounded="lg"
                                        class="centered-input mb-2"
                                        :rules="[v => !!v || 'شماره موبایل الزامی است']"
                                        @input="form.mobile = toEnglishDigits(form.mobile)"
                                        autofocus
                                    />

                                    <v-btn
                                        block
                                        size="large"
                                        type="submit"
                                        color="green-darken-2"
                                        class="text-white rounded-lg mt-4"
                                        height="50"
                                        :loading="isLoading"
                                        elevation="3"
                                    >
                                        <span class="font-weight-bold">دریافت کد تایید</span>
                                        <v-icon end>mdi-arrow-left</v-icon>
                                    </v-btn>
                                </v-form>
                            </v-window-item>

                            <!-- مرحله ۲: تایید کد OTP -->
                            <v-window-item :value="2" class="text-center">
                                <div class="mb-6 bg-green-lighten-5 pa-3 rounded-lg d-flex justify-space-between align-center">
                                        <span class="text-caption text-grey-darken-2">
                                            کد ارسال شده به: <strong dir="ltr">{{ form.mobile }}</strong>
                                        </span>
                                    <v-btn
                                        variant="text"
                                        density="compact"
                                        color="green-darken-2"
                                        size="small"
                                        @click="step = 1"
                                    >
                                        (ویرایش)
                                    </v-btn>
                                </div>

                                <v-form @submit.prevent="verifyCode">
                                    <div class="d-flex justify-center mb-6 dir-ltr-force">
                                        <v-otp-input
                                            v-model="form.code"
                                            length="5"
                                            variant="outlined"
                                            color="green-darken-2"
                                            height="60"
                                            class="custom-otp"
                                            autofocus
                                            @finish="verifyCode"
                                            @input="form.code = toEnglishDigits(form.code)"
                                        />
                                    </div>

                                    <v-btn
                                        block
                                        size="large"
                                        type="submit"
                                        color="green-darken-2"
                                        class="text-white rounded-lg mb-4"
                                        height="50"
                                        :loading="isLoading"
                                        elevation="3"
                                    >
                                        ورود به پنل
                                    </v-btn>
                                </v-form>

                                <!-- تایمر و ارسال مجدد -->
                                <div class="mt-4">
                                    <v-fade-transition mode="out-in">
                                        <div v-if="countdown > 0" key="timer" class="text-body-2 text-grey-darken-1">
                                            <v-icon size="small" class="ml-1">mdi-timer-outline</v-icon>
                                            ارسال مجدد تا
                                            <strong class="text-green-darken-2 mx-1">{{ countdown }}</strong>
                                            ثانیه دیگر
                                        </div>
                                        <v-btn
                                            v-else
                                            key="resend"
                                            variant="text"
                                            color="green-darken-3"
                                            prepend-icon="mdi-refresh"
                                            @click="resendCode"
                                            :loading="isResending"
                                        >
                                            ارسال مجدد کد
                                        </v-btn>
                                    </v-fade-transition>
                                </div>
                            </v-window-item>
                        </v-window>

                        <!-- فوتر کارت -->
                        <div class="mt-8 text-center">
                            <v-divider class="mb-4"></v-divider>
                            <a href="/" class="text-decoration-none text-caption text-grey hover-green transition-swing">
                                بازگشت به صفحه اصلی سایت
                            </a>
                        </div>

                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </WebLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ref, onMounted } from "vue";
import { route } from "ziggy-js";
import WebLayout from "@/Layouts/WebLayout.vue";
import { toEnglishDigits } from "@/utils/helpers.js";

const props = defineProps({
    type: {
        type: String,
        default: 'user' // 'user' or 'institution'
    }
});

const isLoading = ref(false);
const isResending = ref(false);
const step = ref(1);
const countdown = ref(0);
let timerInterval = null;

const form = useForm({
    mobile: '',
    code: '',
    type: props.type, // مقداردهی اولیه نوع کاربر
});

// شروع تایمر معکوس
const startCountdown = (seconds) => {
    countdown.value = seconds;
    clearInterval(timerInterval);
    timerInterval = setInterval(() => {
        if (countdown.value > 0) {
            countdown.value--;
        } else {
            clearInterval(timerInterval);
        }
    }, 1000);
};

const sendCode = () => {
    if(!form.mobile) return; // اعتبارسنجی ساده

    // اطمینان از ارسال تایپ صحیح
    form.type = props.type;

    form.post(route('panel.auth.sendCode'), {
        preserveScroll: true,
        preserveState: true,
        onStart: () => { isLoading.value = true; },
        onSuccess: () => {
            startCountdown(90); // 1.5 دقیقه
            step.value = 2;
            isLoading.value = false;
        },
        onError: () => { isLoading.value = false; },
        onFinish: () => { isLoading.value = false; }
    });
};

const verifyCode = () => {
    if(!form.code || form.code.length < 5) return;

    form.post(route('panel.auth.verifyCode'), {
        preserveScroll: true,
        preserveState: true,
        onStart: () => { isLoading.value = true; },
        onSuccess: () => { isLoading.value = false; }, // ریدایرکت توسط بک‌اند انجام می‌شود
        onError: () => { isLoading.value = false; },
        onFinish: () => { isLoading.value = false; }
    });
};

const resendCode = async () => {
    isResending.value = true;
    form.code = '';

    // شبیه‌سازی فراخوانی مجدد sendCode اما با مدیریت state محلی
    form.post(route('panel.auth.sendCode'), {
        preserveScroll: true,
        onSuccess: () => {
            startCountdown(90);
            isResending.value = false;
        },
        onError: () => { isResending.value = false; },
        onFinish: () => { isResending.value = false; }
    });
};

onMounted(() => {
    // اگر فرم اروری داشته باشد که مربوط به کد است، ممکن است بخواهیم در مرحله ۲ بمانیم
    // اما به طور پیش‌فرض روی ۱ ست شده است.
});
</script>

<style scoped>
/* --- فونت و استایل‌های پایه --- */
.persian-wrapper :deep(*) {
    font-family: inherit !important;
    letter-spacing: 0 !important;
}

.login-wrapper {
    background-color: #f8fcf9 !important; /* سبز خیلی خیلی محو */
}

/* --- تنظیمات اینپوت‌ها --- */
.dir-ltr-force {
    direction: ltr !important;
}

/* تنظیم دقیق OTP Input */
:deep(.v-otp-input) {
    justify-content: center;
    direction: ltr;
}

:deep(.v-otp-input .v-field) {
    border-radius: 12px;
    background-color: #f9f9f9;
}

/* --- اشکال پس‌زمینه (Blobs) --- */
.bg-shape {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    z-index: 0;
    opacity: 0.5;
}

.shape-1 {
    top: -100px;
    right: -100px;
    width: 500px;
    height: 500px;
    background: rgba(76, 175, 80, 0.12);
}

.shape-2 {
    bottom: -100px;
    left: -100px;
    width: 400px;
    height: 400px;
    background: rgba(129, 199, 132, 0.15);
}

/* --- استایل کارت --- */
.glass-card {
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 2;
}

.z-index-1 {
    position: relative;
    z-index: 1;
}

/* --- افکت هاور لینک --- */
.hover-green:hover {
    color: #2e7d32 !important; /* green-darken-3 */
}
.transition-swing {
    transition: 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
}
</style>
