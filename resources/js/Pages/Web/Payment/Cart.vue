<template>
    <WebLayout title="Cart">
        <div class="zo-cart-section">
            <v-container>
                <v-row dense>
                    <v-col cols="12">
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li><a href="#">خانه</a></li>
                                    <li><a href="#">فروشگاه کتاب</a></li>
                                    <li><span>سبد خرید</span></li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>

            <v-container>
                <v-row>
                    <v-col cols="12">

                        <!-- *** حالت EMPTY: نمایش داخل CARD *** -->
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
                                :href="`${route('panel.login')}?redirect=${route('web.cart')}`"
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
                        <!-- *** حالت WITH DATA: نمایش جدول واقعی *** -->
                        <div v-else class="zo-section">
                            <v-table>
                                <thead>
                                <tr>
                                    <th class="text-center">ردیف</th>
                                    <th class="text-center">تصویر</th>
                                    <th class="text-center">عنوان</th>
                                    <th class="text-center">قیمت</th>
                                    <th class="text-center">تعداد</th>
                                    <th class="text-center">جمع جزء</th>
                                    <th class="text-center">حذف</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr
                                    v-for="(item, index) in items"
                                    :key="item.id"
                                    :class="{
                                        'bg-red-lighten-5': item.valid === false,
                                        'bg-yellow-lighten-4': item.warning === true
                                    }"
                                >
                                    <td class="text-center">{{ index + 1 }}</td>

                                    <td class="text-center">
                                        <Link :href="item.data.url">
                                            <img
                                                :src="item.data.thumbnail"
                                                alt=""
                                                style="width:70px; height:auto"
                                            >
                                        </Link>
                                    </td>



                                    <td class="text-center" v-if="!item.valid">
                                        <del style="color: #9c9c9c"><Link :href="item.data.url">{{ item.data.title }}</Link></del>
                                        <p style="color: #ff0000">{{ item.message }}</p>
                                    </td>
                                    <td class="text-center" v-else-if="item.warning">
                                        <strong><Link :href="item.data.url">{{ item.data.title }}</Link></strong>
                                        <p style="color: #6f6f24">{{ item.message }}</p>
                                    </td>
                                    <td class="text-center" v-else>
                                        <strong><Link :href="item.data.url">{{ item.data.title }}</Link></strong>
                                    </td>
                                    <td class="text-center" v-if="item.data.special_price">
                                        <strong class="mr-2 ml-1">{{ numberFormat(item.data.special_price) }}</strong>
                                        <del>{{ numberFormat(item.data.price) }}</del>
                                        <small>تومان</small>
                                    </td>
                                    <td class="text-center" v-else>
                                        <strong class="ml-1">{{ numberFormat(item.data.price) }}</strong>
                                        <small>تومان</small>
                                    </td>

                                    <td class="text-center">
                                        <v-number-input
                                            v-model="item.qty"
                                            :max="item.data.max_order"
                                            :min="1"
                                            variant="outlined"
                                            control-variant="split"
                                            hide-details
                                            :disabled="!item.valid || disabling"
                                            @update:model-value="updateQty(item.id,item.qty)"
                                        />
                                    </td>

                                    <td class="text-center">
                                        <strong class="ml-1">{{ numberFormat(item.data.sale_price * item.qty) }}</strong>
                                        <small>تومان</small>
                                    </td>
                                    <td class="text-center">
                                        <v-btn
                                            v-if="item.valid"
                                            icon
                                            variant="text"
                                            color="error"
                                            size="small"
                                            @click="removeItem(item.id)"
                                            :disabled="disabling"
                                            :loading="isRemoving[item.id]"
                                        >
                                            <v-icon size="24">mdi-trash-can-outline</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>

                                <!-- جمع کل -->
                                <tr class="zo-total">
                                    <td></td>
                                    <td></td>
                                    <td class="text-center"><strong>جمع کل</strong></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center">
                                        <!-- جمع کل بدون تخفیف -->
                                        <div v-if="totalOriginalPrice !== totalPrice" style="color:#777;">
                                            <del>{{ numberFormat(totalOriginalPrice) }}</del>
                                        </div>

                                        <!-- جمع کل واقعی (با تخفیف‌ها) -->
                                        <div>
                                            <strong>{{ numberFormat(totalPrice) }}</strong>
                                            <small>تومان</small>
                                        </div>

                                    </td>
                                </tr>
                                </tbody>
                            </v-table>

                            <div class="zo-actions">
                                <v-btn
                                    flat
                                    color="primary"
                                    class="zo-action ml-2"
                                    :disabled="disabling"
                                    :loading="checkoutLoading"
                                    @click="checkout"
                                >
                                    اقدام به پرداخت
                                </v-btn>
                            </div>
                        </div>

                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>

<script setup>
import {ref, computed} from "vue";
import WebLayout from "@/Layouts/WebLayout.vue";
import {route} from "ziggy-js";
import {numberFormat} from "../../../utils/helpers.js";
import {Link, router} from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    items: Object,
})
const items = ref(props.items.data);
const disabling = ref(false)
const checkoutLoading = ref(false)
const isRemoving = ref({})
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
const removeItem = (id) => {
    router.delete(route("web.cart.item.destroy",id),{
        preserveScroll: true,
        preserveState: false,
        onStart: () => {
            isRemoving.value = {...isRemoving.value, [id]: true}
            disabling.value = true;
        },
        onSuccess: () => {
            const newLoading = {...isRemoving.value}
            delete newLoading[id]
            isRemoving.value = newLoading
            disabling.value = false;
        },
        onError: () => {
            const newLoading = {...isRemoving.value}
            delete newLoading[id]
            isRemoving.value = newLoading
            disabling.value = false;
        }
    })
};

const updateQty = (id,qty) => {
    router.put(route("web.cart.item.update",id), {qty:qty}, {
        preserveScroll: true,
        preserveState: false,
        onStart: () => {
            disabling.value = true;
        },
        onSuccess: () => {
            disabling.value = false;
        },
        onError: () => {
            disabling.value = false;
        }
    })
};

const checkout = () => {
    checkoutLoading.value = true;
    disabling.value = true;
    router.get(route('web.checkout'))
}
</script>
