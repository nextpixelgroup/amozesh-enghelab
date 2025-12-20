<template>
    <WebLayout>
        <PanelLayout>
            <div class="zo-dashboard-section">
                <v-btn @click="logout" :loading="leaving" :disabled="leaving">خروج</v-btn>

            </div>
        </PanelLayout>
    </WebLayout>
</template>
<script setup lang="ts">
import WebLayout from "@/Layouts/WebLayout.vue";
import { router } from "@inertiajs/vue3";
import PanelLayout from "@/Layouts/PanelLayout.vue";
import { route } from "ziggy-js";
import { ref } from "vue";
const leaving = ref(false)
const logout = () => {
    leaving.value = true
    router.post(route('panel.logout'), {}, {
        onFinish: () => (leaving.value = false)
    })
}

</script>
