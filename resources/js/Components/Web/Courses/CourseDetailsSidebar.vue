<template>
    <div class="zo-sidebar-section">
        <v-card flat class="zo-card">
            <div class="zo-content">
                <div class="zo-thumbnail">
                    <div class="zo-bookmark">
                        <BookmarkTemplate
                            v-if="user"
                            :id="course.id"
                            :isBookmarked="course.isBookmarked"
                            type="course"
                        />
                    </div>
                    <img :src="course.thumbnail" alt="" class="rounded-lg">
                </div>
                <div class="zo-price">رایگان</div>
                <v-btn block flat variant="elevated" :class="`zo-add ${isEnrolled ? 'added' : ''}`" @click="!isEnrolled && enrollInCourse()" :disabled="isEnroll" :loading="isEnroll">
                    <img src="/assets/img/site/click.svg" alt="">
                    {{ isEnrolled ? 'شما عضو دوره هستید' : 'ثبت نام در این دوره' }}
                </v-btn>
                <div class="zo-progress" v-if="isEnrolled">
                    <span class="zo-name">پیشرفت شما</span>
                    <span class="zo-percent">{{ course.progress }}٪ کامل شده</span>
                    <v-progress-linear v-model="progress" color="secondary" height="10" rounded></v-progress-linear>
                </div>
                <div class="zo-users">
                    <div class="zo-name">
                        <img src="/assets/img/site/c-gap-green.svg" alt="">
                        <span>کل شرکت کنندگان</span>
                    </div>
                    <div class="zo-stat">{{ course.students }}</div>
                </div>
                <div class="zo-info">
                    <ul>
                        <li>
                            <div>
                                <img src="/assets/img/site/c-clock.svg" alt="">
                                <span>شروع دوره</span>
                            </div>
                            <strong>{{ course.published_at }}</strong>
                        </li>
                        <li>
                            <div>
                                <img src="/assets/img/site/c-calendar.svg" alt="">
                                <span>تعداد جلسات</span>
                            </div>
                            <strong>{{ course.stats.lessons }} جلسه</strong>
                        </li>
                        <!--
                        <li>
                            <div>
                                <img src="/assets/img/site/c-file.svg" alt="">
                                <span>دسته</span>
                            </div>
                            <strong>{{ course.category }}</strong>
                        </li>
                        -->
                        <li>
                            <div>
                                <img src="/assets/img/site/c-star.svg" alt="">
                                <span>امتیاز دوره</span>
                            </div>
                            <strong>{{ rate }}</strong>
                        </li>
                    </ul>
                </div>
                <div class="zo-rating" v-if="isEnrolled">
                    <v-icon v-for="n in 5" :key="n" :color="n <= (hover || user_rate) ? '#c8a064' : 'grey lighten-1'" @mouseover="!isRatingLoading ? hover = n : null" @mouseleave="!isRatingLoading ? hover = 0 : null" @click="submitRating(n)" :disabled="isRatingLoading" size="20">
                        mdi-star
                    </v-icon>
                    <v-progress-circular v-if="isRatingLoading" indeterminate color="#c8a064" size="20" width="2" class="ms-2" :disabled="isRatingLoading"></v-progress-circular>
                </div>
                <div class="zo-prof" id="about-teacher">
                    <strong>درباره مدرس</strong>
                    <div class="zo-name">
                        <div class="zo-avatar">
                            <img :src="course.teacher.avatar" alt="">
                        </div>
                        <div>
                            <a :href="course.teacher.url" target="_blank"><span>{{ course.teacher.name }}</span></a>
                            <ul>
                                <li>
                                    <img src="/assets/img/site/c-lessons.svg" alt="">
                                    <span>{{ course.teacher.courses }} دوره</span>
                                </li>
                                <li>
                                    <img src="/assets/img/site/c-gap-grey.svg" alt="">
                                    <span>{{ course.teacher.students }} دانشجو</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </v-card>
    </div>
    <ShowMessage v-model:show="message.isShow" :message="message.text" :type="message.type" />
