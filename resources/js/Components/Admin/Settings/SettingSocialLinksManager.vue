<template>
    <v-card class="mb-6">
        <v-card-title class="d-flex align-center">
            <v-icon class="me-2">mdi-share-variant</v-icon>
            لینک شبکه‌های اجتماعی
        </v-card-title>

        <v-card-text>
            <v-row>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.soroush"
                        label="لینک کانال سروش"
                        variant="outlined"
                        density="comfortable"
                        dir="ltr"
                    />
                </v-col>

                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.eitaa"
                        label="لینک کانال ایتا"
                        variant="outlined"
                        density="comfortable"
                        dir="ltr"
                    />
                </v-col>
            </v-row>
        </v-card-text>

        <v-divider />

        <div class="d-flex mt-4 justify-end">
            <v-btn
                color="primary"
                :loading="isSaving"
                :disabled="isSaving"
                @click="saveSocialLinks"
            >
                ذخیره تغییرات
            </v-btn>

        </div>
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
