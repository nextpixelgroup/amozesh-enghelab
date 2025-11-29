<template>
    <WebLayout>
        <div class="zo-book-section">
            <v-container>
                <v-row dense>
                    <v-col cols=12>
                        <div class="zo-breadcrumbs-section">
                            <nav>
                                <ul>
                                    <li>
                                        <Link :href="route('web.index')">خانه</Link>
                                    </li>
                                    <li>
                                        <Link :href="route('web.books.index')">فروشگاه کتاب</Link>
                                    </li>
                                    <li>
                                        <span>{{book.title}}</span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
            <v-container>
                <v-row>
                    <v-col cols="12" lg="9">
                        <v-card flat class="zo-card">
                            <div class="zo-content">
                                <v-row>
                                    <v-col lg="5" cols="12">
                                        <div class="zo-thumbnail">
                                            <img :src="book.thumbnail" alt="">
                                        </div>
                                    </v-col>
                                    <v-col lg="7" cols="12">
                                        <div class="zo-title">
                                            <h1>{{ book.title }}</h1>
                                        </div>
                                        <div class="zo-sub">{{book.subtitle}}</div>
                                        <div class="zo-excerpt">
                                            <p>{{book.summary}}</p>
                                        </div>
                                        <div class="zo-stars">
                                            <span>امتیاز دهی</span>
                                            <v-icon
                                                v-for="n in 5"
                                                :key="n"
                                                :color="n <= (hover || user_rate) ? '#c8a064' : 'grey lighten-1'"
                                                @mouseover="!isRatingLoading ? hover = n : null"
                                                @mouseleave="!isRatingLoading ? hover = 0 : null"
                                                @click="submitRating(n)"
                                                :disabled="isRatingLoading"
                                            >
                                                mdi-star
                                            </v-icon>
                                            {{rate}}

                                            <v-progress-circular
                                                v-if="isRatingLoading"
                                                indeterminate
                                                color="#c8a064"
                                                size="20"
                                                width="2"
                                                class="ms-2"
                                                :disabled="isRatingLoading"
                                            ></v-progress-circular>
                                        </div>
                                    </v-col>
                                </v-row>
                                <div class="zo-info">
                                    <ul>
                                        <li>
                                            <img src="/assets/img/site/c-user.svg" alt="" class="img-fluid">
                                            <div>
                                                <span>ناشر</span>
                                                <strong>{{book.author}}</strong>
                                            </div>
                                        </li>
                                        <li>
                                            <img src="/assets/img/site/c-calendar.svg" alt="" class="img-fluid">
                                            <div>
                                                <span>سال انتشار</span>
                                                <strong>{{book.year_published}}</strong>
                                            </div>
                                        </li>
                                        <li>
                                            <img src="/assets/img/site/c-file.svg" alt="" class="img-fluid">
                                            <div>
                                                <span>قطع</span>
                                                <strong>{{book.size}}</strong>
                                            </div>
                                        </li>
                                        <li>
                                            <img src="/assets/img/site/c-copy.svg" alt="" class="img-fluid">
                                            <div>
                                                <span>نوبت چاپ</span>
                                                <strong>{{book.edition}}</strong>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="zo-nav">
                                    <ul>
                                        <li>
                                            <a href="#" class="zo-active">مشخصات کتاب</a>
                                        </li>
                                        <li>
                                            <a href="#">نظرات کاربران</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="zo-text">
                                    <div class="zo-subtitle">درباره کتاب</div>
                                    <div v-html="book.content"></div>
                                </div>
                                <div class="zo-swiper">
                                    <swiper
                                        dir="rtl"
                                        :slides-per-view="3.5"
                                        :space-between="35"
                                        :modules="[Navigation]"
                                        navigation
                                    >
                                        <swiper-slide
                                            v-for="(item, index) in related.data"
                                            :key="index"
                                        >
                                            <BookCard :book="item"/>
                                        </swiper-slide>
                                    </swiper>
                                </div>
                                <Comments :user="user" :item="book" type="book"/>
                            </div>
                        </v-card>
                    </v-col>
                    <v-col cols="12" lg="3">
                        <v-card flat class="zo-card">
                            <div class="zo-content">
                                <div class="zo-price-section">
                                    <div class="zo-label">
                                        <img src="/assets/img/site/price.svg" alt="">
                                        <span>قیمت</span>
                                    </div>
                                    <div class="zo-price">
                                        <div class="zo-regular" v-if="book.is_stock">
                                            <strong>{{book.price}}</strong>
                                            <small>تومان</small>
                                        </div>
                                        <div class="zo-regular" v-else>
                                            <strong>ناموجود</strong>
                                        </div>

                                    </div>
                                </div>
                                <div class="zo-number">
                                    <v-number-input
                                        v-if="book.cartItemId"
                                        v-model="order.qty"
                                        :min="1"
                                        :max="book.max_order"
                                        :reverse="false"
                                        color="primary"
                                        control-variant="split"
                                        variant="outlined"
                                        class="qty-input"
                                        @update:model-value="updateQty"
                                        :loading="qtyLoading"
                                        :disabled="qtyLoading"
                                    ></v-number-input>
                                </div>
                                <v-btn
                                    v-if="book.is_stock && !book.cartItemId"
                                    block
                                    flat
                                    rounded
                                    color="secondary"
                                    class="zo-add"
                                    prepend-icon="mdi mdi-cart-outline"
                                    :loading="isOrdering"
                                    @click="addToCart"
                                >
                                    خرید آنلاین
                                </v-btn>
                                <v-btn
                                    v-if="book.cartItemId"
                                    block
                                    flat
                                    rounded
                                    color="#ba000d"
                                    class="zo-add"
                                    prepend-icon="mdi mdi-cart-off"
                                    :loading="isOrdering"
                                    :disabled="disabling"
                                    @click="removeFromCart"
                                >
                                    حذف از سبد خرید
                                </v-btn>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>

    <ShowMessage
        v-model:show="message.isShow"
        :message="message.text"
        :type="message.type"
    />
