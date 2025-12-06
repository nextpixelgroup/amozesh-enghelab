<script setup>
import {computed, ref, watch} from "vue";

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true
    },
    category: {
        type: Object,
        default: () => ({
            title: '',
            slug: '',
            description: '',
        })
    },
    isEditing: {
        type: Boolean,
        default: false
    },
    loading: Boolean
})

const emit = defineEmits(['update:modelValue', 'save'])

const form = ref({...props.category})
const title = computed(() => props.isEditing ? 'ویرایش دسته' : 'افزودن دسته جدید')

watch(() => props.category, (newVal) => {
    form.value = {...newVal}
}, {deep: true})

const save = () => {
    emit('save', form.value)
}

const close = () => {
    emit('update:modelValue', false)
}
</script>

<template>
    <v-dialog
        :model-value="modelValue"
        max-width="600px"
        @update:model-value="close"
    >
        <v-card>
            <v-card-title class="d-flex justify-space-between align-center">
                <strong>{{ title }}</strong>
                <v-btn icon flat size="small" @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text class="pb-2">
                <v-row dense>
                    <v-col class="v-col-12">
                        <v-text-field
                            v-model="form.title"
                            label="عنوان دسته"
                            required
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="mdi-text"
                            hide-details
                        ></v-text-field>
                    </v-col>
                    <v-col class="v-col-12">
                        <v-text-field
                            v-model="form.slug"
                            label="نامک"
                            required
                            variant="outlined"
                            density="comfortable"
                            prepend-inner-icon="mdi-link"
                            hide-details
                        ></v-text-field>
                    </v-col>
                    <v-col class="v-col-12">
                        <v-textarea
                            v-model="form.description"
                            label="توضیحات"
                            variant="outlined"
                            density="comfortable"
                            rows="3"
                            prepend-inner-icon="mdi-text-long"
                            hide-details
                        ></v-textarea>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions class="justify-center mb-3">
                <v-btn color="primary" variant="tonal" @click="save" :loading="loading" :disabled="loading">
                    ثبت تغییرات
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
