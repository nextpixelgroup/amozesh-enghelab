<template>
    <v-card class="mb-6 border" elevation="0" rounded="lg">
        <!-- هدر اصلی با استایل جدید -->
        <v-toolbar color="green-lighten-5" density="compact" class="px-2 border-b">
            <v-icon color="green-darken-2" start>mdi-monitor-dashboard</v-icon>
            <v-toolbar-title class="text-body-1 font-weight-bold green">
                مدیریت اسلایدر صفحه اصلی
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-chip size="small" color="green" variant="flat" class="font-weight-bold">
                {{ slidesLocal.length }} اسلاید
            </v-chip>
        </v-toolbar>

        <v-card-text class="bg-grey-lighten-5 pa-4">
            <!-- نمایش پیام در صورت خالی بودن -->
            <v-fade-transition>
                <div v-if="slidesLocal.length === 0" class="d-flex flex-column align-center justify-center py-10 text-grey">
                    <v-icon size="64" color="grey-lighten-2">mdi-image-multiple-outline</v-icon>
                    <p class="mt-2">هنوز اسلایدی اضافه نشده است</p>
                </div>
            </v-fade-transition>

            <v-expansion-panels v-model="panel" multiple variant="popout" class="mb-4">
                <v-expansion-panel
                    v-for="(slide, index) in slidesLocal"
                    :key="slide.id ?? `new-${index}`"
                    :value="index"
                    rounded="lg"
                    elevation="1"
                >
                    <v-expansion-panel-title>
                        <div class="d-flex align-center w-100">
                            <!-- شماره اسلاید -->
                            <v-avatar color="green-lighten-1" size="24" class="me-3 text-caption font-weight-bold text-white">
                                {{ index + 1 }}
                            </v-avatar>

                            <!-- نمایش عنوان هوشمند -->
                            <div class="d-flex flex-column">
                                <span class="text-body-2 font-weight-bold" :class="{'text-grey': !slide.title}">
                                    {{ slide.title || 'عنوان اسلاید (خالی)' }}
                                </span>
                            </div>

                            <v-spacer></v-spacer>

                            <!-- دکمه حذف -->
                            <v-btn
                                icon
                                color="error"
                                size="small"
                                variant="flat"
                                class="ms-3 text-red-darken-2 delete-btn"
                                @click.stop="removeSlide(slide.id, index)"
                            >
                                <v-icon size="small">mdi-close</v-icon>
                                <v-tooltip activator="parent" location="top">حذف اسلاید</v-tooltip>
                            </v-btn>
                        </div>
                    </v-expansion-panel-title>

                    <v-expansion-panel-text>
                        <v-divider class="mb-4"></v-divider>
                        <v-row>
                            <!-- ستون سمت راست: فرم‌های متنی -->
                            <v-col cols="12" md="7">
                                <v-row dense>
                                    <v-col cols="12">
                                        <v-text-field
                                            v-model="slide.title"
                                            variant="outlined"
                                            density="comfortable"
                                            label="عنوان اصلی"
                                            prepend-inner-icon="mdi-format-title"
                                            hide-details="auto"
                                            class="mb-3"
                                        />
                                    </v-col>

                                    <v-col cols="12" md="8">
                                        <v-text-field
                                            v-model="slide.link"
                                            variant="outlined"
                                            density="comfortable"
                                            label="لینک مقصد"
                                            prepend-inner-icon="mdi-link-variant"
                                            dir="ltr"
                                            hide-details="auto"
                                            class="mb-3"
                                        />
                                    </v-col>

                                    <v-col cols="12" md="4">
                                        <v-select
                                            v-model="slide.target"
                                            :items="targetsLocal"
                                            item-title="text"
                                            item-value="value"
                                            variant="outlined"
                                            density="comfortable"
                                            label="نحوه باز شدن"
                                            prepend-inner-icon="mdi-open-in-new"
                                            hide-details="auto"
                                            class="mb-3"
                                        />
                                    </v-col>

                                    <v-col cols="12">
                                        <v-textarea
                                            v-model="slide.description"
                                            variant="outlined"
                                            density="comfortable"
                                            label="توضیحات تکمیلی"
                                            rows="3"
                                            prepend-inner-icon="mdi-text-short"
                                            hide-details="auto"
                                        />
                                    </v-col>
                                </v-row>
                            </v-col>

                            <!-- ستون سمت چپ: آپلود تصویر -->
                            <v-col cols="12" md="5">
                                <v-card variant="tonal" color="grey-darken-1" class="h-100 border-dashed">
                                    <v-card-text class="d-flex flex-column h-100">
                                        <div class="d-flex align-center mb-2 text-primary">
                                            <v-icon class="me-2" size="small">mdi-image-area</v-icon>
                                            <span class="text-caption font-weight-bold">تصویر اسلاید</span>
                                        </div>

                                        <div class="flex-grow-1">
                                            <ImageUploader
                                                v-model:model-value="slide.image.id"
                                                :initialUrl="slide.image?.url"
                                                upload-route="admin.upload.general.image"
                                                label="انتخاب فایل"
                                                accept="image/*"
                                                type="setting"
                                            />
                                        </div>
                                        <div class="text-caption text-grey text-center mt-2">
                                            سایز پیشنهادی: ۱۹۲۰x۶۰۰ پیکسل
                                        </div>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>

            <!-- دکمه‌های پایین -->
            <v-card-actions class="d-flex justify-space-between pt-4 border-t mt-2">
                <v-btn
                    color="primary"
                    variant="text"
                    prepend-icon="mdi-plus-circle"
                    height="45"
                    @click="addSlide"
                >
                    افزودن اسلاید جدید
                </v-btn>

                <v-btn
                    color="primary"
                    variant="flat"
                    :loading="isSaving"
                    :disabled="isSaving"
                    prepend-icon="mdi-content-save-check"
                    class="px-6"
                    height="45"
                    elevation="2"
                    @click="saveSlides"
                >
                    ذخیره تغییرات
                </v-btn>
            </v-card-actions>
        </v-card-text>
    </v-card>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import ImageUploader from '@/Components/ImageUploader.vue'

