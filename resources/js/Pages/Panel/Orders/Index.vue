<template>
    <WebLayout>
        <PanelLayout>
            <v-container>
                <v-row>
                    <v-col cols="12">
                        <div class="zo-header mb-3">
                            <v-row class="align-center">
                                <v-col cols="12">
                                    <div class="zo-info d-lg-flex d-sm-none">
                                        <div class="zo-icon elevation-4">
                                            <i class="mdi mdi-cart"></i>
                                        </div>
                                        <div class="zo-name">
                                            <strong class="d-block mb-1">لیست سفارش‌ها</strong>
                                            <span>در این بخش می توانید لیست سفارش‌های خود را مشاهده کنید.</span>
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
                                        <th class="text-center">کد سفارش</th>
                                        <th class="text-center">جزییات سفارش</th>
                                        <th class="text-center">قیمت</th>
                                        <th class="text-center">ثبت سفارش</th>
                                        <th class="text-center">وضعیت</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(order, index) in orders" :key="order.code">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center">{{ order.code }}</td>
                                        <td>
                                            <ul>
                                                <li v-for="(item, i) in order.items" :key="item.title + i">
                                                    <img :src="item.image" />
                                                    <div>
                                                        <strong>{{ item.title }}</strong>
                                                        <small>{{ item.subtitle }}</small>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            <div class="zo-price text-center">
                                                <del>{{ order.oldPrice }}</del>
                                                <div class="zo-sale">
                                                    <strong>{{ order.newPrice }}</strong>
                                                    <small>تومان</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span>{{ order.date }}</span>
                                        </td>
                                        <td class="text-center">
                                            <v-chip small :class="getStatusClass(order.status)">
                                                {{ order.status }}
                                            </v-chip>
                                        </td>
                                        <td class="text-center">
                                            <v-btn color="primary" @click="showDetails(order)">
                                                جزئیات سفارش
                                            </v-btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <!-- Dialog -->
            <v-dialog v-model="detailDialog" max-width="600">
                <v-card class="pa-4 rounded-lg">
                    <v-card-title class="d-flex justify-space-between align-center">
                        <h6 class="font-weight-bold">جزئیات کامل سفارش</h6>
                        <v-btn icon="mdi-close" variant="text" @click="detailDialog = false"></v-btn>
                    </v-card-title>
                    <v-divider class="mb-4"></v-divider>
                    <v-card-text v-if="selectedOrder">
                        <div class="mb-4 d-flex justify-space-between">
                            <span>
                                کد سفارش:
                                <strong>{{ selectedOrder.code }}</strong>
                            </span>
                            <span>
                                وضعیت:
                                <v-chip size="small" color="primary">
                                    {{ selectedOrder.status }}
                                </v-chip>
                            </span>
                        </div>
                        <v-list dense border class="rounded-lg">
                            <v-list-item v-for="(item, i) in selectedOrder.items" :key="item.title + i" class="py-3">
                                <template #prepend>
                                    <v-img :src="item.image" width="50" class="ml-3" />
                                </template>
                                <v-list-item-title class="font-weight-bold">
                                    {{ item.title }}
                                    <v-chip size="x-small" variant="outlined" color="primary" class="font-weight-bold">
                                        {{ item.number }}
                                    </v-chip>
                                </v-list-item-title>
                                <v-list-item-subtitle>
                                    {{ item.subtitle }}
                                </v-list-item-subtitle>
                                <template #append>
                                    <div class="text-left">
                                        <div class="zo-price">
                                            <del>{{ selectedOrder.oldPrice }}</del>
                                            <div class="zo-sale">
                                                <strong>{{ selectedOrder.newPrice }}</strong>
                                                <small>تومان</small>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </v-list-item>
                        </v-list>
                        <div class="mt-5 pa-4 bg-grey-lighten-4 rounded-lg">
                            <div class="d-flex justify-space-between mb-2">
                                <span>مبلغ کل</span>
                                <div class="zo-price">
                                    <strong>{{ selectedOrder.oldPrice }}</strong>
                                    <small>تومان</small>
                                </div>
                            </div>
                            <div class="d-flex justify-space-between mb-2">
                                <span>هزینه ارسال</span>
                                <div class="zo-price">
                                    <strong>90.000</strong>
                                    <small>تومان</small>
                                </div>
                            </div>
                            <div class="d-flex justify-space-between text-primary font-weight-bold">
                                <span>مبلغ قابل پرداخت</span>
                                <div class="zo-price">
                                    <strong>{{ selectedOrder.newPrice }}</strong>
                                    <small>تومان</small>
                                </div>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </PanelLayout>
    </WebLayout>
</template>
<script setup>
import { ref } from "vue";
import WebLayout from "@/Layouts/WebLayout.vue";
import PanelLayout from "@/Layouts/PanelLayout.vue";

const detailDialog = ref(false);
const selectedOrder = ref(null);