</template>
<script setup>
import {computed, ref} from 'vue';

import WebLayout from "@/Layouts/WebLayout.vue";
import Comments from "@/Components/Web/Comments.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import ShowMessage from "@/Components/ShowMessage.vue";
import {Navigation} from "swiper/modules";
import CourseCard from "@/Components/Web/Courses/CourseCard.vue";
import {Swiper, SwiperSlide} from "swiper/vue";
import BookCard from "@/Components/Web/Books/BookCard.vue";



const props = defineProps({
    book: Object,
    related: Object,
    user: Object,
});
const hover = ref(0);
const isRatingLoading = ref(false);
const isOrdering = ref(false);
const qtyLoading = ref(false);
const disabling = ref(false);
const message = ref({
    isShow: false,
    text: '',
    type: '',
})
const book = computed(() => props.book.data);
const user_rate = ref(book.value.user_rate)
const rate = ref(book.value.rate)
const order = useForm({
    qty: book.value.qty ?? 1,
    bookSlug: book.value.slug,
});
const submitRating = async (n) => {
    isRatingLoading.value = true;
    const response = await axios.post(route('web.books.rating', { book: book.value.slug }), {rate: n});
    if(response.data.status === 'success'){
        isRatingLoading.value = false;
        rate.value = response.data.data.rate;
        user_rate.value = n;
        message.value.isShow = true;
        message.value.text = response.data.message;
        message.value.type = 'success';
    }
    else {
        isRatingLoading.value = false;
        message.value.isShow = true;
        message.value.text = response.data.message || 'خطایی رخ داد';
        message.value.type = 'error';
    }
}

const addToCart = () => {
    order.post(route('web.cart.store', book.value.slug), {
        preserveScroll: true,
        preserveState: true,
        onStart: () => {
          isOrdering.value = true;
        },
        onSuccess: () => {
            isOrdering.value = false;
        },
        onError: () => {
            isOrdering.value = false;
        },
    })
}
const removeFromCart = () => {
    order.delete(route('web.cart.item.destroy', book.value.cartItemId), {
        preserveScroll: true,
        preserveState: true,
        onStart: () => {
          isOrdering.value = true;
        },
        onSuccess: () => {
            isOrdering.value = false;
        },
        onError: () => {
            isOrdering.value = false;
        },
    })
}

const updateQty = () => {
    order.put(route('web.cart.item.update', book.value.cartItemId), {
        preserveScroll: true,
        preserveState: true,
        onStart: () => {
            qtyLoading.value = true;
            disabling.value = true;
        },
        onSuccess: () => {
            qtyLoading.value = false;
            disabling.value = false;
        },
        onError: () => {
            qtyLoading.value = false;
            disabling.value = false;
        },
    })
}

</script>
