<template>
    <AdminLayout>
        <Head :title="adminPageTitle"/>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">افزودن کاربر</strong>
                            <span>در این بخش می توانید کاربر با نقش های متفاوت ایجاد کنید.</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <UserDetails v-if="form.role == 'user'" :roles="roles" :days="days" :months="months" :years="years"
                     :gender="gender" :form="form" :degree="degree" :institutions="institutions"/>
        <AdminDetails v-else-if="form.role == 'admin'" :roles="roles" :days="days" :months="months" :years="years"
                      :gender="gender" :form="form"/>
        <ContentManagerDetails v-else-if="form.role == 'content-manager'" :roles="roles" :days="days"
                               :months="months" :years="years" :gender="gender" :form="form"/>
        <TeacherDetails v-else-if="form.role == 'teacher'" :roles="roles" :days="days" :months="months"
                        :years="years" :gender="gender" :form="form" :site_url="site_url"/>
        <InstitutionDetails v-else-if="form.role == 'institution'" :roles="roles" :form="form"/>

        <div class="text-end">
            <v-btn color="primary" type="submit" :loading="isLoading" @click="addUser">
                ایجاد کاربر
            </v-btn>
        </div>
    </AdminLayout>
</template>
<script setup>
import {Form, Head, useForm, usePage} from '@inertiajs/vue3'
import {defineComponent, ref, watch} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import usePageTitle from "@/Composables/usePageTitle.js";
import AdminDetails from "@/Components/User/AdminDetails.vue";
import UserDetails from "@/Components/User/UserDetails.vue";
import ContentManagerDetails from "@/Components/User/ContentManagerDetails.vue";
import TeacherDetails from "@/Components/User/TeacherDetails.vue";
import InstitutionDetails from "@/Components/User/InstitutionDetails.vue";

const {adminPageTitle} = usePageTitle('ایجاد کاربر');

defineComponent({
    components: {AdminLayout, Head}
})

const props = defineProps({
    roles: Object,
    institutions: Object,
    gender: Object,
    years: Object,
    months: Object,
    days: Object,
    site_url: String,
    degree: String,
})
const roles = ref(props.roles);
const institutions = ref(props.institutions);
const gender = ref(props.gender);
const years = ref(props.years);
const months = ref(props.months);
const days = ref(props.days);
const degree = ref(props.degree);
const isLoading = ref(false);
const showPassword = ref(false);

const form = useForm({
    'firstname': '',
    'lastname': '',
    'gender': '',
    'national_code': '',
    'mobile': '',
    'tel': '',
    'email': '',
    'address': '',
    'postal_code': '',
    'birth_day': '',
    'birth_month': '',
    'birth_year': '',
    'company': '',
    'role': 'user',
    'username': '',
    'password': '',
    'slug': '',
    'institution_id': '',
    'academic_title': '',
    'image': '',
    'teaching': '',
    'job_title': '',
    'degree': '',
    'history': '',
    'skills': '',
    'bio': '',
    'avatar_id': '',
    'image_id': '',
    'educations': []
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
watch(() => form.role, (newRole, oldRole) => {
    if (newRole !== oldRole) {
        // اگر نقش تغییر کرد، فیلدهای غیرمرتبط پاک میشن
        if (newRole === 'user') {
            delete form.username
            delete form.password
            // یا اگر نمی‌خوای delete کنی، فقط خالیش کن
            // form.username = ''
        }

        if (newRole === 'institution') {
            // مثلاً مؤسسه شاید lastname نداشته باشه
            delete form.lastname
        }

        if (newRole === 'admin') {
            // نقش ادمین همه فیلدها رو داره پس شاید نیاز به پاک‌سازی نباشه
        }
    }
})

</script>
<style>
.v-text-field__suffix__text {
    direction: ltr;
}
</style>
