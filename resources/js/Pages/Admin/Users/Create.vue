<template>
    <AdminLayout>
        <Head title="ایجاد کاربر"/>
        <v-form @submit.prevent="addUser">
            <v-container>
                <v-row>
                    <v-col cols="4">
                        <v-text-field
                            type="text"
                            v-model="form.firstname"
                            variant="outlined"
                            density="comfortable"
                            label="نام"
                        />
                    </v-col>
                    <v-col cols="4">
                        <v-text-field
                            type="text"
                            v-model="form.lastname"
                            variant="outlined"
                            density="comfortable"
                            label="نام خانوادگی"
                        />
                    </v-col>
                    <v-col cols="4">
                        <v-select
                            v-model="form.gender"
                            label="جنسیت"
                            variant="outlined"
                            density="comfortable"
                            :items="gender"
                        />
                    </v-col>
                    <v-col cols="4">
                        <v-text-field
                            type="text"
                            v-model="form.national_code"
                            label="کدملی"
                            variant="outlined"
                            density="comfortable"
                        />
                    </v-col>
                    <v-col cols="4">
                        <v-text-field
                            type="text"
                            v-model="form.mobile"
                            variant="outlined"
                            density="comfortable"
                            label="تلفن همراه"
                        />
                    </v-col>
                    <v-col cols="4">
                        <v-text-field
                            type="email"
                            v-model="form.email"
                            variant="outlined"
                            density="comfortable"
                            label="ایمیل"
                        />
                    </v-col>
                    <v-col cols="8">
                        <v-text-field
                            type="text"
                            v-model="form.address"
                            variant="outlined"
                            density="comfortable"
                            label="آدرس"
                        />
                    </v-col>
                    <v-col cols="4">
                        <v-text-field
                            type="text"
                            v-model="form.postal_code"
                            variant="outlined"
                            density="comfortable"
                            label="کدپستی"
                        />
                    </v-col>

                    <v-col cols="6">
                        <v-row>
                            <v-col cols="4">
                                <v-select
                                    v-model="form.birth_day"
                                    label="روز تولد"
                                    variant="outlined"
                                    density="comfortable"
                                    :items="days"
                                />
                            </v-col>
                            <v-col cols="4">
                                <v-select
                                    v-model="form.birth_month"
                                    label="ماه تولد"
                                    variant="outlined"
                                    density="comfortable"
                                    :items="months"
                                />
                            </v-col>
                            <v-col cols="4">
                                <v-select
                                    v-model="form.birth_year"
                                    label="سال تولد"
                                    variant="outlined"
                                    density="comfortable"
                                    :items="years"
                                />
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            type="text"
                            v-model="form.company"
                            variant="outlined"
                            density="comfortable"
                            label="محل فعالیت"
                        />
                    </v-col>
                    <v-col cols="3">
                        <v-select
                            v-model="form.role"
                            label="نقش کاربری"
                            variant="outlined"
                            density="comfortable"
                            :items="roles"
                            item-title="title"
                            item-value="value"
                        />
                    </v-col>
                    <v-col
                        cols="3"
                        v-if="['admin', 'content-manager'].includes(form.role)"
                    >
                        <v-text-field
                            type="text"
                            v-model="form.username"
                            variant="outlined"
                            density="comfortable"
                            label="نام کاربری"
                        />
                    </v-col>
                    <v-col
                        cols="3"
                        v-if="['user', 'admin', 'content-manager'].includes(form.role)"
                    >
                        <v-text-field
                            v-model="form.password"
                            variant="outlined"
                            density="comfortable"
                            label="کلمه عبور"
                            :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="showPassword ? 'text' : 'password'"
                            @click:append-inner="togglePasswordVisibility"
                        />
                    </v-col>
                    <v-col
                        cols="6"
                        v-if="form.role === 'teacher'"
                    >
                        <v-text-field
                            type="text"
                            v-model="form.slug"
                            variant="outlined"
                            density="comfortable"
                            label="نامک"
                            :suffix="site_url+'/t/'"
                            dir="ltr"
                        />
                    </v-col>
                    <v-col cols="12">
                        <v-btn
                            color="primary"
                            type="submit"
                            :loading="isLoading"
                        >ایجاد کاربر
                        </v-btn>
                    </v-col>
                </v-row>
            </v-container>

        </v-form>
    </AdminLayout>
</template>
<script setup>
import {Form, Head, useForm, usePage} from '@inertiajs/vue3'
import {defineComponent, ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";

defineComponent({
    components: {AdminLayout, Head}
})

const props = defineProps({
    roles: Object,
    gender: Object,
    years: Object,
    months: Object,
    days: Object,
    site_url: String,
})
const roles = ref(props.roles);
const gender = ref(props.gender);
const years = ref(props.years);
const months = ref(props.months);
const days = ref(props.days);
const site_url = ref(props.site_url);

const isLoading = ref(false);
const showPassword = ref(false);

const form = useForm({
    'firstname': '',
    'lastname': '',
    'gender': '',
    'national_code': '',
    'mobile': '',
    'email': '',
    'address': '',
    'postal_code': '',
    'birth_day': '',
    'birth_month': '',
    'birth_year': '',
    'company': '',
    'role': '',
    'username': '',
    'password': '',
    'slug': '',
});

const addUser = () => {
    form.post(route('admin.users.store'), {
        onStart: () => {
            isLoading.value = true;
        },
        onSuccess: () => {

        },
        onFinish: () => {
            isLoading.value = false;
        }
    });
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value
}

</script>
<style>
.v-text-field__suffix__text {
    direction: ltr;
}
</style>
