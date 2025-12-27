<template>
    <Head title="ورود به سیستم" />
    <WebLayout>
        <v-container class="mt-8 fill-height justify-center align-center position-relative z-index-1">
            <v-row justify="center" class="w-100">
                <v-col cols="12" sm="10" md="7" lg="5" xl="4">

                    <v-card class="pa-6 pa-md-8 rounded-xl persian-wrapper glass-card" elevation="10">

                        <!-- هدر اصلی: فقط در مرحله ۱ یا اگر دیتای موسسه نبود نمایش داده شود -->
                        <div class="text-center mb-8" v-if="step === 1">
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

                        <!-- هدر اختصاصی موسسه: فقط در مرحله ۲ و اگر دیتا وجود داشت -->
                        <div v-else class="text-center mb-8">
                            <v-img
                                v-if="institution?.logo"
                                :src="institution?.logo"
                                :alt="institution?.name"
                                cover
                                width="50"
                                class="mx-auto mb-4"
                            ></v-img>

                            <h2 class="text-h6 font-weight-black text-green-darken-4 mb-1 d-flex justify-center align-center">
                                <v-icon color="green" class="ml-2" size="small">mdi-check-decagram</v-icon>
                                {{ institution?.name }}
                            </h2>
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
                                        maxlength="11"
                                        :rules="mobileRules"
                                        @input="handleMobileInput"
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

                                <!-- باکس شماره موبایل -->
                                <div class="mb-6 bg-green-lighten-5 pa-3 rounded-lg d-flex justify-space-between align-center border-dashed-green">
                                    <span class="text-caption text-grey-darken-3">
                                        کد ارسال شده به: <strong dir="ltr" class="text-green-darken-4">{{ form.mobile }}</strong>
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
                                            <strong class="text-green-darken-2 mx-1">{{ formatCountdown(countdown) }}</strong>
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
                            <Link :href="route('panel.index')" class="text-decoration-none text-caption text-grey hover-green transition-swing">
                                بازگشت<v-icon>mdi-chevron-left</v-icon>
                            </Link>
                        </div>

                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </WebLayout>
</template>

<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted, computed } from "vue"; // computed اضافه شد
import { route } from "ziggy-js";
import WebLayout from "@/Layouts/WebLayout.vue";
import { toEnglishDigits } from "@/utils/helpers.js";

const page = usePage();
const props = defineProps({
    type: {
        type: String,
        default: 'user'
    }
});

// تغییر مهم: تبدیل به computed برای دریافت تغییرات آنی از فلش
const institution = computed(() => {
    // فرض بر این است که دیتا در فرمت { name: '...', logo: '...' } می‌آید
    return page.props.flash?.data?.institution || null;
});
console.log(institution.value)

const isLoading = ref(false);
const isResending = ref(false);
const step = ref(1);
const countdown = ref(0);
let timerInterval = null;

const form = useForm({
    mobile: '',
    code: '',
    type: props.type,
});

// قوانین اعتبارسنجی (Validation Rules)
const mobileRules = [
    v => !!v || 'شماره موبایل الزامی است',
    v => (v && v.length === 11) || 'شماره موبایل باید ۱۱ رقم باشد',
    v => (v && v.startsWith('09')) || 'شماره موبایل معتبر نیست (باید با 09 شروع شود)'
];

// هندل کردن ورودی (فارسی به انگلیسی + حذف حروف + محدودیت تعداد)
const handleMobileInput = () => {
    if (!form.mobile) return;

    // ۱. تبدیل اعداد فارسی به انگلیسی
    let value = toEnglishDigits(form.mobile);

    // ۲. حذف هر کاراکتری که عدد نیست (مثلا حروف یا فاصله)
    value = value.replace(/\D/g, '');

    // ۳. اطمینان از اینکه بیشتر از ۱۱ رقم نشود (برای حالت Copy/Paste)
    if (value.length > 11) {
        value = value.slice(0, 11);
    }

    form.mobile = value;
};

// ... (توابع تایمر بدون تغییر) ...
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

const formatCountdown = (seconds) => {
    if (seconds <= 60) {
        return `${seconds} ثانیه`;
    }
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${mins}:${secs < 10 ? '0' + secs : secs} دقیقه`;
};

const sendCode = () => {
    if(!form.mobile) return;

    form.type = props.type;

    form.post(route('panel.auth.sendCode'), {
        preserveScroll: true,
        preserveState: true, // مهم: برای حفظ استیت صفحه
        onStart: () => { isLoading.value = true; },
        onSuccess: () => {
            // وقتی ساکسس می‌شود، اینرشیا page.props را آپدیت می‌کند
            // و institution به طور خودکار مقدار می‌گیرد
            startCountdown(90);
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
        onSuccess: () => { isLoading.value = false; },
        onError: () => { isLoading.value = false; },
        onFinish: () => { isLoading.value = false; }
    });
};

const resendCode = async () => {
    isResending.value = true;
    form.code = '';

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
    // لاجیک خاصی نیاز نیست
});
</script>

<style scoped>
/* --- فونت و استایل‌های پایه --- */
.persian-wrapper :deep(*) {
    font-family: inherit !important;
    letter-spacing: 0 !important;
}

.login-wrapper {
    background-color: #f8fcf9 !important;
}

/* --- تنظیمات اینپوت‌ها --- */
.dir-ltr-force {
    direction: ltr !important;
}

:deep(.v-otp-input) {
    justify-content: center;
    direction: ltr;
}

:deep(.v-otp-input .v-field) {
    border-radius: 12px;
    background-color: #f9f9f9;
}

/* --- استایل کارت --- */
.glass-card {
    background: rgba(255, 255, 255, 0.95) !important; /* کمی مات‌تر برای خوانایی لوگو */
    backdrop-filter: blur(12px);
    position: relative;
    z-index: 2;
}

.z-index-1 {
    position: relative;
    z-index: 1;
}

/* --- افکت هاور لینک --- */
.hover-green:hover {
    color: #2e7d32 !important;
}
.transition-swing {
    transition: 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
}

/* استایل جدید برای باکس شماره موبایل */
.border-dashed-green {
    border: 1px dashed rgba(76, 175, 80, 0.5);
}
</style>
