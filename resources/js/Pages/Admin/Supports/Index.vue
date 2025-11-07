<template>
    <AdminLayout>
        <Head title="دوره‌های آموزشی"/>
        <div class="zo-header-section mb-5">
            <div class="zo-info d-lg-flex d-sm-none">
                <div class="zo-icon elevation-4">
                    <i class="mdi mdi-message-text-fast"></i>
                </div>
                <div class="zo-name">
                    <strong class="d-block mb-1">پیام‌ها</strong>
                    <span>در این بخش می توانید پیام‌های کاربران را مشاهده کنید.</span>
                </div>
            </div>
        </div>
        <v-card class="pa-3 mb-3 elevation-2">
            <v-row dense class="align-center">
                <v-col class="v-col-lg-2 v-col-12">
                    <v-select hide-details
                              variant="outlined"
                              density="compact"
                              label="بخش"
                              :items="['تماس با ما', 'دوره‌ها و آموزش', 'پیشنهادات']"
                    >
                    </v-select>
                </v-col>
                <v-col class="v-col-lg-2 v-col-12">
                    <v-text-field
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو موبایل"
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-3 v-col-12">
                    <v-text-field
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو کاربر"
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-4 v-col-12">
                    <v-text-field
                        hide-details
                        variant="outlined"
                        density="compact"
                        label="جستجو نظر"
                    >
                    </v-text-field>
                </v-col>
                <v-col class="v-col-lg-1 v-col-12">
                    <v-btn block variant="outlined" color="primary">جستجو</v-btn>
                </v-col>
            </v-row>
        </v-card>
        <v-card class="pa-3 elevation-2">
            <v-table>
                <thead>
                <tr>
                    <th class="text-center">ردیف</th>
                    <th class="text-center">بخش</th>
                    <th class="text-center">کاربر</th>
                    <th class="text-center">شماره موبایل</th>
                    <th class="text-center">پست الکترونیک</th>
                    <th class="text-center">تاریخ ارسال</th>
                    <th class="text-center">عملیات</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="item in messages"
                    :key="item.name"
                >
                    <td class="text-center">{{ item.row }}</td>
                    <td class="text-center">
                        {{ item.dep }}
                    </td>
                    <td class="text-center">
                        <strong>{{ item.user }}</strong>
                    </td>
                    <td class="text-center">
                        {{ item.mobile }}
                    </td>
                    <td class="text-center">
                        {{ item.email }}
                    </td>
                    <td>
                        <span class="d-block">{{ item.date }}</span>
                        <small>{{ item.time }}</small>
                    </td>
                    <td>
                        <div class="d-flex justify-center ga-1">
                            <v-tooltip text="نمایش پیام" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        icon="mdi-eye"
                                        size="small"
                                        color="primary"
                                        @click="openDialog(item)"
                                        v-bind="props"
                                    >
                                    </v-btn>
                                </template>
                            </v-tooltip>
                            <v-tooltip text="آرشیو پیام" location="top">
                                <template v-slot:activator="{ props }">
                                    <v-btn icon="mdi-trash-can" size="small" color="secondary" v-bind="props"></v-btn>
                                </template>
                            </v-tooltip>
                        </div>
                    </td>
                </tr>
                </tbody>
            </v-table>
            <v-pagination rounded="circle" :length="8"></v-pagination>
        </v-card>
        <v-dialog v-model="dialog" max-width="600">
            <v-card>
                <v-card-title class="d-flex justify-space-between align-center">
                    <strong>متن پیام</strong>
                    <v-btn icon flat size="small" @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>{{ message }}</v-card-text>
            </v-card>
        </v-dialog>
    </AdminLayout>
</template>
<script setup>
import {Head, Link, router} from '@inertiajs/vue3'
import {ref} from "vue";
import AdminLayout from "../../../Layouts/AdminLayout.vue";

const dialog = ref(false)
const message = ref('')

function openDialog(item) {
    message.value = item.message
    dialog.value = true
}

