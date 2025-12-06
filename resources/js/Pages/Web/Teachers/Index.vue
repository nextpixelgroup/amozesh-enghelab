<template>
    <Head title="مدرسین"/>
    <WebLayout>
        <div class="zo-profs-section">
            <v-container>
                <v-row class="align-center">
                    <v-col cols="12">
                        <div class="zo-title-section">
                            <img src="/assets/img/site/right-primary.svg" alt="">
                            <strong>مدرسین خانه انقلاب</strong>
                            <img src="/assets/img/site/left-primary.svg" alt="">
                        </div>
                    </v-col>
                    <v-col lg="3" sm="6" cols="12" v-for="teacher in teachers.data">
                        <TeacherCard :key="teacher.id" :teacher="teacher"/>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </WebLayout>
</template>
<script setup>
import {Head} from "@inertiajs/vue3";
import WebLayout from "@/Layouts/WebLayout.vue";
import TeacherCard from "@/Components/Web/TeacherCard.vue";
const props = defineProps({
    teachers: Object
});
</script>
