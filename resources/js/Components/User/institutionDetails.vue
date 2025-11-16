<template>
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
    <v-col class="v-col-12">
        <ImageUploader
            v-model:model-value="form.avatar_id"
            upload-route="admin.upload.users.image"
            label="فقط فایل تصویری آپلود کنید"
            title="بارگذاری آواتار"
            accept="image/*"
        />
    </v-col>
</template>
<script setup>
import ImageUploader from "@/Components/ImageUploader.vue";
const props = defineProps({
    form: {
        type: Object,
        required: true
    },
})
</script>
