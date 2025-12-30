<template>
    <WebLayout>
        <PanelLayout>
            <v-container class="zo-certificate-section">
                <v-row>
                    <v-col cols="12">
                        <div class="zo-header mb-3">
                            <v-row class="align-center">
                                <v-col cols="12">
                                    <div class="zo-info d-lg-flex d-sm-none">
                                        <div class="zo-icon elevation-4">
                                            <i class="mdi mdi-certificate"></i>
                                        </div>
                                        <div class="zo-name">
                                            <strong class="d-block mb-1">لیست گواهینامه‌ها</strong>
                                            <span>در این بخش می توانید لیست گواهینامه‌های خود را مشاهده کنید.</span>
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                        <v-card class="zo-card pa-6">
                            <v-table>
                                <thead>
                                    <tr>
                                        <th class="text-center">ردیف</th>
                                        <th class="text-center">تصویر</th>
                                        <th>دوره موفق</th>
                                        <th class="text-center">تاریخ</th>
                                        <th class="text-center">شماره گواهینامه</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in certificates" :key="index">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center">
                                            <img :src="item.course.thumbnail" />
                                        </td>
                                        <td class="zo-course">
                                            <strong>{{ item.course.title }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <small dir="ltr">{{ item.created_at.title }}</small>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ item.certificate_number }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </PanelLayout>
    </WebLayout>
</template>
<script setup lang="ts">
import PanelLayout from "@/Layouts/PanelLayout.vue";
import WebLayout from "@/Layouts/WebLayout.vue";
import {ref} from "vue";
const props = defineProps({
    certificates: Object
})
/* داده‌های ثابت (Mock Data) */
const certificates = ref(props.certificates?.data);

</script>
<style scoped>
.zo-certificate-section .v-table>.v-table__wrapper>table>thead>tr {
    background: rgb(250, 250, 250);
}

.zo-certificate-section .v-table>.v-table__wrapper>table>thead>tr>th {
    padding: 6px;
    font-size: 0.9rem;
    background: rgb(245, 245, 245);
}

.zo-certificate-section .v-table>.v-table__wrapper>table>tbody>tr>td {
    padding: 8px;
}

.zo-certificate-section img {
    max-width: 45px;
    display: block;
    margin: auto;
}

.zo-certificate-section .zo-course strong {
    display: block;
}

.zo-certificate-section .zo-course small {
    color: var(--Sub);
}

</style>
