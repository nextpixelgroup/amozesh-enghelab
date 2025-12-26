<template>
    <v-card class="mb-6 border" elevation="0" rounded="lg">
        <!-- هدر اصلی با استایل هماهنگ -->
        <v-toolbar color="green-lighten-5" density="compact" class="px-2 border-b">
            <v-icon color="green-darken-2" start>mdi-share-variant</v-icon>
            <v-toolbar-title class="text-body-1 font-weight-bold green">
                لینک شبکه‌های اجتماعی
            </v-toolbar-title>
        </v-toolbar>

        <v-card-text class="bg-grey-lighten-5 pa-4">
            <v-row>
                <!-- ورودی سروش پلاس -->
                <v-col cols="12" md="6">
                    <v-card variant="outlined" class="bg-white border-dashed" rounded="lg">
                        <v-card-text>
                            <div class="d-flex align-center mb-3">
                                <v-avatar color="green-lighten-5" rounded="lg" class="me-3" size="40">
                                    <v-icon color="green-darken-3" size="24">mdi-message-text</v-icon>
                                </v-avatar>
                                <div>
                                    <div class="text-body-2 font-weight-bold">پیام‌رسان سروش پلاس</div>
                                    <div class="text-caption text-grey">لینک دعوت یا کانال</div>
                                </div>
                            </div>
                            <v-text-field
                                v-model="form.soroush"
                                label="آدرس اینترنتی (URL)"
                                placeholder="https://splus.ir/your-channel"
                                variant="outlined"
                                density="comfortable"
                                dir="ltr"
                                hide-details="auto"
                                clearable
                                prepend-inner-icon="mdi-link"
                            />
                        </v-card-text>
                    </v-card>
                </v-col>

                <!-- ورودی ایتا -->
                <v-col cols="12" md="6">
                    <v-card variant="outlined" class="bg-white border-dashed" rounded="lg">
                        <v-card-text>
                            <div class="d-flex align-center mb-3">
                                <v-avatar color="green-lighten-5" rounded="lg" class="me-3" size="40">
                                    <v-icon color="green-darken-3" size="24">mdi-message-text</v-icon>
                                </v-avatar>
                                <div>
                                    <div class="text-body-2 font-weight-bold">پیام‌رسان ایتا</div>
                                    <div class="text-caption text-grey">لینک دعوت یا کانال</div>
                                </div>
                            </div>
                            <v-text-field
                                v-model="form.eitaa"
                                label="آدرس اینترنتی (URL)"
                                placeholder="https://eitaa.com/your-channel"
                                variant="outlined"
                                density="comfortable"
                                dir="ltr"
                                hide-details="auto"
                                clearable
                                prepend-inner-icon="mdi-link"
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
                @click="saveSocialLinks"
            >
                ذخیره تغییرات
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const props = defineProps({
    socialLinks: {
        type: Object,
        default: () => ({
            soroush: '',
            eitaa: '',
        }),
    },
})

const emit = defineEmits(['saved'])

const form = useForm({
    soroush: props.socialLinks?.soroush ?? '',
    eitaa: props.socialLinks?.eitaa ?? '',
})

const isSaving = ref(false)

watch(
    () => props.socialLinks,
    (links) => {
        form.soroush = links?.soroush ?? ''
        form.eitaa = links?.eitaa ?? ''
    },
    { deep: true }
)

const saveSocialLinks = () => {
    form.put(route('admin.settings.social.update'), {
        preserveScroll: true,
        preserveState: true,
        onStart: () => (isSaving.value = true),
        onSuccess: (response) => {
            emit('saved', response.props.socialLinks)
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
