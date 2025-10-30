<script>
import {Head, useForm} from '@inertiajs/vue3'
import {ref} from 'vue'
import {route} from 'ziggy-js'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import FlashMessage from '../../../Components/FlashMessage.vue'

export default {
    components: {Head, AdminLayout, FlashMessage},
    setup() {
        const form = useForm({
            username: '',
            password: '',
            remember: false,
        })

        const showPassword = ref(false)

        const login = () => {
            form.post(route('admin.login.store'), {preserveScroll: true})
        }

        const togglePasswordVisibility = () => {
            showPassword.value = !showPassword.value
        }

        const socialButtons = [
            {src: '/assets/img/soroosh.svg', alt: 'Soroosh'},
            {src: '/assets/img/bale.svg', alt: 'Bale'},
            {src: '/assets/img/eitaa.svg', alt: 'Eitaa'},
            {src: '/assets/img/aparat.svg', alt: 'Aparat'},
        ]

        return {form, login, showPassword, togglePasswordVisibility, socialButtons}
    },
}
</script>

<template>
    <Head title="ورود به پنل مدیریت"/>

    <v-container fluid class="pa-0">
        <v-row no-gutters class="zo-login-section">
            <v-col cols="12" lg="6" class="d-flex align-center justify-center">
                <div class="zo-form">
                    <div class="text-center mb-8">
                        <v-avatar color="primary" size="80" class="elevation-4 mb-4">
                            <v-icon icon="mdi-badge-account" size="40"/>
                        </v-avatar>
                        <h1 class="text-h5 font-weight-bold mb-2">ورود به پنل کاربری</h1>
                        <p class="text-medium-emphasis">لطفاً اطلاعات ورود خود را وارد کنید</p>
                    </div>

                    <FlashMessage class="mb-6"/>

                    <v-form @submit.prevent="login">
                        <v-text-field
                            v-model="form.username"
                            label="نام کاربری"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="mdi-account"
                            class="mb-4"
                            hide-details="auto"
                        />

                        <v-text-field
                            v-model="form.password"
                            label="رمز عبور"
                            variant="outlined"
                            density="comfortable"
                            :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            prepend-inner-icon="mdi-lock"
                            :type="showPassword ? 'text' : 'password'"
                            class="mb-2"
                            hide-details="auto"
                            @click:append-inner="togglePasswordVisibility"
                        />

                        <v-btn
                            type="submit"
                            color="primary"
                            size="large"
                            block
                            :loading="form.processing"
                            :disabled="form.processing"
                            class="text-subtitle-1 py-6"
                        >
                            <template v-if="!form.processing">
                                ورود به حساب کاربری
                            </template>
                            <template v-else>
                                <v-progress-circular
                                    indeterminate
                                    color="white"
                                    size="20"
                                    width="2"
                                    class="mr-2"
                                ></v-progress-circular>
                            </template>
                        </v-btn>
                        <ul class="zo-social">
                            <li v-for="(btn, index) in socialButtons" :key="index">
                                <v-btn color="primary">
                                    <img :src="btn.src" :alt="btn.alt" width="20" height="20"/>
                                </v-btn>
                            </li>
                        </ul>
                        <div class="zo-copyright">
                            طراحی و پیاده سازی توسط نکست پیکسل
                        </div>
                    </v-form>
                </div>
            </v-col>
            <v-col cols="12" lg="6" class="d-none d-lg-flex zo-cover"/>
        </v-row>
    </v-container>
</template>

<style scoped>
.zo-login-section {
    height: 100vh;
    position: relative;
}

.zo-login-section .zo-form {
    width: 100%;
    max-width: 450px;
    height: 100%;
    padding: 90px 0;
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

.zo-login-section .zo-cover {
    position: relative;
    overflow: hidden;
    background: url(/assets/img/login.png) center/cover no-repeat;
}

.zo-login-section .zo-cover::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(5, 105, 60, 0.75);
    z-index: 15;
}

.zo-login-section .zo-social {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 60px;
}

.zo-login-section .zo-social li {
    display: inline-block;
}

.zo-login-section .zo-social li .v-btn {
    min-width: 45px;
    min-height: 45px;
    padding: 0;
    border-radius: 50%;
}

.zo-login-section .zo-copyright {
    position: absolute;
    bottom: 15px;
    right: 15px;
    left: 15px;
    font-size: .875rem;
    color: rgb(0, 0, 0, .5);
    text-align: center;
}
</style>