</template>
<script setup>
import { computed, ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import ShowMessage from "@/Components/ShowMessage.vue";
import BookmarkTemplate from "@/Components/Web/BookmarkTemplate.vue";

const props = defineProps({
    course: {
        type: Object,
        required: true
    },
    isEnrolled: {
        type: Boolean,
        required: true
    },
    enrollPath: {
        type: [Boolean, null],
        required: true
    },
    showEnrollPathDialog: {
        type: [Boolean],
        required: true
    },
    user: {
        type: Object,
        required: true
    }
})

const progress = computed(() => props.course.progress)
const user_rate = ref(props.course.user_rate)
const rate = ref(props.course.rate)
const hover = ref(0)
const isEnroll = ref(false);
const isRatingLoading = ref(false)
const message = ref({
    isShow: false,
    text: '',
    type: '',
})
const emit = defineEmits(['update:showEnrollPathDialog']); // اضافه کردن این خط
const enrollInCourse = () => {
    if (props.enrollPath === false){
        emit('update:showEnrollPathDialog', true); // تغییر مقدار showEnrollPathDialog
        return false;
    }
    if (props.isEnrolled.value) return false;
    try {
        router.post(route('web.courses.enroll', { course: props.course.id }), {}, {
            preserveScroll: true,
            onStart: () => {
                isEnroll.value = true;
            },
            onSuccess: () => {
                // عملیات بعد از موفقیت‌آمیز بودن درخواست
                isEnroll.value = false;
                // می‌توانید state مربوطه را به‌روز کنید
            },
            onError: (errors) => {
                console.error('خطا در ثبت‌نام:', errors);
                isEnroll.value = false;
            }
        });
    } catch (error) {
        console.error('خطا در ارسال درخواست:', error);
        isEnroll.value = false;
    }
}

const submitRating = async (n) => {
    isRatingLoading.value = true;
    const response = await axios.post(route('web.courses.rating', { course: props.course.slug }), { rate: n });
    if (response.data.status === 'success') {
        isRatingLoading.value = false;
        rate.value = response.data.data.rate;
        user_rate.value = n;
        message.value.isShow = true;
        message.value.text = response.data.message;
        message.value.type = 'success';
    } else {
        isRatingLoading.value = false;
        message.value.isShow = true;
        message.value.text = response.data.message || 'خطایی رخ داد';
        message.value.type = 'error';
    }
}

</script>
<style scoped>
.zo-sidebar-section .zo-content {
    padding: 20px
}

.zo-sidebar-section .zo-content .zo-thumbnail {
    margin: 0 0 15px;
    position: relative
}

.zo-sidebar-section .zo-content .zo-thumbnail .zo-bookmark {
    position: absolute;
    top: 15px;
    left: 15px
}

.zo-sidebar-section .zo-content .zo-thumbnail img {
    display: block;
    margin: auto
}

.zo-sidebar-section .zo-content .zo-price {
    width: 100%;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 0 15px;
    background: url('/assets/img/free.svg') no-repeat;
    background-position: center;
    background-size: contain;
    text-align: center
}

.zo-sidebar-section .zo-content .zo-add {
    min-height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    background: var(--Primary);
    color: rgb(255, 255, 255);
    border-radius: .5rem
}

.zo-sidebar-section .zo-content .zo-add.added {
    cursor: auto;
    background: var(--Secondary)
}

.zo-sidebar-section .zo-content .zo-add img {
    margin: 0 0 0 10px
}

.zo-sidebar-section .zo-content .zo-progress {
    width: 100%;
    margin: 15px 0;
    padding: 15px 20px;
    background: rgb(5, 105, 60);
    color: rgb(255, 255, 255);
    border-radius: .5rem
}

.zo-sidebar-section .zo-content .zo-progress .zo-name {
    display: block;
    margin: 0 0 10px
}

.zo-sidebar-section .zo-content .zo-progress .zo-percent {
    display: block;
    margin: 0 0 2.5px;
    font-size: .90rem
}

.zo-sidebar-section .zo-content .zo-users {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 15px 0;
    padding: 15px;
    color: rgb(5, 105, 60);
    border: 1px dashed rgb(240, 240, 240);
    border-radius: .75rem
}

.zo-sidebar-section .zo-content .zo-users .zo-name {
    display: flex;
    align-items: center;
    gap: 5px
}

.zo-sidebar-section .zo-content .zo-info {
    width: 100%;
    display: inline-block;
    padding: 0 0 10px;
    border-bottom: 1px dashed rgb(240, 240, 240)
}

.zo-sidebar-section .zo-content .zo-info ul li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    font-size: .95rem
}

.zo-sidebar-section .zo-content .zo-info ul li div {
    display: flex;
    align-items: center;
    gap: 5px
}

.zo-sidebar-section .zo-content .zo-info ul li strong {
    text-align: left
}

.zo-sidebar-section .zo-content .zo-rating {
    display: flex;
    justify-content: center;
    margin: 30px 0 0
}

.zo-sidebar-section .zo-content .zo-prof {
    margin: 15px 0 0
}

.zo-sidebar-section .zo-content .zo-prof strong {
    display: block;
    margin: 0 0 5px;
    font-size: 1.125rem;
    color: var(--Secondary);
}

.zo-sidebar-section .zo-content .zo-prof .zo-name {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 0 0 15px
}

.zo-sidebar-section .zo-content .zo-prof .zo-name .zo-avatar img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 50%
}

.zo-sidebar-section .zo-content .zo-prof ul {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 10px 0 0
}

.zo-sidebar-section .zo-content .zo-prof ul li {
    display: flex;
    align-items: center;
    gap: 5px;
    position: relative;
    color: rgb(120, 125, 135)
}

.zo-sidebar-section .zo-content .zo-prof p {
    font-size: .90rem;
    color: rgb(120, 125, 135)
}

@media (max-width: 575px) {

    .zo-sidebar-section .zo-content {
        padding: 15px
    }

    .zo-sidebar-section .zo-content .zo-info ul li {
        display: block
    }

    .zo-sidebar-section .zo-content .zo-info ul li strong {
        display: block;
        padding: 10px 0 0;
        text-align: right
    }
}

</style>
