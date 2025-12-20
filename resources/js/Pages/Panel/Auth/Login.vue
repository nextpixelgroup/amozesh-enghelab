<template>
    <Head title="Login" />
    <WebLayout>
        <v-container>
            <v-row class="justify-center">
                <v-col cols="12" md="6" lg="4">
                    <div class="zo-login-section">
                        <div class="zo-form">
                            <div class="text-center mb-8">
                                <v-avatar color="primary" size="80" class="elevation-4 mb-4">
                                    <v-icon icon="mdi-account-circle" size="40" />
                                </v-avatar>
                                <h1 class="font-weight-bold mb-2">ورود به پنل کاربری</h1>
                                <p class="text-medium-emphasis">لطفاً اطلاعات ورود خود را وارد کنید.</p>
                            </div>
                            <p class="text-center mb-1" v-if="step === 2">
                                کد تایید به شماره <strong>{{ form.mobile }}</strong> ارسال شده است.
                            </p>
                            <v-form @submit.prevent="sendCode" v-if="step === 1">
                                <v-text-field label="شماره همراه" v-model="form.mobile" required variant="outlined" prepend-inner-icon="mdi-cellphone" @input="form.mobile = toEnglishDigits(form.mobile)" />
                                <v-btn block size="large" type="submit" color="primary" :loading="isLoading">
                                    ارسال کد تایید
                                </v-btn>
                            </v-form>
                            <v-form @submit.prevent="verifyCode" v-if="step === 2">
                                <v-otp-input autofocus label="کد تایید" v-model="form.code" length="5" variant="outlined" style="direction: ltr" @input="form.code = toEnglishDigits(form.code)" />
                                <v-btn block size="large" type="submit" color="primary" :loading="isLoading">تایید</v-btn>
                            </v-form>
                            <div v-if="step === 2">
                                <div v-if="countdown > 0" class="text-center my-3">
                                    ارسال مجدد کد تا {{ countdown }} ثانیه دیگر
                                </div>
                                <v-btn block v-else variant="text" class="my-3" @click="resendCode" :loading="isResending">
                                    ارسال دوباره کد
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </WebLayout>
</template>
<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from "vue";
import { route } from "ziggy-js";
import WebLayout from "@/Layouts/WebLayout.vue";
import { toEnglishDigits } from "@/utils/helpers.js";
const isLoading = ref(false)
const isResending = ref(false)
const step = ref(1)
const form = useForm({
    mobile: '',
    code: '',
});

const countdown = ref(0)
let timerInterval = null

// شروع تایمر
const startCountdown = (seconds) => {
    countdown.value = seconds
    clearInterval(timerInterval)
    timerInterval = setInterval(() => {
        if (countdown.value > 0) {
            countdown.value--
        } else {
            clearInterval(timerInterval)
        }
    }, 1000)
}

const sendCode = () => {
    try {
        form.post(route('panel.auth.sendCode'), {
            preserveScroll: true,
            preserveState: true,
            onStart: () => {
                isLoading.value = true;
            },
            onSuccess: () => {
                startCountdown(90)
                step.value = 2;
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
            onFinish: () => {
                isLoading.value = false;
            }
        });
    } catch (error) {
        isLoading.value = false;
    }
}

const verifyCode = () => {
    try {
        form.post(route('panel.auth.verifyCode'), {
            preserveScroll: true,
            preserveState: true,
            onStart: () => {
                isLoading.value = true;
            },
            onSuccess: () => {
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
            onFinish: () => {
                isLoading.value = false;
            }
        });
    } catch (error) {
        isLoading.value = false;
    }
}

const resendCode = async () => {
    isResending.value = true;
    form.code = null;
    await sendCode()
    isResending.value = false;
}

</script>
<style scoped>
.zo-login-section {
    position: relative
}

.zo-login-section .zo-form {
    width: 100%;
    height: 100%;
    padding: 60px 0 30px;
    position: relative;
}

.zo-login-section .zo-form:before {
    content: '';
    width: 250px;
    height: 250px;
    display: block;
    margin: 0 auto;
    position: absolute;
    top: -150px;
    right: 0;
    left: 0;
    background: rgb(0, 105, 60);
    opacity: 0.5;
    border-radius: 50%;
    filter: blur(90px);
    z-index: 1
}

.zo-login-section h1 {
    font-size: 1.5rem;
}

.zo-login-section .v-otp-input :deep(.v-otp-input__content) {
    max-width: 370px;
    padding: 0
}

</style>
