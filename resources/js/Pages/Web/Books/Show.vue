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
                                            <div class="zo-bookmark">
                                                <BookmarkTemplate
                                                    v-if="props.user"
                                                    :id="book.id"
                                                    :isBookmarked="book.isBookmarked"
                                                    type="book"
                                                />
                                            </div>
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
                                    <div class="zo-description-content" v-html="book.content"></div>
                                </div>
                                </div>
                                <div class="zo-books-section">
                                    <div class="zo-swiper">
                                        <swiper dir="rtl" :modules="[Navigation]" :breakpoints="{
                                            0: {
                                                slidesPerView: 1.25,
                                                spaceBetween: 10
                                            },
                                            990: {
                                                slidesPerView: 1.5,
                                                spaceBetween: 15
                                            },
                                            1260: {
                                                slidesPerView: 2.5,
                                                spaceBetween: 15
                                            }
                                        }">
                                            <swiper-slide
                                                v-for="(item, index) in related.data"
                                                :key="index"
                                            >
                                                <BookCard :book="item"/>
                                            </swiper-slide>
                                        </swiper>
                                    </div>
                                </div>
                                <Comments :user="user" :item="book" type="book"/>
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
import BookmarkTemplate from "@/Components/Web/BookmarkTemplate.vue";



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
            window.dispatchEvent(new CustomEvent('cart-updated'));

        },
        onError: () => {
            isOrdering.value = false;
        },
        onFinish: () => {
        }
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
            window.dispatchEvent(new CustomEvent('cart-updated'));

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
            window.dispatchEvent(new CustomEvent('cart-updated'));
        },
        onError: () => {
            qtyLoading.value = false;
            disabling.value = false;
        },
    })
}

</script>
<style scoped>
.zo-book-section .zo-card {
    border-radius: 0.75rem;
    box-shadow: 0 5px 30px rgba(0, 0, 0, .085)
}

.zo-book-section .zo-card .zo-content {
    padding: 20px
}

.zo-book-section .zo-card .zo-content .zo-thumbnail {
    width: 100%;
    height: 100%;

    /* کلید حل مشکل اینجاست: */
    object-fit: contain;
    /* contain: کل عکس را نشان میدهد (بدون کراپ) */
    /* cover: کل فضا را پر میکند (باعث کراپ میشود) - حالت فعلی شما احتمالا این است */

    object-position: center; /* عکس را دقیقا در مرکز نگه می‌دارد */
    max-height: 100%;
}

.zo-book-section .zo-card .zo-content .zo-thumbnail {
    margin: 0 0 15px;
    position: relative
}

.zo-book-section .zo-card .zo-content .zo-thumbnail .zo-bookmark {
    position: absolute;
    top: -10px;
    right: -5px
}

.zo-book-section .zo-card .zo-content .zo-title {
    margin: 15px 0 0
}

.zo-book-section .zo-card .zo-content .zo-title h1 {
    margin: 0 0 5px;
    font-family: 'Estedad-Bold';
    font-size: 1.25rem
}

.zo-book-section .zo-card .zo-content .zo-sub {
    display: block;
    margin: 0 0 5px;
    color: var(--Sub)
}

.zo-book-section .zo-card .zo-content .zo-excerpt {
    margin: 0 0 15px;
    font-size: .95rem;
    text-align: justify
}

.zo-book-section .zo-card .zo-content .zo-stars {
    display: inline-block;
    padding: 12.5px 15px;
    background: rgb(245, 245, 245);
    border: 1px solid rgb(240, 240, 240);
    border-radius: .5rem
}

.zo-book-section .zo-card .zo-content .zo-stars span {
    display: inline-block;
    padding: 0 0 0 5px;
    font-size: .90rem
}

.zo-book-section .zo-card .zo-content .zo-stars i {
    font-size: .95rem
}

.zo-book-section .zo-card .zo-content .zo-info {
    width: 100%;
    display: inline-block;
    margin: 15px 0 25px;
    padding: 15px 0;
    border-top: 1px dashed rgb(240, 240, 240);
    border-bottom: 1px dashed rgb(240, 240, 240)
}

.zo-book-section .zo-card .zo-content .zo-info ul {
    display: flex;
    flex-wrap: wrap;
}

.zo-book-section .zo-card .zo-content .zo-info ul li {
    width: 20%;
    display: flex;
    align-items: center;
    gap: 5px
}

.zo-book-section .zo-card .zo-content .zo-info ul li:first-child {
    width: 40%;
}

.zo-book-section .zo-card .zo-content .zo-info ul li img {
    width: 25px;
}

.zo-book-section .zo-card .zo-content .zo-info ul li div span {
    display: inline-block;
    padding: 0 0 0 10px;
    color: var(--Sub)
}

.zo-book-section .zo-card .zo-content .zo-nav {
    width: 100%;
    display: inline-block;
    margin: 0 0 30px;
    padding: 0 0 25px;
    border-bottom: 1px dashed rgb(240, 240, 240)
}

.zo-book-section .zo-card .zo-content .zo-nav ul {
    display: flex;
    align-items: center;
    gap: 2.5px;
}

.zo-book-section .zo-card .zo-content .zo-nav ul li {
    display: inline-block;
}

