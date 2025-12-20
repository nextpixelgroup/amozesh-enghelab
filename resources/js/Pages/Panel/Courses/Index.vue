<template>
    <WebLayout>
        <PanelLayout>
            <v-btn @click="logout" :loading="leaving" :disabled="leaving">خروج</v-btn>
        </PanelLayout>
        <div class="zo-dashboard-section">
            <div class="zo-nav">
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#" class="zo-active">
                                            <i class="mdi mdi-account-circle"></i>
                                            <span>پروفایل کاربری</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-human-male-board"></i>
                                            <span>آموزش‌های من</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-bookmark"></i>
                                            <span>علاقه‌مندی‌ها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-cart"></i>
                                            <span>سفارش‌ها</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="mdi mdi-phone-in-talk"></i>
                                            <span>ارتباط پشتیبان</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </v-col>
                    </v-row>
                </v-container>
            </div>
        </div>
        <v-btn @click="logout" :loading="leaving" :disabled="leaving">خروج</v-btn>
    </WebLayout>
</template>
<script setup lang="ts">
import WebLayout from "@/Layouts/WebLayout.vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {ref} from "vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";
import { router } from "@inertiajs/vue3";
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