const statuses = ["درانتظار", "پرداخت شده", "درحال پردازش", "تکمیل", "لغو", "برگشت"];

const getStatusClass = (status) => {
    switch (status) {
        case "درانتظار":
            return "zo-status zo-pending";
        case "پرداخت شده":
            return "zo-status zo-paid";
        case "درحال پردازش":
            return "zo-status zo-processing";
        case "تکمیل":
            return "zo-status zo-completed";
        case "لغو شده":
            return "zo-status zo-canceled";
        case "برگشت":
            return "zo-status zo-refunded";
        default:
            return "zo-status";
    }
};


const orders = ref([{
        code: "192469",
        date: "1404/09/19 - 15:44",
        status: "درانتظار",
        oldPrice: "۲۳۰٬۰۰۰",
        newPrice: "۲۰۰٬۰۰۰",
        items: [{
            title: "کتاب آیین رستگاری",
            subtitle: "مصاحبات آیت الله طهرانی با برخی دوستان",
            number: "3",
            image: "/assets/img/sample/17.png",
        }, ],
    },
    {
        code: "192470",
        date: "1404/09/20 - 10:30",
        status: "درانتظار",
        oldPrice: "۴۶۰٬۰۰۰",
        newPrice: "۴۰۰٬۰۰۰",
        items: [{
                title: "کتاب آیین رستگاری",
                subtitle: "مصاحبات آیت الله طهرانی",
                number: "7",
                image: "/assets/img/sample/18.png",
            },
            {
                title: "کتاب آینده انقلاب",
                subtitle: "چاپ جدید با ویرایش جدید",
                number: "2",
                image: "/assets/img/sample/19.png",
            },
        ],
    },
    {
        code: "192471",
        date: "1404/09/21 - 12:20",
        status: statuses[Math.floor(Math.random() * statuses.length)],
        oldPrice: "۵۵۰٬۰۰۰",
        newPrice: "۵۰۰٬۰۰۰",
        items: [{
            title: "کتاب تاریخ اسلام",
            subtitle: "جلد دوم",
            number: "1",
            image: "/assets/img/sample/20.png",
        }, ],
    },
    {
        code: "192472",
        date: "1404/09/22 - 09:15",
        status: statuses[Math.floor(Math.random() * statuses.length)],
        oldPrice: "۱۲۰٬۰۰۰",
        newPrice: "۱۰۰٬۰۰۰",
        items: [{
            title: "کتاب منطق",
            subtitle: "ویراست جدید",
            number: "2",
            image: "/assets/img/sample/21.png",
        }, ],
    },
    {
        code: "192473",
        date: "1404/09/23 - 16:50",
        status: statuses[Math.floor(Math.random() * statuses.length)],
        oldPrice: "۷۸۰٬۰۰۰",
        newPrice: "۷۰۰٬۰۰۰",
        items: [{
                title: "کتاب فلسفه",
                subtitle: "چاپ سوم",
                number: "3",
                image: "/assets/img/sample/22.png",
            },
            {
                title: "کتاب منطق و فلسفه",
                subtitle: "جلد اول",
                number: "1",
                image: "/assets/img/sample/23.png",
            },
        ],
    },
]);

const showDetails = (order) => {
    selectedOrder.value = JSON.parse(JSON.stringify(order));
    detailDialog.value = true;
};

</script>
<style scoped>
.v-table>.v-table__wrapper>table>thead>tr {
    background: rgb(250, 250, 250);
}

.v-table>.v-table__wrapper>table>thead>tr>th {
    padding: 0;
    font-size: 0.9rem;
    background: rgb(245, 245, 245);
}

.v-table>.v-table__wrapper>table>tbody>tr>td {
    padding: 5px;
}

ul li {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 1.5px 0;
    list-style: none;
}

ul li img {
    max-width: 35px;
}

ul li div strong {
    display: block;
    margin: 0 0 -1.5px;
    font-family: "Estedad-Bold";
    font-size: 0.9rem;
}

ul li div small {
    font-size: 0.875rem;
    color: var(--Sub);
}

.zo-price del {
    position: relative;
    text-decoration: none;
    font-size: 0.8rem;
    color: var(--Sub);
}

.zo-price del:before {
    content: "";
    width: 100%;
    height: 1px;
    position: absolute;
    top: 10px;
    left: 0;
    background: rgb(90, 90, 90);
    transform: rotate(-15deg);
}

.zo-price strong {
    padding: 0 0 0 1.5px;
    font-family: "Estedad-Bold";
}

.zo-status {
    background: transparent
}

.zo-status :deep(span) {
    background: transparent
}

.zo-completed,
.zo-paid {
    color: rgba(5, 105, 60)
}

.zo-pending {
    color: rgba(105, 60, 5)
}

.zo-canceled,
.zo-refunded {
    color: rgb(0, 0, 0)
}

</style>
