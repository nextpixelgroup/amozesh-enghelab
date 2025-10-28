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
            status: true
        })
    },
    isEditing: {
        type: Boolean,
        default: false
    }
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
            <v-card-title>
                <span class="text-h5">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-text-field
                                v-model="form.title"
                                label="عنوان دسته"
                                required
                                outlined
                                dense
                            ></v-text-field>
                            <v-text-field
                                v-model="form.slug"
                                label="نامک"
                                required
                                outlined
                                dense
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea
                                v-model="form.description"
                                label="توضیحات"
                                outlined
                                rows="3"
                                dense
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12">
                            <v-switch
                                v-model="form.status"
                                :label="`وضعیت: ${form.status ? 'فعال' : 'غیرفعال'}`"
                                color="success"
                                hide-details
                            ></v-switch>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" text @click="close">انصراف</v-btn>
                <v-btn color="primary" @click="save">ذخیره</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
