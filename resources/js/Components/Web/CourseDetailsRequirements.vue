<template>
    <div class="zo-prerequisites-courses-section">
    <div class="zo-courses-section">
        <strong class="zo-title">پیش نیازها</strong>
        <v-row dense>
            <v-col cols="12" lg="6"
                   v-for="(course, index) in prerequisites"
                   :key="index"
            >
                <a href="#" class="zo-course">
                    <v-row dense>
                        <v-col cols="12" lg="6">
                            <figure>
                                <div class="zo-thumbnail">
                                    <img :src="course.image" class="img-fluid"/>
                                </div>
                                <div class="zo-category">
                                    <img src="/assets/img/site/c-cat.svg"
                                         class="img-fluid"/>
                                    <span>{{ course.category }}</span>
                                </div>
                            </figure>
                        </v-col>
                        <v-col cols="12" lg="6">
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
                        </v-col>
                    </v-row>
                </a>
            </v-col>
        </v-row>
    </div>
</div>
</template>
<script setup>
</script>
