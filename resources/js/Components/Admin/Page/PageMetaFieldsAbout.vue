<template>
    <v-row>
        <!-- بخش همکاری با سازمان‌ها -->
        <v-col cols="12">
            <label class="zo-label">همکاری با سازمان ها و دانشگاه ها</label>
            <MultipleSelector
                v-model="localMeta.institutions"
                :items="institutions"
                prepend-inner-icon="mdi-format-list-group-plus"
            />
        </v-col>

        <!-- بخش اساتید -->
        <v-col cols="12">
            <label class="zo-label">اساتید</label>
            <MultipleSelector
                v-model="localMeta.teachers"
                :items="teachers"
                prepend-inner-icon="mdi-format-list-group-plus"
            />
        </v-col>

        <!-- ================== بخش اول ================== -->
        <v-col cols="12">
            <h1 class="text-h5 zo-label">بخش اول</h1>
        </v-col>

        <v-col cols="12">
            <label class="zo-label">عنوان</label>
            <v-text-field
                v-model="localMeta.section1Title"
                hide-details
                density="compact"
                variant="outlined"
                prepend-inner-icon="mdi-text-short"
            ></v-text-field>
        </v-col>

        <v-col cols="12">
            <label class="zo-label">محتوا</label>
            <v-textarea
                v-model="localMeta.section1Content"
                hide-details
                density="compact"
                variant="outlined"
                prepend-inner-icon="mdi-text-long"
            ></v-textarea>
        </v-col>

        <v-col cols="12">
            <label class="zo-label">تصویر</label>
            <!--
              نکته: اینجا بررسی می‌کنیم که آبجکت section1Thumbnail وجود داشته باشد
              تا از خطای undefined جلوگیری کنیم.
             -->
            <ImageUploader
                v-if="localMeta.section1Thumbnail"
                v-model:model-value="localMeta.section1Thumbnail.id"
                :initialUrl="localMeta.section1Thumbnail?.url"
                upload-route="admin.upload.pages.image"
                label="فقط فایل تصویری آپلود کنید"
                accept="image/*"
                type="section1Thumbnail"
                :page-id="pageId"
            />
        </v-col>

        <!-- ================== بخش دوم ================== -->
        <v-col cols="12">
            <h1 class="text-h5 zo-label">بخش دوم</h1>
        </v-col>

        <v-col cols="12">
            <label class="zo-label">عنوان</label>
            <v-text-field
                v-model="localMeta.section2Title"
                hide-details
                density="compact"
                variant="outlined"
                prepend-inner-icon="mdi-text-short"
            ></v-text-field>
        </v-col>

        <v-col cols="12">
            <label class="zo-label">محتوا</label>
            <v-textarea
                v-model="localMeta.section2Content"
                hide-details
                density="compact"
                variant="outlined"
                prepend-inner-icon="mdi-text-long"
            ></v-textarea>
        </v-col>
    </v-row>
</template>

<script setup>
import { computed } from 'vue';
import ImageUploader from "@/Components/ImageUploader.vue";
import MultipleSelector from "@/Components/MultipleSelector.vue";

// تعریف ورودی‌ها
const props = defineProps({
    // این پراپ همان form.meta شماست که با v-model پاس داده می‌شود
    modelValue: {
        type: Object,
        required: true,
        default: () => ({})
    },
    // لیست آیتم‌های سلکتورها
    institutions: {
        type: Array,
        default: () => []
    },
    teachers: {
        type: Array,
        default: () => []
    },
    pageId: {
        type: Number,
        required: true
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