// Props & Emits
const props = defineProps({
    slides: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['saved', 'deleted'])

// State
const panel = ref([])
const isSaving = ref(false)
const slidesLocal = ref([])
const targetsLocal = ref([
    { text: 'همان صفحه (Self)', value: '_self' },
    { text: 'تب جدید (Blank)', value: '_blank' },
])

// Helpers
function cloneSlides(list) {
    return (list ?? []).map(slide => ({
        id: slide.id ?? null,
        title: slide.title ?? '',
        description: slide.description ?? '',
        link: slide.link ?? '',
        target: slide.target ?? '_self',
        image: {
            id: slide.image?.id ?? null,
            url: slide.image?.url ?? '',
        },
    }))
}

// *** رفع خطا: تعریف تابع قبل از استفاده در Watch ***
const expandAllPanels = () => {
    panel.value = [...Array(slidesLocal.value.length).keys()]
}

// Watchers
watch(
    () => props.slides,
    (newSlides) => {
        slidesLocal.value = cloneSlides(newSlides)
        if(newSlides.length > 0 && panel.value.length === 0) {
            expandAllPanels()
        }
    },
    { deep: true, immediate: true }
)

// Actions
const addSlide = () => {
    slidesLocal.value.push({
        id: null,
        title: '',
        description: '',
        link: '',
        target: '_self',
        image: {
            id: null,
            url: '',
        },
    })

    // باز کردن پنل جدید
    setTimeout(() => {
        const newIndex = slidesLocal.value.length - 1
        if (!panel.value.includes(newIndex)) {
            panel.value = [...panel.value, newIndex]
        }
    }, 100)
}

const removeSlide = async (id, index) => {
    const confirmMsg = 'آیا از حذف این اسلاید اطمینان دارید؟';
    const confirmed = window.$confirm ? await window.$confirm(confirmMsg) : confirm(confirmMsg);

    if (!confirmed) return

    if (!id) {
        slidesLocal.value.splice(index, 1)
        // اصلاح ایندکس‌ها
        panel.value = panel.value
            .filter(p => p !== index)
            .map(p => p > index ? p - 1 : p)
        return
    }

    router.delete(route('admin.settings.slides.destroy', { id }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            slidesLocal.value = cloneSlides(response.props.slides)
            emit('deleted', response.props.slides)
            if(slidesLocal.value.length === 0) panel.value = []
        },
    })
}

const saveSlides = async () => {
    const form = useForm({
        slides: slidesLocal.value.map(slide => ({
            ...slide,
            image: {
                id: slide.image?.id ?? null,
                url: slide.image?.url ?? '',
            }
        })),
    })

    await form.put(route('admin.settings.slides.update'), {
        onStart: () => {
            isSaving.value = true
        },
        onSuccess: (response) => {
            slidesLocal.value = cloneSlides(response.props.slides)
            emit('saved', response.props.slides)
        },
        onFinish: () => (isSaving.value = false),
    })
}

onMounted(() => {
    slidesLocal.value = cloneSlides(props.slides)
    if (slidesLocal.value.length > 0) {
        expandAllPanels()
    }
})
</script>

<style scoped>
/* استایل برای ایجاد حاشیه خط‌چین دور باکس آپلود */
.border-dashed {
    border-style: dashed !important;
}

.delete-btn {
    width: 24px;
    height: 24px;
    border-radius: 4px;
}
</style>
