<template>
    <div class="zo-related-courses-section">
        <div class="zo-courses-section">
            <strong class="zo-title">دوره‌های مرتبط</strong>
            <div class="zo-swiper">
                <swiper
                    dir="rtl"
                    :slides-per-view="3.5"
                    :space-between="35"
                    :modules="[Navigation]"
                    navigation
                >
                    <swiper-slide
                        v-for="(course, index) in courses"
                        :key="index"
                    >
                        <a href="#" class="zo-course">
                            <figure>
                                <div class="zo-thumbnail">
                                    <img :src="course.image" class="img-fluid"/>
                                </div>
                                <div class="zo-category">
                                    <img src="/assets/img/site/c-cat.svg" class="img-fluid"/>
                                    <span>{{ course.category }}</span>
                                </div>
                            </figure>
                            <div class="zo-content">
                                <h2>{{ course.title }}</h2>
                                <span class="zo-prof">{{ course.professor }}</span>
                                <ul>
                                    <li>
                                        <img src="/assets/img/site/c-clock.svg"/>
                                        <span>{{ course.time }}</span>
                                    </li>
                                    <li>
                                        <img src="/assets/img/site/c-students.svg"/>
                                        <span>{{ course.students }} دانشجو</span>
                                    </li>
                                </ul>
                                <div class="zo-price">
                                    <span>قیمت</span>
                                    <strong>{{ course.price }}</strong>
                                </div>
                                <div class="zo-more">اطلاعات بیشتر</div>
                            </div>
                        </a>
                    </swiper-slide>
                </swiper>
            </div>
        </div>
    </div>
</template>
<script setup>

import {Navigation} from "swiper/modules";
import {Swiper, SwiperSlide} from "swiper/vue";
</script>
