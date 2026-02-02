<template>
    <Head :title="`انقلاب | ${page.props.pageTitle}`"/>
    <v-app>
        <v-main class="zo-main">
            <v-container fluid>
                <slot />
            <ConfirmDialog ref="confirmRef" />
            <FlashMessage />

            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref,onMounted } from 'vue'
import FlashMessage from '@/Components/FlashMessage.vue'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'
import {Head, usePage} from "@inertiajs/vue3";
const page = usePage();
const confirmRef = ref(null)

/* Global Confirm */
onMounted(() => {
    window.$confirm = async (message, options = {}) =>
        await confirmRef.value.open({
            msg: message,
            ttl: options.title || 'تأیید عملیات',
            color: options.color || 'red',
        })
})
</script>
