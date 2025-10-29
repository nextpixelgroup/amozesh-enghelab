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
        <div class="d-flex align-center message-content">
            <v-icon v-if="snackColor === 'success'" class="me-2" size="large">mdi-check-circle</v-icon>
            <v-icon v-else-if="snackColor === 'error'" class="me-2" size="large">mdi-alert-circle</v-icon>
            <v-icon v-else class="me-2" size="large">mdi-information</v-icon>
            {{ snackText }}
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

const page = usePage()

// Watch for flash messages from server
watch(
    () => page.props.flash,
    (flash) => {
        if (!flash) return

        // Only process if there's a message
        if (flash.error || flash.success || flash.message) {
            if (flash.error) {
                snackColor.value = 'error'
                snackText.value = typeof flash.error === 'string'
                    ? flash.error
                    : flash?.message || 'خطایی رخ داد'
            } else if (flash.success) {
                snackColor.value = 'success'
                snackText.value = typeof flash.success === 'string'
                    ? flash.success
                    : flash?.message || 'عملیات موفق'
            } else if (flash.message) {
                snackColor.value = 'info'
                snackText.value = flash.message
            }

            // Only show the snackbar if we have text to display
            if (snackText.value) {
                snackbar.value = true
            }
        }
    },
    { immediate: true, deep: true }
)

// Watch for validation errors (from page.props.errors)
watch(
    () => page.props.errors,
    (errors) => {
        if (errors && Object.keys(errors).length > 0) {
            const firstKey = Object.keys(errors)[0]
            if (errors[firstKey]) {
                snackColor.value = 'error'
                snackText.value = Array.isArray(errors[firstKey])
                    ? errors[firstKey][0]
                    : errors[firstKey]
                snackbar.value = true
            }
        }
    },
    { immediate: true, deep: true }
)
</script>
