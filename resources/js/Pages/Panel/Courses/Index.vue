<template>
    <WebLayout>
        <PanelLayout>
            <v-btn @click="logout" :loading="leaving" :disabled="leaving">خروج</v-btn>
        </PanelLayout>
    </WebLayout>
</template>
<script setup lang="ts">
import WebLayout from "@/Layouts/WebLayout.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {ref} from "vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
const leaving = ref(false)
const logout = () => {
    leaving.value = true
    router.post(route('panel.logout'), {}, {
        onFinish: () => (leaving.value = false)
    })
}
</script>
