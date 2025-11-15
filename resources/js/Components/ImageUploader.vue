<template>
    <ShowMessage
        v-model:show="isError"
        :message="errorMessage"
        type="error"
    />

    <!-- اگر فایل آپلود شده است -->
    <div v-if="fileUploaded" class="thumbnail-box">

        <div class="thumbnail-image">
            <v-badge
                location="top left"
                color="error"
                :model-value="true"
                class="thumbnail-badge"
            >
                <img
                    :src="thumbnailUrl"
                    class="thumbnail"
                    @click="dialog = true"

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
        </div>
        <div class="thumbnail-text">
           {{thumbnailText}}
        </div>

        <!-- دیالوگ بزرگ برای تصویر -->
        <v-dialog v-model="dialog" max-width="800">
            <v-card>
                <v-card-title class="d-flex justify-end align-center pa-0">
                    <v-btn
                        icon
                        @click="dialog = false"
                        size="small"
                        class="dialog-close-btn"
                    >
                        <v-icon size="small">mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text class="pa-0 d-flex" style="height: 80vh;">
                    <v-img
                        :src="thumbnailUrl"
                        class="w-100 h-100"
                        cover
                    >
                        <template v-slot:placeholder>
                            <v-row
                                class="fill-height ma-0"
                                align="center"
                                justify="center"
                            >
                                <v-progress-circular
                                    indeterminate
                                    color="primary"
                                ></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>

    <!-- اگر فایل هنوز آپلود نشده است -->
    <v-file-upload
        v-else
        v-model="thumbnail"
        density="compact"
        variant="plain"
        :title="title"
        @change="uploadThumbnail"
        :disabled="fileUploading"
        :accept="accept"
        :label="label"
        :rules="[v => !v || v.type.startsWith(accept.replace('/*', '/')) || `فقط فایل ${accept.replace('/*', '')} مجاز است`]"
        class="custom-file-upload"
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

const dialog = ref(false);

const props = defineProps({
    modelValue: [String, Number, null],
    uploadRoute: { type: String, required: true, default: '' },
    title: { type: String, default: 'بارگذاری تصویر' },
    label: { type: String, default: 'فایل را اینجا رها کنید یا کلیک کنید' },
    accept: { type: String, default: 'image/*' },
    maxSize: { type: Number, default: 5 * 1024 * 1024 },
    initialUrl: { type: String, default: '' },
    thumbnailText: { type: String, default: 'برای تغییر تصویر، ابتدا حذف کنید' }
});

const emit = defineEmits([
    'update:modelValue',
    'uploaded',
    'removed'
]);

const thumbnail = ref(null);
const thumbnailUrl = ref(props.initialUrl);

const progress = ref(0);
const fileUploading = ref(false);
const fileUploaded = ref(!!props.initialUrl);

watch(() => props.initialUrl, (newUrl) => {
    if (newUrl) {
        thumbnailUrl.value = newUrl;
        fileUploaded.value = true;
    } else {
        thumbnailUrl.value = '';
        fileUploaded.value = false;
    }
});

const errorMessage = ref('');
const isError = ref(false)
const showError = (message) => {
    errorMessage.value = message;
    isError.value = true;
    console.error('Upload Error:', message);
    setTimeout(() => { isError.value = false; }, 5000);
};

const uploadThumbnail = async () => {
    if (!thumbnail.value) return;
    const file = thumbnail.value;

    const acceptType = props.accept.replace('/*', '');
    if (!file.type.startsWith(acceptType)) {
        showError(`لطفاً فقط فایل ${acceptType} آپلود کنید`);
        thumbnail.value = null;
        return;
    }

    if (file.size > props.maxSize) {
        showError(`حجم فایل نباید بیشتر از ${props.maxSize / (1024*1024)} مگابایت باشد`);
        thumbnail.value = null;
        return;
    }

    const formData = new FormData();
    formData.append('file', file);

    progress.value = 0;
    fileUploading.value = true;

    try {
        const response = await axios.post(route(props.uploadRoute), formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
            onUploadProgress: (e) => {
                if (e.total) progress.value = Math.round((e.loaded / e.total) * 100);
            },
        });

        const result = response.data;
        thumbnailUrl.value = result.data.url;
        fileUploaded.value = true;
        progress.value = 0;

        emit('update:modelValue', result.data.id);
        emit('uploaded', { url: result.data.url, id: result.data.id, file });
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
    emit('update:modelValue', null);
    emit('removed', removedUrl);
};

watch(() => props.modelValue, (newVal) => {
    if (!newVal) {
        thumbnailUrl.value = '';
        fileUploaded.value = false;
    }
}, { immediate: true });
</script>

<style scoped>
.thumbnail-box {
    display: flex;
    flex-direction: row;
    align-items: center;
    border: 1px dashed #bbb;
    background: #fafafa;
    padding: 10px 15px;
    border-radius: 8px;
    width: 100%;
    cursor: pointer;
    transition: 0.2s;
}

.thumbnail-box:hover {
    background: #f0f0f0;
}

.thumbnail-text {
    font-size: 14px;
    color: #555;
    flex: 1;
    text-align: right;
    padding-right: 15px;
}

.thumbnail-image {
    display: flex;
    align-items: center;
    margin-right: auto;
}

/* Custom styles for file upload */
.custom-file-upload {
    --v-input-control-height: 36px;
    min-height: 36px !important;
}

.custom-file-upload .v-field {
    --v-input-control-height: 36px;
    min-height: 36px !important;
}

.custom-file-upload .v-field__field {
    min-height: 36px !important;
}

.custom-file-upload .v-field__input {
    min-height: 36px !important;
    padding-top: 4px !important;
    padding-bottom: 4px !important;
}

.thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.delete-btn {
    position: absolute;
    top: -5px;
    right: -5px;
    z-index: 2;
    margin: 0;
    padding: 4px;
    min-width: 24px;
    height: 24px;
    background: #f44336 !important;
    border-radius: 4px;
    color: white !important;
}

.delete-btn:hover {
    background: #d32f2f !important;
}

.dialog-close-btn {
    background: #f44336 !important;
    color: white !important;
    border-radius: 4px;
    margin: 8px 8px 0 0;
    position: absolute;
    right: 8px;
    top: 8px;
    z-index: 10;
}

.dialog-close-btn:hover {
    background: #d32f2f !important;
}

.delete-btn .v-icon {
    font-size: 12px;
}

.thumbnail-badge :deep(.v-badge__badge) {
    position: static;
    transform: none;
    background: transparent !important;
    border: none;
    box-shadow: none;
    width: auto;
    height: auto;
    min-width: auto;
    padding: 0;
}

.thumbnail-badge :deep(.v-badge__badge .v-btn) {
    min-width: 18px;
    width: 18px;
    height: 18px;
    margin: 0;
    padding: 0;
}
</style>
