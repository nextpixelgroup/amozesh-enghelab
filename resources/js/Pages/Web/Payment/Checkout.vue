<template>
    <WebLayout title="Checkout">
        <div class="zo-checkout-section">
            <v-container>
                <v-row dense>
                    <v-col cols=12>
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <a href="#">خانه</a>
                                    </li>
                                    <li>
                                        <a href="#">فروشگاه کتاب</a>
                                    </li>
                                    <li>
                                        <a href="#">سبد خرید</a>
                                    </li>
                                    <li>
                                        <span>پرداخت آنلاین</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-card
                    v-if="!user || items.length === 0"
                    class="pa-12 text-center d-flex flex-column align-center"
                    elevation="3"
                    rounded="lg"
                >

                    <!-- Illustration مینیمال سفارشی -->
                    <img src="/assets/img/cart.svg" alt="" style="max-width: 240px;">
                    <v-card-text class="text-center" style="padding-top: 0;">
                        <h5 class="" style="font-weight: 600; color: #05693c; margin-bottom: 4px;">
                            سبد خرید شما خالی است
                        </h5>

                        <h6 class="" style="color: #4d4d4d; line-height: 1.8;">
                            {{
                                !user
                                    ? 'برای ادامه لطفاً وارد حساب کاربری شوید.'
                                    : 'برای ادامه لطفاً محصولی را به سبد خرید اضافه کنید.'
                            }}
                        </h6>
                    </v-card-text>

                    <v-btn
                        v-if="!user"
                        color="primary"
                        class="mt-6"
                        variant="flat"
                        :href="`${route('panel.index')}?redirect=${route('web.cart')}`"
                    >
                        ورود به حساب کاربری
                    </v-btn>
                    <v-btn
                        v-else
                        color="primary"
                        class="mt-6"
                        variant="flat"
                        :href="route('web.books.index')"
                    >
                        رفتن به فروشگاه کتاب
                    </v-btn>
                </v-card>
                <v-row dense v-else>
                    <v-col lg="8" cols="12">
                        <div class="zo-section">
                            <div class="zo-content">
                                <h3>جزئیات پرداخت</h3>
                                <div class="zo-form">
                                    <v-row dense>
                                        <v-col md="6" cols="12">
                                            <label>نام</label>
                                            <v-text-field
                                                v-model="form.firstname"
                                                hide-details
                                                density="compact"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-account-circle"
                                                label=""
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="6" cols="12">
                                            <label>نام خانوادگی</label>
                                            <v-text-field
                                                v-model="form.lastname"
                                                hide-details
                                                density="compact"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-account-circle"
                                                label="">

                                            </v-text-field>
                                        </v-col>
                                        <v-col md="8" cols="12">
                                            <label>آدرس کامل پستی</label>
                                            <v-text-field
                                                v-model="form.address"
                                                hide-details
                                                density="compact"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-map"
                                                label=""
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="4" cols="12">
                                            <label>کد پستی</label>
                                            <v-text-field
                                                v-model="form.postal_code"
                                                hide-details
                                                density="compact"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-map-marker"
                                                label=""
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="6" cols="12">
                                            <label>شماره موبایل</label>
                                            <v-text-field
                                                v-model="form.mobile"
                                                hide-details
                                                density="compact"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-cellphone"
                                                label=""
                                                readonly
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col md="6" cols="12">
                                            <label>پست الکترونیک</label>
                                            <v-text-field
                                                v-model="form.email"
                                                hide-details
                                                density="compact"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-at"
                                                label=""
                                            >
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <label>توضیحات</label>
                                            <v-textarea
                                                v-model="form.note"
                                                hide-details
                                                density="compact"
                                                variant="outlined"
                                                prepend-inner-icon="mdi-text"
                                                placeholder="یادداشت‌هایی درباره سفارش شما، مثلاً نکات ویژه برای تحویل."
                                            >
                                            </v-textarea>
                                        </v-col>
                                    </v-row>
                                </div>
                            </div>
                        </div>
                    </v-col>
                    <v-col lg="4" cols="12">
                        <div class="zo-section">
                            <div class="zo-content">
                                <h3>سفارش شما</h3>
                                <table>
                                    <thead>
                                        <tr v-for="(item,index) in items" :key="index">
                                            <th v-if="item.valid">{{item.data.title}} {{item.qty}}x</th>
                                            <td v-if="item.valid" class="text-left">
                                                <div class="zo-price">
                                                    <div class="zo-regular">
                                                        <del v-if="item.data.special_price">{{numberFormat(item.data.price * item.qty)}}</del>
                                                        <strong>{{numberFormat(item.data.sale_price * item.qty)}}</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>جمع جزء</th>
                                            <td class="text-left">
                                                <div class="zo-total">
                                                    <div class="zo-price">
                                                        <div class="zo-regular">
                                                            <del v-if="totalOriginalPrice !== totalPrice" style="color:#777;">{{numberFormat(totalOriginalPrice)}}</del>
                                                            <strong>{{numberFormat(totalPrice)}}</strong>
                                                            <small>تومان</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                                <br>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>حمل و نقل</th>
                                            <td class="text-left">
                                                <div class="zo-price">
                                                    <div class="zo-regular">
                                                        <strong>{{numberFormat(shipping_price)}}</strong>
                                                        <small>تومان</small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>جمع کل</th>
                                            <td class="text-left">
                                                <div class="zo-total">
                                                    <div class="zo-price">
                                                        <div class="zo-regular">
                                                            <strong>{{numberFormat(shipping_price+totalPrice)}}</strong>
                                                            <small>تومان</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="zo-bank">
                                    <span>پرداخت امن توسط درگاه بانک ملی</span>
                                    <img src="/assets/img/meli.png" width="25" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="zo-actions">
                            <v-btn
                                block
                                flat
                                color="primary"
                                class="zo-action"
                                :disabled="isLoading"
                                :loading="isLoading"
                                @click="pay"
                            >
                                پرداخت آنلاین
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import WebLayout from "@/Layouts/WebLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import {route} from "ziggy-js";
import {numberFormat} from "@/utils/helpers.js";
const props = defineProps({
    user: Object,
    items: Object,
    shipping_price: Number,
})
const items = computed(() => props.items.data);

const isLoading = ref(false)
const totalPrice = computed(() =>
    items.value
        .filter(item => item.valid !== false)   // فقط آیتم‌های معتبر
        .reduce((sum, item) => sum + item.data.sale_price * item.qty, 0)
);

const totalOriginalPrice = computed(() =>
    items.value
        .filter(item => item.valid !== false)
        .reduce((sum, item) => sum + item.data.price * item.qty, 0)
);

const form = useForm({
    firstname : props.user?.firstname,
    lastname : props.user?.lastname,
    address : props.user?.address,
    postal_code : props.user?.postal_code,
    mobile : props.user?.mobile,
    email : props.user?.email,
    note : '',
    gateway : 'sadad',
    items : items
});

const pay = () => {
    form.post(route('web.pay'), {
        preserveScroll:true,
        preserveState:true,
        onStart: () => {
            isLoading.value = true;
        },
        onSuccess: () => {
            isLoading.value = false;
        },
        onError: () => {
            isLoading.value = false;
        }
    })
}
</script>
<style scoped>
.table tr td {
    border-bottom: 1px solid rgb(235, 235, 235);
}

</style>
