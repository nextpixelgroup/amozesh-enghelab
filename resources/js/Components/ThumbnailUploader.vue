<template>

    <ShowMessage
        v-model:show="isError"
        :message="errorMessage"
        type="error"
    />
    <v-badge
        v-if="fileUploaded"
        location="top left"
        color="error"
        :model-value="true"
        class="thumbnail-badge"
    >
        <img
            :src="thumbnailUrl"
            width="100%"
            height="auto"
            class="mb-4"
            style="border: 1px solid #ddd; border-radius: 4px;"
        />
        <template #badge>
            <v-btn
                icon
                size="x-small"
                variant="flat"
                color="error"
                @click.stop="removeThumbnail"
                class="delete-btn"
            >
                <v-icon size="small">mdi-close</v-icon>
            </v-btn>
        </template>
    </v-badge>
    <v-file-upload
        v-if="!fileUploaded"
        v-model="thumbnail"
        density="comfortable"
        variant="comfortable"
        :title="title"
        @change="uploadThumbnail"
        :disabled="fileUploading"
        :accept="accept"
        :label="label"
        :rules="[v => !v || v.type.startsWith(accept.replace('/*', '/')) || `فقط فایل ${accept.replace('/*', '')} مجاز است`]"
    >
    </v-file-upload>
    <v-progress-linear
        v-if="progress > 0 && !fileUploaded"
        :model-value="progress"
        color="primary"
        height="8"
        class="mt-3 mb-3"
        striped
        rounded
        reactive
    ></v-progress-linear>
</template>

<script setup>
import { ref, watch } from 'vue';
import { route } from 'ziggy-js';
import {VFileUpload} from 'vuetify/labs/VFileUpload'
import ShowMessage from "@/Components/ShowMessage.vue";
const props = defineProps({
    modelValue: [String, Number, null],
    // Required props
    uploadRoute: {
        type: String,
        required: true,
        default: ''
    },
    // Optional props with defaults
    title: {
        type: String,
        default: 'بارگذاری تصویر'
    },
    label: {
        type: String,
        default: 'فایل را اینجا رها کنید یا کلیک کنید'
    },
    accept: {
        type: String,
        default: 'image/*'
    },
    maxSize: {
        type: Number,
        default: 5 * 1024 * 1024 // 5MB
    },
    initialUrl: {
        type: String,
        default: ''
    }
});

const emit = defineEmits([
    'update:modelValue', // Add this for v-model support
    'uploaded',
    'removed',
    // Remove 'error' from here as we're handling it internally
]);

const thumbnail = ref(null);
const thumbnailUrl = ref(props.initialUrl);
const progress = ref(0);
const fileUploading = ref(false);
const fileUploaded = ref(!!props.initialUrl);

// Watch for changes in initialUrl
watch(() => props.initialUrl, (newUrl) => {
    if (newUrl) {
        thumbnailUrl.value = newUrl;
        fileUploaded.value = true;
    } else {
        thumbnailUrl.value = '';
        fileUploaded.value = false;
    }
});

// Error handling
const errorMessage = ref('');
const isError = ref(false)
const showError = (message) => {
    errorMessage.value = message;
    isError.value = true;
    console.error('Upload Error:', message);
    setTimeout(() => {
        isError.value = false;
    }, 5000);
};
const uploadThumbnail = async () => {
    if (!thumbnail.value) return;

    const file = thumbnail.value;

    if (!file) return;

    // Check file type
    const acceptType = props.accept.replace('/*', '');
    if (!file.type.startsWith(acceptType)) {
        emit('error', `لطفاً فقط فایل ${acceptType} آپلود کنید`);
        thumbnail.value = null;
        return;
    }

    // Check file size
    if (file.size > props.maxSize) {
        const maxSizeMB = props.maxSize / (1024 * 1024);
        emit('error', `حجم فایل نباید بیشتر از ${maxSizeMB} مگابایت باشد`);
        thumbnail.value = null;
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    progress.value = 0;
    fileUploading.value = true;

    try {
        const response = await axios.post(route(props.uploadRoute), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onUploadProgress: (e) => {
                if (e.total) {
                    progress.value = Math.round((e.loaded / e.total) * 100);
                }
            },
        });

        const result = response.data;
        thumbnailUrl.value = result.data.url;
        fileUploaded.value = true;
        progress.value = 0;

        // Update the model value with the new ID
        emit('update:modelValue', result.data.id);

        // Emit the uploaded file data
        emit('uploaded', {
            url: result.data.url,
            id: result.data.id,
            file: file
        });

    } catch (error) {
        const errorMessage = error.response?.data?.message || 'خطا در آپلود فایل!';
        showError(errorMessage);
    } finally {
        fileUploading.value = false;
    }
};

const removeThumbnail = () => {
    const removedUrl = thumbnailUrl.value;
    thumbnail.value = null;
    thumbnailUrl.value = '';
    fileUploaded.value = false;

    // Clear the model value
    emit('update:modelValue', null);

    // Emit the removed event
    emit('removed', removedUrl);
};


// Watch for external modelValue changes (for edit mode)
watch(() => props.modelValue, (newVal) => {
    if (!newVal) {
        thumbnailUrl.value = '';
        fileUploaded.value = false;
    }
    // If you have an initialUrl prop, you can handle it here
    // or you can fetch the thumbnail URL based on the ID
}, { immediate: true });
</script>

<style scoped>
.thumbnail-badge {
    width: 100%;
}

.thumbnail-badge :deep(.v-badge__badge) {
    cursor: pointer;
    top: 0;
    right: -1px !important;
}

.thumbnail-badge :deep(.v-badge__badge .delete-btn) {
    width: 24px;
    height: 24px;
}

.thumbnail-badge :deep(.v-badge__badge .v-btn) {
    width: 100%;
    height: 100%;
}
</style>
