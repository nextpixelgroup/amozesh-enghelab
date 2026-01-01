<template>
    <AdminLayout title="پروفایل">
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">ویرایش پروفایل کاربری</strong>
                            <span>در این بخش می توانید پروفایل کاربری خود را ویرایش کنید.</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
        <v-card class="pa-5 mb-3 elevation-2">
            <v-row dense>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.firstname" label="نام" variant="outlined" density="comfortable" prepend-inner-icon="mdi-account" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.lastname" label="نام خانوادگی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-account-circle" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.username" type="text" variant="outlined" density="comfortable" label="نام کاربری" prepend-inner-icon="mdi-account-star" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-select v-model="form.gender" :items="genders" label="جنسیت" variant="outlined" density="comfortable" prepend-inner-icon="mdi-gender-male" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="user.mobile" readonly label="تلفن همراه" variant="outlined" density="comfortable" prepend-inner-icon="mdi-cellphone-settings" hide-details type="text" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.tel" type="text" variant="outlined" density="comfortable" label="تلفن ثابت" prepend-inner-icon="mdi-phone-classic" hide-details />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.email" label="ایمیل" variant="outlined" density="comfortable" prepend-inner-icon="mdi-email" hide-details type="email" />
                </v-col>
                <v-col cols="12" md="6" lg="3">
                    <v-text-field v-model="form.national_code" type="text" label="کدملی" variant="outlined" density="comfortable" prepend-inner-icon="mdi-badge-account" hide-details />
                </v-col>
            </v-row>
        </v-card>
        <div class="text-end">
            <v-btn
                color="primary"
                type="submit"
                @click="saveProfile"
                :loading="isLoading"
                :disabled="isLoading"
            >
                ویرایش پروفایل کاربری
            </v-btn>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import ImageUploader from "@/Components/ImageUploader.vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {ref} from "vue";
const isLoading = ref(false)
const props = defineProps({
    user: Object,
    genders: Object,
})
const form = useForm({
    firstname: props.user.firstname,
    lastname: props.user.lastname,
    username: props.user.username,
    gender: props.user.gender,
    tel: props.user.tel,
    email: props.user.email,
    national_code: props.user.national_code,
});



const saveProfile = () => {
    form.put(route('admin.profile.update'), {
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
    })
}

</script>
