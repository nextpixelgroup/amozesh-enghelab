<template>
    <AdminLayout>
        <Head title="تنظیمات فهرست"/>
        <div class="zo-header-section mb-5">
            <v-row class="align-center">
                <v-col class="v-col-lg-9">
                    <div class="zo-info d-lg-flex d-sm-none">
                        <div class="zo-icon elevation-4">
                            <i class="mdi mdi-cog"></i>
                        </div>
                        <div class="zo-name">
                            <strong class="d-block mb-1">تنظیمات فهرست</strong>
                            <span>تنظیمات فهرست سایت</span>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
    </AdminLayout>
</template>
<script setup lang="ts">
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Head} from "@inertiajs/vue3";
</script>