.zo-book-section .zo-card .zo-content .zo-nav ul li a {
    padding: 5px 15px;
    color: var(--Text);
}

.zo-book-section .zo-card .zo-content .zo-nav ul li a:hover {
    background: rgb(225, 225, 225, .25);
    border-top-left-radius: 10px;
    border-bottom-right-radius: 10px
}

.zo-book-section .zo-card .zo-content .zo-nav ul li a.zo-active {
    background: var(--Secondary);
    color: var(--White);
    border-top-left-radius: 10px;
    border-bottom-right-radius: 10px
}

.zo-book-section .zo-card .zo-content .zo-subtitle {
    display: block;
    margin: 0 0 15px;
    font-size: 1.125rem;
    color: var(--Secondary);
}

.zo-book-section .zo-card .zo-content .zo-text {
    width: 100%;
    display: inline-block;
    margin: 0 0 30px;
    text-align: justify
}

.zo-book-section .zo-card .zo-content .zo-text p {
    margin: 0 0 10px;
    text-align: justify
}

.zo-book-section .zo-card .zo-content .zo-price-section {
    width: 100%;
    display: flex;
    align-items: center;
    margin: 0 0 15px;
    padding: 15px 10px;
    background: rgb(245, 245, 245);
    border: 1px dashed rgb(215, 215, 215);
    border-radius: 0.375rem
}

.zo-book-section .zo-card .zo-content .zo-price-section .zo-label {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 0 0 0 35px;
    position: relative
}

.zo-book-section .zo-card .zo-content .zo-price-section .zo-label:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 1px;
    height: 90%;
    border-left: 1px solid rgb(215, 215, 215)
}

.zo-book-section .zo-card .zo-content .zo-price-section .zo-price {
    width: 100%;
    display: inline-block
}

.zo-book-section .zo-card .zo-content .zo-price-section .zo-price .zo-regular {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 1.5px
}

.zo-book-section .zo-card .zo-content .zo-price-section .zo-price .zo-sale {
    display: flex;
    align-items: center;
    flex-direction: column
}

.zo-book-section .zo-card .zo-content .zo-price-section .zo-sale del {
    position: relative;
    text-decoration: none
}

.zo-book-section .zo-card .zo-content .zo-price-section .zo-sale del:before {
    content: '';
    width: 115%;
    height: 1px;
    position: absolute;
    top: 10px;
    left: -5px;
    background: rgba(0, 0, 0, .5);
    transform: rotate(10deg)
}

.zo-book-section .zo-card .zo-content .zo-price-section .zo-sale div {
    display: flex;
    align-items: baseline;
    gap: 1.5px
}

.zo-book-section .zo-card .zo-content .zo-add {
    height: 40px !important;
    padding: 0 25px !important
}

.zo-book-section .zo-books-section {
    margin: 0 0 30px;
    padding: 0 15px 0 0
}

.zo-text {
    width: 100%;
    display: inline-block;
    margin: 0 0 30px;
}

.zo-text .zo-title {
    display: block;
    margin: 0 0 15px;
    font-size: 1.125rem;
    color: var(--Secondary);
}

/* ✅ پاراگراف‌ها: حفظ فاصله و آرایش متن */
.zo-text :deep(p) {
    margin: 0 0 1.2rem 0; /* فاصله بین پاراگراف‌ها */
    text-align: justify;
    line-height: 1.9;
}

/* ✅ پاراگراف خالی‌ها فقط فاصله ظریف بدهند */
.zo-text :deep(p:empty) {
    margin: 0;
    display: block;
    height: 0.8rem; /* فاصله بصری ملایم بین پاراگراف‌ها */
}

/* ✅ فونت‌ها و سبک هدرها */
.zo-text :deep(h1),
.zo-text :deep(h2),
.zo-text :deep(h3),
.zo-text :deep(h4),
.zo-text :deep(h5),
.zo-text :deep(h6) {
    margin: 0 0 1rem;
    font-weight: 600;
}

.zo-text :deep(h1) { font-size: 1.25rem; }
.zo-text :deep(h2) { font-size: 1.15rem; }
.zo-text :deep(h3) { font-size: 1.10rem; }
.zo-text :deep(h4) { font-size: 1.05rem; }
.zo-text :deep(h5) { font-size: 1rem; }
.zo-text :deep(h6) { font-size: .95rem; }

/* ✅ لیست‌ها */
.zo-text :deep(ul) {
    padding: 0 0 15px;
}

.zo-text :deep(ul li) {
    width: 100%;
    display: inline-block;
    padding: 1.5px 10px 1.5px 0;
    position: relative;
}

.zo-text :deep(ul li:before) {
    content: '';
    width: 5px;
    height: 5px;
    position: absolute;
    top: 9.5px;
    right: 0;
    background: var(--Secondary);
}

/* ✅ فونت‌های داخل span و strong */
.zo-text :deep(span) {
    font-size: 16px; /* هماهنگ با خروجی TipTap */
}

.zo-text :deep(strong) {
    font-weight: 600;
}

@media (max-width: 960px) {
    .zo-text {
        margin: 0 0 5px;
    }
    .zo-text :deep(p) {
        line-height: 1.8;
        margin-bottom: 1rem;
    }
}
</style>
