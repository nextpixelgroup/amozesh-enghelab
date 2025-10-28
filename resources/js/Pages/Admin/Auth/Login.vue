<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: ''
})

const submit = () => {
    form.post('/login')
}
</script>

<template>
    <Head title="Login" />

    <v-container class="d-flex justify-center align-center" style="height: 100vh;">
        <v-card elevation="8" width="400" class="pa-6 rounded-lg">

            <div class="text-center mb-6">
                <h2 class="text-h5 font-weight-medium">ورود به حساب</h2>
            </div>

            <v-form @submit.prevent="submit">
                <v-text-field
                    label="ایمیل"
                    v-model="form.email"
                    type="email"
                    variant="outlined"
                    density="comfortable"
                    :error="!!form.errors.email"
                    :error-messages="form.errors.email"
                    class="mb-4"
                    autofocus
                />

                <v-text-field
                    label="رمز عبور"
                    v-model="form.password"
                    type="password"
                    variant="outlined"
                    density="comfortable"
                    :error="!!form.errors.password"
                    :error-messages="form.errors.password"
                    class="mb-6"
                />

                <v-btn
                    type="submit"
                    block
                    size="large"
                    color="primary"
                    :loading="form.processing"
                >
                    ورود
                </v-btn>
            </v-form>

        </v-card>
    </v-container>
</template>

<style scoped>
h2 {
    font-family: 'iranyekan', sans-serif;
}
</style>
