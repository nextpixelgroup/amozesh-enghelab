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
            <v-btn color="primary" type="submit" :loading="isLoading" @click="updateUser">
                ویرایش کاربر
            </v-btn>
        </div>
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
import ImageUploader from "@/Components/ImageUploader.vue";
import ContentManagerDetails from "@/Components/User/ContentManagerDetails.vue";
import TeacherDetails from "@/Components/User/TeacherDetails.vue";
import InstitutionDetails from "@/Components/User/InstitutionDetails.vue";
import UserDetails from "@/Components/User/UserDetails.vue";
import AdminDetails from "@/Components/User/AdminDetails.vue";
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
    institutions: Object,
    degree: String,
})
const roles = ref(props.roles);
const gender = ref(props.gender);
const years = ref(props.years);
const months = ref(props.months);
const days = ref(props.days);
const user = ref(props.user.data);
//console.log(user.value.avatar)
const restrictions = ref(props.restrictions);
const institutions = ref(props.institutions);
const degree = ref(props.degree);
const site_url = ref(props.site_url);
const isLoading = ref(false);
const showPassword = ref(false);
console.log(user.value)
const form = useForm({
    'firstname': user.value.firstname,
    'lastname': user.value.lastname,
    'gender': user.value.gender.value,
    'national_code': user.value.national_code,
    'mobile': user.value.mobile,
    'tel': user.value.tel,
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
    'academic_title': user.value.teacherDetails.academic_title,
    'teaching': user.value.teacherDetails?.teaching,
    'job_title': user.value.teacherDetails?.job_title,
    'degree': user.value.teacherDetails?.degree,
    'history': user.value.teacherDetails?.history,
    'skills': user.value.teacherDetails?.skills,
    'bio': user.value.teacherDetails?.bio,
    'institution_id': user.value?.institution_id,
    'avatar_id': user.value?.avatar?.id,
    'image': user.value?.teacherDetails?.image,
    'image_id': user.value?.teacherDetails?.image?.id,
    'educations': user.value.educations && user.value.educations.length > 0
        ? user.value.educations.map(education => ({
            id: education.id || null,
            university: education.university || '',
            city: education.city || '',
            field_of_study: education.field_of_study || '',
            degree: education.degree || '',
            start_year: education.start_date?.object?.year || null,
            start_month: education.start_date?.object?.month || null,
            end_year: education.is_studying ? null : (education.end_date?.object?.year || null),
            end_month: education.is_studying ? null : (education.end_date?.object?.month || null),
            is_studying: education.is_studying || false,
            description: education.description || ''
        }))
        : []
});

const viewPage = () => {
    window.open(route('web.teacher.show', form.slug), '_blank');
}

const updateUser = () => {
    form.put(route('admin.users.update', user.value.id), {
        preserveScroll: true,
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

const addEducation = () => {
    form.educations.push({
        id: null,
        university: '',
        field_of_study: '',
        degree: '',
        start_month: '',
        start_year: '',
        end_month: '',
        end_year: '',
        is_studying: false,
        description: '',
        city: '',
    });
};
const removeEducation = (index) => {
    form.educations.splice(index, 1);
};

// Initialize with one empty education if empty
const handleStudyingChange = (education) => {
    if (education.is_studying) {
        education.end_month = '';
        education.end_year = '';
    }
};

</script>
