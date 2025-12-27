<template>
    <v-card class="mb-6 border" elevation="0" rounded="lg">
        <!-- هدر اصلی -->
        <v-toolbar color="green-lighten-5" density="compact" class="px-2 border-b">
            <v-icon color="green-darken-2" start>mdi-image-area</v-icon>
            <v-toolbar-title class="text-body-1 font-weight-bold green">
                لوگو و تصاویر
            </v-toolbar-title>
        </v-toolbar>

        <v-card-text class="bg-grey-lighten-5 pa-4">
            <v-row>
                <v-col cols="12" md="6">
                    <v-card variant="outlined" class="bg-white border-dashed" rounded="lg">
                        <v-card-text>
                            <div class="d-flex align-center mb-3">
                                <v-avatar color="green-lighten-5" rounded="lg" class="me-3" size="40">
                                    <v-icon color="green-darken-3" size="24">mdi-image-edit</v-icon>
                                </v-avatar>
                                <div>
                                    <div class="text-body-2 font-weight-bold">لوگو هدر</div>
                                </div>
                            </div>
                            <!-- اصلاح: اتصال به form به جای props -->
                            <ImageUploader
                                v-model:model-value="form.logoHeader"
                                :initialUrl="logos.logoHeader?.url"
                                upload-route="admin.upload.general.image"
                                label="انتخاب فایل"
                                accept="image/*"
                                type="setting"
                                settingName="web.logos"
                                settingValue="logoHeader"
                            />
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col cols="12" md="6">
                    <v-card variant="outlined" class="bg-white border-dashed" rounded="lg">
                        <v-card-text>
                            <div class="d-flex align-center mb-3">
                                <v-avatar color="green-lighten-5" rounded="lg" class="me-3" size="40">
                                    <v-icon color="green-darken-3" size="24">mdi-image-edit</v-icon>
                                </v-avatar>
                                <div>
                                    <div class="text-body-2 font-weight-bold">لوگو فوتر</div>
                                </div>
                            </div>
                            <!-- اصلاح: اتصال به form به جای props -->
                            <ImageUploader
                                v-model:model-value="form.logoFooter"
                                :initialUrl="logos.logoFooter?.url"
                                upload-route="admin.upload.general.image"
                                label="انتخاب فایل"
                                accept="image/*"
                                type="setting"
                                settingName="web.logos"
                                settingValue="logoFooter"
                            />
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-card-text>

        <!-- بخش دکمه‌ها -->
        <v-card-actions class="d-flex justify-end pt-4 border-t px-4 bg-white">
            <v-btn
                color="primary"
                variant="flat"
                :loading="isSaving"
                :disabled="isSaving"
                prepend-icon="mdi-content-save-check"
                class="px-6"
                height="45"
                elevation="2"
                @click="save"
            >
                ذخیره تغییرات
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import ImageUploader from "@/Components/ImageUploader.vue";

const props = defineProps({
    logos: {
        type: Object,
        default: () => ({ header: null, footer: null })
    },
})

const emit = defineEmits(['saved'])

// اصلاح: مقداردهی اولیه صحیح با ID (یا null اگر وجود نداشت)
const form = useForm({
    logoHeader: props.logos?.logoHeader?.id ?? null,
    logoFooter: props.logos?.logoFooter?.id ?? null,
})
console.log(props.logos)

const isSaving = ref(false)

const save = () => {
    form.put(route('admin.settings.logos.update'), {
        preserveScroll: true,
        preserveState: true,
        onStart: () => (isSaving.value = true),
        onSuccess: (response) => {
            // نکته: مطمئن شوید socialLinks در ریسپانس وجود دارد یا آن را اصلاح کنید
            emit('saved', response?.props?.socialLinks)
        },
        onFinish: () => (isSaving.value = false),
    })
}
</script>

<style scoped>
.border-dashed {
    border-style: dashed !important;
    border-color: rgba(var(--v-theme-on-surface), 0.12) !important;
}
</style>
