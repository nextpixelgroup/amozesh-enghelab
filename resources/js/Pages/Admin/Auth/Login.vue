<script>
import {Head, useForm} from '@inertiajs/vue3'
import {ref} from 'vue'
import {route} from 'ziggy-js'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import FlashMessage from "../../../Components/FlashMessage.vue";

export default {
    components: {FlashMessage, Head, AdminLayout},
    methods: {route},
    setup() {
        const form = useForm({
            username: '',
            password: '',
            remember: false
        })

        const showPassword = ref(false)

        const login = () => {
            form.post(route('admin.login.store'), {
                preserveScroll: true,
            })
        }

        const togglePasswordVisibility = () => {
            showPassword.value = !showPassword.value
        }

        return {form, login, showPassword, togglePasswordVisibility}
    },
}
</script>

<template>
    <Head title="ورود به پنل مدیریت"/>

    <v-container fluid class="pa-0">
        <v-row no-gutters class="zo-login-section">
            <v-col cols="12" lg="6" class="d-flex align-center justify-center">
                <div class="pa-8" style="max-width: 500px; width: 100%">
                    <div class="text-center mb-8">
                        <v-avatar color="primary" size="80" class="elevation-4 mb-4">
                            <v-icon icon="mdi-shield-account" size="40"></v-icon>
                        </v-avatar>
                        <h1 class="text-h5 font-weight-bold mb-2">ورود به پنل مدیریت</h1>
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
                        ></v-text-field>
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
                        ></v-text-field>
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
                                در حال ورود...
                            </template>
                        </v-btn>
                        <ul class="zo-social">
                            <li>
                                <v-btn color="primary" v-bind="props">
                                    <img src="/assets/img/soroosh.svg" width="20" height="20">
                                </v-btn>
                            </li>
                            <li>
                                <v-btn v-bind="props" color="primary">
                                    <img src="/assets/img/bale.svg" width="20" height="20">
                                </v-btn>
                            </li>
                            <li>
                                <v-btn color="primary" v-bind="props">
                                    <img src="/assets/img/eitaa.svg" width="20" height="20">
                                </v-btn>
                            </li>
                            <li>
                                <v-btn color="primary" v-bind="props">
                                    <img src="/assets/img/aparat.svg" width="20" height="20">
                                </v-btn>
                            </li>
                        </ul>
                    </v-form>
                </div>
            </v-col>
            <v-col cols="12" lg="6" class="d-none d-lg-flex zo-cover"></v-col>
        </v-row>
    </v-container>
</template>

<style scoped>

.zo-login-section {
    height: 100vh;
}

.zo-login-section .zo-cover {
    position: relative;
    overflow: hidden;
    background-image: url(/assets/img/login.png);
    background-size: cover;
    background-position: center
}

.zo-login-section .zo-cover::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(5, 105, 60, .75);
    z-index: 15;
}

.zo-login-section .zo-social {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin: 60px 0 0
}

.zo-login-section .zo-social li {
    display: inline-block
}

.zo-login-section .zo-social li .v-btn {
    min-width: 45px;
    min-height: 45px;
    padding: 0;
    border-radius: 50%
}

.zo-login-section .zo-social li a.zo-one {
    background: rgb(20, 75, 155)
}

.zo-login-section .zo-social li a.zo-two {
    background: rgb(65, 195, 150)
}

.zo-login-section .zo-social li a.zo-three {
    background: rgb(230, 120, 0)
}

.zo-login-section .zo-social li a.zo-four {
    background: rgb(235, 20, 90)
}

.zo-login-section .zo-social li a.zo-five {
    background: rgb(5, 105, 60)
}
</style>
