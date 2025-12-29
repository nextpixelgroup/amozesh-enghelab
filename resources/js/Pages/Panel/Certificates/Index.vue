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
                                        <th class="text-center">تاریخ صدور</th>
                                        <th class="text-center">شماره گواهینامه</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in certificates" :key="index">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center">
                                            <img :src="item.image" />
                                        </td>
                                        <td class="zo-course">
                                            <strong>{{ item.title }}</strong>
                                            <small>{{ item.description }}</small>
                                        </td>
                                        <td class="text-center">
                                            <small>{{ item.date }}</small>
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ item.code }}</strong>
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

/* داده‌های ثابت (Mock Data) */
const certificates = [{
        image: "/assets/img/sample/21.png",
        title: "مبانی فکری و اعتقادی انقلاب اسلامی ایران",
        description: "آشنایی با اصول فکری، اعتقادی و مبانی نظری انقلاب اسلامی",
        date: "1404/09/05 10:32",
        code: "874512963"
    },
    {
        image: "/assets/img/sample/22.png",
        title: "انقلاب اسلامی ایران؛ از شکل‌گیری تا تداوم",
        description: "ریشه‌های تاریخی، اجتماعی و فرهنگی انقلاب اسلامی ایران",
        date: "1404/08/18 14:45",
        code: "562198437"
    },
    {
        image: "/assets/img/sample/23.png",
        title: "جهاد تبیین در گام دوم انقلاب",
        description: "بررسی راهبردهای تبیین، روایت صحیح و مقابله با جنگ شناختی",
        date: "1404/07/02 09:15",
        code: "931475286"
    },
    {
        image: "/assets/img/sample/24.png",
        title: "هویت ملی در اندیشه امام خمینی (ره)",
        description: "تحلیل مفهوم هویت ملی و استقلال فرهنگی در اندیشه امام خمینی",
        date: "1404/06/21 16:08",
        code: "748236519"
    },
    {
        image: "/assets/img/sample/18.png",
        title: "هویت ملی در اندیشه آیت‌الله خامنه‌ای",
        description: "چیستی هویت ملی و نسبت آن با تمدن نوین اسلامی",
        date: "1404/05/11 11:54",
        code: "615983742"
    },
    {
        image: "/assets/img/sample/17.png",
        title: "انقلاب اسلامی و نظام مردم‌سالاری دینی",
        description: "بررسی پیوند جمهوریت و اسلامیت در نظام جمهوری اسلامی ایران",
        date: "1404/04/03 13:27",
        code: "489721356"
    }
];

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