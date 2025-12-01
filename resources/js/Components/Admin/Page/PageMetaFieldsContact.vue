<template>
    <v-col cols="12">
        <label class="zo-label">آدرس</label>
        <v-text-field
            v-model="localMeta.address"
            hide-details
            density="compact"
            variant="outlined"
            prepend-inner-icon="mdi-text-short"
        ></v-text-field>
    </v-col>
    <v-col cols="12">
        <label class="zo-label">تلفن تماس</label>
        <v-text-field
            v-model="localMeta.tel"
            hide-details
            density="compact"
            variant="outlined"
            prepend-inner-icon="mdi-text-short"
        ></v-text-field>
    </v-col>
    <v-col cols="12">
        <label class="zo-label">ایمیل</label>
        <v-text-field
            v-model="localMeta.email"
            hide-details
            density="compact"
            variant="outlined"
            prepend-inner-icon="mdi-text-short"
        ></v-text-field>
    </v-col>
    <v-col cols="12">
        <label class="zo-label">تصویر</label>

        <ImageUploader
            v-model:model-value="mapImageId"
            :initialUrl="localMeta?.mapImage?.url"
            upload-route="admin.upload.pages.image"
            label="فقط فایل تصویری آپلود کنید"
            accept="image/*"
            type="page"
        />
    </v-col>
</template>
<script setup lang="ts">

import {computed} from "vue";
import ImageUploader from "@/Components/ImageUploader.vue";

const props = defineProps({
    // این پراپ همان form.meta شماست که با v-model پاس داده می‌شود
    modelValue: {
        type: Object,
        required: true,
        default: () => ({})
    },
});
const mapImageId = computed({
    get: () => localMeta.value.mapImage?.id ?? null,
    set: (id) => {
        if (!localMeta.value.mapImage) {
            localMeta.value.mapImage = {};
        }
        localMeta.value.mapImage.id = id;
    }
});
// تعریف ایونت برای آپدیت دوطرفه
const emit = defineEmits(['update:modelValue']);

// استفاده از Computed برای مدیریت تمیز v-model روی آبجکت
const localMeta = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

</script>
