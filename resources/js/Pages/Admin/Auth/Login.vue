<script>
import { Head, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import FlashMessage from "../../../Components/FlashMessage.vue";

export default {
    components: { FlashMessage, Head, AdminLayout },
    methods: { route },
    setup() {
        const form = useForm({
            username: '',
            password: '',
            remember: false
        })

        const login = () => {
            form.post(route('admin.login.store'), {
                preserveScroll: true,
            })
        }

        return { form, login }
    },
}
</script>

<template>
    <Head title="ورود به پنل مدیریت" />

    <v-container fluid class="fill-height pa-0">
        <v-row no-gutters class="fill-height">
            <!-- Left side - Login Form -->
            <v-col cols="12" md="12" class="d-flex align-center justify-center">
                <div class="pa-8" style="max-width: 500px; width: 100%">
                    <div class="text-center mb-8">
                        <v-avatar color="primary" size="80" class="mb-4">
                            <v-icon icon="mdi-lock" size="40"></v-icon>
                        </v-avatar>
                        <h1 class="text-h4 font-weight-bold mb-2">ورود به پنل مدیریت</h1>
                        <p class="text-medium-emphasis">لطفاً اطلاعات ورود خود را وارد کنید</p>
                    </div>

                    <!-- Flash Messages -->
                    <FlashMessage class="mb-6" />

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
                            :append-inner-icon="'mdi-eye-off'"
                            prepend-inner-icon="mdi-lock"
                            :type="'password'"
                            class="mb-2"
                            hide-details="auto"
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
                    </v-form>

                </div>
            </v-col>

        </v-row>
    </v-container>
</template>

<style scoped>
/* RTL Fixes */
:deep(.v-field__prepend-inner) {
    padding-right: 8px !important;
    padding-left: 0 !important;
}

:deep(.v-field__append-inner) {
    padding-left: 8px !important;
    padding-right: 0 !important;
}

:deep(.v-field--focused) {
    --v-field-border-opacity: 1;
}
</style>
