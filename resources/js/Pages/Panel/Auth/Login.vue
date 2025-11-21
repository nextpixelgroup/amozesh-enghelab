<template>
    <Head title="Login" />
    <WebLayout>
        <v-container>
            <v-row>
                <v-col cols="12" md="6">
                    <v-card>
                        <v-card-title>{{ step === 1 ? 'لطفا شماره همراه را وارد نمایید' : `کد تایید به شماره ${form.mobile} ارسال شده است` }}</v-card-title>
                        <v-card-text>
                            <v-form @submit.prevent="sendCode" v-if="step === 1">
                                <v-text-field
                                    label="شماره همراه"
                                    v-model="form.mobile"
                                    required
                                    @input="form.mobile = toEnglishDigits(form.mobile)"
                                />
                                <v-btn type="submit" color="primary" :loading="isLoading">ارسال کد تایید</v-btn>
                            </v-form>
                            <v-form @submit.prevent="verifyCode" v-if="step === 2">
                                <v-otp-input
                                    label="کد تایید"
                                    v-model="form.code"
                                    length="5"
                                    style="direction: ltr"
                                    @input="form.code = toEnglishDigits(form.code)"
                                />

                                <v-btn type="submit" color="primary" :loading="isLoading">تایید</v-btn>



                            </v-form>
                            <div v-if="step === 2">
                                <div v-if="countdown > 0" class="mt-2">
                                    ارسال مجدد کد تا {{ countdown }} ثانیه دیگر
                                </div>
                                <v-btn
                                    v-else
                                    color="secondary"
                                    variant="outlined"
                                    @click="resendCode"
                                    :loading="isResending"
                                    class="mt-2"
                                >
                                    ارسال دوباره کد
                                </v-btn>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </WebLayout>
</template>
<script setup>
import {Head, useForm} from '@inertiajs/vue3'
import {ref} from "vue";
import {route} from "ziggy-js";
import WebLayout from "@/Layouts/WebLayout.vue";
import {toEnglishDigits} from "@/utils/helpers.js";
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
                startCountdown(5)
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
    }
    catch (error) {
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
    }
    catch (error) {
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
