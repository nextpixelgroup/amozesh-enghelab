<template>
    <Head :title="adminPageTitle"/>
    <AdminLayout>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">ویرایش کاربر</strong>
                            <span>در این بخش می توانید اطلاعات و لاگ های کاربر را مشاهده می کنید.</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-5 mb-3 elevation-2">
            <v-form @submit.prevent="updateUser">
                <v-row dense>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.firstname"
                            variant="outlined"
                            density="comfortable"
                            label="نام"
                            prepend-inner-icon="mdi-account"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.lastname"
                            variant="outlined"
                            density="comfortable"
                            label="نام خانوادگی"
                            prepend-inner-icon="mdi-account-circle"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-select
                            v-model="form.gender"
                            label="جنسیت"
                            variant="outlined"
                            density="comfortable"
                            :items="gender"
                            prepend-inner-icon="mdi-gender-male"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.national_code"
                            label="کدملی"
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="mdi-badge-account"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.mobile"
                            variant="outlined"
                            density="comfortable"
                            label="تلفن همراه"
                            prepend-inner-icon="mdi-cellphone-settings"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="email"
                            v-model="form.email"
                            variant="outlined"
                            density="comfortable"
                            label="ایمیل"
                            prepend-inner-icon="mdi-email"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-8 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.address"
                            variant="outlined"
                            density="comfortable"
                            label="آدرس"
                            prepend-inner-icon="mdi-map-marker-radius"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.postal_code"
                            variant="outlined"
                            density="comfortable"
                            label="کدپستی"
                            prepend-inner-icon="mdi-home-variant"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-6 v-col-12">
                        <v-row dense>
                            <v-col class="v-col-4">
                                <v-select
                                    v-model="form.birth_day"
                                    label="روز تولد"
                                    variant="outlined"
                                    density="comfortable"
                                    :items="days"
                                    prepend-inner-icon="mdi-calendar-today"
                                    hide-details
                                />
                            </v-col>
                            <v-col class="v-col-4">
                                <v-select
                                    v-model="form.birth_month"
                                    label="ماه تولد"
                                    variant="outlined"
                                    density="comfortable"
                                    :items="months"
                                    prepend-inner-icon="mdi-calendar-month"
                                    hide-details
                                />
                            </v-col>
                            <v-col class="v-col-4">
                                <v-select
                                    v-model="form.birth_year"
                                    label="سال تولد"
                                    variant="outlined"
                                    density="comfortable"
                                    :items="years"
                                    prepend-inner-icon="mdi-calendar-badge"
                                    hide-details
                                />
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col class="v-col-lg-3 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.company"
                            variant="outlined"
                            density="comfortable"
                            label="محل فعالیت"
                            prepend-inner-icon="mdi-domain"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-3 v-col-12">
                        <v-select
                            v-model="form.role"
                            label="نقش کاربری"
                            variant="outlined"
                            density="comfortable"
                            :items="roles"
                            item-title="title"
                            item-value="value"
                            prepend-inner-icon="mdi-shield-account"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-12" v-if="['admin', 'content-manager'].includes(form.role)">
                        <v-text-field
                            type="text"
                            v-model="form.username"
                            variant="outlined"
                            density="comfortable"
                            label="نام کاربری"
                            prepend-inner-icon="mdi-clipboard-account"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-12" v-if="['user', 'admin', 'content-manager'].includes(form.role)">
                        <v-text-field
                            v-model="form.password"
                            variant="outlined"
                            density="comfortable"
                            label="کلمه عبور"
                            :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                            :type="showPassword ? 'text' : 'password'"
                            @click:append-inner="togglePasswordVisibility"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-12" v-if="form.role === 'teacher'">
                        <v-text-field
                            type="text"
                            v-model="form.slug"
                            variant="outlined"
                            density="comfortable"
                            label="نامک"
                            :suffix="site_url+'/'"
                            dir="ltr"
                            prepend-inner-icon="mdi-link"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-12 text-left">
                        <v-btn color="primary" type="submit" :loading="isLoading">
                            ویرایش کاربر
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </v-card>
        <v-divider class="mb-4 mt-4"></v-divider>
        <v-card class="pa-5 mb-3 elevation-2">
            <UserRestrictionComponent
                :user="user"
                :restrictions="restrictions"
                :restrictionTypes="restrictionTypes"
            />
        </v-card>
    </AdminLayout>
</template>
<script setup>
import {Form, Head, Link, router, useForm, usePage} from '@inertiajs/vue3'
import {defineComponent, ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import UserRestrictionComponent from "@/Components/UserRestrictionComponent.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
const {adminPageTitle} = usePageTitle('ویرایش کاربر');

defineComponent({
    components: {AdminLayout, Head}
})

const props = defineProps({
    roles: Object,
    gender: Object,
    years: Object,
    months: Object,
    days: Object,
    user: Object,
    site_url: Object,
    restrictions: Object,
    restrictionTypes: Object,
})
const roles = ref(props.roles);
const gender = ref(props.gender);
const years = ref(props.years);
const months = ref(props.months);
const days = ref(props.days);
const user = ref(props.user.data);
const restrictions = ref(props.restrictions);
const site_url = ref(props.site_url);
const isLoading = ref(false);
const showPassword = ref(false);
const form = useForm({
    'firstname': user.value.firstname,
    'lastname': user.value.lastname,
    'gender': user.value.gender.value,
    'national_code': user.value.national_code,
    'mobile': user.value.mobile,
    'email': user.value.email,
    'address': user.value.address,
    'postal_code': user.value.postal_code,
    'birth_day': user.value.birth_date.object.day,
    'birth_month': user.value.birth_date.object.month,
    'birth_year': user.value.birth_date.object.year,
    'company': user.value.company,
    'role': user.value.role.value,
    'username': user.value.username,
    'password': '',
    'slug': user.value.slug,
});

const viewPage = () => {
    window.open(route('web.teacher.show', form.slug), '_blank');
}

const updateUser = () => {
    form.put(route('admin.users.update', user.value.id), {
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
