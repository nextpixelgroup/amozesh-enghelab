<template>
    <v-snackbar 
        v-model="snackbar" 
        :color="snackColor" 
        location="bottom" 
        timeout="4000"
    >
        {{ snackText }}
        <template #actions>
            <v-btn variant="text" @click="snackbar = false">بستن</v-btn>
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
        console.log('Flash received:', flash)
        if (!flash) return
        
        if (flash.error) {
            snackColor.value = 'error'
            snackText.value = typeof flash.error === 'string' 
                ? flash.error 
                : flash.error?.message || 'خطایی رخ داد'
            snackbar.value = true
        } else if (flash.success) {
            snackColor.value = 'success'
            snackText.value = typeof flash.success === 'string' 
                ? flash.success 
                : flash.success?.message || 'عملیات موفق'
            snackbar.value = true
        } else if (flash.message) {
            snackColor.value = 'info'
            snackText.value = flash.message
            snackbar.value = true
        }
    },
    { immediate: true, deep: true }
)

// Watch for validation errors (from page.props.errors)
watch(
    () => page.props.errors,
    (errors) => {
        console.log('Errors received:', errors)
        if (errors && Object.keys(errors).length > 0) {
            const firstKey = Object.keys(errors)[0]
            snackColor.value = 'error'
            snackText.value = errors[firstKey]
            snackbar.value = true
        }
    },
    { immediate: true, deep: true }
)
</script>
