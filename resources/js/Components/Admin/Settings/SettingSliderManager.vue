<template>
    <v-card class="mb-6">
        <v-card-title class="d-flex align-center">
            <v-icon class="me-2">mdi-image-multiple</v-icon>
            مدیریت اسلایدر صفحه اصلی
        </v-card-title>

        <v-card-text>
            <v-expansion-panels v-model="panel" multiple>
                <v-expansion-panel
                    v-for="(slide, index) in slidesLocal"
                    :key="slide.id ?? index"
                    :value="index"
                >
                    <v-expansion-panel-title>
                        <span>اسلاید {{ index + 1 }}</span>
                        <v-spacer></v-spacer>
                        <v-btn
                            icon
                            color="error"
                            variant="flat"
                            class="delete-btn ml-2 px-1 py-1"
                            @click="removeSlide(slide.id, index)"
                        >
                            <v-icon size="small">mdi-close</v-icon>
                        </v-btn>
                    </v-expansion-panel-title>

                    <v-expansion-panel-text>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="slide.title"
                                    variant="outlined"
                                    density="comfortable"
                                    label="عنوان اسلاید"
                                    class="mb-3"
                                />
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="slide.link"
                                    variant="outlined"
                                    density="comfortable"
                                    label="لینک"
                                    class="mb-3"
                                    dir="ltr"
                                />
                            </v-col>

                            <v-col cols="12" md="6">
                                <v-select
                                    v-model="slide.target"
                                    :items="targetsLocal"
                                    item-title="text"
                                    item-value="value"
                                    variant="outlined"
                                    density="comfortable"
                                    label="نحوه باز شدن لینک"
                                    class="mb-3"
                                />
                            </v-col>

                            <v-col cols="12">
                                <v-textarea
                                    v-model="slide.description"
                                    variant="outlined"
                                    density="comfortable"
                                    label="توضیحات"
                                    rows="2"
                                    class="mb-3"
                                />
                            </v-col>

                            <v-col cols="12">
                                <ImageUploader
                                    v-model:model-value="slide.image.id"
                                    :initialUrl="slide.image?.url"
                                    upload-route="admin.upload.general.image"
                                    label="فقط فایل تصویری آپلود کنید"
                                    accept="image/*"
                                    type="general"
                                />
                            </v-col>
                        </v-row>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>

            <div class="d-flex mt-4">
                <v-btn
                    color="primary"
                    variant="tonal"
                    prepend-icon="mdi-plus"
                    class="me-2"
                    @click="addSlide"
                >
                    افزودن اسلاید جدید
                </v-btn>

                <v-spacer />

                <v-btn
                    color="primary"
                    :loading="isSaving"
                    :disabled="isSaving"
                    @click="saveSlides"
                >
                    ذخیره تغییرات
                </v-btn>
            </div>
        </v-card-text>
    </v-card>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import ImageUploader from '@/Components/ImageUploader.vue'

const props = defineProps({
    slides: {
        type: Array,
        default: () => [],
    },
})

const emit = defineEmits(['saved', 'deleted'])

const panel = ref([])
const isSaving = ref(false)
const slidesLocal = ref(cloneSlides(props.slides))
const targetsLocal = ref([
    { text: 'همان صفحه', value: '_self' },
    { text: 'تب جدید', value: '_blank' },
])

watch(
    () => props.slides,
    (newSlides) => {
        slidesLocal.value = cloneSlides(newSlides)
        expandAllPanels()
    },
    { deep: true }
)

function cloneSlides(list) {
    return (list ?? []).map(slide => ({
        ...slide,
        image: {
            ...(slide.image ?? {}),
        },
    }))
}

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
    panel.value.push(slidesLocal.value.length - 1)
}

const removeSlide = async (id, index) => {
    const confirmed = await window.$confirm?.('آیا از حذف این اسلاید اطمینان دارید؟')
    if (!confirmed) return

    if (!id) {
        slidesLocal.value.splice(index, 1)
        return
    }

    router.delete(route('admin.settings.slides.destroy', { id }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            slidesLocal.value = cloneSlides(response.props.slides)
            expandAllPanels()
            emit('deleted', response.props.slides)
        },
    })
}

const saveSlides = async () => {
    const form = useForm({
        slides: slidesLocal.value,
    })

    form.transform(data => ({
        ...data,
        slides: data.slides.map(slide => ({
            ...slide,
            image_id: slide.image?.id ?? null,
        })),
    }))

    await form.put(route('admin.settings.slides.update'), {
        onStart: () => (isSaving.value = true),
        onSuccess: (response) => {
            slidesLocal.value = cloneSlides(response.props.slides)
            expandAllPanels()
            emit('saved', response.props.slides)
        },
        onFinish: () => (isSaving.value = false),
    })
}

const expandAllPanels = () => {
    panel.value = [...Array(slidesLocal.value.length).keys()]
}

onMounted(expandAllPanels)
</script>

<style scoped>
.delete-btn {
    width: 24px;
    height: 24px;
    border-radius: 4px;
}
</style>
