<template>
    <v-row>
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
    </v-row>
</template>

<script setup>
const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    roles: {
        type: Array,
        default: () => []
    }
})
</script>
