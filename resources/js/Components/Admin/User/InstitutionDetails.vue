<template>
    <div>
        <v-card class="pa-5 mb-3 elevation-2">
            <v-row dense>
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12" lg="6">
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
                <v-col cols="12" lg="3">
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
                <v-col cols="12">
                    <ImageUploader
                        v-model:model-value="form.avatar_id"
                        :initial-url="form.avatar?.url"
                        upload-route="admin.upload.users.image"
                        label="فقط فایل تصویری آپلود کنید"
                        title="بارگذاری آواتار"
                        accept="image/*"
                        type="userAvatar"
                    />
                </v-col>
            </v-row>
        </v-card>
    </div>
</template>
<script setup>
import ImageUploader from "@/Components/ImageUploader.vue";

const props = defineProps({
    roles: {
        type: Object,
        required: true
    },
    form: {
        type: Object,
        required: true
    },
})
</script>
