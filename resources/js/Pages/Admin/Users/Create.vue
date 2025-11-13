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
        <v-form @submit.prevent="addUser">
            <v-card class="pa-5 mb-3 elevation-2">
                <v-row dense>
                    <v-col class="v-col-12">
                        <v-sheet
                            elevation="0"
                            class="pa-4 rounded-lg bg-grey-lighten-4 mb-4"
                        >
                            <div class="d-flex align-center">
                                <v-icon
                                    icon="mdi-account-details"
                                    color="primary"
                                    class="ml-2"
                                    size="24"
                                ></v-icon>
                                <h3 class="font-weight-medium">مشخصات کاربر</h3>
                            </div>
                        </v-sheet>
                    </v-col>
                    <v-col class="v-col-lg-4 v-col-12">
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
                    <v-col class="v-col-12"></v-col>
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
                    <v-col class="v-col-lg-4 v-col-12" v-if="form.role !== 'institution'">
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

                </v-row>
            </v-card>
            <v-card class="pa-5 mb-3 elevation-2">
                <v-row dense>
                    <v-col class="v-col-12">
                        <v-sheet
                            elevation="0"
                            class="pa-4 rounded-lg bg-grey-lighten-4 mb-4"
                        >
                            <div class="d-flex align-center">
                                <v-icon
                                    icon="mdi mdi-information-outline"
                                    color="primary"
                                    class="ml-2"
                                    size="24"
                                ></v-icon>
                                <h3 class="font-weight-medium">جزئیات بیشتر</h3>
                            </div>
                        </v-sheet>
                    </v-col>
                    <v-col class="v-col-lg-3 v-col-12" v-if="form.role !== 'institution'">
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
                    <v-col class="v-col-lg-3 v-col-12">
                        <v-text-field
                            type="text"
                            v-model="form.tel"
                            variant="outlined"
                            density="comfortable"
                            label="تلفن ثابت"
                            prepend-inner-icon="mdi-phone-classic"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-lg-3 v-col-12">
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
                    <v-col class="v-col-lg-3 v-col-12" v-if="form.role !== 'institution'">
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
                    <v-col class="v-col-lg-4 v-col-12" v-if="form.role !== 'institution'">
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
                    <v-col class="v-col-lg-3 v-col-12" v-if="form.role === 'user'">
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
                    <v-col class="v-col-lg-3 v-col-12" v-if="['admin', 'content-manager'].includes(form.role)">
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
                    <v-col class="v-col-3" v-if="['user', 'admin', 'content-manager'].includes(form.role)">
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
                    <v-col class="v-col-2" v-if="form.role == 'user'">
                        <v-select
                            v-model="form.institution_id"
                            label="موسسه و دانشگاه"
                            variant="outlined"
                            density="comfortable"
                            :items="institutions"
                            item-title="title"
                            item-value="value"
                            prepend-inner-icon="mdi-shield-account"
                            hide-details
                            clearable
                        />
                    </v-col>

                    <v-col class="v-col-8" v-if="form.role === 'teacher'">
                        <v-text-field
                            type="text"
                            v-model="form.slug"
                            variant="outlined"
                            density="comfortable"
                            label="نامک"
                            :suffix="site_url+'/t/'"
                            dir="ltr"
                            prepend-inner-icon="mdi-link"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
                        <v-text-field
                            v-model="form.academic_title"
                            type="text"
                            variant="outlined"
                            density="comfortable"
                            label="عنوان علمی یا تخصصی"
                            prepend-inner-icon="mdi-flask-outline"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
                        <v-text-field
                            type="text"
                            variant="outlined"
                            density="comfortable"
                            label="عنوان شغلی"
                            v-model="form.job_title"
                            prepend-inner-icon="mdi-briefcase"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-4" v-if="form.role === 'teacher'">
                        <v-text-field
                            type="text"
                            variant="outlined"
                            density="comfortable"
                            label="مدرک تحصیلی"
                            v-model="form.degree"
                            prepend-inner-icon="mdi-certificate"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-6" v-if="form.role === 'teacher'">
                        <v-text-field
                            type="text"
                            variant="outlined"
                            density="comfortable"
                            label="سوابق تدریس"
                            v-model="form.history"
                            prepend-inner-icon="mdi-clipboard-text-outline"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-6" v-if="form.role === 'teacher'">
                        <v-text-field
                            type="text"
                            variant="outlined"
                            density="comfortable"
                            label="مهارت ها و تخصص"
                            v-model="form.skills"
                            prepend-inner-icon="mdi-book-open-variant"
                            hide-details
                        />
                    </v-col>
                    <v-col class="v-col-12" v-if="form.role === 'teacher'">
                        <v-textarea
                            type="text"
                            variant="outlined"
                            density="comfortable"
                            label="بیوگرافی"
                            v-model="form.bio"
                            prepend-inner-icon="mdi-account-details-outline"
                            hide-details
                        />
                    </v-col>

                </v-row>
                <v-row dense v-if="form.role === 'user'">
                    <v-card class="mb-4" variant="outlined">
                        <v-card-title class="d-flex align-center bg-grey-lighten-4">
                            <v-icon icon="mdi-school" class="ml-2"></v-icon>
                            سوابق تحصیلی
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                variant="text"
                                size="small"
                                @click="addEducation"
                                prepend-icon="mdi-plus"
                            >
                                افزودن مدرک تحصیلی
                            </v-btn>
                        </v-card-title>

                        <v-card-text>
                            <v-row v-for="(education, index) in form.educations" :key="index" class="mb-4">
                                <v-col cols="12" class="d-flex justify-space-between align-center">
                                    <h4 class="text-subtitle-1">سابقه تحصیلی {{ index + 1 }}</h4>
                                    <v-btn
                                        icon="mdi-delete"
                                        variant="text"
                                        color="error"
                                        size="small"
                                        @click="removeEducation(index)"
                                    ></v-btn>
                                </v-col>

                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="education.university"
                                        label="نام دانشگاه یا موسسه"
                                        variant="outlined"
                                        density="comfortable"
                                        hide-details
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="education.city"
                                        label="شهر محل تحصیل"
                                        variant="outlined"
                                        density="comfortable"
                                        hide-details
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field
                                        v-model="education.field_of_study"
                                        label="رشته تحصیلی"
                                        variant="outlined"
                                        density="comfortable"
                                        hide-details
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" md="4">
                                    <v-select
                                        v-model="education.degree"
                                        label="مدرک تحصیلی"
                                        variant="outlined"
                                        density="comfortable"
                                        :items="degree"
                                        prepend-inner-icon="mdi-calendar-badge"
                                        hide-details
                                    />
                                </v-col>

                                <v-row dense>
                                    <v-col class="v-col-6">
                                        <v-select
                                            v-model="education.start_month"
                                            label="ماه شروع تحصیل"
                                            variant="outlined"
                                            density="comfortable"
                                            :items="months"
                                            prepend-inner-icon="mdi-calendar-month"
                                            hide-details
                                        />
                                    </v-col>
                                    <v-col class="v-col-6">
                                        <v-select
                                            v-model="education.start_year"
                                            label="سال شروع تحصیل"
                                            variant="outlined"
                                            density="comfortable"
                                            :items="years"
                                            prepend-inner-icon="mdi-calendar-badge"
                                            hide-details
                                        />
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col class="v-col-6">
                                        <v-select
                                            v-model="education.end_month"
                                            label="ماه پایان تحصیل"
                                            variant="outlined"
                                            density="comfortable"
                                            :items="months"
                                            prepend-inner-icon="mdi-calendar-month"
                                            hide-details
                                            :disabled="education.is_studying"
                                        />
                                    </v-col>
                                    <v-col class="v-col-6">
                                        <v-select
                                            v-model="education.end_year"
                                            label="سال پایان تحصیل"
                                            variant="outlined"
                                            density="comfortable"
                                            :items="years"
                                            prepend-inner-icon="mdi-calendar-badge"
                                            hide-details
                                            :disabled="education.is_studying"
                                        />
                                    </v-col>
                                </v-row>

                                <v-col cols="12" md="3" class="d-flex align-center">
                                    <v-checkbox
                                        v-model="education.is_studying"
                                        label="در حال تحصیل هستم"
                                        hide-details
                                        @update:model-value="handleStudyingChange(education)"


                                    ></v-checkbox>
                                </v-col>

                                <v-col cols="12">
                                    <v-textarea
                                        v-model="education.description"
                                        label="توضیحات"
                                        variant="outlined"
                                        rows="2"
                                        hide-details
                                    ></v-textarea>
                                </v-col>

                                <v-divider v-if="index < form.educations.length - 1" class="my-2"></v-divider>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-row>
                <v-row dense>
                    <v-col class="v-col-12 text-left">
                        <v-btn color="primary" type="submit" :loading="isLoading">
                            ایجاد کاربر
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card>
        </v-form>
    </AdminLayout>
</template>
<script setup>
import {Form, Head, useForm, usePage} from '@inertiajs/vue3'
import {defineComponent, ref, watch} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";
import {route} from "ziggy-js";
import usePageTitle from "@/Composables/usePageTitle.js";

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
    'academic_title': '',
    'image': '',
    'teaching': '',
    'job_title': '',
    'degree': '',
    'history': '',
    'skills': '',
    'bio': '',
    'educations' : []
});


const addEducation = () => {
    form.educations.push({
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
    if (form.educations.length > 1) {
        form.educations.splice(index, 1);
    }
};

// Initialize with one empty education if empty
const handleStudyingChange = (education) => {
    if (education.is_studying) {
        education.end_month = '';
        education.end_year = '';
    }
};

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
