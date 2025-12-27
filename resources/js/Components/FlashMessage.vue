<template>
    <v-snackbar
        v-model="snackbar"
        :color="snackColor"
        location="bottom"
        :timeout="8000"
        class="flash-message"
        :class="snackColor"
        content-class="snackbar-content"
    >
        <div class="d-flex flex-column">
            <!-- بخش اصلی پیام -->
            <div class="d-flex align-center message-content">
                <v-icon v-if="snackColor === 'success'" class="me-2" size="large">mdi-check-circle</v-icon>
                <v-icon v-else-if="snackColor === 'error'" class="me-2" size="large">mdi-alert-circle</v-icon>
                <v-icon v-else class="me-2" size="large">mdi-information</v-icon>

                <span class="font-weight-bold">{{ snackText }}</span>
            </div>


        </div>

        <template #actions>
            <v-btn
                variant="text"
                color="white"
                icon
                @click="snackbar = false"
                class="close-btn"
                size="small"
            >
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const snackbar = ref(false)
const snackColor = ref('primary')
const snackText = ref('')
const snackData = ref(null) // متغیر جدید برای نگهداری دیتا

const page = usePage()

// Watch for flash messages from server
watch(
    () => page.props.flash,
    (flash) => {
        if (!flash) return

        let hasMessage = false;

        // Reset data
        snackData.value = null;

        // Check for Data explicitly passed
        if (flash.data) {
            snackData.value = flash.data;
        }

        if (flash.error) {
            snackColor.value = 'error'
            snackText.value = typeof flash.error === 'string'
                ? flash.error
                : flash?.message || 'خطایی رخ داد'
            hasMessage = true
        } else if (flash.success) {
            snackColor.value = 'success'
            snackText.value = typeof flash.success === 'string'
                ? flash.success
                : flash?.message || 'عملیات موفق'
            hasMessage = true
        } else if (flash.message) {
            snackColor.value = 'info'
            snackText.value = flash.message
            hasMessage = true
        }

        // اگر پیامی بود نمایش بده
        if (hasMessage) {
            snackbar.value = true
        }
    },
    { immediate: true, deep: true }
)

// Watch for validation errors (from page.props.errors)
watch(
    () => page.props.errors,
    (errors) => {
        if (errors && Object.keys(errors).length > 0) {
            // گاهی اوقات ممکن است بخواهید دیتای خطا را هم اینجا هندل کنید
            // اما معمولا errors فقط شامل پیام‌های اعتبارسنجی است.

            const firstKey = Object.keys(errors)[0]
            if (errors[firstKey]) {
                snackColor.value = 'error'
                snackText.value = Array.isArray(errors[firstKey])
                    ? errors[firstKey][0]
                    : errors[firstKey]

                // در حالت Validation Error معمولا دیتای اضافی نداریم مگر اینکه خودتان لاجیک خاصی بزنید
                snackData.value = null

                snackbar.value = true
            }
        }
    },
    { immediate: true, deep: true }
)
</script>

<style scoped>
.list-unstyled {
    list-style: none;
    padding: 0;
    margin: 0;
}
</style>
