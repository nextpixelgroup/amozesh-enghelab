<template>
    <v-col cols="12">
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

    <v-col cols="12" lg="4">
        <v-text-field
            v-model="form.firstname"
            label="نام"
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="mdi-account"
            hide-details
            type="text"
        />
    </v-col>

    <v-col cols="12" lg="4">
        <v-text-field
            v-model="form.lastname"
            label="نام خانوادگی"
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="mdi-account-circle"
            hide-details
            type="text"
        />
    </v-col>

    <v-col cols="12" lg="4">
        <v-text-field
            v-model="form.mobile"
            label="تلفن همراه"
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="mdi-cellphone-settings"
            hide-details
            type="text"
        />
    </v-col>

    <v-col cols="12">
        <v-text-field
            v-model="form.email"
            label="ایمیل"
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="mdi-email"
            hide-details
            type="email"
        />
    </v-col>

    <v-col cols="12">
        <v-text-field
            v-model="form.address"
            label="آدرس"
            variant="outlined"
            density="comfortable"
            prepend-inner-icon="mdi-map-marker-radius"
            hide-details
            type="text"
        />
    </v-col>
    <v-col class="v-col-lg-3 v-col-12">
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
    <v-col class="v-col-lg-6 v-col-12">
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
    <v-col class="v-col-lg-6 v-col-12">
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
    <v-col class="v-col-lg-3 v-col-12">
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
            v-model="form.username"
            variant="outlined"
            density="comfortable"
            label="نام کاربری"
            prepend-inner-icon="mdi-clipboard-account"
            hide-details
        />
    </v-col>
    <v-col class="v-col-3">
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
</template>
<script setup>
import {ref} from "vue";
const props = defineProps({
    roles: {
        type: Object,
        required: true
    },
    form: {
        type: Object,
        required: true
    },
    gender: {
        type: Object,
        required: true
    },
    years: {
        type: Object,
        required: true
    },
    months: {
        type: Object,
        required: true
    },
    days: {
        type: Object,
        required: true
    },
})

const showPassword = ref(false);
const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value
}

</script>