const messages = ref([
        {
            "id": "1",
            "row": "1",
            "dep": "تماس با ما",
            "user": "محسن امیریان",
            "mobile": "09123503696",
            "email": "mohsen.amirian@gmail.com",
            "message": "سلام و عرض ادب خدمت استاد مظلوم عزیز واقعا دست مریزاد باید بهتون گفت که هدفتون فقط خدمت و آموزش دادن با حداقل هزینه و بدون منت هستش بنده که واقعا کیف میکنم از دوره هاتون اگر زحمتی نیست دوره افزایش سرعت سایت و دوره رفع خطاهای سایت هم ضبط کنید",
            "date": "1404/07/19",
            "time": "9:42 ب.ظ"
        },
        {
            "id": "2",
            "row": "2",
            "dep": "پشتیبانی فنی",
            "user": "زهرا کریمی",
            "mobile": "09151239874",
            "email": "zahra.karimi@gmail.com",
            "message": "سلام، من در ورود به حساب کاربری مشکل دارم، لطفاً بررسی بفرمایید.",
            "date": "1404/08/02",
            "time": "11:15 ق.ظ"
        },
        {
            "id": "3",
            "row": "3",
            "dep": "دوره‌ها و آموزش",
            "user": "علی رحمانی",
            "mobile": "09128765432",
            "email": "ali.rahmani@yahoo.com",
            "message": "آیا دوره جدید طراحی UI هم به‌زودی منتشر می‌شود؟ خیلی مشتاقم شرکت کنم.",
            "date": "1404/08/04",
            "time": "4:28 ب.ظ"
        },
        {
            "id": "4",
            "row": "4",
            "dep": "پشتیبانی مالی",
            "user": "فاطمه عباسی",
            "mobile": "09351239876",
            "email": "fatemeh.abbasi@outlook.com",
            "message": "من هزینه دوره رو پرداخت کردم ولی در پنل من فعال نشده، لطفاً بررسی بفرمایید.",
            "date": "1404/08/05",
            "time": "10:09 ق.ظ"
        },
        {
            "id": "5",
            "row": "5",
            "dep": "تماس با ما",
            "user": "رضا شریفی",
            "mobile": "09134567892",
            "email": "reza.sharifi@gmail.com",
            "message": "استاد عزیز، دوره سئو پیشرفته واقعاً عالی بود. ممنون بابت زحماتتون.",
            "date": "1404/08/06",
            "time": "6:40 ب.ظ"
        },
        {
            "id": "6",
            "row": "6",
            "dep": "پشتیبانی فنی",
            "user": "نرگس محمدی",
            "mobile": "09129874563",
            "email": "narges.mohammadi@gmail.com",
            "message": "سلام، لینک دانلود یکی از جلسات باز نمی‌شود. لطفاً بررسی کنید.",
            "date": "1404/08/07",
            "time": "1:33 ب.ظ"
        },
        {
            "id": "7",
            "row": "7",
            "dep": "پیشنهادات",
            "user": "حمیدرضا احمدی",
            "mobile": "09364578921",
            "email": "hamidreza.ahmadi@gmail.com",
            "message": "به نظرم اگر دوره‌ها زیرنویس یا کپشن داشته باشند خیلی بهتر می‌شود.",
            "date": "1404/08/07",
            "time": "8:22 ب.ظ"
        },
        {
            "id": "8",
            "row": "8",
            "dep": "دوره‌ها و آموزش",
            "user": "مریم صالحی",
            "mobile": "09153467892",
            "email": "maryam.salehi@gmail.com",
            "message": "سلام استاد، امکانش هست دوره امنیت وردپرس هم تهیه کنید؟",
            "date": "1404/08/08",
            "time": "5:05 ب.ظ"
        },
        {
            "id": "9",
            "row": "9",
            "dep": "پشتیبانی فنی",
            "user": "سینا مرادی",
            "mobile": "09127893456",
            "email": "sina.moradi@gmail.com",
            "message": "در هنگام تماشای ویدیوها با خطای 'ویدیو در دسترس نیست' مواجه می‌شوم.",
            "date": "1404/08/09",
            "time": "3:18 ب.ظ"
        },
        {
            "id": "10",
            "row": "10",
            "dep": "تماس با ما",
            "user": "نگار سادات حسینی",
            "mobile": "09381234567",
            "email": "negar.hosseini@gmail.com",
            "message": "خیلی ممنون از تیم پشتیبانی، پاسخ‌گویی شما عالی بود. سپاس از زحماتتون.",
            "date": "1404/08/10",
            "time": "12:47 ب.ظ"
        }
    ]
)
</script>
